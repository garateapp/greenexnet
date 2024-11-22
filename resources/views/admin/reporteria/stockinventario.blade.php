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
                <div class="col-sm-6 col-xl-3">
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
                </div>


                <div class="col-sm-6 col-xl-3">
                    <div class="card text-white bg-info">
                        <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-center">Horas de Espera</div>
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



                <div class="col-sm-6 col-xl-3">
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
                </div>
            </div>
            <div class="row">
                <button class="btn bg-danger rounded-full" id="toggleButton" style="margin-left:-40px;"
                    title="Ocultar/Mostrar">
                    <i class="fa fa-chart-bar text-white"></i>
                    <br />
                </button>
            </div>
            <div class="row" id="graficosContainer">
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
            <div class="col-sm-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        Recepciones
                    </div>
                    <div class="card-body">

                        <table id="lotesTable"
                            class="display table table-bordered table-striped table-hover ajaxTable datatable datatable-existencias"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>Especie</th>
                                    <th>Empresa</th>
                                    <th>Exportadora</th>
                                    <th>Productor</th>
                                    <th>Nota Calidad</th>
                                    <th>Variedad</th>
                                    <th>Lote Recepción</th>
                                    <th>Horas en Espera</th>
                                    <th>Cajas</th>
                                    <th>Peso Neto</th>

                                </tr>

                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="1">Subtotal<br />Totales</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
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
    <script>
        $(document).ready(function() {
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
            $('#lotesTable thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#lotesTable thead');
            var table = $('#lotesTable').DataTable({
                fixedColumns: true,
                fixedHeader: true,

                ajax: {
                    url: "{{ route('admin.reporteria.obtieneDatosStockInventario') }}",
                    method: "GET",
                    dataSrc: 'data', // Asegúrate de que los datos provienen de la propiedad 'data'
                },

                displayLength: 10,
                initComplete: function() {
                    var api = this.api();
                    api.columns().every(function() {
                        var column = this;
                        if (column.index() < 6) {
                            if (column.index == 4) {
                                var select = $(
                                        '<select style="width: 100%;" id="filtroNota"><option value="">Todos</option></select>'
                                    )
                                    .appendTo($(column.header()).empty())
                                    .on('change', function() {
                                        var val = $.fn.dataTable.util.escapeRegex($(this)
                                            .val());
                                        column.search(val ? '^' + val + '$' : '', true,
                                                false)
                                            .draw();
                                    });

                                // Extrae valores únicos de la columna y los agrega al <select>
                                column
                                    .data()
                                    .unique()
                                    .sort()
                                    .each(function(d) {
                                        select.append('<option value="' + d + '">' + d +
                                            '</option>');
                                    });
                            } else {
                                var select = $(
                                        '<select style="width: 100%;"><option value="">Todos</option></select>'
                                    )
                                    .appendTo($(column.header()).empty())
                                    .on('change', function() {
                                        var val = $.fn.dataTable.util.escapeRegex($(this)
                                            .val());
                                        column.search(val ? '^' + val + '$' : '', true,
                                                false)
                                            .draw();
                                    });

                                // Extrae valores únicos de la columna y los agrega al <select>
                                column
                                    .data()
                                    .unique()
                                    .sort()
                                    .each(function(d) {
                                        select.append('<option value="' + d + '">' + d +
                                            '</option>');
                                    });
                            }
                        } else {
                            // Para las demás columnas, mantenemos el filtro de texto
                            var input = $(
                                    '<input type="text" placeholder="Filtrar..." style="width: 100%; box-sizing: border-box;"/>'
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
                },
                columns: [{
                        data: 'n_especie',
                        title: 'Especie'
                    },


                    {
                        data: 'n_empresa',
                        title: 'Empresa'
                    }, // Aquí accedemos a los detalles
                    {
                        data: 'n_exportadora',
                        title: 'Exportadora'
                    },
                    {
                        data: 'n_productor',
                        title: 'Productor'
                    },

                    {
                        data: 'nota_calidad',
                        title: 'Nota Calidad'
                    },
                    {
                        data: 'n_variedad',
                        title: 'Variedad'
                    },
                    {
                        data: 'numero_g_recepcion',
                        title: 'N° Recepción'
                    },
                    {
                        data: 'horas_en_espera',
                        title: 'Horas en Espera'
                    },
                    {
                        data: 'cantidad',
                        title: 'Cajas'
                    },
                    {
                        data: 'peso_neto',
                        title: 'Peso Neto'
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Función para convertir a número
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i ===
                            'number' ? i : 0;
                    };

                    // Total general para todas las páginas
                    var totalCantidad = api
                        .column(8)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var totalPesoNeto = api
                        .column(9)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Subtotal para filas visibles (filtradas)
                    var subtotalCantidad = api
                        .column(8, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var subtotalPesoNeto = api
                        .column(9, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Actualizar el pie de tabla con subtotales y totales
                    $(api.column(8).footer()).html(
                        ` ${subtotalCantidad.toFixed(2)}<br> ${totalCantidad.toFixed(2)}`
                    );
                    $(api.column(9).footer()).html(
                        ` ${subtotalPesoNeto.toFixed(2)}<br> ${totalPesoNeto.toFixed(2)}`
                    );
                }
            });
        });







        $.ajax({
            url: "{{ route('admin.reporteria.obtieneRecepcionDatosRecepcion') }}",
            type: "GET",
            dataType: "json",
            success: function(data) {

                $("#proIniciado").html(formatNumber(data.datosProcesados[0].peso_neto));

                $("#proSinIniciar").html(formatNumber(data.datosSinProcesar[0].peso_neto));
                $("#horaEspera").html(data.maximaEsperaHoras.horas_en_espera);

                $("#cajasProcesadas").html(formatNumber(data.pesoxFecha[0].cantidad));
                console.log(data.variedadxCereza);
                //cargaPesoCantidadxFecha(data);
                cargaNotaCalidad(data);
                const variedades = data.variedadxCereza.map(item => item.n_variedad);
                const cantidades = data.variedadxCereza.map(item => parseFloat(item.cantidad));
                const pesosNetos = data.variedadxCereza.map(item => parseFloat(item.peso_neto));

                // Configurar el gráfico
                const ctx = document.getElementById('variedadesChart').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: variedades, // Etiquetas (variedades)
                        datasets: [{
                                label: 'Cantidad',
                                data: cantidades,
                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Peso Neto',
                                data: pesosNetos,
                                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Cantidad y Peso Neto por Variedad'
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Variedad'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Valores'
                                }
                            }
                        }
                    }
                });

            }
        });

        // function cargaDataTable() {
        //     $.ajax({
        //         url: "{{ route('admin.reporteria.obtieneDatosStockInventario') }}",
        //     })
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
        // function cargaPesoCantidadxFecha(data) {
        //     const labels2 = data.pesoxFecha.map(item => new Date(item.fecha_g_recepcion_sh)
        //         .toLocaleDateString()); // Fechas
        //     const cantidadData = data.pesoxFecha.map(item => formatNumber(parseFloat(item
        //         .cantidad))); // Cantidades
        //     const pesoNetoData = data.pesoxFecha.map(item => formatNumber(parseFloat(item
        //         .peso_neto))); // Pesos netos

        //     // Crear el gráfico de líneas
        //     const ctx2 = document.getElementById('pesoNetoProcesadoChart').getContext('2d');
        //     const myLineChart = new Chart(ctx2, {
        //         type: 'line',
        //         data: {
        //             labels: labels2, // Las fechas como etiquetas en el eje X
        //             datasets: [{
        //                     label: 'Cantidad',
        //                     data: cantidadData,
        //                     borderColor: '#aadd94', // Color de la línea de cantidad
        //                     backgroundColor: '#aadd94', // Color de fondo de la línea
        //                     fill: false, // Sin relleno bajo la línea
        //                     tension: 0.1 // Suavizar la línea
        //                 },
        //                 {
        //                     label: 'Peso Neto',
        //                     data: pesoNetoData,
        //                     borderColor: '#ff7314', // Color de la línea de peso neto
        //                     backgroundColor: '#ff7314', // Color de fondo de la línea
        //                     fill: false, // Sin relleno bajo la línea
        //                     tension: 0.1 // Suavizar la línea
        //                 }
        //             ]
        //         },
        //         options: {
        //             responsive: true,
        //             scales: {
        //                 x: {
        //                     title: {
        //                         display: true,
        //                         text: 'Fecha'
        //                     },
        //                     ticks: {
        //                         autoSkip: true,
        //                         maxRotation: 90, // Para evitar que las fechas se sobrepongan
        //                         minRotation: 45
        //                     }
        //                 },
        //                 y: {
        //                     title: {
        //                         display: true,
        //                         text: 'Valor'
        //                     }
        //                 }
        //             },
        //             plugins: {
        //                 legend: {
        //                     position: 'top'
        //                 }
        //             }
        //         }
        //     });

        // }

        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    </script>
@endsection
