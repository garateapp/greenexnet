@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Lector de Códigos de Cajas
        </div>
        <div class="card-body">
            <div id="reader" style="width:300px;height:250px"></div>
            <div id="reader__scan_region" style="width: 100%; min-height: 100px; text-align: center; position: relative;">
                <video muted="true" playsinline="" style="width: 640px; display: block;"></video><canvas id="qr-canvas"
                    width="360" height="360" style="width: 360px; height: 360px; display: none;"></canvas>

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
