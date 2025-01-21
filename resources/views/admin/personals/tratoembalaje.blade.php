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

        .highlight {
            background-color: green;
            color: white;
        }

        #loading-animation {
            display: flex;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
        }

        video {
            border-radius: 10px;
        }
    </style>
    <div id="loading-animation"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
        <video autoplay loop muted style="width: 300px; height: auto;">
            <source src="{{ asset('img/transito.webm') }}" type="video/webm">
            Your browser does not support the video tag.
        </video>
        <br />
        <div class="text-white text-opacity-75 text-end" id="loading-animation-text">Separando y Contando Cajas,
            Espera por favor..... :)</div>
    </div>
    <div class="card">
        <div class="card-header">
            Trato Embalaje
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.personals.ejecutaCuadratura') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="fecha">Fecha Inicio</label>
                    <input type="text" id="fecha_inicio" class="form-control date" name="fecha_inicio" value="" />
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha Final</label>
                    <input type="text" id="fecha_final" class="form-control date" name="fecha_final" value="" />
                </div>
                <button type="button" class="button btn-success" id="btnconsultar">Consultar</button>
            </form>

        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Listado de Tratos
        </div>
        <button id="exportExcel" class="btn btn-info" style="width: 10%;">Exportar a Excel BUK</button> &nbsp; &nbsp;
        &nbsp;<button id="exportDetalleExcel" class="btn btn-info" style="width: 10%;">Exportar Detalle a Excel </button>
        <div id="exportContainer" style="display: none;">
            <table id="exportTable">
                <thead>
                    <tr>
                        <th>Número de Documento</th>
                        <th>Código de Ficha</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se llenarán dinámicamente las filas -->
                </tbody>
            </table>

        </div>
        <div id="detalleExportContainer" style="display: none;">
            <table id="exportDetalleTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Trabajador</th>
                        <th>Embalaje</th>
                        <th>Cantidad</th>
                        <th>Valor por Kilo</th>

                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se llenarán dinámicamente las filas -->
                </tbody>
            </table>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="mainTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th></th> <!-- Para el botón de expansión -->
                            <th>Rut</th>
                            <th>Nombre</th>

                            <th>Total a Pagar</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>
        <script>
            $(document).ready(function() {
                function showLoading() {

                    $("#loading-animation").fadeIn();
                }

                function hideLoading() {
                    $("#loading-animation").fadeOut();
                }

                $(document).on('click', '#btnconsultar', function() {
                    // Obtener los checkboxes seleccionados de la tabla 1
                    showLoading();


                    $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'POST',
                            url: "{{ route('admin.personals.ejecutaTratoembalaje') }}",
                            data: {
                                fechaInicio: $("#fecha_inicio").val(),
                                fechaFinal: $("#fecha_final").val(),
                            }
                        })
                        .done(function(data) {

                            hideLoading();
                            const table = $('#mainTable').DataTable({
                                data: data,
                                columns: [{
                                        className: 'details-control',
                                        orderable: false,
                                        data: null,
                                        defaultContent: '<button class="btn btn-info btn-sm">+</button>'
                                    },
                                    {
                                        data: 'Rut_Trabajador',
                                        title: 'Rut'
                                    },
                                    {
                                        data: 'Nombre',
                                        title: 'Nombre'
                                    },

                                    {
                                        data: 'Total_a_pagar',
                                        title: 'Total a Pagar',
                                        render: $.fn.dataTable.render.number(',', '.', 0)
                                    }
                                ],
                                order: [
                                    [1, 'asc']
                                ],

                            });

                            function generateExportTable(data) {
                                const tbody = $('#exportTable tbody');
                                tbody.empty(); // Limpia el contenido anterior

                                data.forEach((item) => {
                                    const row = `
                                        <tr>
                                            <td>${item.Rut_Trabajador}</td>
                                            <td>F1</td>
                                            <td>${item.Total_a_pagar}</td>
                                        </tr>
                                    `;
                                    tbody.append(row);
                                });
                            }

                            function generateExportDetalleTable(d) {
                                console.log(d);
                                const tbody = $('#exportDetalleTable tbody');
                                tbody.empty(); // Limpia el contenido anterior
                                d.forEach(function(detalleFecha) {
                                    // Dentro de cada `detalleFecha`, iterar sobre su array `Detalles`
                                    detalleFecha.Detalles.forEach(function(detalle) {
                                        console.log(detalle);
                                            const row = `
                                    <tr>
                                            <td>${detalle.Fecha}</td>
                        <td>${detalle.Detalles[0].C_Trabajador}</td>
                        <td>${detalle.Detalles[0].N_embalaje_Actual}</td>
                        <td>${detalle.Detalles[0].Cantidad_Cajas}</td>
                        <td>${detalle.Detalles[0].Valor_Caja}</td>

                                        </tr>`;
                                        tbody.append(row);

                                    });
                                });

                            }
                            generateExportTable(data);
                            generateExportDetalleTable(data);
                            // Función para exportar la tabla a Excel
                            $('#exportExcel').on('click', function() {
                                const tableHTML = $('#exportTable').prop('outerHTML');
                                const filename = 'tratoembalaje.xlsx';

                                // Crear un archivo Excel con SheetJS
                                const wb = XLSX.utils.book_new();
                                const ws = XLSX.utils.table_to_sheet($(tableHTML)[0]);
                                XLSX.utils.book_append_sheet(wb, ws, 'Exportación');
                                XLSX.writeFile(wb, filename);
                            });
                            $('#exportDetalleExcel').on('click', function() {
                                const tableHTML = $('#exportDetalleTable').prop('outerHTML');
                                const filename = 'tratoembalajeDetalle.xlsx';

                                // Crear un archivo Excel con SheetJS
                                const wb = XLSX.utils.book_new();
                                const ws = XLSX.utils.table_to_sheet($(tableHTML)[0]);
                                XLSX.utils.book_append_sheet(wb, ws, 'Exportación');
                                XLSX.writeFile(wb, filename);
                            });
                            // Formato de subtabla
                            function format(d) {
                                // `d` es la fila con los datos principales
                                console.log(d);
                                let html =
                                    '<table class="table table-bordered"><thead><tr><th>Fecha</th><th>Trabajador</th><th>Embalaje</th><th>Cantidad</th><th>Valor por Kilo</th></tr></thead><tbody>';

                                // Iterar sobre el array `Detalles` de `d`
                                d.Detalles.forEach(function(detalleFecha) {
                                    // Dentro de cada `detalleFecha`, iterar sobre su array `Detalles`
                                    detalleFecha.Detalles.forEach(function(detalle) {

                                        html += `<tr>
                        <td>${detalleFecha.Fecha}</td>
                        <td>${detalle.C_Trabajador}</td>
                        <td>${detalle.N_embalaje_Actual}</td>
                        <td>${detalle.Cantidad_Cajas}</td>
                        <td>${detalle.Valor_Caja}</td>
                    </tr>`;
                                    });
                                });

                                html += '</tbody></table>';
                                return html;
                            }


                            // Evento para mostrar/ocultar subtabla
                            $('#mainTable tbody').on('click', 'td.details-control button', function() {
                                const tr = $(this).closest('tr');
                                const row = table.row(tr);

                                if (row.child.isShown()) {
                                    // Cierra la fila
                                    row.child.hide();
                                    $(this).text('+');
                                } else {
                                    // Muestra la subtabla
                                    row.child(format(row.data())).show();
                                    $(this).text('-');
                                }
                            });
                        });
                });
            });
        </script>
        <!-- Incluye SheetJS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    @endsection
