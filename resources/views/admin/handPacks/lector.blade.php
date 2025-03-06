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
            const params = new URLSearchParams(window.location.search);

// Extraer los valores de cada parámetro
const rut = params.get("rut");
const embalaje = params.get("embalaje");
const guid = params.get("guid");
            $("#qr").focus();
            $("#qr").val(rut+"]"+embalaje+"]"+guid);
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
    });
    </script>
@endsection
