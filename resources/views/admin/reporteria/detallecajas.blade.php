@extends('layouts.admin')
@section('content')
    <style>
        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #a9dd94;
            border-color: #dee2e6 #dee2e6 #f8fafc;
        }

        tr.group {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }

        .group-header {
            font-weight: bold;
            cursor: pointer;
            background-color: #f2f2f2;
        }

        .details-table {
            margin: 10px 0;
            width: 100%;
            border-collapse: collapse;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .text-bold {
            font-weight: bolder;
        }


        .bg-info {
            background-color: #81b940 !important;
        }

        .bg-danger {
            background-color: #ff7313 !important;
        }

        #cerrarFiltros {
            cursor: pointer;
        }

        #chart-container {
            position: relative;
            width: 90%;
            /* Ajusta el ancho del gráfico */
            margin: auto;
            /* Centra el contenedor */
        }

        canvas {
            display: block;
            max-width: 100%;
            /* Asegura que el canvas no se desborde */
            height: auto !important;
            /* Mantiene la proporción del gráfico */
        }

        /* Estilo para hacer el gráfico responsivo */
        #chart-container {
            position: relative;
            width: 90%;
            /* Ajusta el ancho del gráfico */
            margin: auto;
            /* Centra el contenedor */
        }

        canvas {
            display: block;
            max-width: 100%;
            /* Asegura que el canvas no se desborde */
            /* height: auto !important; */
            /* Mantiene la proporción del gráfico */
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 90%;
        }

        th,
        td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: #f4f4f4;
        }

        .total-row {
            font-weight: bold;
            background-color: #e8f0fe;
        }

        #kilosPorDia {
            width: 100%;
            /* El tamaño que necesites */
            height: 400px;
            /* Establece un tamaño fijo o máximo */
            max-height: 600px;
            /* Evita el crecimiento infinito */
            overflow: auto;
            /* Permite desplazamiento si el contenido es más grande */
        }

        .highlight {
            background-color: green;
            color: white;
        }

        #loading-animation {
            display: flex;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
        }

        video {
            border-radius: 10px;
        }
    </style>
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pako/2.1.0/pako.min.js"></script>
            <script>
                function showLoading() {

                    $("#loading-animation").fadeIn();
                }

                function hideLoading() {
                    $("#loading-animation").fadeOut();
                }
                document.addEventListener("DOMContentLoaded", async () => {
                    let data = [];
                    await getCajas();
                    async function getCajas() {
                        try {
                            const response = await fetch('https://net.greenexweb.cl/storage/cajas2.json');
                             data = await response.json();
                            console.log(data);



                        } catch (error) {
                            console.error("Error al cargar cajas:", error);
                        }

                    }



                    function buscaCajas(ncajas) {
                        const ncaja = document.getElementById('CodCaja').value;
                        const errorDiv = document.getElementById('error-message');
                        const resultadoDiv = document.getElementById('resultado');

                        // Ocultar mensajes previos
                        errorDiv.style.display = 'none';
                        resultadoDiv.style.display = 'none';

                        // Abrir conexión con IndexedDB
                        const caja = data.find(item => item.ncaja === ncajas);




                        if (caja) {
                            // Mostrar los datos
                            document.getElementById('res-ncaja').textContent = caja.ncaja || '';
                            document.getElementById('res-productor').textContent = caja.n_productor ||
                                'No especificado';
                            document.getElementById('res-especie').textContent = caja.n_especie || 'No especificado';
                            document.getElementById('res-variedad').textContent = caja.n_variedad || 'No especificado';
                            document.getElementById('res-cantidad').textContent = caja.cantidad || '0';
                            document.getElementById('res-peso-neto').textContent = (caja.peso_neto || '0') + ' kg';
                            document.getElementById('res-peso-bruto').textContent = (caja.peso_bruto || '0') + ' kg';
                            document.getElementById('res-contenedor').textContent = caja.contenedor ||
                                'No especificado';
                            document.getElementById('res-nave').textContent = caja.nave || 'No especificado';
                            document.getElementById('res-destinatario').textContent = caja.n_destinatario ||
                                'No especificado';
                            document.getElementById('res-exportadora').textContent = caja.n_exportadora ||
                                'No especificado';
                            document.getElementById('res-fecha-cosecha').textContent = caja.fecha_cosecha ||
                                'No especificado';
                            document.getElementById('res-fecha-produccion').textContent = caja.fecha_produccion ||
                                'No especificado';
                            document.getElementById('res-fecha-despacho').textContent = caja.fecha_despacho ||
                                'No especificado';
                            // document.getElementById('res-fecha-Salida-etd').textContent = data.etd || 'No especificado';


                            resultadoDiv.style.display = 'block';
                        } else {
                            errorDiv.textContent = 'No se encontró la caja especificada';
                            errorDiv.style.display = 'block';
                        }

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
                });
            </script>
        @endsection
