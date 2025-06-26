@extends('layouts.admin')

<!-- Bootstrap Datepicker CSS -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<style>
    .indicador {
        font-size: 1.5em;
        font-weight: bolder;
    }

    #loading-animation {
        display: flex;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
    }

    video {
        border-radius: 10px;
    }

    .select2 {
        flex: 1;
        /* Hace que los selects ocupen el mismo ancho */
        min-width: 150px;
        /* Evita que sean demasiado pequeños */
    }

    .subtotal-row {
        background-color: #f0f0f0;
        font-weight: bold;
    }

    tr.subtotal {
        font-weight: bold;
        background-color: #f0f0f0;
    }

    td.numeric {
        text-align: right;
    }

    .negative {
        color: red;
    }

    .form-switch .form-check-input:checked {
        background-position: right center;
        background-image: url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e);
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    #tarjadoTable {
        /* table-layout: fixed; */
        width: 100% !important;
    }

    #tarjadoTable td,
    #tarjadoTable th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    .dataTables_wrapper {
        overflow-x: auto;
    }

    .dataTables_wrapper .table {
        width: 100% !important;
        overflow-x: auto;
    }

    .dataTables_scrollBody {
        overflow-x: auto !important;
        max-width: 100%;
    }
</style>
<!-- Bootstrap Datepicker JS -->

@section('content')
    <div class="content">

        <div class="row">

            <div id="loading-animation"
                style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
                <video autoplay loop muted style="width: 200px; height: auto;">
                    <source src="{{ asset('img/transito.webm') }}" type="video/webm">
                    Your browser does not support the video tag.
                </video>
                <br />
                <div class="text-white text-opacity-75 text-end" id="loading-animation-text">Separando y Contando Cajas en
                    los
                    Pallets,
                    Espera por favor..... :)</div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>
                            <i class="fa fa-home"></i>
                            Tarjado
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="card">
                                    <div class="card-header">
                                        Filtros
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="fecha">Fecha Inicio</label>
                                                    <input type="text" id="filtroFecha" class="form-control date"
                                                        name="filtroFecha" value="" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="filtroEspecie">Especie</label>
                                                    <select class="form-control select2" id="filtroEspecie"
                                                        name="filtroEspecie" multiple="multiple">
                                                        <option value="">Seleccione un Especie</option>
                                                        @foreach ($especies as $especie)
                                                            <option value="{{ $especie->id }}">{{ $especie->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="filtroEmbalaje">Embalaje</label>
                                                    <select class="form-control select2" id="filtroEmbalaje"
                                                        name="filtroEmbalaje" multiple="multiple">
                                                        <option value="">Seleccione un Embalaje</option>
                                                        @foreach ($embalajes as $embalaje)
                                                            <option value="{{ $embalaje->id }}">{{ $embalaje->c_embalaje }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="filtroAltura">Altura</label>
                                                    <select class="form-control select2" id="filtroAltura"
                                                        name="filtroAltura" multiple="multiple">
                                                        <option value="">Seleccione un Altura</option>
                                                        <option value="T">Pallet Incompleto</option>
                                                        <option value="240">2,40</option>
                                                        <option value="120">1,20</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="button btn-success"
                                            id="btnconsultar">Consultar</button>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="card">
                                <div class="card-header">
                                    Tarjado
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="resumen" class="alert alert-info mt-3"></div>

                                            <div class="row">
                                                <table id="tarjadoTable"
                                                    class="table table-striped table-bordered dt-responsive nowrap"
                                                    style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>Especie</th>
                                                            <th>Variedad</th>
                                                            <th>Proceso</th>
                                                            <th>Notas</th>
                                                            <th>Estado</th>
                                                            <th>Embalaje</th>
                                                            <th>Categoría</th>
                                                            <th>Altura</th>
                                                            <th>Folio</th>
                                                            <!-- Las columnas de calibres se agregarán dinámicamente -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- dentro de las columnas calibres debemos colocar todos los valores de la columna cantidad -->
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal para mostrar materiales -->
                <div class="modal fade" id="materialesModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Materiales Utilizados</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Aquí se inyecta el contenido -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js">
            </script>
            <script>
                $(document).ready(function() {
                    $('.select2').select2();
                    // $('.date').datepicker({
                    //     format: 'yyyy-mm-dd',
                    //     autoclose: true,
                    //     todayHighlight: true,
                    //     language: 'es'
                    // });

                    let table;

                    function cargarDatos() {
                        const especies = $("#filtroEspecie").val() || [];
                        const embalaje = $("#filtroEmbalaje").val() || [];
                        const altura = $("#filtroAltura").val() || [];
                        const fecha = $("#filtroFecha").val();

                        $.ajax({
                            url: "{{ route('admin.tarjado.getTarjado') }}",
                            type: "post",
                            data: {
                                _token: "{{ csrf_token() }}",
                                especie: especies,
                                embalaje: embalaje,
                                altura: altura,
                                fecha: fecha
                            },
                            beforeSend: function() {
                                $('#loading-animation').show();
                            },
                            success: function(response) {
                                $('#loading-animation').hide();
                                console.log('Respuesta del servidor:', response);
                                // Destruir tabla si existe
                                if (table) table.destroy();

                                // Limpiar tabla actual
                                $('#tarjadoTable thead tr').empty()
                                    .append(
                                        `<th>Especie</th><th>Variedad</th><th>Proceso</th><th>Notas</th><th>Estado</th><th>Embalaje</th><th>Categoría</th><th>Altura</th><th>Folio</th>`
                                    );

                                // Agregar calibres como columnas
                                response.calibres.forEach(calibre => {
                                    $('#tarjadoTable thead tr').append(`<th>${calibre}</th>`);
                                });
                                $('#tarjadoTable thead tr').append(`<th>Total</th>`);
                                // Llenar cuerpo de la tabla
                                table = $('#tarjadoTable').DataTable({
                                    data: response.data,
                                    responsive: true, // Habilita responsividad
                                    autoWidth: false,
                                    scrollX: true, // Permite scroll horizontal
                                    scrollCollapse: true,

                                    columns: [{
                                            data: 'especie'
                                        },
                                        {
                                            data: 'variedad'
                                        },
                                        {
                                            data: 'proceso'
                                        },
                                        {
                                            data: 'notas',
                                            render: function(data, type, row) {
                                                return `<span title="${data}" data-toggle="tooltip">${data?.substring(0, 20)}...</span>`;
                                            }
                                        }, {
                                            data: 'estado'
                                        },
                                        {
                                            data: 'embalaje'
                                        },
                                        {
                                            data: 'categoria'
                                        },
                                        {
                                            data: 'altura'
                                        },
                                        {
                                            data: 'folio'
                                        },
                                        ...response.calibres.map(calibre => ({
                                            data: null,
                                            render: function(data, type, row) {
                                                return row.calibres[calibre] || '';
                                            }
                                        })),
                                        {
                                            data: null,
                                            render: function(data, type, row) {
                                                let total = 0;
                                                for (let key in row.calibres) {
                                                    total += parseInt(row.calibres[key]) || 0;
                                                }
                                                return total;
                                            },
                                            className: "text-right"
                                        }
                                    ],
                                    columnDefs: [{
                                            responsivePriority: 1,
                                            targets: 0
                                        }, // Prioridad alta para mostrar siempre
                                        {
                                            responsivePriority: 2,
                                            targets: -1
                                        } // Última columna (total) baja prioridad
                                    ],
                                    paging: true,
                                    searching: true,
                                    ordering: true,
                                    info: true,
                                    language: {
                                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                                    }
                                });
                                let totalProcesos = response.data.length;
                                let totalCajas = 0;

                                response.data.forEach(row => {
                                    for (let key in row.calibres) {
                                        totalCajas += parseInt(row.calibres[key]) || 0;
                                    }
                                });
                                $('[data-toggle="tooltip"]').tooltip();
                                $('#resumen').html(
                                    `Total Procesos: ${totalProcesos} | Total Cajas: ${totalCajas}`);
                                $('#tarjadoTable tbody').on('click', 'td', function() {
                                    const table = $('#tarjadoTable').DataTable();
                                    const row = table.row($(this).closest('tr'));
                                    const data = row.data();
                                    console.log("Datos de la fila:", data);
                                    // Ignorar clic en la columna "Total"
                                    const colIndex = $(this).index();
                                    const totalColumnIndex = $('#tarjadoTable thead th').length -
                                        1; // última columna es Total

                                    if (colIndex >= 9 && colIndex <
                                        totalColumnIndex
                                    ) { // ajusta este rango según tu número real de columnas fijas
                                        const calibre = response.calibres[colIndex -
                                            9]; // ajusta si tienes más/menos columnas fijas
                                        const cantidad = data.calibres[calibre];
                                        proceso=data.proceso;
                                        folio=data.folio;
                                        if (!cantidad || cantidad <= 0) return;

                                        $.ajax({
                                            url: "{{ route('admin.tarjado.getMaterialesUtilizados') }}",
                                            method: "POST",
                                            data: {
                                                _token: "{{ csrf_token() }}",
                                                folio: data.folio,
                                                embalaje_id: data.embalaje || null,
                                                altura: data.altura,
                                                fecha: $("#filtroFecha").val(),
                                                cantidad: cantidad
                                            },
                                            success: function(res) {
                                                console.log("Materiales utilizados:", res);

                                                // Ejemplo: mostrar en un modal o tooltip
                                                let tablaHTML = `
        <h5 class="mb-3">Materiales para el embalaje: ${res.embalaje} Folio: ${folio} Proceso: ${proceso}</h5>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Material</th>
                    <th>Unidad de Medida</th>
                    <th>Material Usado</th>
                    <th>Material Faltante</th>
                    <th>Costo Generado (CLP)</th>
                    <th>Costo Faltante (CLP)</th>
                    <th>Total (CLP)</th>
                </tr>
            </thead>
            <tbody>
                ${res.materialesUtilizados.map(m => {
                    const totalCLP = m.cantidadTotal * m.costoxcajaclp;
                    return `
                                    <tr>
                                        <td>${m.material.nombre}</td>
                                        <td>${m.material.unidad}</td>
                                        <td>${(m.materialusado).toLocaleString(
                                            'es-CL', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            })}</td>
                                        <td>${Math.abs(m.materialfaltante).toLocaleString(
                                            'es-CL', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            })}</td>
                                        <td>${Math.abs(m.costoxcajaclp*cantidad).toLocaleString(
                                            'es-CL', {
                                                minimumFractionDigits: 0,
                                                maximumFractionDigits: 0
                                            })}</td>
                                        <td>$${Math.abs(m.costoxcajaclp*m.cajasFaltantes) .toLocaleString(
                                            'es-CL', {
                                                minimumFractionDigits: 0,
                                                maximumFractionDigits: 0
                                            })}</td>
                                        <td>$${totalCLP.toFixed(0).toLocaleString(
                                            'es-CL', {
                                                minimumFractionDigits: 0,
                                                maximumFractionDigits: 0
                                            })}</td>
                                    </tr>
                                `;
                }).join('')}
            </tbody>
        </table>
    `;

                                                $('#materialesModal .modal-body').html(
                                                    tablaHTML);
                                                $('#materialesModal').modal('show');
                                            },
                                            error: function(err) {
                                                Swal.fire('Error',
                                                    'No se pudieron cargar los materiales.',
                                                    'error');
                                                console.error(err);
                                            }
                                        });
                                    }
                                });
                            },
                            error: function(error) {
                                $('#loading-animation').hide();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al cargar los datos.'
                                });
                                console.error(error);
                            }
                        });
                    }

                    // Manejar evento click del botón Consultar
                    $('#btnconsultar').on('click', function() {
                        cargarDatos();
                    });
                });
            </script>
        @endsection
