@extends('layouts.admin')
@section('content')
    <div class="card">
        <div id="loading-animation"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
            <video autoplay loop muted style="width: auto; height: auto;">
                <source src="{{ asset('img/proceso.webm') }}" type="video/webm">
                Tu navegador no soporta el video.
            </video>
            <br />

        </div>
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Lector de Códigos de Cajas</span>
            <button id="btnSync" class="btn btn-secondary" title="Sincronizar Datos">
                <i class="fas fa-sync"></i>
            </button>
        </div>
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="CodCaja">Ingrese Número de Caja</label>
                    <input class="form-control" type="text" name="CodCaja" id="CodCaja" required>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="button" id="btn-consultar">Consultar</button>
                </div>
                <div class="card-body">

                    <div id="reader" style="width:300px;height:250px"></div>
                    <div id="reader__scan_region"
                        style="width: 100%; min-height: 100px; text-align: center; position: relative;">
                        {{-- <video muted="true" playsinline="" style="width: 640px; display: block;"></video><canvas
                            id="qr-canvas" width="360" height="360"
                            style="width: 360px; height: 360px; display: none;"></canvas> --}}

                        <div
                            style="display: none; position: absolute; top: 0px; z-index: 1; background: yellow; text-align: center; width: 100%;">
                            Scanner paused</div>
                    </div>

                    <div id="scanned-result"></div>
                    <div id="error-message" class="alert alert-danger" style="display: none;">
                    </div>

                    <div id="resultado" class="mt-4" style="display: none;">
                        <h4>Información de la Caja</h4>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>N° caja</th>
                                    <td id="res-ncaja"></td>
                                </tr>
                                <tr>
                                    <th>Productor</th>
                                    <td id="res-productor"></td>
                                </tr>
                                <tr>
                                    <th>Especie</th>
                                    <td id="res-especie"></td>
                                </tr>
                                <tr>
                                    <th>Variedad</th>
                                    <td id="res-variedad"></td>
                                </tr>
                                <tr>
                                    <th>Cantidad</th>
                                    <td id="res-cantidad"></td>
                                </tr>
                                <tr>
                                    <th>Peso Neto</th>
                                    <td id="res-peso-neto"></td>
                                </tr>
                                <tr>
                                    <th>Peso Bruto</th>
                                    <td id="res-peso-bruto"></td>
                                </tr>
                                <tr>
                                    <th>Exportadora</th>
                                    <td id="res-exportadora"></td>
                                </tr>
                                <tr>
                                    <th>Destinatario</th>
                                    <td id="res-destinatario"></td>
                                </tr>
                                <tr>
                                    <th>Nave</th>
                                    <td id="res-nave"></td>
                                </tr>
                                <tr>
                                    <th>Contenedor</th>
                                    <td id="res-contenedor"></td>
                                </tr>
                                <tr>
                                    <th>Fecha Producción</th>
                                    <td id="res-fecha-cosecha"></td>
                                </tr>
                                <tr>
                                    <th>Fecha Corte</th>
                                    <td id="res-fecha-produccion"></td>
                                </tr>
                                <tr>
                                    <th>Fecha Ingreso</th>
                                    <td id="res-fecha-despacho"></td>
                                </tr>
                                <tr>
                                    <th>Fecha Salida ETD</th>
                                    <td id="res-fecha-etd"></td>
                                </tr>
                                <tr>
                                    <th>Transporte</th>
                                    <td id="res-transporte"></td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.0.3/highlight.min.js"></script>
            <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
            <script>
                function showLoading() {

                    $("#loading-animation").fadeIn();
                }

                function hideLoading() {
                    $("#loading-animation").fadeOut();
                }
                $(document).ready(function() {
                    $("#btnSync").click(function() {
                        obtieneCajas();

                    });
                })

                async function obtieneCajas() {
                    let min = 0;
                    let max = 0;
                    showLoading();
                    try {
                        // Obtener valores mínimos y máximos
                        const response = await $.ajax({
                            url: "{{ route('admin.reporteria.getMinMaxCajas') }}",
                            type: "GET",
                            dataType: "json"
                        });

                        min = response.min;
                        max = response.max;

                        // Llamar a getCajas de forma secuencial
                        while (min < max) {
                            console.log(min);
                            min = await getCajas(min);

                        }

                        console.log("Datos cargados completamente.");
                        hideLoading();

                    } catch (error) {
                        console.error("Error en obtieneCajas:", error);
                    }
                }

                async function getCajas(min) {

                    const response = await $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('admin.reporteria.SyncDatosCajas') }}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            min: min
                        }
                    });

                    console.log(response.Cajas);


                    SetDBCajas(response.Cajas);
                    return response.min;

                    // } catch (error) {
                    //     console.error("Error en getCajas:", error);
                    //     throw error; // Interrumpe el bucle si hay un error
                    // }
                }

                function SetDBCajas(cajas) {
                    const request = indexedDB.open('CajasDB', 4); // Usa la misma versión que la anterior
                    request.onupgradeneeded = (event) => {
                        const db = event.target.result;
                        if (!db.objectStoreNames.contains('CajasDB')) {
                            const store = db.createObjectStore('CajasDB', {
                                keyPath: 'id',
                                autoIncrement: true
                            });
                            // Crear un índice para ncaja
                            store.createIndex('ncaja', 'ncaja', {
                                unique: false
                            });
                        }
                    };

                    request.onsuccess = function(event) {
                        const db = event.target.result;

                        if (!db.objectStoreNames.contains('CajasDB')) {
                            const store = db.createObjectStore("CajasDB", {
                                keyPath: "ncaja"
                            });
                            store.createIndex("ncaja", "ncaja");
                            console.error("El almacén 'CajasDB' no existe. Verifica la creación del almacén.");
                            return;
                        }

                        const transaction = db.transaction('CajasDB', 'readwrite');
                        const store = transaction.objectStore('CajasDB');

                        cajas.forEach(caja => {
                            if (caja.id_pkg_stock_det) {
                                store.put(caja); // Insertar si el campo clave es válido
                            } else {
                                console.warn("Objeto inválido, falta la clave primaria:", caja);
                            }
                        });

                        transaction.oncomplete = function() {
                            console.log("Datos almacenados correctamente en IndexedDB.");
                        };

                        transaction.onerror = function(event) {
                            console.error("Error en la transacción de IndexedDB:", event.target.error);
                        };
                    };

                    request.onerror = function(event) {
                        console.error("Error al abrir la base de datos IndexedDB:", event.target.error);
                    };
                }

                function contarRegistrosTotales() {
                    // Abre la base de datos
                    const request = indexedDB.open('CajasDB');

                    request.onsuccess = (event) => {
                        const db = event.target.result;

                        // Inicia una transacción de solo lectura
                        const transaction = db.transaction('CajasDB', 'readonly');
                        const store = transaction.objectStore('CajasDB');

                        // Usa el método count para obtener el total de registros
                        const countRequest = store.count();

                        countRequest.onsuccess = (event) => {
                            console.log(`📦 Total de registros en CajasDB: ${event.target.result}`);
                        };

                        countRequest.onerror = (event) => {
                            console.error('❌ Error al contar los registros:', event.target.error);
                        };
                    };

                    request.onerror = (event) => {
                        console.error('❌ Error al abrir la base de datos:', event.target.error);
                    };
                }

                function buscaCajas(ncajas) {
                    const ncaja = document.getElementById('CodCaja').value;
                    const errorDiv = document.getElementById('error-message');
                    const resultadoDiv = document.getElementById('resultado');

                    // Ocultar mensajes previos
                    errorDiv.style.display = 'none';
                    resultadoDiv.style.display = 'none';

                    // Abrir conexión con IndexedDB
                    const request = indexedDB.open('CajasDB', 4);

                    request.onerror = (event) => {
                        errorDiv.textContent = 'Error al conectar con la base de datos';
                        errorDiv.style.display = 'block';
                    };

                    request.onsuccess = (event) => {
                        const db = event.target.result;
                        const transaction = db.transaction(['CajasDB'], 'readonly');
                        const store = transaction.objectStore('CajasDB');
                        const index = store.index('ncaja');
                        const getRequest = index.get(ncaja);

                        getRequest.onsuccess = (event) => {
                            const data = event.target.result;
                            if (data) {
                                // Mostrar los datos
                                document.getElementById('res-ncaja').textContent = data.ncaja || '';
                                document.getElementById('res-productor').textContent = data.n_productor ||
                                    'No especificado';
                                document.getElementById('res-especie').textContent = data.n_especie || 'No especificado';
                                document.getElementById('res-variedad').textContent = data.n_variedad || 'No especificado';
                                document.getElementById('res-cantidad').textContent = data.cantidad || '0';
                                document.getElementById('res-peso-neto').textContent = (data.peso_neto || '0') + ' kg';
                                document.getElementById('res-peso-bruto').textContent = (data.peso_bruto || '0') + ' kg';
                                document.getElementById('res-contenedor').textContent = data.contenedor ||
                                    'No especificado';
                                document.getElementById('res-nave').textContent = data.nave || 'No especificado';
                                document.getElementById('res-destinatario').textContent = data.n_destinatario ||
                                    'No especificado';
                                document.getElementById('res-exportadora').textContent = data.n_exportadora ||
                                    'No especificado';
                                document.getElementById('res-fecha-cosecha').textContent = data.fecha_cosecha ||
                                    'No especificado';
                                document.getElementById('res-fecha-produccion').textContent = data.fecha_produccion ||
                                    'No especificado';
                                document.getElementById('res-fecha-despacho').textContent = data.fecha_despacho ||
                                    'No especificado';
                                document.getElementById('res-fecha-Salida-etd').textContent = data.etd ||


                                    resultadoDiv.style.display = 'block';
                            } else {
                                errorDiv.textContent = 'No se encontró la caja especificada';
                                errorDiv.style.display = 'block';
                            }
                        };

                        getRequest.onerror = (event) => {
                            errorDiv.textContent = 'Error al buscar la caja';
                            errorDiv.style.display = 'block';
                        };
                    };
                }

                function buscaEmbarque(folio) {

                    const request = indexedDB.open('EmbarqueDB');

                    request.onsuccess = (event) => {
                        const db = event.target.result;

                        if (!db.objectStoreNames.contains('EmbarqueDB')) {
                            console.error('El almacén "EmbarqueDB" no existe en la base de datos.');
                            return;
                        }

                        const transaction = db.transaction('EmbarqueDB', 'readonly');
                        const store = transaction.objectStore('EmbarqueDB');

                        const cursorRequest = store.openCursor();
                        let resultados = [];

                        cursorRequest.onsuccess = (event) => {
                            const cursor = event.target.result;

                            if (cursor) {
                                if (cursor.value.folio === folio) {
                                    resultados.push(cursor.value);
                                }
                                cursor.continue(); // Continúa al siguiente registro
                            } else {
                                console.log('🔍 Registros encontrados:', resultados);
                            }
                        };

                        cursorRequest.onerror = (event) => {
                            console.error(' Error al abrir el cursor:', event.target.error);
                        };

                        transaction.oncomplete = () => {
                            console.log(' Transacción completada correctamente.');
                        };
                    };

                    request.onerror = (event) => {
                        console.error(' Error al abrir la base de datos:', event.target.error);
                    };
                }

                function docReady(fn) {
                    // see if DOM is already available
                    if (document.readyState === "complete" || document.readyState === "interactive") {
                        // call on next available tick
                        setTimeout(fn, 1);
                    } else {
                        document.addEventListener("DOMContentLoaded", fn);
                    }
                }
                /** Ugly function to write the results to a table dynamically. */
                function printScanResultPretty(codeId, decodedText, decodedResult) {

                    // const urlParams = new URL(`${decodedText}`).searchParams;
                    // console.log(urlParams);
                    // // Obtener el valor del parámetro "RUN"
                    // const runValue = urlParams.get("RUN");
                    buscaCajas(decodedText);
                    alert(decodedText);
                    console.log(decodedText);
                    //$("#personal_id").val(runValue).trigger('change');




                }
                docReady(function() {
                    hljs.initHighlightingOnLoad();
                    var lastMessage;
                    var codeId = 0;

                    function onScanSuccess(decodedText, decodedResult) {
                        /**
                         * If you following the code example of this page by looking at the
                         * source code of the demo page - good job!!
                         *
                         * Tip: update this function with a success callback of your choise.
                         */
                        if (lastMessage !== decodedText) {
                            lastMessage = decodedText;
                            printScanResultPretty(codeId, decodedText, decodedResult);
                            ++codeId;
                        }
                    }
                    var qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
                        // Square QR Box, with size = 80% of the min edge.
                        var minEdgeSizeThreshold = 250;
                        var edgeSizePercentage = 0.75;
                        var minEdgeSize = (viewfinderWidth > viewfinderHeight) ?
                            viewfinderHeight : viewfinderWidth;
                        var qrboxEdgeSize = Math.floor(minEdgeSize * edgeSizePercentage);
                        if (qrboxEdgeSize < minEdgeSizeThreshold) {
                            if (minEdgeSize < minEdgeSizeThreshold) {
                                return {
                                    width: minEdgeSize,
                                    height: minEdgeSize
                                };
                            } else {
                                return {
                                    width: minEdgeSizeThreshold,
                                    height: minEdgeSizeThreshold
                                };
                            }
                        }
                        return {
                            width: qrboxEdgeSize,
                            height: qrboxEdgeSize
                        };
                    }
                    let html5QrcodeScanner = new Html5QrcodeScanner(
                        "reader", {
                            fps: 10,
                            qrbox: qrboxFunction,
                            // Important notice: this is experimental feature, use it at your
                            // own risk. See documentation in
                            // mebjas@/html5-qrcode/src/experimental-features.ts
                            experimentalFeatures: {
                                useBarCodeDetectorIfSupported: true
                            },
                            rememberLastUsedCamera: true,
                            showTorchButtonIfSupported: true
                        });
                    html5QrcodeScanner.render(onScanSuccess);
                });
                $("#btn-consultar").click(function() {
                    buscaCajas($("#CodCaja").val());
                });
                //contarRegistrosTotales();
            </script>
        @endsection
