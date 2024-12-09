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

    <div class="content">
        <div class="container-fluid">


            <div id="loading-animation"
                style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
                <video autoplay loop muted style="width: auto; height: auto;">
                    <source src="{{ asset('img/embarque.webm') }}" type="video/webm">
                    Tu navegador no soporta el video.
                </video>
                <br />

            </div>
            <div class="container-lg px-4">
                <div class="row g-4 mb-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="card text-white bg-info">
                            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="text-center">Total Kilos Enviados</div>
                                    <div class="fs-4 fw-semibold text-center">
                                        <i class="fas fa-weight-hanging" style="color: #FFFFFF; font-size: x-large;"></i>
                                        <span class="fs-6 fw-normal text-center" id="totalKilosEnviados"
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
                                    <div class="text-center">Cantidad Enviada </div>
                                    <div class="fs-4 fw-semibold text-center">
                                        <i class="fas fa-shipping-fast" style="color: #FFFFFF; font-size: x-large;"></i>
                                        <span class="fs-6 fw-normal text-center" id="totalCajasEnviadas"
                                            style="font-size: x-large;"></span>
                                    </div>
                                    <div class="text-center"><br /></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                    <label for="filtroExportadora">Exportadora</label>
                    <select id="filtroExportadora" class="form-control select2" multiple="multiple"></select>
                </div>
                <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                    <label for="filtroVariedad">Variedad</label>
                    <select id="filtroVariedad" class="form-control select2" multiple="multiple"></select>
                </div>
                <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                    <label for="filtroCliente">Cliente</label>
                    <select id="filtroCliente" class="form-control select2" multiple="multiple"></select>
                </div>
                <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                    <label for="filtroEtiqueta">Etiqueta</label>
                    <select id="filtroEtiqueta" class="form-control select2" multiple="multiple"></select>
                </div>
                <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                    <label for="filtroTransporte">Tipo transporte</label>
                    <select id="filtroTransporte" class="form-control select2" multiple="multiple"></select>
                </div>
                <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                    <button id="btnRecargar" class="btn btn-secondary mb-3" style="margin-top: 30px;" title="Recargar"><i
                            class="fas fa-sync"></i></button>
                    <button class="btn btn-secondary mb-3" id="toggleButton" style="margin-top: 30px;"
                        title="Ocultar/Mostrar">
                        <i class="fa fa-chart-bar text-white"></i>

                    </button>

                </div>

            </div>
            <div class="row" id="graficosContainer" style="display: none;">
                <!-- Primera columna -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body" id="chart-container">
                            <h5 class="card-title text-center">Cantidad Embarcada por Semana</h5>
                            <canvas id="CantxSemanaChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Segunda columna -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body" id="chart-container">
                            <h5 class="card-title text-center">Cantidad x Cliente</h5>
                            <canvas id="CantidadxClienteChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body" id="chart-container">
                            <h5 class="card-title text-center">Cantidad v/s Meta x Cliente</h5>
                            <canvas id="MetasxClienteChart"></canvas>
                            <div id="tabla-container-metas"></div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="modal fade" id="calibreModal" tabindex="-1" aria-labelledby="calibreModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="calibreModalLabel">Detalles del Calibre</h5>
                            <button type="button" id="btnImprimir" class="btn-secondary" data-bs-dismiss="modal"
                                aria-label="Close"><i class="fas fa-print"></i></button>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="fas fa-close"></i></button>
                        </div>

                        <div class="modal-body" id="modalCalibreContent">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Folio</th>
                                        <th>Variedad</th>
                                        <th>Embalaje</th>
                                        <th>Etiqueta</th>
                                        <th>Calibre</th>
                                        <th>Cantidad</th>
                                        <th>Peso</th>
                                    </tr>
                                </thead>
                                <tbody id="calibreModalBody">
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="row" id="vistaCalibres" style="display: none;">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">

                        </div>
                        <div class="card-body">
                            <div class="container-lg px-4" id="contenedor"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Embarques
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="EmbarqueTable"
                            class="display table table-bordered table-striped table-hover ajaxTable datatable datatable-transito"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Semana</th>
                                    <th>Cantidad</th>
                                    <th>Cliente</th>
                                    <th>Peso Neto</th>
                                    <th>Transporte</th>
                                    <th>Exportadora</th>
                                    <th>Pais Destino</th>
                                    <th>Despacho</th>
                                    <th>Altura</th>
                                    <th>Variedad</th>
                                    <th>Embalaje</th>
                                    <th>Etiqueta</th>

                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th>SubTotal<br />Total</th>
                                    <th></th>
                                    <th></th>
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
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1"></script>
    <script>
        $(document).ready(function() {
            let table = null;
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

            function formatNumber2(number) {
                return new Intl.NumberFormat('es-CL', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            }

            function showLoading() {

                $("#loading-animation").fadeIn();
            }

            function hideLoading() {
                $("#loading-animation").fadeOut();
            }
            showLoading();



            $.ajax({

                url: "{{ route('admin.reporteria.obtieneEmbarques') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    datos = data.data;
                    var variedades = [];
                    var calibres = [];
                    var exportadoras = [];
                    var etiqueta = [];
                    var transporte = [];
                    variedades = data.n_variedades;
                    cliente = data.cliente;
                    exportadoras = data.n_exportadora;
                    etiqueta = data.n_etiqueta;
                    transporte = data.transporte;
                    var chartData = data.chartCantxSemana;
                    var chartData2 = data.chartCatxCliente;
                    variedades.forEach(
                        function(value) {

                            $('#filtroVariedad').append(new Option(value, value));
                        });

                    etiqueta.forEach(
                        function(value) {
                            $('#filtroEtiqueta').append(new Option(value, value));
                        });
                    cliente.forEach(
                        function(value) {
                            $('#filtroCliente').append(new Option(value, value));
                        });
                    exportadoras.forEach(
                        function(value) {
                            $('#filtroExportadora').append(new Option(value, value));
                        });
                    transporte.forEach(
                        function(value) {
                            $('#filtroTransporte').append(new Option(value, value));
                        });

                    $("#totalKilosEnviados").html(formatNumber2(data.totalPeso));
                    $("#totalCajasEnviadas").html(formatNumber2(data.total));

                    table = $('#EmbarqueTable').DataTable({
                        fixedColumns: true,
                        fixedHeader: true,
                        responsive: true,
                        language: {

                            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-CL.json'
                        },
                        displayLength: 10,
                        data: datos,
                        columns: [{
                                className: 'dt-control',
                                orderable: false,
                                data: null,
                                defaultContent: ''
                            },
                            {
                                data: 'Semana',
                                title: 'Semana'
                            },
                            {
                                data: 'Cantidad',
                                title: 'Cantidad'
                            },
                            {
                                data: 'c_destinatario',
                                title: 'Cliente'
                            },
                            {
                                data: 'Peso_neto',
                                title: 'Peso Neto',
                                render: function(data, type, row) {
                                    // Formateamos el número con separador de miles y sin decimales
                                    return new Intl.NumberFormat('es-CL', {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    }).format(data);
                                }
                            },
                            {
                                data: 'transporte',
                                title: 'Transporte'
                            },
                            {
                                data: 'n_exportadora',
                                title: 'Exportadora',
                                visible: false

                            },
                            {
                                data: 'n_pais_destino',
                                title: 'Pais Destino'
                            },
                            {
                                data: 'numero_g_despacho',
                                title: 'Despacho'
                            },
                            {
                                data: 'n_altura',
                                title: 'Altura'
                            },

                            {
                                data: 'N_Variedad',
                                title: 'Variedad'
                            },
                            {
                                data: 'n_embalaje',
                                title: 'Embalaje'
                            },
                            {
                                data: 'n_etiqueta',
                                title: 'Etiqueta'
                            },

                        ],
                        footerCallback: function(row, data, start, end, display) {

                            let api = this.api();

                            // Función para convertir a número
                            let intVal = function(i) {
                                return typeof i === 'string' ? i.replace(/[\$,]/g, '') *
                                    1 :
                                    typeof i ===
                                    'number' ? i : 0;
                            };
                            let totalPesoNeto = api
                                .column(4)
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            let subtotalPesoNeto = api
                                .column(4, {
                                    page: 'current'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                            let totalCantidad = api
                                .column(2)
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            let subtotalCantidad = api
                                .column(2, {
                                    page: 'current'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                            $(api.column(4).footer()).html(
                                ` ${formatNumber2(subtotalPesoNeto.toFixed(0))}<br> ${formatNumber2(totalPesoNeto.toFixed(0))}`
                            );
                            $(api.column(2).footer()).html(
                                ` ${formatNumber2(subtotalCantidad.toFixed(0))}<br> ${formatNumber2(totalCantidad.toFixed(0))}`
                            );
                            // $("#proSinIniciar").html(
                            //     `${formatNumber(totalPesoNeto.toFixed(0))}`)
                        }
                    });
                    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                        const filtroVariedad = $('#filtroVariedad').val() || [];
                        const filtroExportadora = $('#filtroExportadora').val() || [];
                        const filtroEtiqueta = $('#filtroEtiqueta').val() || [];
                        const filtroCliente = $('#filtroCliente').val() || [];
                        const filtroTransporte = $('#filtroTransporte').val() || [];
                        const variedad = data[
                            10]; // Ajusta el índice según la posición de la columna en la tabla
                        const Exportadora = data[6]; // Ajusta el índice
                        const etiqueta = data[11]; // Ajusta el índice
                        const cliente = data[3]; // Ajusta el índice
                        const transporte = data[5]; // Ajusta el índice
                        const coincideVariedad = filtroVariedad.length === 0 || filtroVariedad
                            .includes(variedad);
                        const coincideExportadora = filtroExportadora.length === 0 ||
                            filtroExportadora
                            .includes(Exportadora);
                        const coincideEtiqueta = filtroEtiqueta.length === 0 || filtroEtiqueta
                            .includes(etiqueta);
                        const coincideCliente = filtroCliente.length === 0 || filtroCliente
                            .includes(cliente);
                        const coincideTransporte = filtroTransporte.length === 0 ||
                            filtroTransporte
                            .includes(transporte);

                        return coincideVariedad && coincideExportadora && coincideEtiqueta &&
                            coincideCliente && coincideTransporte;
                    });

                    const labels = chartData.map(chartData => `Semana ${chartData.Semana}`);
                    const values = chartData.map(chartData => parseFloat(chartData.Cantidad));

                    // Configuración del gráfico CAnt x Semana
                    const ctx = document.getElementById('CantxSemanaChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Cantidad por Semana',
                                data: values,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 50000
                                    }
                                }
                            }
                        }
                    });
                    // Configuración del gráfico
                    const labels2 = chartData2.map(chartData2 => chartData2.c_destinatario.trim());
                    const values2 = chartData2.map(chartData2 => parseFloat(chartData2.Cantidad));


                    const ctx2 = document.getElementById('CantidadxClienteChart').getContext('2d');
                    new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: labels2,
                            datasets: [{
                                label: 'Cantidad por Destinatario',
                                data: values2,
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                borderColor: 'rgba(153, 102, 255, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 5000
                                    }
                                }
                            }
                        }
                    });
                    const labels3 = chartData2.map(data => data.c_destinatario);
                    const cantidades3 = chartData2.map(data => parseFloat(data.Cantidad));
                    const metas = chartData2.map(data => data.meta ||
                    0); // Asegurar que las metas nulas sean 0

                    // Configuración del gráfico
                    const ctx3 = document.getElementById('MetasxClienteChart').getContext('2d');
                    new Chart(ctx3, {
                        type: 'bar',
                        data: {
                            labels: labels3,
                            datasets: [{
                                    label: 'Cantidad',
                                    data: cantidades3,
                                    backgroundColor: 'rgba(54, 162, 235, 0.5)', // Azul translúcido
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Meta',
                                    data: metas,
                                    backgroundColor: 'rgba(255, 99, 132, 0.5)', // Rojo translúcido
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Cantidad'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Clientes'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top'
                                }
                            }
                        }
                    });
                    const tablaContainer = document.getElementById('tabla-container-metas');
        const tablaHTML = `
            <table>
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Cantidad</th>
                        <th>Meta</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.dataMetas.map(data => `
                        <tr>
                            <td>${data.c_destinatario}</td>
                            <td>${data.Cantidad}</td>
                            <td>${data.meta || 0}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
        tablaContainer.innerHTML = tablaHTML;
                    //
                    // Configuración del gráfico CAnt x Cliente
                    hideLoading();
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.log(xhr.responseText);
                },
            });
            // console.log(data);
            $('.select2').on('change', function() {
                table.draw(); // Redibuja la tabla aplicando los filtros
            });
        });
    </script>
@endsection
