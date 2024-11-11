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

                    <div
                        style="display: none; position: absolute; top: 0px; z-index: 1; background: yellow; text-align: center; width: 100%;">
                        Scanner paused</div>
                </div>

                <div id="scanned-result"></div>
                <div class="form-group">
                    <label for="fecha_hora">{{ trans('cruds.asistencium.fields.fecha_hora') }}</label>
                    <input class="form-control datetime {{ $errors->has('fecha_hora') ? 'is-invalid' : '' }}"
                        type="text" name="fecha_hora" id="fecha_hora" value="{{ date('Y-m-d H:i:s') }}">
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

        docReady(function() {
            hljs.initHighlightingOnLoad();
            var lastMessage;
            var codeId = 0;
            //$("#personal_id").val("9160225-3");

            function onScanSuccess(decodedText, decodedResult) {
                /**
                 * If you following the code example of this page by looking at the
                 * source code of the demo page - good job!!
                 *
                 * Tip: update this function with a success callback of your choise.
                 */
                if (lastMessage !== decodedText) {
                    lastMessage = decodedText;
                    // Crear un objeto URL y usar URLSearchParams para acceder a los parámetros
                    const urlParams = new URL(lastMessage).searchParams;

                    // Obtener el valor del parámetro "RUN"
                    const runValue = urlParams.get("RUN");


                    //enviar datos de asistencia
                    try {
                        const response = await fetch(
                            'https://net.greenexweb.cl/api/v1/asistencia/guardarAsistencia', {
                                method: 'POST',
                                headers: {
                                    "Content-Type": "application/json",
                                    "Authorization": `Bearer ${token}`
                                },
                                body: JSON.stringify({
                                    run: runValue,
                                    turno: $("#turno_id").val(),
                                    ubicacion: $("#locacion_id").val(),
                                    puesto: data.find((item) => item.key === selectedId).key,
                                    // Enviar el valor de RUN al servidor
                                }),
                            });

                        const jsonResponse = await response.json();
                        if (response.ok) {

                            $("#personal_id").val("");
                        } else {
                            alert("Error al enviar los datos: " + jsonResponse.message);
                        }
                    } catch (error) {
                        alert("Error al enviar los datos: " + jsonResponse.message);
                    } finally {

                    }
                };
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
            }); html5QrcodeScanner.render(onScanSuccess);
        });
    </script>
@endsection
