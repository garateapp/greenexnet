@extends('layouts.admin')
@section('content')
    <div class="card">
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
                </div>
                <div class="card-body">

                    <div id="reader" style="width:300px;height:250px"></div>
                    <div id="reader__scan_region"
                        style="width: 100%; min-height: 100px; text-align: center; position: relative;">
                        <video muted="true" playsinline="" style="width: 640px; display: block;"></video><canvas
                            id="qr-canvas" width="360" height="360"
                            style="width: 360px; height: 360px; display: none;"></canvas>

                        <div
                            style="display: none; position: absolute; top: 0px; z-index: 1; background: yellow; text-align: center; width: 100%;">
                            Scanner paused</div>
                    </div>

                    <div id="scanned-result"></div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.0.3/highlight.min.js"></script>
            <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
            <script>
                $(document).ready(function() {
                    $("#btnSync").click(function() {
                        obtieneCajas();

                    });
                })

                async function obtieneCajas() {
                    let min = 0;
                    let max = 0;

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
                            min = await getCajas(min);
                        }

                        console.log("Datos cargados completamente.");

                    } catch (error) {
                        console.error("Error en obtieneCajas:", error);
                    }
                }

                async function getCajas(min) {
                    try {
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
                        const request = indexedDB.open('miBaseDatos', 2); // Incrementa la versión si es necesario

                        request.onupgradeneeded = function(event) {
                            const db = event.target.result;

                            if (!db.objectStoreNames.contains('cajas')) {
                                db.createObjectStore('cajas', {
                                    keyPath: 'id_pkg_stock_det'
                                });
                                console.log("Almacén de objetos 'cajas' creado correctamente.");
                            }
                        };

                        request.onsuccess = function(event) {
                            console.log("Base de datos abierta correctamente.");
                        };

                        request.onerror = function(event) {
                            console.error("Error al abrir la base de datos:", event.target.error);
                        };

                        SetDBCajas(response.Cajas);
                        return response.min;

                    } catch (error) {
                        console.error("Error en getCajas:", error);
                        throw error; // Interrumpe el bucle si hay un error
                    }
                }

                function SetDBCajas(cajas) {
                    const request = indexedDB.open('CajasDB', 2); // Usa la misma versión que la anterior

                    request.onsuccess = function(event) {
                        const db = event.target.result;

                        if (!db.objectStoreNames.contains('cajas')) {
                            console.error("El almacén 'cajas' no existe. Verifica la creación del almacén.");
                            return;
                        }

                        const transaction = db.transaction('cajas', 'readwrite');
                        const store = transaction.objectStore('cajas');

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

                function buscaCajas(folio) {
                    const request = indexedDB.open('CajasDB');
                    request.onsuccess = (event) => {
                        const db = event.target.result;
                        const transaction = db.transaction('CajasDB', 'readonly');
                        const store = transaction.objectStore('CajasDB');

                        const getAllRequest = store.getAll();

                        getAllRequest.onsuccess = (event) => {
                            const records = event.target.result;
                            const filtered = records.filter(record => record.folio === folio);
                            console.log('Registros filtrados:', filtered);
                        };

                        getAllRequest.onerror = (event) => {
                            console.error('Error al obtener los registros:', event.target.error);
                        };
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
            </script>
        @endsection
