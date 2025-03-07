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
            let rut='';
            let embalaje='';
            let guid='';
            // Convertir a un objeto URLSearchParams
            let queryString = window.location.search.split('?')[1];
            let params2 = new URLSearchParams(queryString);

            // Obtener el valor del parámetro "qr"
            let qrValue = params2.get("qr");

            if (qrValue) {
                // Dividir los valores usando el separador "|"
                let qrData = qrValue.split("|");

                // Asignar cada parte a una variable
                rut = qrData[0]; // 10.353.155-1
                embalaje = qrData[1]; // 6 Kgs
                guid = qrData[2]; // d362fac1

                // Imprimir en consola o usarlo como necesites
                console.log("rut:", rut);
                console.log("embalaje:", embalaje);
                console.log("guid:", guid);
            } else {
                console.log("El parámetro 'qr' no está presente en la URL.");
                rut = params.get("rut");
                embalaje = params.get("embalaje");
                guid = params.get("guid");
            }
            // Extraer los valores de cada parámetro

            $("#qr").focus();
            $("#qr").val(rut + "]" + embalaje + "]" + guid);
            var qrCodeValue = $("#qr").val();
            processQrCode(qrCodeValue);
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
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                $("#scanned-result2")
                                    .html("EMBALAJE PROCESADO")
                                    .addClass("alert alert-success")
                                    .removeClass("alert alert-danger");
                            } else {
                                $("#scanned-result2")
                                    .html("EMBALAJE NO PROCESADO: " + response.data)
                                    .addClass("alert alert-danger")
                                    .removeClass("alert alert-success");
                            }
                            // Limpiar y enfocar
                            $("#qr").val("");
                            setTimeout(function() {
                                $("#qr").focus();
                            }, 100);
                        },
                        error: function(xhr) {
                            $("#scanned-result2")
                                .html("Error al procesar el QR")
                                .addClass("alert alert-danger");
                            console.log(xhr.responseText);
                            $("#qr").val("");
                            $("#qr").focus();
                        }
                    });
                }
            }
            $("#qr").on("change", function() {
                var qrCodeValue = $(this).val();
                processQrCode(qrCodeValue);
            });
        });
    </script>
@endsection
