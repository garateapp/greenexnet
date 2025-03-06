@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.handPack.title_singular') }}
        </div>

        <div class="card-body">
           
                <div class="form-group">
                    <label class="required" for="rut">{{ trans('cruds.handPack.fields.rut') }}</label>
                    <input class="form-control" type="text" name="qr" id="qr" value="" required>

                </div>
                <span id="result" class="center"></span>
                <div id="reader" style="width:300px;height:250px"></div>
                <div id="reader__scan_region"
                style="width: 100%; min-height: 100px; text-align: center; position: relative;"><video muted="true"
                    playsinline="" style="width: 640px; display: block;"></video><canvas id="qr-canvas" width="360"
                    height="360" style="width: 360px; height: 360px; display: none;"></canvas>

                <div
                    style="display: none; position: absolute; top: 0px; z-index: 1; background: yellow; text-align: center; width: 100%;">
                    Scanner paused</div>
            </div>
            <div id="scanned-result"></div>
            <div id="scanned-result2"></div>
                <div id="scanned-result"></div>
                <div class="form-group">
                    <button class="btn btn-danger" type="button" id="btnGuardar" style="display:none">
                        {{ trans('global.save') }}
                    </button>
                </div>
            
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.0.3/highlight.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        //9.160.225'3]9 KGs]ddabb85d

        $(document).ready(function() {
            $("#qr").focus();
            let html5QrCode = new Html5Qrcode("reader");
            let lastMessage = null;
               // Función para procesar el QR (manual o escaneado)
               function processQrCode(qrCodeValue) {
                qrCodeValue = qrCodeValue.replace(/[\r\n]+/g, '').trim();
                console.log("Código QR escaneado: ", qrCodeValue);

                if (qrCodeValue.length > 0) {
                    $.ajax({
                        url: "{{ route('admin.hand-packs.lectorQr') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            qr: qrCodeValue
                        },
                        success: function (response) {
                            console.log(response);
                            if (response.success) {
                                $("#scanned-result")
                                    .html("EMBALAJE PROCESADO")
                                    .addClass("alert alert-success")
                                    .removeClass("alert alert-danger");
                            } else {
                                $("#scanned-result")
                                    .html("EMBALAJE NO PROCESADO: " + response.data)
                                    .addClass("alert alert-danger")
                                    .removeClass("alert alert-success");
                            }
                            // Limpiar y enfocar
                            $("#qr").val("");
                            setTimeout(function () {
                                $("#qr").focus();
                            }, 100);
                        },
                        error: function (xhr) {
                            $("#scanned-result")
                                .html("Error al procesar el QR")
                                .addClass("alert alert-danger");
                            console.log(xhr.responseText);
                            $("#qr").val("");
                            $("#qr").focus();
                        }
                    });
                }
            }
            $("#qr").on("change", function () {
                var qrCodeValue = $(this).val();
                processQrCode(qrCodeValue);
            });
            // Configuración del escáner de cámara
            const html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }, // Cuadro de escaneo cuadrado
                    experimentalFeatures: {
                        useBarCodeDetectorIfSupported: true
                    }
                }
            );

            let lastMessage = null;

            // Callback al escanear con éxito
             // Callback de escaneo exitoso
             function onScanSuccess(decodedText, decodedResult) {
                if (decodedText !== lastMessage) {
                    lastMessage = decodedText;
                    $("#qr").val(decodedText);
                    processQrCode(decodedText);
                }
            }

            // Obtener cámaras disponibles y llenar el select
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    const cameraSelect = $("#cameraSelect");
                    devices.forEach(device => {
                        cameraSelect.append(`<option value="${device.id}">${device.label || device.id}</option>`);
                    });

                    // Iniciar con la primera cámara por defecto
                    startScanner(devices[0].id);
                } else {
                    $("#scanned-result").html("No se encontraron cámaras").addClass("alert alert-danger");
                }
            }).catch(err => {
                $("#scanned-result").html("Error al acceder a las cámaras: " + err).addClass("alert alert-danger");
            });

            // Función para iniciar el escáner con una cámara específica
            function startScanner(cameraId) {
                const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                html5QrCode.start(
                    cameraId,
                    config,
                    onScanSuccess,
                    (error) => console.warn("Error de escaneo: ", error)
                ).catch(err => {
                    $("#scanned-result").html("Error al iniciar la cámara: " + err).addClass("alert alert-danger");
                });
            }

            // Cambiar cámara al seleccionar una nueva
            $("#cameraSelect").on("change", function () {
                const newCameraId = $(this).val();
                html5QrCode.stop().then(() => {
                    startScanner(newCameraId);
                }).catch(err => {
                    $("#scanned-result").html("Error al cambiar cámara: " + err).addClass("alert alert-danger");
                });
            });
        });
        
    </script>
     <script>
        // function docReady(fn) {
        //     // see if DOM is already available
        //     if (document.readyState === "complete" || document.readyState === "interactive") {
        //         // call on next available tick
        //         setTimeout(fn, 1);
        //     } else {
        //         document.addEventListener("DOMContentLoaded", fn);
        //     }
        // }
        // /** Ugly function to write the results to a table dynamically. */
        // function printScanResultPretty(codeId, decodedText, decodedResult) {

        //     ///const urlParams = new URL(`${decodedText}`).searchParams;
        //     $("#qr").val(`${decodedText}`);
        //     var qrCodeValue = $("#qr").val();
        //         qrCodeValue = qrCodeValue.replace(/[\r\n]+/g, '').trim();
        //         $("#btnGuardar").focus();
        //         console.log("Código QR escaneado: ", qrCodeValue);
        //         if (qrCodeValue.length > 0) {
        //             $.ajax({
        //                 url: "{{ route('admin.hand-packs.lectorQr') }}",
        //                 type: "POST",
        //                 data: {
        //                     _token: "{{ csrf_token() }}",
        //                     qr: qrCodeValue
        //                 },
        //                 success: function(response) {
        //                     console.log(response);
        //                     if (response.success) {
        //                         $("#scanned-result").html("EMBALAJE PROCESADO").addClass("alert alert-success").removeClass("alert alert-danger");

        //                         // Limpiar el campo QR y poner foco en él
        //                         $("#qr").val("");
        //                         // Asegurarse de que el campo esté vacío al poner foco en él
        //                         setTimeout(function() {
        //                             $("#qr")
        //                                 .focus(); // Asegura que el foco se dé después de limpiar el valor
        //                         }, 100);
        //                     } else {
        //                         alert("EMBALAJE NO PROCESADO");
        //                         $("#scanned-result").html("EMBALAJE NO PROCESADO"+" "+response.data).addClass("alert alert-danger").removeClass("alert alert-success");
        //                         $("#qr").val("");
        //                         $("#qr")
        //                         .focus(); // Asegura que el foco se dé después de limpiar el valor

        //                     }
        //                 },
        //                 error: function(xhr) {
        //                     alert("Hubo un problema al enviar el QR.");
        //                     console.log(xhr.responseText);
        //                 }
        //             });
        //         }
        //     // Formatear la fecha y hora como "YYYY-MM-DD HH:MM:SS"
            

        // }
        // docReady(function() {
        //     hljs.initHighlightingOnLoad();
        //     var lastMessage;
        //     var codeId = 0;

        //     function onScanSuccess(decodedText, decodedResult) {
        //         /**
        //          * If you following the code example of this page by looking at the
        //          * source code of the demo page - good job!!
        //          *
        //          * Tip: update this function with a success callback of your choise.
        //          */
        //         if (lastMessage !== decodedText) {
        //             lastMessage = decodedText;
        //             printScanResultPretty(codeId, decodedText, decodedResult);
        //             ++codeId;
        //         }
        //     }
        //     var qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
        //         // Square QR Box, with size = 80% of the min edge.
        //         var minEdgeSizeThreshold = 250;
        //         var edgeSizePercentage = 0.75;
        //         var minEdgeSize = (viewfinderWidth > viewfinderHeight) ?
        //             viewfinderHeight : viewfinderWidth;
        //         var qrboxEdgeSize = Math.floor(minEdgeSize * edgeSizePercentage);
        //         if (qrboxEdgeSize < minEdgeSizeThreshold) {
        //             if (minEdgeSize < minEdgeSizeThreshold) {
        //                 return {
        //                     width: minEdgeSize,
        //                     height: minEdgeSize
        //                 };
        //             } else {
        //                 return {
        //                     width: minEdgeSizeThreshold,
        //                     height: minEdgeSizeThreshold
        //                 };
        //             }
        //         }
        //         return {
        //             width: qrboxEdgeSize,
        //             height: qrboxEdgeSize
        //         };
        //     }
        //     let html5QrcodeScanner = new Html5QrcodeScanner(
        //         "reader", {
        //             fps: 10,
        //             qrbox: qrboxFunction,
        //             // Important notice: this is experimental feature, use it at your
        //             // own risk. See documentation in
        //             // mebjas@/html5-qrcode/src/experimental-features.ts
        //             experimentalFeatures: {
        //                 useBarCodeDetectorIfSupported: true
        //             },
        //             //rememberLastUsedCamera: true,
        //             //showTorchButtonIfSupported: true
        //         });
        //     html5QrcodeScanner.render(onScanSuccess);
        // });
    </script>
@endsection
