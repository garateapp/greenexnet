@extends('layouts.admin')
@section('content')
    <style>
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
    </style>
      @if (session('message'))
      <div class="alert alert-success">
          {{ session('message') }}
      </div>
  @endif
  @if (session('error'))
      <div class="alert alert-danger">
          {{ session('error') }}
      </div>
  @endif
    <div class="card">
        <div class="card-header">
            Trato Embalaje Contratistas
        </div>

        <div class="card-body">

            <form name="frmUploadTrato" method="POST" action="{{ route('admin.personals.uploadtrato') }}"
                enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="file">Selecciona archivo</label>
                    <input type="file" name="file" id="file" required>
                    <button type="submit" class="btn btn-primary" id="btnUploadTrato">Subir Archivo</button>
                    <a href="{{ route('admin.personals.downloadtrato') }}" target="_blank">Descargar Plantilla</a>
                </div>
            </form>

            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="text" id="fecha_inicio" class="form-control date" name="fecha_inicio" value=""
                    required />
            </div>
            <div class="form-group">
                <label for="fecha">Fecha Final</label>
                <input type="text" id="fecha_final" class="form-control date" name="fecha_final" value="" />
            </div>
            <div class="form-group">
                <label for="trabajador">Trabajador</label>
                <select id="personal_id" name="personal_id" class="form-control select2">
                    @foreach ($personal as $id => $entry)
                        )
                        <option value="{{ $id }}">{{ $entry }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="button" class="button btn-success" id="btnconsultar">Consultar</button>
            </div>

        </div>

    </div>
    <div class="card">
        <div class="card-header">
            Resultado trato
        </div>
        <div class="card-body">
            <button class="btn btn-warning" data-toggle="modal" data-target="#addModalTrato">
                Agregar
            </button>
            <div class="table-responsive">
                <table id="mainTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th></th> <!-- Para el botón de expansión -->
                            <th>ID</th>
                            <th>Rut</th>
                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                            <th>Total a Pagar</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="modal fade" id="addModalTrato" tabindex="-1" role="dialog" aria-labelledby="addModalTratoLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCostoModalLabel">Agregar Trato</h5>
                    <button type="button" id="btnCloseModal" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <form id="tratoForm">
                        <div class="form-group">
                            <label for="valor">9</label>
                            <input type="number" step="1" id="9" name="9" class="form-control"
                                value="0" required>
                        </div>
                        <div class="form-group">
                            <label for="valor">7</label>
                            <input type="number" step="1" id="7" name="7" class="form-control"
                                value="0" required>
                        </div>
                        <div class="form-group">
                            <label for="valor">6</label>
                            <input type="number" step="1" id="6" name="6" class="form-control"
                                value="0" required>
                        </div>
                        <div class="form-group">
                            <label for="valor">5</label>
                            <input type="number" step="1" id="5" name="5" class="form-control"
                                value="0" required>
                        </div>
                        <div class="form-group">
                            <input type="hidden" id="monto" class="text-black" readonly></input>
                            <input type="hidden" id="montoTotal" name="montoTotal" class="text-black"></input>
                            <span id="info" class="text-error"></span>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-warning" id="calcTratoBtn">Calcular</button>
                            <button type="button" class="btn btn-primary" id="saveTratoBtn">Guardar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let maxVal = 0;
            var factor = 0;
            var cant_x_factor = 0;
            const data = @json($data);
            // Configurar Select2
            $('.select2').select2();
            let table = $('#mainTable').DataTable({
                                data:data,
                                columns: [{
                                        className: 'details-control',
                                        orderable: false,
                                        data: null,
                                        defaultContent: '<button class="btn btn-info btn-sm">+</button>'
                                    },
                                    {
                                        data: 'id',
                                        title: 'ID'
                                    },
                                    {
                                        data: 'personal.rut',
                                        title: 'Rut'
                                    },
                                    {
                                        data: 'personal.nombre',
                                        title: 'Nombre'
                                    },
                                    {
                                        data: 'fecha',
                                        title: 'Fecha'
                                    },
                                    {
                                        data: 'cantidad',
                                        title: 'Cantidad'
                                    },
                                    {
                                        data: 'monto_a_pagar',
                                        title: 'Monto a Pagar',
                                        render: $.fn.dataTable.render.number(',', '.', 0)
                                    },
                                    {
                                        data: 'id',
                                        render: function(data) {
                                            return `<button class="btn btn-danger btn-sm delete-btn" data-id="${data}">Eliminar</button>`;
                                        },
                                        orderable: false,
                                        searchable: false
                                    }
                                ],
                                order: [
                                    [4, 'asc']
                                ],
                                destroy: true

                            });
            $(document).on('click', '#calcTratoBtn', function() {
                // Obtener los checkboxes seleccionados de la tabla 1

                calculaMontos();

            });

            function formatDate(dateStr) {
                var parts = dateStr.split('/');
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            $("#btnconsultar").on('click', function() {
                if ($("#fecha_inicio").val() == "" || $("#fecha_final").val() == "") {
                    alert("Por favor rellene los campos de fechas para mostrar los tratos de un periodo");
                } else if ($("#fecha_inicio").val() > $("#fecha_final").val()) {
                    alert("La fecha de inicio debe ser menor a la fecha final");

                } else {
                    var fechaInicio = formatDate($("#fecha_inicio").val());
                    var fechaFin = formatDate($("#fecha_final").val());
                    calculaMontos();
                    $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'POST',
                            url: "{{ route('admin.personals.consultahandpack') }}",
                            data: {
                                fecha: fechaInicio,
                                fecha_fin: fechaFin,
                                personal_id: $("#personal_id").val(),

                            }
                        })
                        .done(function(data) {
                            console.log(data);
                            const table = $('#mainTable').DataTable({
                                data: data,
                                columns: [{
                                        className: 'details-control',
                                        orderable: false,
                                        data: null,
                                        defaultContent: '<button class="btn btn-info btn-sm">+</button>'
                                    },
                                    {
                                        data: 'id',
                                        title: 'ID'
                                    },
                                    {
                                        data: 'personal.rut',
                                        title: 'Rut'
                                    },
                                    {
                                        data: 'personal.nombre',
                                        title: 'Nombre'
                                    },
                                    {
                                        data: 'fecha',
                                        title: 'Fecha'
                                    },
                                    {
                                        data: 'cantidad',
                                        title: 'Cantidad'
                                    },
                                    {
                                        data: 'monto_a_pagar',
                                        title: 'Monto a Pagar',
                                        render: $.fn.dataTable.render.number(',', '.', 0)
                                    },
                                    {
                                        data: 'id',
                                        render: function(data) {
                                            return `<button class="btn btn-danger btn-sm delete-btn" data-id="${data}">Eliminar</button>`;
                                        },
                                        orderable: false,
                                        searchable: false
                                    }
                                ],
                                order: [
                                    [4, 'asc']
                                ],
                                destroy: true

                            });
                        });
                }
            })
            $("#saveTratoBtn").on('click', function() {
                var fechaInicio = formatDate($("#fecha_inicio").val());
                calculaMontos();
                $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        url: "{{ route('admin.personals.guardatratohandpack') }}",
                        data: {
                            fecha: fechaInicio,
                            personal_id: $("#personal_id").val(),
                            cantidad: maxVal,
                            monto_a_pagar: $("#montoTotal").val(),
                            factor_a_pagar: factor,
                            cant_x_factor: cant_x_factor
                        }
                    })
                    .done(function(data) {
                        console.log(data);
                        const table = $('#mainTable').DataTable({
                            data: data,
                            columns: [{
                                    className: 'details-control',
                                    orderable: false,
                                    data: null,
                                    defaultContent: '<button class="btn btn-info btn-sm">+</button>'
                                },
                                {
                                    data: 'id',
                                    title: 'ID'
                                },
                                {
                                    data: 'personal.rut',
                                    title: 'Rut'
                                },
                                {
                                    data: 'personal.nombre',
                                    title: 'Nombre'
                                },
                                {
                                    data: 'fecha',
                                    title: 'Fecha'
                                },
                                {
                                    data: 'cantidad',
                                    title: 'Cantidad'
                                },
                                {
                                    data: 'monto_a_pagar',
                                    title: 'Monto a Pagar',
                                    render: $.fn.dataTable.render.number(',',
                                        '.', 0)
                                },
                                {
                                    data: 'id',
                                    render: function(data) {
                                        return `<button class="btn btn-danger btn-sm delete-btn" data-id="${data}">Eliminar</button>`;
                                    },
                                    orderable: false,
                                    searchable: false
                                }
                            ],
                            order: [
                                [4, 'asc']
                            ],
                            destroy: true

                        });
                        $("#addTratoModal").modal('hide');
                        $("#tratoForm")[0].reset();

                    });

            });
            $('#mainTable').on('click', '.delete-btn', function() {
                let id = $(this).data('id');

                if (confirm('¿Estás seguro de que deseas eliminar esta línea?')) {
                    $.ajax({
                        url: `/admin/personals/destroyhandpack/${id}`,
                        method: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                var fechaInicio = formatDate($("#fecha_inicio").val());
                    var fechaFin = formatDate($("#fecha_final").val());
                    calculaMontos();
                    $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'POST',
                            url: "{{ route('admin.personals.consultahandpack') }}",
                            data: {
                                fecha: fechaInicio,
                                fecha_fin: fechaFin,
                                personal_id: $("#personal_id").val(),

                            }
                        })
                        .done(function(data) {
                            console.log(data);
                            const table = $('#mainTable').DataTable({
                                data: data,
                                columns: [{
                                        className: 'details-control',
                                        orderable: false,
                                        data: null,
                                        defaultContent: '<button class="btn btn-info btn-sm">+</button>'
                                    },
                                    {
                                        data: 'id',
                                        title: 'ID'
                                    },
                                    {
                                        data: 'personal.rut',
                                        title: 'Rut'
                                    },
                                    {
                                        data: 'personal.nombre',
                                        title: 'Nombre'
                                    },
                                    {
                                        data: 'fecha',
                                        title: 'Fecha'
                                    },
                                    {
                                        data: 'cantidad',
                                        title: 'Cantidad'
                                    },
                                    {
                                        data: 'monto_a_pagar',
                                        title: 'Monto a Pagar',
                                        render: $.fn.dataTable.render.number(',', '.', 0)
                                    },
                                    {
                                        data: 'id',
                                        render: function(data) {
                                            return `<button class="btn btn-danger btn-sm delete-btn" data-id="${data}">Eliminar</button>`;
                                        },
                                        orderable: false,
                                        searchable: false
                                    }
                                ],
                                order: [
                                    [4, 'asc']
                                ],
                                destroy: true

                            });
                        });// Recargar la tabla
                            } else {
                                alert('Error al eliminar la línea.');
                            }
                        },
                        error: function() {
                            alert(
                                'Ocurrió un error al intentar eliminar la línea.'
                            );
                        }
                    });
                }
            });
            $('#btnUploadTrato').on('submit', function(e) {


                var formData = new FormData($("#formUploadTrato")[0]);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alert('Archivo subido exitosamente.');
                        const table = $('#mainTable').DataTable({
                            data: data,
                            columns: [{
                                    className: 'details-control',
                                    orderable: false,
                                    data: null,
                                    defaultContent: '<button class="btn btn-info btn-sm">+</button>'
                                },
                                {
                                    data: 'id',
                                    title: 'ID'
                                },
                                {
                                    data: 'personal.rut',
                                    title: 'Rut'
                                },
                                {
                                    data: 'personal.nombre',
                                    title: 'Nombre'
                                },
                                {
                                    data: 'fecha',
                                    title: 'Fecha'
                                },
                                {
                                    data: 'cantidad',
                                    title: 'Cantidad'
                                },
                                {
                                    data: 'monto_a_pagar',
                                    title: 'Monto a Pagar',
                                    render: $.fn.dataTable.render.number(',',
                                        '.', 0)
                                },
                                {
                                    data: 'id',
                                    render: function(data) {
                                        return `<button class="btn btn-danger btn-sm delete-btn" data-id="${data}">Eliminar</button>`;
                                    },
                                    orderable: false,
                                    searchable: false
                                }
                            ],
                            order: [
                                [4, 'asc']
                            ],
                            destroy: true

                        });
                        $("#addTratoModal").modal('hide');
                        $("#tratoForm")[0].reset();

                    },
                        // Aquí puedes agregar código para manejar la respuesta del servidor

                    error: function(response) {
                        alert('Error al subir el archivo.');
                        // Aquí puedes agregar código para manejar el error
                    }
                });
            });

            function calculaMontos() {
                var monto = 0;
                var montoTotal = 0;
                var info = '';
                var cont = 0;
                var c9 = $("#9").val();
                var c7 = $("#7").val();
                var c6 = $("#6").val();
                var c5 = $("#5").val();
                maxVal = Math.max(c9, c7, c6, c5);
                var t9 = c9 * 9;
                var t7 = c7 * 7;
                var t6 = c6 * 6;
                var t5 = c5 * 5;
                monto = t9 + t7 + t6 + t5;
                const valCajaResta = 315;
                const valCaja9 = 613;
                const valCaja7 = 477;
                const valCaja6 = 405;
                const valCaja5 = 341;


                if (c9 == maxVal) {
                    montoTotal = (((t9 + t7 + t6 + t5) - valCajaResta) / 9) * valCaja9;
                    factor = valCaja9;
                    cant_x_factor = 9;
                } else if (c7 == maxVal) {
                    montoTotal = (((t9 + t7 + t6 + t5) - valCajaResta) / 7) * valCaja7;
                    factor = valCaja7;
                    cant_x_factor = 7;
                } else if (c6 == maxVal) {
                    montoTotal = (((t9 + t7 + t6 + t5) - valCajaResta) / 6) * valCaja6;
                    factor = valCaja6;
                    cant_x_factor = 6;
                } else if (c5 == maxVal) {

                    montoTotal = (((t9 + t7 + t6 + t5) - valCajaResta) / 5) * valCaja5;
                    factor = valCaja5;
                    cant_x_factor = 5;
                } else {
                    montoTotal = 0;
                    factor = 0;
                    cant_x_factor = 0;

                }
                var info = "";
                $("#monto").val(Intl.NumberFormat('es-CL', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(montoTotal));
                $("#montoTotal").val(montoTotal);
                if (montoTotal > 0) {
                    info = 'Total a pagar: $' + (Intl.NumberFormat('es-CL', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(montoTotal));
                } else {
                    info = 'el total a pagar no supera $0';
                }
                $('#info').html(info);
            }
        });
    </script>
@endsection
