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


                <div id="scanned-result"></div>
                <div class="form-group">
                    <button class="btn btn-danger" type="button" id="btnGuardar" style="display:none">
                        {{ trans('global.save') }}
                    </button>
                </div>
            
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        //9.160.225'3]9 KGs]ddabb85d

        $(document).ready(function() {
            $("#qr").focus();
            $("#qr").on("change", function() {
                var qrCodeValue = $(this).val();
                qrCodeValue = qrCodeValue.replace(/[\r\n]+/g, '').trim();
                $("#btnGuardar").focus();
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
                                $("#scanned-result").html("EMBALAJE PROCESADO").addClass("alert alert-success").removeClass("alert alert-danger");

                                // Limpiar el campo QR y poner foco en él
                                $("#qr").val("");
                                // Asegurarse de que el campo esté vacío al poner foco en él
                                setTimeout(function() {
                                    $("#qr")
                                        .focus(); // Asegura que el foco se dé después de limpiar el valor
                                }, 100);
                            } else {
                                alert("EMBALAJE NO PROCESADO");
                                $("#scanned-result").html("EMBALAJE NO PROCESADO"+" "+response.data).addClass("alert alert-danger").removeClass("alert alert-success");
                                $("#qr").val("");
                                $("#qr")
                                .focus(); // Asegura que el foco se dé después de limpiar el valor

                            }
                        },
                        error: function(xhr) {
                            alert("Hubo un problema al enviar el QR.");
                            console.log(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endsection
