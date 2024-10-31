@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.datosCaja.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <form method="POST" action="#" name="formDatosCaja" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="fecha_inicio">Ingrese Número de Caja</label>
                    <input class="form-control " type="text" name="CodCaja" id="CodCaja" value="" required>


                </div>

                <div class="form-group">
                    <button class="btn btn-danger" type="button" id="btn-consultar">
                        Consultar
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Escanee el Código QR</div>
        <div class="card-body">
            <div id="divQR" class="text-center"
                style="display:none height:auto; width:128px; margin-left: auto; margin-right: auto"></div>
        </div>
        <div class="card-footer">
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        $(document).on('click', '#btn-consultar', function() {
            // Obtener los checkboxes seleccionados de la tabla 1
            $("#divQR").html('');


            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: 'datos-cajas/buscaDatosCaja',
                    data: {
                        codCaja: $("#CodCaja").val(),
                    }
                })
                .done(function(response) {
                    console.log(response);
                    if (response.success) {
                        $("#divQR").show();
                    }
                    if (response.length == 0) {
                        $("#divQR").html('No se encontró la caja :(');
                    } else {
                        var qrcode = new QRCode('divQR', {
                            text: response.CodLinea + "-" + response.Turno + "-" + response
                                .ProductorReal +
                                "-" + response.VariedadReal + "-" + response.Proceso + "-" + response
                                .CalibreTimbrado + "-100-" + response.Marca + "-" + response.CAT + "-" +
                                response.CodConfeccion + "-" + response.PesoTimbrado + "-" + response
                                .Salida,
                            width: 128, // Ancho del código QR
                            height: 128, // Alto del código QR
                            colorDark: "#000000",
                            colorLight: "#FFFFFF",
                            correctLevel: QRCode.CorrectLevel.M
                        });
                    }

                })
                .fail(function(response) {
                    console.log(response);
                });
        });
    </script>
@endsection
