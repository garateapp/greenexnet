@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <strong>Costeo Real de Materiales por Proceso</strong>
                </div>
                <div class="card-body">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label for="process_number" class="form-label">Numero de proceso (numero_i)</label>
                            <input type="text" id="process_number" class="form-control" placeholder="Ej: 1850">
                        </div>
                        <div class="col-md-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="force_recalculate">
                                <label class="form-check-label" for="force_recalculate">
                                    Forzar recálculo
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button id="btn_calculate" class="btn btn-primary">Calcular consumo real</button>
                        </div>
                    </div>
                    <div id="calc_message" class="alert mt-3 d-none"></div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <strong>Resumen</strong>
                </div>
                <div class="card-body">
                    <div class="row" id="summary_container">
                        <div class="col-md-3 mb-2">
                            <div class="border rounded p-2">
                                <small class="text-muted">Regla aplicada</small>
                                <div id="summary_rule">-</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="border rounded p-2">
                                <small class="text-muted">Costo real</small>
                                <div id="summary_real">$0</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="border rounded p-2">
                                <small class="text-muted">Costo prorrateado</small>
                                <div id="summary_prorated">$0</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="border rounded p-2">
                                <small class="text-muted">Brecha</small>
                                <div id="summary_gap">$0</div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <strong>Proceso:</strong> <span id="summary_process">-</span>
                        <span class="ms-4"><strong>Estado:</strong> <span id="summary_status">-</span></span>
                        <span class="ms-4"><strong>Fecha cálculo:</strong> <span id="summary_date">-</span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Consumo real por material</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-bordered" id="table_real_by_material">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Cantidad</th>
                                <th>Costo promedio</th>
                                <th>Costo total</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Prorrateado por material (fuente documento)</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-bordered" id="table_prorated_by_material">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Cantidad</th>
                                <th>Costo promedio</th>
                                <th>Costo total</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Detalle real por pallet</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-bordered" id="table_real_by_pallet">
                        <thead>
                            <tr>
                                <th>Pallet</th>
                                <th>Filas</th>
                                <th>Costo total</th>
                                <th>Materiales</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Configuracion de reglas</strong></div>
                <div class="card-body">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label" for="rule_selector">Regla seleccionada</label>
                            <select id="rule_selector" class="form-control"></select>
                        </div>
                        <div class="col-md-7">
                            <button type="button" class="btn btn-secondary" id="btn_rule_reload">Recargar</button>
                            <button type="button" class="btn btn-outline-primary" id="btn_rule_new">Nueva</button>
                            <button type="button" class="btn btn-outline-danger" id="btn_rule_delete">Eliminar</button>
                        </div>
                    </div>
                    <div id="config_message" class="alert mt-3 d-none"></div>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <input type="hidden" id="rule_set_id">
                            <label class="form-label" for="rule_name">Nombre</label>
                            <input type="text" id="rule_name" class="form-control mb-2">
                            <label class="form-label" for="rule_priority">Prioridad</label>
                            <input type="number" id="rule_priority" class="form-control mb-2" value="100">
                            <label class="form-label" for="rule_packaging_code">Filtro embalaje</label>
                            <input type="text" id="rule_packaging_code" class="form-control mb-2">
                            <label class="form-label" for="rule_exportadora_id">Filtro exportadora ID</label>
                            <input type="number" id="rule_exportadora_id" class="form-control mb-2">
                            <div class="form-check mb-2">
                                <input type="checkbox" id="rule_is_active" class="form-check-input" checked>
                                <label class="form-check-label" for="rule_is_active">Activa</label>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="btn_rule_save">Guardar regla</button>
                        </div>
                        <div class="col-md-4">
                            <input type="hidden" id="pattern_id">
                            <label class="form-label" for="pattern_total_rows">Patron: total filas</label>
                            <input type="number" id="pattern_total_rows" min="1" max="40" class="form-control mb-2">
                            <label class="form-label" for="pattern_rows_with_consumption">Filas con consumo (csv)</label>
                            <input type="text" id="pattern_rows_with_consumption" class="form-control mb-2" placeholder="1,2,3,6">
                            <label class="form-label" for="pattern_vertical_straps_count">Zunchos verticales</label>
                            <input type="number" id="pattern_vertical_straps_count" min="0" max="20" value="0" class="form-control mb-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btn_pattern_save">Guardar patron</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn_pattern_reset">Limpiar</button>
                        </div>
                        <div class="col-md-4">
                            <input type="hidden" id="rule_material_id">
                            <label class="form-label" for="rule_material_catalog">Material catalogo</label>
                            <select id="rule_material_catalog" class="form-control mb-2">
                                <option value="">Sin vinculo</option>
                                @foreach($materialsCatalog as $material)
                                    <option value="{{ $material->id }}">{{ $material->codigo }} - {{ $material->nombre }}</option>
                                @endforeach
                            </select>
                            <label class="form-label" for="rule_material_key">Material key</label>
                            <input type="text" id="rule_material_key" class="form-control mb-2">
                            <label class="form-label" for="rule_material_name">Nombre</label>
                            <input type="text" id="rule_material_name" class="form-control mb-2">
                            <label class="form-label" for="rule_material_qty">Cantidad</label>
                            <input type="number" step="0.0001" id="rule_material_qty" class="form-control mb-2" value="0">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btn_rule_material_save">Guardar material</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn_rule_material_reset">Limpiar</button>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4 table-responsive">
                            <table class="table table-sm table-bordered" id="table_rules_overview">
                                <thead><tr><th>ID</th><th>Nombre</th><th>P</th><th>A</th></tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-md-4 table-responsive">
                            <table class="table table-sm table-bordered" id="table_patterns">
                                <thead><tr><th>ID</th><th>Filas</th><th>Consumo</th><th>Accion</th></tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-md-4 table-responsive">
                            <table class="table table-sm table-bordered" id="table_rule_materials">
                                <thead><tr><th>ID</th><th>Key</th><th>Cant</th><th>Accion</th></tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const routes = {
            calculate: "{{ route('admin.material-consumption.calculate') }}"
        };

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function formatNumber(value) {
            const number = Number(value || 0);
            return number.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function formatMoney(value) {
            return '$' + formatNumber(value);
        }

        function setMessage(message, type) {
            const $message = $('#calc_message');
            $message.removeClass('d-none alert-success alert-danger alert-warning alert-info');
            $message.addClass('alert-' + type);
            $message.text(message);
        }

        function renderSummary(data) {
            $('#summary_rule').text(data.rule_set ? ('#' + data.rule_set.id + ' - ' + data.rule_set.name) : '-');
            $('#summary_real').text(formatMoney(data.totals.real));
            $('#summary_prorated').text(formatMoney(data.totals.prorated));
            $('#summary_gap').text(formatMoney(data.totals.gap));
            $('#summary_process').text(data.process_number || '-');
            $('#summary_status').text(data.status || '-');
            $('#summary_date').text(data.calculated_at || '-');
        }

        function renderMaterialTable(selector, rows) {
            const $tbody = $(selector + ' tbody');
            $tbody.empty();

            if (!rows || rows.length === 0) {
                $tbody.append('<tr><td colspan=\"4\" class=\"text-center\">Sin datos</td></tr>');
                return;
            }

            rows.forEach(function(row) {
                $tbody.append(
                    '<tr>' +
                        '<td>' + escapeHtml(row.material_name || row.material_key) + '</td>' +
                        '<td class=\"text-end\">' + formatNumber(row.quantity) + '</td>' +
                        '<td class=\"text-end\">' + formatMoney(row.avg_unit_cost) + '</td>' +
                        '<td class=\"text-end\">' + formatMoney(row.total_cost) + '</td>' +
                    '</tr>'
                );
            });
        }

        function renderPalletTable(rows) {
            const $tbody = $('#table_real_by_pallet tbody');
            $tbody.empty();

            if (!rows || rows.length === 0) {
                $tbody.append('<tr><td colspan=\"4\" class=\"text-center\">Sin datos</td></tr>');
                return;
            }

            rows.forEach(function(row) {
                const materials = (row.materials || []).map(function(material) {
                    return escapeHtml(material.material_name || material.material_key) +
                        ': ' + formatNumber(material.quantity) +
                        ' (' + formatMoney(material.total_cost) + ')';
                }).join('<br>');

                $tbody.append(
                    '<tr>' +
                        '<td>' + escapeHtml(row.pallet_code || '-') + '</td>' +
                        '<td class=\"text-end\">' + escapeHtml(row.total_rows || '-') + '</td>' +
                        '<td class=\"text-end\">' + formatMoney(row.total_cost) + '</td>' +
                        '<td>' + materials + '</td>' +
                    '</tr>'
                );
            });
        }

        $('#btn_calculate').on('click', function() {
            const processNumber = ($('#process_number').val() || '').trim();
            if (!processNumber) {
                setMessage('Debes ingresar un numero de proceso.', 'warning');
                return;
            }

            setMessage('Calculando consumo real. Espera por favor...', 'info');
            $('#btn_calculate').prop('disabled', true);

            $.ajax({
                url: routes.calculate,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    process_number: processNumber,
                    force_recalculate: $('#force_recalculate').is(':checked') ? 1 : 0
                },
                success: function(response) {
                    renderSummary(response);
                    renderMaterialTable('#table_real_by_material', response.real_by_material || []);
                    renderMaterialTable('#table_prorated_by_material', response.prorated_by_material || []);
                    renderPalletTable(response.real_by_pallet || []);

                    if (response.status === 'failed') {
                        setMessage('El cálculo falló: ' + (response.error_message || 'sin detalle'), 'danger');
                    } else {
                        setMessage('Cálculo completado para proceso ' + processNumber + '.', 'success');
                    }
                },
                error: function(xhr) {
                    const msg = (xhr.responseJSON && xhr.responseJSON.message)
                        ? xhr.responseJSON.message
                        : 'No fue posible calcular el consumo real.';
                    setMessage(msg, 'danger');
                },
                complete: function() {
                    $('#btn_calculate').prop('disabled', false);
                }
            });
        });
    })();
</script>
<script>
    (function() {
        const csrfToken = "{{ csrf_token() }}";
        const materialsCatalog = @json($materialsCatalog->keyBy('id')->map(function($m){ return ['id'=>$m->id,'nombre'=>$m->nombre,'codigo'=>$m->codigo]; }));
        const routes = {
            rulesIndex: "{{ route('admin.material-consumption.rules.index') }}",
            rulesStore: "{{ route('admin.material-consumption.rules.store') }}",
            ruleUpdate: "{{ route('admin.material-consumption.rules.update', ['ruleSet' => '__ID__']) }}",
            ruleDestroy: "{{ route('admin.material-consumption.rules.destroy', ['ruleSet' => '__ID__']) }}",
            patternUpsert: "{{ route('admin.material-consumption.rules.patterns.upsert', ['ruleSet' => '__ID__']) }}",
            patternDestroy: "{{ route('admin.material-consumption.rule-patterns.destroy', ['rowPattern' => '__ID__']) }}",
            materialStore: "{{ route('admin.material-consumption.rules.materials.store', ['ruleSet' => '__ID__']) }}",
            materialUpdate: "{{ route('admin.material-consumption.rule-materials.update', ['ruleMaterial' => '__ID__']) }}",
            materialDestroy: "{{ route('admin.material-consumption.rule-materials.destroy', ['ruleMaterial' => '__ID__']) }}"
        };

        let rulesState = [];
        let selectedRuleSetId = null;

        function url(template, id) { return template.replace('__ID__', id); }
        function keyfy(value) { return String(value || '').toUpperCase().replace(/[^A-Z0-9]+/g, '_').replace(/^_+|_+$/g, ''); }
        function findRule(id) { return rulesState.find(r => Number(r.id) === Number(id)) || null; }
        function setMsg(message, type = 'info') {
            const $m = $('#config_message');
            $m.removeClass('d-none alert-success alert-danger alert-warning alert-info').addClass('alert-' + type).text(message);
        }
        function api(method, endpoint, data, onSuccess) {
            $.ajax({
                method: method,
                url: endpoint,
                data: data || {},
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: onSuccess,
                error: function(xhr) {
                    const message = (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error))
                        ? (xhr.responseJSON.message || xhr.responseJSON.error)
                        : 'Error en la operacion.';
                    setMsg(message, 'danger');
                }
            });
        }

        function resetRuleForm() {
            $('#rule_set_id').val('');
            $('#rule_name').val('');
            $('#rule_priority').val('100');
            $('#rule_packaging_code').val('');
            $('#rule_exportadora_id').val('');
            $('#rule_is_active').prop('checked', true);
        }
        function resetPatternForm() {
            $('#pattern_id').val('');
            $('#pattern_total_rows').val('');
            $('#pattern_rows_with_consumption').val('');
            $('#pattern_vertical_straps_count').val('0');
        }
        function resetMaterialForm() {
            $('#rule_material_id').val('');
            $('#rule_material_catalog').val('');
            $('#rule_material_key').val('');
            $('#rule_material_name').val('');
            $('#rule_material_qty').val('0');
        }

        function renderRuleSelector() {
            const $sel = $('#rule_selector');
            $sel.empty();
            if (!rulesState.length) {
                $sel.append('<option value="">Sin reglas</option>');
                return;
            }
            rulesState.forEach(rule => {
                $sel.append('<option value="' + rule.id + '">#' + rule.id + ' - ' + rule.name + '</option>');
            });
        }

        function renderOverviewTable() {
            const $tbody = $('#table_rules_overview tbody');
            $tbody.empty();
            if (!rulesState.length) {
                $tbody.append('<tr><td colspan="4" class="text-center">Sin reglas</td></tr>');
                return;
            }
            rulesState.forEach(rule => {
                $tbody.append('<tr><td>' + rule.id + '</td><td>' + (rule.name || '-') + '</td><td>' + (rule.priority || '-') + '</td><td>' + (rule.is_active ? 'SI' : 'NO') + '</td></tr>');
            });
        }

        function renderPatterns(rule) {
            const $tbody = $('#table_patterns tbody');
            $tbody.empty();
            const rows = rule?.row_patterns || [];
            if (!rows.length) {
                $tbody.append('<tr><td colspan="4" class="text-center">Sin patrones</td></tr>');
                return;
            }
            rows.sort((a,b) => Number(a.total_rows || 0) - Number(b.total_rows || 0)).forEach(p => {
                const csv = Array.isArray(p.rows_with_consumption) ? p.rows_with_consumption.join(',') : '';
                $tbody.append('<tr>' +
                    '<td>' + p.id + '</td>' +
                    '<td>' + p.total_rows + '</td>' +
                    '<td>' + (csv || '-') + '</td>' +
                    '<td>' +
                        '<button class="btn btn-xs btn-outline-primary me-1 btn-edit-pattern" data-id="' + p.id + '">E</button>' +
                        '<button class="btn btn-xs btn-outline-danger btn-del-pattern" data-id="' + p.id + '">X</button>' +
                    '</td>' +
                '</tr>');
            });
        }

        function renderMaterials(rule) {
            const $tbody = $('#table_rule_materials tbody');
            $tbody.empty();
            const rows = rule?.materials || [];
            if (!rows.length) {
                $tbody.append('<tr><td colspan="4" class="text-center">Sin materiales</td></tr>');
                return;
            }
            rows.sort((a,b) => String(a.material_key || '').localeCompare(String(b.material_key || ''))).forEach(m => {
                $tbody.append('<tr>' +
                    '<td>' + m.id + '</td>' +
                    '<td>' + (m.material_key || '-') + '</td>' +
                    '<td>' + Number(m.quantity_per_unit || 0).toFixed(2) + '</td>' +
                    '<td>' +
                        '<button class="btn btn-xs btn-outline-primary me-1 btn-edit-rule-material" data-id="' + m.id + '">E</button>' +
                        '<button class="btn btn-xs btn-outline-danger btn-del-rule-material" data-id="' + m.id + '">X</button>' +
                    '</td>' +
                '</tr>');
            });
        }

        function renderCurrentRule() {
            const rule = findRule(selectedRuleSetId);
            if (!rule) {
                resetRuleForm();
                renderPatterns(null);
                renderMaterials(null);
                return;
            }
            $('#rule_set_id').val(rule.id || '');
            $('#rule_name').val(rule.name || '');
            $('#rule_priority').val(rule.priority || 100);
            $('#rule_packaging_code').val(rule.packaging_code || '');
            $('#rule_exportadora_id').val(rule.exportadora_id || '');
            $('#rule_is_active').prop('checked', !!rule.is_active);
            renderPatterns(rule);
            renderMaterials(rule);
            resetPatternForm();
            resetMaterialForm();
        }

        function refreshRules(preferredId) {
            api('GET', routes.rulesIndex, {}, function(response) {
                rulesState = Array.isArray(response) ? response : [];
                renderRuleSelector();
                renderOverviewTable();
                if (preferredId) {
                    selectedRuleSetId = Number(preferredId);
                }
                if (!selectedRuleSetId || !findRule(selectedRuleSetId)) {
                    selectedRuleSetId = rulesState.length ? Number(rulesState[0].id) : null;
                }
                if (selectedRuleSetId) {
                    $('#rule_selector').val(String(selectedRuleSetId));
                }
                renderCurrentRule();
            });
        }

        function requireRule() {
            if (!selectedRuleSetId) {
                setMsg('Selecciona o crea una regla primero.', 'warning');
                return null;
            }
            const rule = findRule(selectedRuleSetId);
            if (!rule) {
                setMsg('Regla no encontrada. Recarga.', 'warning');
                return null;
            }
            return rule;
        }

        $('#btn_rule_save').on('click', function() {
            const id = $('#rule_set_id').val();
            const name = ($('#rule_name').val() || '').trim();
            if (!name) {
                setMsg('Nombre de regla es obligatorio.', 'warning');
                return;
            }
            const payload = {
                name: name,
                priority: $('#rule_priority').val() || 100,
                packaging_code: ($('#rule_packaging_code').val() || '').trim() || null,
                exportadora_id: ($('#rule_exportadora_id').val() || '').trim() || null,
                is_active: $('#rule_is_active').is(':checked') ? 1 : 0
            };
            const method = id ? 'PUT' : 'POST';
            const endpoint = id ? url(routes.ruleUpdate, id) : routes.rulesStore;
            api(method, endpoint, payload, function(resp) {
                setMsg('Regla guardada.', 'success');
                refreshRules(resp.id || id);
            });
        });

        $('#btn_rule_new').on('click', function() {
            selectedRuleSetId = null;
            resetRuleForm();
            resetPatternForm();
            resetMaterialForm();
            setMsg('Formulario limpio para nueva regla.', 'info');
        });
        $('#btn_rule_reload').on('click', function() { refreshRules(selectedRuleSetId); });
        $('#btn_rule_delete').on('click', function() {
            const rule = requireRule();
            if (!rule) return;
            if (!confirm('Eliminar regla seleccionada?')) return;
            api('DELETE', url(routes.ruleDestroy, rule.id), {}, function() {
                setMsg('Regla eliminada.', 'success');
                selectedRuleSetId = null;
                refreshRules(null);
            });
        });
        $('#rule_selector').on('change', function() {
            selectedRuleSetId = Number($(this).val() || 0) || null;
            renderCurrentRule();
        });

        $('#btn_pattern_save').on('click', function() {
            const rule = requireRule();
            if (!rule) return;
            const totalRows = Number($('#pattern_total_rows').val() || 0);
            if (totalRows <= 0) {
                setMsg('Total filas debe ser mayor a 0.', 'warning');
                return;
            }
            const payload = {
                total_rows: totalRows,
                rows_with_consumption: ($('#pattern_rows_with_consumption').val() || '').trim(),
                vertical_straps_count: Number($('#pattern_vertical_straps_count').val() || 0),
                includes_corner_posts: 0,
                includes_pallet: 1,
                includes_grill: 0
            };
            api('POST', url(routes.patternUpsert, rule.id), payload, function() {
                setMsg('Patron guardado.', 'success');
                resetPatternForm();
                refreshRules(rule.id);
            });
        });
        $('#btn_pattern_reset').on('click', resetPatternForm);
        $(document).on('click', '.btn-edit-pattern', function() {
            const rule = requireRule();
            if (!rule) return;
            const id = Number($(this).data('id'));
            const pattern = (rule.row_patterns || []).find(p => Number(p.id) === id);
            if (!pattern) return;
            $('#pattern_id').val(pattern.id);
            $('#pattern_total_rows').val(pattern.total_rows || '');
            $('#pattern_rows_with_consumption').val(Array.isArray(pattern.rows_with_consumption) ? pattern.rows_with_consumption.join(',') : '');
            $('#pattern_vertical_straps_count').val(pattern.vertical_straps_count || 0);
        });
        $(document).on('click', '.btn-del-pattern', function() {
            const rule = requireRule();
            if (!rule) return;
            if (!confirm('Eliminar patron?')) return;
            api('DELETE', url(routes.patternDestroy, $(this).data('id')), {}, function() {
                setMsg('Patron eliminado.', 'success');
                refreshRules(rule.id);
            });
        });

        $('#btn_rule_material_save').on('click', function() {
            const rule = requireRule();
            if (!rule) return;
            const itemId = $('#rule_material_id').val();
            const materialId = ($('#rule_material_catalog').val() || '').trim() || null;
            const materialKey = keyfy($('#rule_material_key').val() || '');
            if (!materialKey) {
                setMsg('Material key es obligatorio.', 'warning');
                return;
            }
            const payload = {
                material_id: materialId,
                material_key: materialKey,
                material_name: ($('#rule_material_name').val() || '').trim() || materialKey,
                consumption_mode: 'fixed_per_pallet',
                quantity_per_unit: Number($('#rule_material_qty').val() || 0),
                cost_source: 'adm_doc_unit_cost',
                is_active: 1
            };
            const method = itemId ? 'PUT' : 'POST';
            const endpoint = itemId ? url(routes.materialUpdate, itemId) : url(routes.materialStore, rule.id);
            api(method, endpoint, payload, function() {
                setMsg('Material guardado.', 'success');
                resetMaterialForm();
                refreshRules(rule.id);
            });
        });
        $('#btn_rule_material_reset').on('click', resetMaterialForm);
        $('#rule_material_catalog').on('change', function() {
            const selectedId = Number($(this).val() || 0);
            if (!selectedId || !materialsCatalog[selectedId]) return;
            const item = materialsCatalog[selectedId];
            if (!($('#rule_material_name').val() || '').trim()) $('#rule_material_name').val(item.nombre || '');
            if (!($('#rule_material_key').val() || '').trim()) $('#rule_material_key').val(keyfy(item.nombre || item.codigo || ''));
        });
        $(document).on('click', '.btn-edit-rule-material', function() {
            const rule = requireRule();
            if (!rule) return;
            const id = Number($(this).data('id'));
            const item = (rule.materials || []).find(m => Number(m.id) === id);
            if (!item) return;
            $('#rule_material_id').val(item.id);
            $('#rule_material_catalog').val(item.material_id ? String(item.material_id) : '');
            $('#rule_material_key').val(item.material_key || '');
            $('#rule_material_name').val(item.material_name || '');
            $('#rule_material_qty').val(item.quantity_per_unit ?? 0);
        });
        $(document).on('click', '.btn-del-rule-material', function() {
            const rule = requireRule();
            if (!rule) return;
            if (!confirm('Eliminar material de regla?')) return;
            api('DELETE', url(routes.materialDestroy, $(this).data('id')), {}, function() {
                setMsg('Material eliminado.', 'success');
                refreshRules(rule.id);
            });
        });

        refreshRules(null);
    })();
</script>
@endsection
