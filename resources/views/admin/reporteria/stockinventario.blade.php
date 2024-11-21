@extends('layouts.admin')


@section('content')
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
            <div class="card">
                <div class="card-header">
                    Gráficos
                </div>
                <div class="card-body">
                    <div class="row g-4 mb-4">
                        <div class="col-sm-4 col-xl-4">
                            <div class="card">
                                <div class="card-header">
                                    Segmentación Nota de Calidad
                                </div>
                                <div class="card-body">
                                    <canvas id="notaCalidadChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-xl-8">
                            <div class="card">
                                <div class="card-header">
                                    Peso Neto Procesado x Fecha
                                </div>
                                <div class="card-body">
                                    <canvas id="pesoNetoProcesadoChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Recepciones
                </div>
                <div class="card-body">

                    <div id="toolbar">

                    </div>
                    <table id="table" data-toolbar="#toolbar" data-search="true" data-show-refresh="true"
                        data-show-toggle="true" data-show-fullscreen="true" data-show-columns="true"
                        data-show-columns-toggle-all="true" data-detail-view="true" data-show-export="true"
                        data-click-to-select="true" data-detail-formatter="detailFormatter" data-minimum-count-columns="2"
                        data-show-pagination-switch="true" data-pagination="true" data-id-field="id"
                        data-filter-control="true" data-page-list="[10, 25, 50, 100, all]" data-show-footer="true">
                        <thead>
                            <tr>
                                <th data-field="n_empresa" data-footer-formatter="idFormatter" data-filter-control="select">
                                    Empresa</th>
                                <th data-field="n_exportadora" data-footer-formatter="nameFormatter"
                                    data-filter-control="select">
                                    Exportadora</th>
                                <th data-field="n_especie" data-footer-formatter="nameFormatter"
                                    data-filter-control="select">
                                    Especie</th>
                                <th data-field="cantidad" data-footer-formatter="priceFormatter">Cantidad</th>
                                <th data-field="nota_calidad" data-footer-formatter="nameFormatter"
                                    data-filter-control="select">
                                    Nota Calidad</th>
                                <th data-field="peso_neto" data-footer-formatter="priceFormatter">Peso Neto</th>
                            </tr>
                        </thead>

                    </table>

                    <script>
                        var $table = $('#table')

                        function idFormatter() {
                            return 'Total'
                        }
                        $(function() {
                            $table.bootstrapTable({
                                url: "{{ route('admin.reporteria.obtieneDatosStockInventario') }}",
                                idField: 'id_empresa',
                                treeShowField: 'n_empresa', // Campo que muestra la jerarquía
                                // Campo que indica el padre
                                showColumns: true,
                                columns: [{
                                        field: 'n_empresa',
                                        title: 'Empresa',

                                    },
                                    {
                                        field: 'n_exportadora',
                                        sortable: true,
                                        title: 'Exportadora'
                                    },
                                    {
                                        field: 'n_especie',
                                        sortable: true,
                                        title: 'Especie'
                                    },
                                    {
                                        field: 'cantidad',
                                        title: 'Cantidad',
                                        sortable: true,
                                        align: 'center',

                                    },
                                    {
                                        field: 'nota_calidad',
                                        title: 'Nota Calidad',
                                        sortable: true,
                                        align: 'center',

                                    },
                                    {
                                        field: 'peso_neto',
                                        title: 'Peso Neto'

                                    }
                                ],

                                onPostBody: function() {
                                    var columns = $table.bootstrapTable('getOptions').columns

                                    if (columns && columns[0][1].visible) {
                                        $table.treegrid({
                                            treeColumn: 0,
                                            onChange: function() {
                                                $table.bootstrapTable('resetView')
                                            }
                                        })
                                    }
                                }
                            })
                        })



                        function statusFormatter(value, row, index) {
                            if (value === 1) {
                                return '<span class="label label-success">ok</span>'
                            }
                            return '<span class="label label-default">ko</span>'
                        }
                    </script>
                </div>
            </div>

            <script>
                $.ajax({
                    url: "{{ route('admin.reporteria.obtieneRecepcionDatosRecepcion') }}",
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data.maximaEsperaHoras);
                        $("#proIniciado").html(formatNumber(data.datosProcesados[0].peso_neto));
                        $("#cajasProcesadas").html(formatNumber(data.datosProcesados[0].cantidad));
                        $("#proSinIniciar").html(formatNumber(data.datosSinProcesar[0].peso_neto));
                        $("#horaEspera").html(data.maximaEsperaHoras.horas_en_espera);
                        console.log(data.nota_calidad);
                        console.log(data.pesoxFecha);
                        //GRafico Circular
                        // Extraer etiquetas y valores

                        cargaPesoCantidadxFecha(data);
                        cargaNotaCalidad(data);
                        //Gráfico de Líneas Peso Cantidad x Fecha
                        // Extraer las fechas, cantidades y pesos netos
                    }
                });

                function cargaNotaCalidad(data) {
                    const labels = data.nota_calidad.map(item => `Calidad ${item.nota_calidad}`);
                    const values = data.nota_calidad.map(item => formatNumber(parseFloat(item.cantidad)));

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

                function cargaPesoCantidadxFecha(data) {
                    const labels2 = data.pesoxFecha.map(item => new Date(item.fecha_g_recepcion_sh)
                        .toLocaleDateString()); // Fechas
                    const cantidadData = data.pesoxFecha.map(item => formatNumber(parseFloat(item
                        .cantidad))); // Cantidades
                    const pesoNetoData = data.pesoxFecha.map(item => formatNumber(parseFloat(item
                        .peso_neto))); // Pesos netos

                    // Crear el gráfico de líneas
                    const ctx2 = document.getElementById('pesoNetoProcesadoChart').getContext('2d');
                    const myLineChart = new Chart(ctx2, {
                        type: 'line',
                        data: {
                            labels: labels2, // Las fechas como etiquetas en el eje X
                            datasets: [{
                                    label: 'Cantidad',
                                    data: cantidadData,
                                    borderColor: '#aadd94', // Color de la línea de cantidad
                                    backgroundColor: '#aadd94', // Color de fondo de la línea
                                    fill: false, // Sin relleno bajo la línea
                                    tension: 0.1 // Suavizar la línea
                                },
                                {
                                    label: 'Peso Neto',
                                    data: pesoNetoData,
                                    borderColor: '#ff7314', // Color de la línea de peso neto
                                    backgroundColor: '#ff7314', // Color de fondo de la línea
                                    fill: false, // Sin relleno bajo la línea
                                    tension: 0.1 // Suavizar la línea
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Fecha'
                                    },
                                    ticks: {
                                        autoSkip: true,
                                        maxRotation: 90, // Para evitar que las fechas se sobrepongan
                                        minRotation: 45
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Valor'
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

                }

                function formatNumber(number) {
                    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }

                //datatable
                var $table = $('#table')
                var $remove = $('#remove')
                var selections = []

                function getIdSelections() {
                    return $.map($table.bootstrapTable('getSelections'), function(row) {
                        return row.id
                    })
                }

                function responseHandler(res) {
                    $.each(res.rows, function(i, row) {
                        row.state = $.inArray(row.id, selections) !== -1
                    })
                    return res
                }

                function detailFormatter(index, row) {
                    var html = []
                    $.each(row, function(key, value) {
                        html.push('<p><b>' + key + ':</b> ' + value + '</p>')
                    })
                    return html.join('')
                }

                function operateFormatter(value, row, index) {
                    return [
                        '<a class="like" href="javascript:void(0)" title="Like">',
                        '<i class="fa fa-heart"></i>',
                        '</a>  ',
                        '<a class="remove" href="javascript:void(0)" title="Remove">',
                        '<i class="fa fa-trash"></i>',
                        '</a>'
                    ].join('')
                }

                window.operateEvents = {
                    'click .like': function(e, value, row, index) {
                        alert('You click like action, row: ' + JSON.stringify(row))
                    },
                    'click .remove': function(e, value, row, index) {
                        $table.bootstrapTable('remove', {
                            field: 'id',
                            values: [row.id]
                        })
                    }
                }

                function totalTextFormatter(data) {
                    return 'Total'
                }

                function totalNameFormatter(data) {
                    return data.length
                }

                function nameFormatter(data) {
                    return data.length
                }

                function priceFormatter(data) {
                    var field = this.field
                    return '$' + data.map(function(row) {
                        return +row[field].substring(1)
                    }).reduce(function(sum, i) {
                        return sum + i
                    }, 0)
                }



                function totalPriceFormatter(data) {
                    var field = this.field
                    return '$' + data.map(function(row) {
                        return +row[field].substring(1)
                    }).reduce(function(sum, i) {
                        return sum + i
                    }, 0)
                }

                document.querySelector('.flip-card').addEventListener('click', function() {
                    this.classList.toggle('flipped');
                });

                // Optional: Populate with actual data
                document.getElementById('horaEspera').textContent = '2h 30m';
                document.getElementById('informacionAdicional').textContent = 'Detalles extras sobre las horas de espera';
            </script>
        @endsection
