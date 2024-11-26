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
    </style>
    <div class="content">
        <div class="container-lg px-4">
            <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card text-white bg-info">
                        <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fs-4 fw-bold text-center">
                                    <div class="text-center">Kilos en Espera</div>
                                    <i class="fas fa-box-open" style="color: #FFFFFF; font-size: x-large;"></i>
                                    <span class="fs-6 fw-normal text-center" id="proSinIniciar" style="font-size: x-large;">
                                    </span>


                                </div>
                                <div class="text-center"><br /></div>
                            </div>

                        </div>
                    </div>
                </div>
                {{-- <div class="col-sm-6 col-xl-3">
                    <div class="card text-white bg-info">
                        <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-center">Kilos Procesado a la Fecha</div>
                                <div class="fs-4 fw-bold text-center">
                                    <i class="fas fa-cogs" style="color: #FFFFFF; font-size: x-large;"></i>
                                    <span class="fs-6 fw-normal text-right" id="proIniciado"
                                        style="font-size: x-large;"></span>


                                </div>
                                <div class="text-center"><br /></div>
                            </div>

                        </div>
                    </div>
                </div> --}}


                <div class="col-sm-6 col-xl-3">
                    <div class="card text-white bg-info">
                        <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-center">Máximo Horas de Espera</div>
                                <div class="fs-4 fw-semibold text-center">
                                    <i class="fas fa-clock" style="color: #FFFFFF; font-size: x-large;"></i>
                                    <span class="fs-6 fw-normal text-center" id="horaEspera"
                                        style="font-size: x-large;"></span>
                                </div>
                                <div class="text-center"><br /></div>
                            </div>
                        </div>
                    </div>

                </div>



                {{-- <div class="col-sm-6 col-xl-3">
                    <div class="card text-white bg-info">
                        <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-center">Cajas Procesadas</div>
                                <div class="fs-4 fw-semibold">
                                    <i class="fas fa-box" style="color: #FFFFFF; font-size: x-large;"></i>
                                    <span class="fs-6 fw-normal text-center" id="cajasProcesadas"
                                        style="font-size: x-large;">
                                    </span>

                                </div>
                                <div class="text-center"><br /></div>

                            </div>

                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="row" id="graficosContainer" style="display: none;">
                <!-- Primera columna -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Gráfico de Variedades</h5>
                            <canvas id="variedadesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Segunda columna -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Gráfico de Nota de Calidad</h5>
                            <canvas id="notaCalidadChart" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-12 table-responsive">
                <button class="btn bg-danger mb-3" id="toggleButton" title="Ocultar/Mostrar">
                    <i class="fa fa-chart-bar text-white"></i>

                </button>
                <button id="toggleFiltros" class="btn btn-primary mb-3"><i class="fas fa-filter"></i></button>
                <button id="btnRecargar" class="btn btn-secondary mb-3"><i class="fas fa-sync"></i></button>

                <div id="filtrosSlide" class="filtros-slide">
                    <h5>Filtros</h5>
                    <button id="cerrar" class="btn btn-danger" style="float: right;margin-top: -30px;"><i
                            class="fas fa-close"></i></button>
                    <div id="filtros">
                        <label for="filtroEmpresa">Empresa</label>
                        <select id="filtroEmpresa" class="form-control select2" multiple="multiple"></select>

                        <label for="filtroExportadora">Exportadora</label>
                        <select id="filtroExportadora" class="form-control select2" multiple="multiple"></select>

                        <label for="filtroProductor">Productor</label>
                        <select id="filtroProductor" class="form-control select2" multiple="multiple"></select>

                        <label for="filtroEspecie">Especie</label>
                        <select id="filtroEspecie" class="form-control select2" multiple="multiple"></select>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        Recepciones
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="lotesTable"
                                class="display table table-bordered table-striped table-hover ajaxTable datatable datatable-existencias"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        {{-- <th>Especie</th>
                                    <th>Empresa</th>
                                    <th>Exportadora</th>
                                    <th>Productor</th>
                                     --}}
                                        <th></th>
                                        <th>Variedad</th>
                                        <th>Nota Calidad</th>
                                        <th>Peso Neto</th>
                                        <th>Horas en Espera</th>

                                        {{-- <th>N° Recepción</th>
                                    <th>Cajas</th> --}}


                                    </tr>

                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th colspan="1">Subtotal<br />Totales</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        {{-- <th></th> --}}
                                        {{-- <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th> --}}
                                    </tr>

                                </tfoot>
                                <tbody>
                                    <!-- Los datos se cargarán aquí -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#btnRecargar").on('click', function() {
                location.reload();
            });
            $('#toggleFiltros').on('click', function() {
                $('#filtrosSlide').toggleClass('active');
            });
            $('#cerrar').on('click', function() {
                $('#filtrosSlide').toggleClass('active');
            });
            $('#toggleButton').on('click', function() {
                $('#graficosContainer').slideToggle('fast', function() {
                    // Cambiar el texto del botón según el estado del div
                    if ($(this).is(':visible')) {
                        $('#toggleButton').addClass('bg-info');
                        $('#toggleButton').removeClass('bg-danger');
                        $('#toggleButton').html('<i class="fa fa-chart-bar text-white"></i><br />');
                    } else {
                        $('#toggleButton').html('<i class="fa fa-chart-bar text-white"></i><br />');
                        $('#toggleButton').addClass('bg-danger');
                        $('#toggleButton').removeClass('bg-info');
                    }
                });
            });

            function format(d) {
                // `d` is the original data object for the row

                var tablaN2 =
                    `<table class="table table-bordered table-striped table-hover ajaxTable datatable table-responsive" id="tblNivel2">
                        <tr>
                            <td></td>
                            <td>Variedad</td>
                            <td>Nota Calidad </td>
                            <td> Peso Neto </td>
                            <td> Horas en Espera</td>
                            <td> Lote </td>

                            </tr>
                        <tbody>`;
                if (Array.isArray(d.nivel_2) && d.nivel_2.length > 0) {
                    // Construir las filas de la tabla dinámicamente
                    let rows = '';
                    d.nivel_2.forEach(item => {

                        rows = `


            <tr data-id="${item.numero_g_recepcion}">
                <td class="dt-control sorting_2"></td>
                <td>${item.n_variedad}</td>
                <td>${item.nota_calidad} </td>
                <td> ${formatNumber(item.peso_neto)} </td>
                <td> ${item.horas_en_espera} </td>
                <td> ${item.numero_g_recepcion} </td>
            </tr>
            <tr class="detalle" data-id="${item.numero_g_recepcion}" style="display: none;">
                  <td colspan="4">
                 <dl>
                    <dt>Empresa:</dt>
                    <dd>
                    ${item.n_empresa}
                    </dd>
                    <dt>Exportadora:</dt>
                    <dd>
                    ${item.n_exportadora}
                    </dd>
                    <dt>Productor:</dt>
                    <dd>${item.n_productor}  </dd>
                    <dt>Horas En Espera:</dt>
                    <dd> ${item.horas_en_espera} </dd>'
                    </dl>
                    </td>
                </tr>
            `;
                        tablaN2 += rows;
                        $rows = '';


                    });
                    tablaN2 += `</tbody></table>`;
                }

                return tablaN2;
            }
            $('#lotesTable thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#lotesTable thead');

            let table = $('#lotesTable').DataTable({
                fixedColumns: true,
                fixedHeader: true,
                responsive: true,
                language: {

                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-CL.json'
                },
                ajax: {
                    url: "{{ route('admin.reporteria.obtieneDatosStockInventario') }}",
                    method: "GET",
                    dataSrc: 'data', // Asegúrate de que los datos provienen de la propiedad 'data'

                },

                displayLength: 10,
                initComplete: function() {
                    let api = this.api();
                    let uniqueValues = {
                        n_empresa: [],
                        n_exportadora: [],
                        n_productor: [],
                        n_especie: []
                    };
                    api.columns().every(function() {
                        let column = this;
                        if (column.index() < 3) {
                            if (column.index == 2) {
                                var
                                    select = $(
                                        '<select style="width: 100%;" id="filtroNota"><option value="">Todos</option></select>'
                                    )
                                    .appendTo($(column.header()).empty()).on('change',
                                        function() {
                                            var val = $.fn.dataTable.util
                                                .escapeRegex($(this).val());
                                            column.search(val ? '^' + val + '$' : '', true,
                                                false).draw();
                                        }
                                    ); // Extraevalores únicos de la columna y los agrega al < select >
                                column
                                    .data()
                                    .unique()
                                    .sort()
                                    .each(function(d) {
                                        select.append('<option value="' +
                                            d + '">' + d +
                                            '</option>');
                                    });
                            } else {
                                var select = $(
                                        '<select style="width: 100%;"><option value = "">Todos</option> </select>'
                                    )
                                    .appendTo($(column.header()).empty())
                                    .on('change', function() {
                                        var val = $.fn.dataTable.util
                                            .escapeRegex($(this)
                                                .val());
                                        column.search(val ? '^' + val +
                                                '$' : '', true,
                                                false)
                                            .draw();
                                    });

                                // Extrae valores únicos de la columna y los agrega al <select>
                                column
                                    .data()
                                    .unique()
                                    .sort()
                                    .each(function(d) {
                                        select.append('<option value="' +
                                            d + '">' + d +
                                            '</option>');
                                    });
                            }
                        } else {
                            // Para las demás columnas, mantenemos el filtro de texto
                            var input = $(
                                    '<input type="text" placeholder="Filtrar..." style="width: 100%; box-sizing: border-box;" />'
                                )
                                .appendTo($(column.header()).empty())
                                .on('keyup change clear', function() {
                                    if (column.search() !== this.value) {
                                        column.search(this.value).draw();
                                    }
                                });
                        }
                    });
                    api.columns.adjust();

                    api.rows().every(function() {
                        var dataDisgregada = this.data().nivel_2;
                        dataDisgregada.forEach((data) => {

                            console.log(data);
                            if (data.n_empresa && !uniqueValues.n_empresa.includes(
                                    data
                                    .n_empresa)) uniqueValues.n_empresa.push(data
                                .n_empresa);
                            if (data.n_exportadora && !uniqueValues.n_exportadora
                                .includes(data.n_exportadora)) uniqueValues
                                .n_exportadora.push(data.n_exportadora);
                            if (data.n_productor && !uniqueValues.n_productor
                                .includes(
                                    data.n_productor)) uniqueValues.n_productor
                                .push(
                                    data.n_productor);
                            if (data.n_especie && !uniqueValues.n_especie.includes(
                                    data
                                    .n_especie)) uniqueValues.n_especie.push(data
                                .n_especie);
                        });

                    });

                    // Ordenar los valores para tener una mejor presentación
                    uniqueValues.n_empresa.sort();
                    uniqueValues.n_exportadora
                        .sort();
                    uniqueValues.n_productor.sort();
                    uniqueValues.n_especie.sort();

                    // Llenar los filtros con los valores únicos obtenidos
                    $('#filtroEmpresa').empty().append(
                        '<option value="">Todos</option>');
                    uniqueValues.n_empresa.forEach(
                        function(value) {
                            $('#filtroEmpresa').append(new Option(value, value));
                        });

                    $('#filtroExportadora').empty().append(
                        '<option value="">Todos</option>');
                    uniqueValues.n_exportadora.forEach(
                        function(value) {
                            $('#filtroExportadora').append(new Option(value,
                                value));
                        });

                    $('#filtroProductor').empty().append(
                        '<option value="">Todos</option>');
                    uniqueValues.n_productor.forEach(
                        function(value) {
                            $('#filtroProductor').append(new Option(value, value));
                        });
                    $('#filtroEspecie').empty().append(
                        '<option value="">Todos</option>');
                    let specieHasCherries =
                        false; // Bandera para verificar si 'Cherries' está presente
                    uniqueValues.n_especie.forEach(function(value) {
                        $('#filtroEspecie').append(new Option(value, value));
                        if (value === "Cherries") {
                            specieHasCherries = true;
                        }
                    });

                    // Pre-seleccionar 'Cherries' si está presente
                    if (specieHasCherries) {
                        $('#filtroEspecie').val("Cherries");
                        // Aplicar el filtro automáticamente
                        // table.column(3).search('^Cherries$', true, false).draw();
                        applyFilters();
                    }


                    // Configurar Select2
                    $('.select2').select2();

                    // Evento de filtro
                    // Evento de cambio en los filtros
                    $('.select2').on('change', function() {
                        // Redibujar la tabla
                        applyFilters();
                        //applyChildRowFilters();
                    });

                    // Función para aplicar los filtros
                    function applyFilters() {
                        let filters = {
                            n_empresa: $('#filtroEmpresa').val() || [],
                            n_exportadora: $('#filtroExportadora').val() || [],
                            n_productor: $('#filtroProductor').val() || [],
                            n_especie: $('#filtroEspecie').val() || [],
                        };

                        // Obtener los datos actuales de la tabla
                        let allData = api.ajax.json().data;

                        // Filtrar los datos localmente
                        let filteredData = allData.filter(function(row) {
                            let matchesFilter = false;

                            if (Array.isArray(row.nivel_2)) {
                                // Iterar sobre los elementos de nivel_2
                                row.nivel_2.forEach(function(item) {
                                    let matchesCurrentItem = true;

                                    // Comprobar si los filtros coinciden
                                    if (filters.n_empresa.length && !filters.n_empresa
                                        .includes(item.n_empresa)) {
                                        matchesCurrentItem = false;
                                    }
                                    if (filters.n_exportadora.length && !filters
                                        .n_exportadora.includes(item.n_exportadora)) {
                                        matchesCurrentItem = false;
                                    }
                                    if (filters.n_productor.length && !filters
                                        .n_productor.includes(item.n_productor)) {
                                        matchesCurrentItem = false;
                                    }
                                    if (filters.n_especie.length && !filters.n_especie
                                        .includes(item.n_especie)) {
                                        matchesCurrentItem = false;
                                    }

                                    // Si algún elemento de nivel_2 coincide, consideramos que la fila cumple
                                    if (matchesCurrentItem) {
                                        matchesFilter = true;
                                    }
                                });
                            }

                            return matchesFilter;
                        });

                        console.log("filteredData", filteredData);

                        // Redibujar la tabla con los datos filtrados
                        table.clear(); // Eliminar filas actuales
                        table.rows.add(filteredData); // Agregar las filas filtradas
                        table.draw(); // Redibujar la tabla
                        if (filteredData.length == 0) {
                            $("#horaEspera").html("0")
                        } else {
                            $("#horaEspera").html(formatNumber(filteredData[0].max_horas_en_espera));
                        }
                    }

                },
                columns: [ // Aquí accedemos a los detalles
                    {
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {
                        data: 'n_variedad',
                        title: 'Variedad'
                    },

                    {
                        data: 'nota_calidad',
                        title: 'Nota Calidad'
                    },
                    {
                        data: 'peso_neto',
                        title: 'Peso Neto',
                        render: function(data, type, row) {
                            // Formateamos el número con separador de miles y sin decimales
                            return new Intl.NumberFormat('es-CL', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(data);
                        },
                    },
                    {
                        data: 'max_horas_en_espera',
                        title: 'Hora Espera',
                    }
                ],


                footerCallback: function(row, data, start, end, display) {
                    let api = this.api();

                    // Función para convertir a número
                    let intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 :
                            typeof i ===
                            'number' ? i : 0;
                    };
                    let totalPesoNeto = api
                        .column(3)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    let subtotalPesoNeto = api
                        .column(3, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    $(api.column(3).footer()).html(
                        ` ${formatNumber(subtotalPesoNeto.toFixed(0))}<br> ${formatNumber(totalPesoNeto.toFixed(0))}`
                    );
                    $("#proSinIniciar").html(`${formatNumber(totalPesoNeto.toFixed(0))}`)
                }
            });
            setInterval(function() {
                location.reload();
            }, 600000);
            table.on('click', 'td.sorting_1', function(e) {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                if (row.child.isShown()) {
                    // Si ya está mostrado, ocultarlo
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Si no está mostrado, mostrarlo
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }
            });
            table.on('click', 'td.sorting_2', function(e) {
                const id = $(this).closest('tr').data('id');

                // Alternar la visibilidad de la fila detalle
                $(`tr.detalle[data-id="${id}"]`).toggle();
            });

        });


        function groupBy(data, keys) {
            return data.reduce((result, currentValue) => {
                // Crea un identificador único basado en las claves para agrupar
                var groupKey = keys.map(key => currentValue[key]).join('-');
                if (!result[groupKey]) {
                    result[groupKey] = {
                        n_variedad: currentValue.n_variedad,
                        nota_calidad: currentValue.nota_calidad,
                        peso_neto_sum: 0, // Inicializamos la suma
                        items: []
                    };
                }
                result[groupKey].peso_neto_sum += parseFloat(currentValue
                    .peso_neto); // Sumar peso_neto
                result[groupKey].items.push(currentValue);
                return result;
            }, {});
        }

        // Función para generar el contenido del child row (Nivel 2)
        function formatChildRow(group) {
            var childContent = '<table class="table table-bordered">';
            group.items.forEach(item => {
                childContent += `
                <tr>
                    <th>Empresa</th>
                    <td>${item.n_empresa}</td>
                </tr>
                <tr>
                    <th>Exportadora</th>
                    <td>${item.n_exportadora}</td>
                </tr>
                <tr>
                    <th>Productor</th>
                    <td>${item.n_productor}</td>
                </tr>
                <tr>
                    <th>Horas En Espera</th>
                    <td>${item.horas_en_espera}</td>
                </tr>
                <tr>
                    <th>Peso Neto</th>
                    <td>${new Intl.NumberFormat('es-CL', { minimumFractionDigits: 0, maximumFractionDigits: 0
                        }).format(item.peso_neto)}</td>
                </tr>
                `;
            });
            childContent += '</table>';
            return childContent;
        }

        function applyChildRowFilters() {
            let filters = {
                n_empresa: $('#filtroEmpresa').val() || [],
                n_exportadora: $('#filtroExportadora').val() || [],
                n_productor: $('#filtroProductor').val() || [],
                n_especie: $('#filtroEspecie').val() || [],
            };
            $('#lotesTable tbody tr.shown').each(function() {
                let rowData = table.row(this).data();
                let matchesFilter = true;

                // Filtrar según los valores seleccionados
                if (filters.n_empresa.length && !filters.n_empresa.includes(rowData.n_empresa)) {
                    matchesFilter = false;
                }
                if (filters.n_exportadora.length && !filters.n_exportadora.includes(rowData
                        .n_exportadora)) {
                    matchesFilter = false;
                }
                if (filters.n_productor.length && !filters.n_productor.includes(rowData
                        .n_productor)) {
                    matchesFilter = false;
                }
                if (filters.n_especie.length && !filters.n_especie.includes(rowData.n_especie)) {
                    matchesFilter = false;
                }

                // Si no coincide con el filtro, ocultar el child row
                if (matchesFilter) {
                    $(this).find('td.dt-control').trigger(
                        'click'); // Mostrar child row si pasa el filtro
                } else {
                    if ($(this).hasClass('shown')) {
                        $(this).find('td.dt-control').trigger(
                            'click'); // Ocultar child row si no pasa el filtro
                    }
                }
                console.log("rowData", rowData);
            });
        }





        // $.ajax({
        //     url: "{{ route('admin.reporteria.obtieneRecepcionDatosRecepcion') }}",
        //     type: "GET",
        //     dataType: "json",
        //     success: function(data) {

        //         // $("#proIniciado").html(formatNumber(data.datosProcesados[0].peso_neto));

        //         // $("#proSinIniciar").html(formatNumber(data.datosSinProcesar[0].peso_neto));
        //         // $("#horaEspera").html(data.maximaEsperaHoras.horas_en_espera);

        //         $("#cajasProcesadas").html(formatNumber(data.pesoxFecha[0].cantidad));
        //         console.log(data.variedadxCereza);
        //         //cargaPesoCantidadxFecha(data);
        //         cargaNotaCalidad(data);
        //         const variedades = data.variedadxCereza.map(item => item.n_variedad);
        //         const cantidades = data.variedadxCereza.map(item => parseFloat(item.cantidad));
        //         const pesosNetos = data.variedadxCereza.map(item => parseFloat(item.peso_neto));

        //         // Configurar el gráfico
        //         const ctx = document.getElementById('variedadesChart').getContext('2d');
        //         const chart = new Chart(ctx, {
        //             type: 'bar',
        //             data: {
        //                 labels: variedades, // Etiquetas (variedades)
        //                 datasets: [{
        //                         label: 'Cantidad',
        //                         data: cantidades,
        //                         backgroundColor: 'rgba(75, 192, 192, 0.6)',
        //                         borderColor: 'rgba(75, 192, 192, 1)',
        //                         borderWidth: 1
        //                     },
        //                     {
        //                         label: 'Peso Neto',
        //                         data: pesosNetos,
        //                         backgroundColor: 'rgba(255, 99, 132, 0.6)',
        //                         borderColor: 'rgba(255, 99, 132, 1)',
        //                         borderWidth: 1
        //                     }
        //                 ]
        //             },
        //             options: {
        //                 responsive: true,
        //                 plugins: {
        //                     title: {
        //                         display: true,
        //                         text: 'Cantidad y Peso Neto por Variedad'
        //                     }
        //                 },
        //                 scales: {
        //                     x: {
        //                         title: {
        //                             display: true,
        //                             text: 'Variedad'
        //                         }
        //                     },
        //                     y: {
        //                         beginAtZero: true,
        //                         title: {
        //                             display: true,
        //                             text: 'Valores'
        //                         }
        //                     }
        //                 }
        //             }
        //         });

        //     }
        // });

        // function cargaDataTable() {
        // $.ajax({
        // url: "{{ route('admin.reporteria.obtieneDatosStockInventario') }}",
        // })
        // }

        function cargaNotaCalidad(data) {
            const labels = data.nota_calidad.map(item => `Calidad ${item.nota_calidad}`);
            const values = data.nota_calidad.map(item => formatNumber(parseFloat(item.peso_neto)));
            console.log(values);
            // Configuración del gráfico
            const ctx = document.getElementById('notaCalidadChart').getContext('2d');
            const myPieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Cantidad Lotes',
                        data: values,
                        backgroundColor: [

                            '#eacbce', '#d58e96', '#850b09', '#dd1818',
                            '#961010'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    onClick: function(event, elements) {
                        const clickedElement = elements[0];
                        console.log(clickedElement._index);
                        const datasetIndex = clickedElement._index;
                        const label = labels[datasetIndex];
                        const labelValue = values[datasetIndex];
                        $("#filtroNota").val(clickedElement._index);
                        // Show an alert with information about the clicked segment

                    },
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const value = tooltipItem.raw.toLocaleString('es-ES', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                    return `${tooltipItem.label}: ${value}`;
                                }
                            }


                        }
                    }
                }
            });

        }

        document.addEventListener("DOMContentLoaded", function() {
            // Obtener los datos del servidor



            // Extraer los datos necesarios

        });


        function formatNumber(number) {
            return new Intl.NumberFormat('es-CL', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }
    </script>
@endsection
