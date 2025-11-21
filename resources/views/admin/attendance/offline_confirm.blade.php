@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Asistencia offline
        </div>

        <div class="card-body">
            <div id="statusBanner" class="alert alert-info">
                Preparando modo sin conexion...
            </div>

            <form id="offlineAttendanceForm">
                @csrf
                <div class="form-group">
                    <label for="rutInput">RUT del personal</label>
                    <input type="text" class="form-control" id="rutInput" required placeholder="Ingrese solo numeros y digito verificador">
                    <small id="rutFeedback" class="form-text text-muted"></small>
                </div>

                <div class="form-group">
                    <label for="personName">Nombre</label>
                    <input type="text" class="form-control" id="personName" readonly placeholder="Se completa desde los datos locales">
                </div>

                <div class="form-group">
                    <label for="locationSelect">Ubicacion</label>
                    <select class="form-control" id="locationSelect" required>
                        <option value="">Cargando ubicaciones...</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar offline</button>
                    <button type="button" id="syncNowBtn" class="btn btn-success ml-2">Sincronizar ahora</button>
                </div>
            </form>

            <div class="mt-3" id="pendingContainer">
                <h5>Registros pendientes</h5>
                <div id="pendingList" class="table-responsive"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@parent
<script>
    (function () {
        const DB_NAME = 'attendance_offline';
        const DB_VERSION = 2;
        const OFFLINE_DATA_URL = "{{ route('admin.attendance.offlineData') }}";
        const SYNC_URL = "{{ route('admin.attendance.offlineSync') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
        const loadingLayer = document.createElement('div');
        loadingLayer.style.position = 'fixed';
        loadingLayer.style.top = 0;
        loadingLayer.style.left = 0;
        loadingLayer.style.width = '100%';
        loadingLayer.style.height = '100%';
        loadingLayer.style.background = 'rgba(255,255,255,0.7)';
        loadingLayer.style.display = 'none';
        loadingLayer.style.zIndex = 1050;
        loadingLayer.style.alignItems = 'center';
        loadingLayer.style.justifyContent = 'center';
        loadingLayer.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div><div class="mt-2">Sincronizando...</div></div>';
        document.body.appendChild(loadingLayer);

        function toggleLoading(show) {
            loadingLayer.style.display = show ? 'flex' : 'none';
        }

        function showToast(message, level = 'info') {
            const colors = { info: 'primary', success: 'success', warning: 'warning', danger: 'danger' };
            const toast = document.createElement('div');
            toast.className = 'alert alert-' + (colors[level] || 'primary') + ' fixed-top m-3 shadow';
            toast.style.zIndex = 1060;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        const statusBanner = document.getElementById('statusBanner');
        const rutInput = document.getElementById('rutInput');
        const rutFeedback = document.getElementById('rutFeedback');
        const personName = document.getElementById('personName');
        const locationSelect = document.getElementById('locationSelect');
        const pendingList = document.getElementById('pendingList');
        const syncNowBtn = document.getElementById('syncNowBtn');
        const offlineForm = document.getElementById('offlineAttendanceForm');

        let db;

        function setStatus(message, level = 'info') {
            statusBanner.className = 'alert alert-' + level;
            statusBanner.textContent = message;
        }

        function normalizeRut(value) {
            return value.replace(/[^0-9kK]/g, '').toUpperCase();
        }

        function computeDv(body) {
            let sum = 0;
            let multiplier = 2;
            for (let i = body.length - 1; i >= 0; i--) {
                sum += parseInt(body[i], 10) * multiplier;
                multiplier = multiplier === 7 ? 2 : multiplier + 1;
            }
            const result = 11 - (sum % 11);
            if (result === 11) return '0';
            if (result === 10) return 'K';
            return String(result);
        }

        function formatRut(value) {
            const clean = normalizeRut(value);
            if (!clean) return '';
            if (clean.length === 1) return clean;
            const body = clean.slice(0, -1);
            const dv = clean.slice(-1);
            const withDots = body.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return withDots + '-' + dv;
        }

        function validateRut(value) {
            const clean = normalizeRut(value);
            if (clean.length < 2) {
                return { valid: false, expected: null };
            }
            const body = clean.slice(0, -1);
            const dv = clean.slice(-1);
            const expected = computeDv(body);
            return { valid: dv === expected, expected, clean };
        }

        function openDatabase() {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open(DB_NAME, DB_VERSION);

                request.onupgradeneeded = (event) => {
                    const database = event.target.result;
                    if (database.objectStoreNames.contains('personals')) {
                        database.deleteObjectStore('personals');
                    }
                    const personalStore = database.createObjectStore('personals', { keyPath: 'id' });
                    personalStore.createIndex('rutIndex', 'normalizedRut', { unique: false });

                    if (database.objectStoreNames.contains('locaciones')) {
                        database.deleteObjectStore('locaciones');
                    }
                    database.createObjectStore('locaciones', { keyPath: 'id' });

                    if (!database.objectStoreNames.contains('pending')) {
                        database.createObjectStore('pending', { keyPath: 'local_id' });
                    }
                };

                request.onsuccess = (event) => {
                    db = event.target.result;
                    resolve(db);
                };

                request.onerror = () => reject(request.error);
            });
        }

        function saveCollection(storeName, rows) {
            return new Promise((resolve, reject) => {
                const tx = db.transaction(storeName, 'readwrite');
                const store = tx.objectStore(storeName);
                store.clear();
                rows.forEach((row) => store.put(row));
                tx.oncomplete = () => resolve();
                tx.onerror = () => reject(tx.error);
            });
        }

        function getAllFromStore(storeName) {
            return new Promise((resolve, reject) => {
                const tx = db.transaction(storeName, 'readonly');
                const store = tx.objectStore(storeName);
                const request = store.getAll();
                request.onsuccess = () => resolve(request.result || []);
                request.onerror = () => reject(request.error);
            });
        }

        function findPersonalByRut(cleanRut) {
            return new Promise((resolve, reject) => {
                const tx = db.transaction('personals', 'readonly');
                const store = tx.objectStore('personals');
                const index = store.index('rutIndex');
                const request = index.get(cleanRut);
                request.onsuccess = () => resolve(request.result);
                request.onerror = () => reject(request.error);
            });
        }

        function refreshLocationsSelect() {
            getAllFromStore('locaciones').then((locations) => {
                locationSelect.innerHTML = '<option value=\"\">Seleccione una ubicacion</option>';
                locations.sort((a, b) => a.nombre.localeCompare(b.nombre));
                locations.forEach((loc) => {
                    const option = document.createElement('option');
                    option.value = loc.id;
                    option.textContent = loc.nombre;
                    locationSelect.appendChild(option);
                });
            }).catch(() => {
                locationSelect.innerHTML = '<option value=\"\">No se pudieron cargar ubicaciones</option>';
            });
        }

        function renderPending() {
            getAllFromStore('pending').then((rows) => {
                if (!rows.length) {
                    pendingList.innerHTML = '<p class=\"text-muted\">No hay registros pendientes.</p>';
                    return;
                }
                rows.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
                let html = '<table class=\"table table-sm table-bordered\"><thead><tr><th>RUT</th><th>Nombre</th><th>Ubicacion</th><th>Guardado</th></tr></thead><tbody>';
                rows.forEach((row) => {
                    html += '<tr>' +
                        '<td>' + row.rut_formatted + '</td>' +
                        '<td>' + (row.person_name || 'N/D') + '</td>' +
                        '<td>' + (row.location_name || row.location_id) + '</td>' +
                        '<td>' + new Date(row.timestamp).toLocaleString() + '</td>' +
                        '</tr>';
                });
                html += '</tbody></table>';
                pendingList.innerHTML = html;
            }).catch(() => {
                pendingList.innerHTML = '<p class=\"text-danger\">No se pudo leer la lista pendiente.</p>';
            });
        }

        function fetchMirrorData() {
            if (!navigator.onLine) {
                setStatus('Sin conexion, utilizando datos locales.', 'warning');
                return;
            }

            setStatus('Actualizando espejo local...', 'info');
            toggleLoading(true);
            fetch(OFFLINE_DATA_URL, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then((response) => response.json())
                .then((data) => {
                    const personals = (data.personals || []).map((p) => ({
                        ...p,
                        normalizedRut: normalizeRut(p.rut)
                    }));
                    const locaciones = (data.locaciones || []).map((l) => ({
                        ...l,
                        locacion_padre_id: l.locacion_padre_id || null
                    }));
                    return Promise.all([
                        saveCollection('personals', personals),
                        saveCollection('locaciones', locaciones)
                    ]);
                })
                .then(() => {
                    setStatus('Datos locales actualizados. Disponible sin conexion.', 'success');
                    showToast('Sincronizacion de espejo completada: ' + new Date().toLocaleTimeString(), 'success');
                    refreshLocationsSelect();
                })
                .catch((error) => {
                    setStatus('No se pudo actualizar el espejo local. Usando datos previos.', 'danger');
                    console.error('Error al actualizar espejo local', error);
                    showToast('Fallo al descargar datos locales.', 'danger');
                })
                .finally(() => {
                    toggleLoading(false);
                });
        }

        function addPendingEntry(entry) {
            return new Promise((resolve, reject) => {
                const tx = db.transaction('pending', 'readwrite');
                tx.objectStore('pending').put(entry);
                tx.oncomplete = () => resolve();
                tx.onerror = () => reject(tx.error);
            });
        }

        function removePendingByIds(ids) {
            return new Promise((resolve, reject) => {
                const tx = db.transaction('pending', 'readwrite');
                const store = tx.objectStore('pending');
                ids.forEach((id) => store.delete(id));
                tx.oncomplete = () => resolve();
                tx.onerror = () => reject(tx.error);
            });
        }

        function syncPending() {
            if (!navigator.onLine) {
                setStatus('No hay conexion para sincronizar.', 'warning');
                return;
            }

            getAllFromStore('pending').then((rows) => {
                if (!rows.length) {
                    setStatus('Sincronizado: no hay registros pendientes.', 'success');
                    showToast('No hay registros pendientes por sincronizar.', 'info');
                    return;
                }

                const payload = {
                    entries: rows.map((row) => ({
                        local_id: row.local_id,
                        rut: row.rut_formatted,
                        location_id: row.location_id,
                        timestamp: row.timestamp
                    }))
                };

                setStatus('Enviando ' + rows.length + ' registro(s) pendientes...', 'info');
                toggleLoading(true);
                fetch(SYNC_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify(payload)
                })
                    .then((response) => response.json().then((data) => ({ ok: response.ok, data })))
                    .then((result) => {
                        const syncedIds = result.data.synced_ids || [];
                        if (syncedIds.length) {
                            return removePendingByIds(syncedIds).then(() => {
                                renderPending();
                                const successLabel = result.data.success ? 'success' : 'warning';
                                setStatus('Sincronizados ' + syncedIds.length + ' registro(s).', successLabel);
                                showToast('Sincronizados ' + syncedIds.length + ' registro(s).', successLabel);
                                if (result.data.errors && result.data.errors.length) {
                                    console.warn('Errores de sincronizacion', result.data.errors);
                                    showToast(result.data.errors.join(' | '), 'warning');
                                }
                            });
                        }
                        setStatus('No se sincronizaron registros. Verifique los datos pendientes.', 'warning');
                        showToast('No se sincronizaron registros. Verifique los pendientes.', 'warning');
                        if (result.data.errors && result.data.errors.length) {
                            console.warn('Errores de sincronizacion', result.data.errors);
                            showToast(result.data.errors.join(' | '), 'warning');
                        }
                    })
                    .catch(() => {
                        setStatus('Fallo la sincronizacion. Reintentaremos cuando vuelva la conexion.', 'danger');
                        showToast('Fallo la sincronizacion. Se reintentara con conexion.', 'danger');
                    })
                    .finally(() => {
                        toggleLoading(false);
                    });
            });
        }

        function generateLocalId() {
            return 'pending-' + Date.now() + '-' + Math.random().toString(16).slice(2);
        }

        rutInput.addEventListener('input', () => {
            const formatted = formatRut(rutInput.value);
            rutInput.value = formatted;
            const validation = validateRut(formatted);

            if (validation.expected) {
                rutFeedback.textContent = 'DV esperado: ' + validation.expected + (validation.valid ? ' (valido)' : ' (no coincide)');
                rutFeedback.className = 'form-text text-' + (validation.valid ? 'success' : 'danger');
            } else {
                rutFeedback.textContent = 'Ingrese el RUT sin puntos ni guion. Ej: 12345678-5';
                rutFeedback.className = 'form-text text-muted';
            }

            const cleanRut = normalizeRut(formatted);
            if (cleanRut.length >= 2) {
                findPersonalByRut(cleanRut).then((person) => {
                    personName.value = person ? person.nombre : '';
                });
            } else {
                personName.value = '';
            }
        });

        offlineForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const formattedRut = formatRut(rutInput.value);
            const validation = validateRut(formattedRut);
            if (!validation.valid) {
                setStatus('El RUT no es valido. Verifique el digito verificador.', 'danger');
                return;
            }
            const cleanRut = validation.clean;
            const locationId = locationSelect.value;
            if (!locationId) {
                setStatus('Debe seleccionar una ubicacion.', 'warning');
                return;
            }

            findPersonalByRut(cleanRut).then((person) => {
                if (!person) {
                    setStatus('El RUT no existe en los datos locales. Actualice el espejo e intente nuevamente.', 'danger');
                    return;
                }

                const entry = {
                    local_id: generateLocalId(),
                    rut_formatted: formattedRut,
                    rut_clean: cleanRut,
                    person_id: person.id,
                    person_name: person.nombre,
                    location_id: Number(locationId),
                    location_name: locationSelect.options[locationSelect.selectedIndex].text,
                    timestamp: new Date().toISOString()
                };

                addPendingEntry(entry).then(() => {
                    setStatus('Registro guardado offline correctamente.', 'success');
                    renderPending();
                    offlineForm.reset();
                    personName.value = '';
                    refreshLocationsSelect();
                    syncPending();
                }).catch(() => {
                    setStatus('No se pudo guardar el registro offline.', 'danger');
                });
            });
        });

        syncNowBtn.addEventListener('click', syncPending);
        window.addEventListener('online', () => {
            setStatus('Conexion restablecida. Sincronizando...', 'info');
            fetchMirrorData();
            syncPending();
        });
        window.addEventListener('offline', () => {
            setStatus('Sin conexion. Los registros se guardaran localmente.', 'warning');
        });

        openDatabase()
            .then(() => {
                setStatus('Base local lista. Cargando datos...', 'info');
                toggleLoading(true);
                fetchMirrorData();
                refreshLocationsSelect();
                renderPending();
            })
            .catch(() => {
                setStatus('No se pudo abrir IndexedDB en este navegador.', 'danger');
                offlineForm.querySelector('button[type=\"submit\"]').disabled = true;
            });
    })();
</script>
@endsection
