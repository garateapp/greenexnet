@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.asistencium.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.asistencia.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="locacion_id">{{ trans('cruds.asistencium.fields.locacion') }}</label>
                    <select class="form-control select2 {{ $errors->has('locacion') ? 'is-invalid' : '' }}"
                        name="locacion_id" id="locacion_id" required>
                        @foreach ($locacions as $id => $entry)
                            <option value="{{ $id }}" {{ old('locacion_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('locacion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('locacion') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.asistencium.fields.locacion_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="turno_id">{{ trans('cruds.asistencium.fields.turno') }}</label>
                    <select class="form-control select2 {{ $errors->has('turno') ? 'is-invalid' : '' }}" name="turno_id"
                        id="turno_id" required>
                        @foreach ($turnos as $id => $entry)
                            <option value="{{ $id }}" {{ old('turno_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('turno'))
                        <div class="invalid-feedback">
                            {{ $errors->first('turno') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.asistencium.fields.turno_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="personal_id">{{ trans('cruds.asistencium.fields.personal') }}</label>
                    <select class="form-control select2 {{ $errors->has('personal') ? 'is-invalid' : '' }}"
                        name="personal_id" id="personal_id" required>
                        @foreach ($personals as $id => $entry)
                            <option value="{{ $id }}" {{ old('personal_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    <span id="error" style='color:darkred;' class="center"></span>
                    <div id="reader" style="width:300px;height:250px"></div>
                    <span id="result" class="center"></span>
                    @if ($errors->has('personal'))
                        <div class="invalid-feedback">
                            {{ $errors->first('personal') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.asistencium.fields.personal_helper') }}</span>
                </div>
                <div id="reader__scan_region"
                    style="width: 100%; min-height: 100px; text-align: center; position: relative;"><video muted="true"
                        playsinline="" style="width: 640px; display: block;"></video><canvas id="qr-canvas" width="360"
                        height="360" style="width: 360px; height: 360px; display: none;"></canvas>
                    <div id="qr-shaded-region"
                        style="position: absolute; border-width: 60px 140px; border-style: solid; border-color: rgba(0, 0, 0, 0.48); box-sizing: border-box; inset: 0px;">
                        <div
                            style="position: absolute; background-color: rgb(255, 255, 255); width: 40px; height: 5px; top: -5px; left: 0px;">
                        </div>
                        <div
                            style="position: absolute; background-color: rgb(255, 255, 255); width: 40px; height: 5px; top: -5px; right: 0px;">
                        </div>
                        <div
                            style="position: absolute; background-color: rgb(255, 255, 255); width: 40px; height: 5px; bottom: -5px; left: 0px;">
                        </div>
                        <div
                            style="position: absolute; background-color: rgb(255, 255, 255); width: 40px; height: 5px; bottom: -5px; right: 0px;">
                        </div>
                        <div
                            style="position: absolute; background-color: rgb(255, 255, 255); width: 5px; height: 45px; top: -5px; left: -5px;">
                        </div>
                        <div
                            style="position: absolute; background-color: rgb(255, 255, 255); width: 5px; height: 45px; bottom: -5px; left: -5px;">
                        </div>
                        <div
                            style="position: absolute; background-color: rgb(255, 255, 255); width: 5px; height: 45px; top: -5px; right: -5px;">
                        </div>
                        <div
                            style="position: absolute; background-color: rgb(255, 255, 255); width: 5px; height: 45px; bottom: -5px; right: -5px;">
                        </div>
                    </div>
                    <div
                        style="display: none; position: absolute; top: 0px; z-index: 1; background: yellow; text-align: center; width: 100%;">
                        Scanner paused</div>
                </div>
                <div id="reader__dashboard" style="width: 100%;">
                    <div id="reader__dashboard_section" style="width: 100%; padding: 10px 0px; text-align: left;">
                        <div>
                            <div id="reader__dashboard_section_csr" style="display: block; text-align: center;"><span
                                    style="margin-right: 10px;"><select id="html5-qrcode-select-camera"
                                        class="html5-qrcode-element" disabled="" style="display: none;">
                                        <option value="2c722bee16d9525d6e7c98c5f54fdf0b0ed93276c27907d47c00ffde9b4ac043">
                                            Integrated Camera (174f:11a8)</option>
                                    </select></span><span><button id="html5-qrcode-button-camera-start"
                                        class="html5-qrcode-element" style="opacity: 1; display: none;">Start
                                        Scanning</button><button id="html5-qrcode-button-camera-stop"
                                        class="html5-qrcode-element" style="display: inline-block;">Stop
                                        Scanning</button><button id="html5-qrcode-button-torch" class="html5-qrcode-element"
                                        style="display: none; margin-left: 5px;">Switch On Torch</button></span></div>
                            <div
                                style="text-align: center; margin: auto auto 10px; width: 80%; max-width: 600px; border: 6px dashed rgb(235, 235, 235); padding: 10px; display: none;">
                                <label for="html5-qrcode-private-filescan-input" style="display: inline-block;"><button
                                        id="html5-qrcode-button-file-selection" class="html5-qrcode-element">Choose Image -
                                        No image choosen</button><input id="html5-qrcode-private-filescan-input"
                                        class="html5-qrcode-element" type="file" accept="image/*"
                                        style="display: none;"></label>
                                <div style="font-weight: 400;">Or drop an image to scan</div>
                            </div>
                        </div>
                        <div style="text-align: center;"><a id="html5-qrcode-anchor-scan-type-change"
                                class="html5-qrcode-element" style="text-decoration: underline; display: none;">Scan an
                                Image File</a></div>
                    </div>
                </div>
                <div id="scanned-result"></div>
                <div class="form-group">
                    <label for="fecha_hora">{{ trans('cruds.asistencium.fields.fecha_hora') }}</label>
                    <input class="form-control datetime {{ $errors->has('fecha_hora') ? 'is-invalid' : '' }}"
                        type="text" name="fecha_hora" id="fecha_hora" value="{{ old('fecha_hora') }}">
                    @if ($errors->has('fecha_hora'))
                        <div class="invalid-feedback">
                            {{ $errors->first('fecha_hora') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.asistencium.fields.fecha_hora_helper') }}</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
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
            let resultSection = document.getElementById('scanned-result');
            let tableBodyId = "scanned-result-table-body";
            if (!document.getElementById(tableBodyId)) {
                let table = document.createElement("table");
                table.className = "styled-table";
                table.style.width = "100%";
                resultSection.appendChild(table);
                let theader = document.createElement('thead');
                let trow = document.createElement('tr');
                let th1 = document.createElement('td');
                th1.innerText = "Count";
                let th2 = document.createElement('td');
                th2.innerText = "Format";
                let th3 = document.createElement('td');
                th3.innerText = "Result";
                trow.appendChild(th1);
                trow.appendChild(th2);
                trow.appendChild(th3);
                theader.appendChild(trow);
                table.appendChild(theader);
                let tbody = document.createElement("tbody");
                tbody.id = tableBodyId;
                table.appendChild(tbody);
            }
            let tbody = document.getElementById(tableBodyId);
            let trow = document.createElement('tr');
            let td1 = document.createElement('td');
            td1.innerText = `${codeId}`;
            let td2 = document.createElement('td');
            td2.innerText = `${decodedResult.result.format.formatName}`;
            let td3 = document.createElement('td');
            td3.innerText = `${decodedText}`;
            trow.appendChild(td1);
            trow.appendChild(td2);
            trow.appendChild(td3);
            tbody.appendChild(trow);
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
