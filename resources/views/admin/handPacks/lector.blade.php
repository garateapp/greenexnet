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

            ///const urlParams = new URL(`${decodedText}`).searchParams;
            console.log(`${decodedText}`);
            $("#qr").val(`${decodedText}`);
            var qrCodeValue = $("#qr").val();
            processQrCode(qrCodeValue);
            // Formatear la fecha y hora como "YYYY-MM-DD HH:MM:SS"
            

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
                    //rememberLastUsedCamera: true,
                    //showTorchButtonIfSupported: true
                });
            html5QrcodeScanner.render(onScanSuccess);
        });
    });
    </script>
@endsection
