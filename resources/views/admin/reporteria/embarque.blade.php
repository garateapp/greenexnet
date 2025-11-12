@extends('layouts.admin')


@section('content')
    <style>
        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #a9dd94;
            border-color: #dee2e6 #dee2e6 #f8fafc;
        }

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
                                    <div class="text-center">Total Pallets</div>
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
                                    <div class="text-center">Cantidad Enviadas </div>
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
                    <div class="col-sm-6 col-xl-3">
                        <div class="card text-white bg-info">
                            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="text-center">Cont. Embarcados a la Fecha </div>
                                    <div class="fs-4 fw-semibold text-center">
                                        <i class="fas fa-shipping-fast" style="color: #FFFFFF; font-size: x-large;"></i>
                                        <span class="fs-6 fw-normal text-center" id="totalContenedores"
                                            style="font-size: x-large;"></span>
                                    </div>
                                    <div class="text-center"><br /></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                        type="button" role="tab" aria-controls="home" aria-selected="true">Resumen</button>
                </li>
                {{-- <li class="nav-item" role="presentation">
                    <button class="nav-link" id="maritimo-tab" data-bs-toggle="tab" data-bs-target="#maritimo"
                        type="button" role="tab" aria-controls="maritimo" aria-selected="false">Maritimo</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="aereo-tab" data-bs-toggle="tab" data-bs-target="#aereo" type="button"
                        role="tab" aria-controls="aereo" aria-selected="false">Aéreo</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="terrestre-tab" data-bs-toggle="tab" data-bs-target="#terrestre"
                        type="button" role="tab" aria-controls="terrestre" aria-selected="false">Terrestre</button>
                </li> --}}
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                    <div class="row" style="margin-bottom: 15px;">
                        {{-- <button class="btn btn-secondary mb-3" id="toggleButton" style="margin-top: 30px;"
                            title="Ocultar/Mostrar">
                            <i class="fa fa-chart-bar text-white"></i>

                        </button> --}}
                    </div>
                    <div id="tabla-container-metas"></div>
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-body" id="chart-container">
                                <h5 class="card-title text-center">Cajas x Cliente</h5>
                                <canvas id="MetasxClienteChart"></canvas>

                            </div>
                        </div>
                    </div>
                </div>






                <div class="tab-pane fade" id="maritimo" role="tabpanel" aria-labelledby="maritimo-tab">
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
                            <label for="filtroSemana">Semana</label>
                            <select id="filtroSemana" class="form-control select2" multiple="multiple"></select>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                            <button id="btnRecargar" class="btn btn-secondary mb-3" style="margin-top: 30px;"
                                title="Recargar"><i class="fas fa-sync"></i></button>


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
                                            <th>Nave</th>
                                            <th>Contenedor</th>
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



                <div class="tab-pane fade" id="aereo" role="tabpanel" aria-labelledby="aereo-tab">...</div>
                <div class="tab-pane fade" id="terrestre" role="tabpanel" aria-labelledby="terrestre-tab">...</div>
            </div>






        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1"></script>
    <script>
        $(document).ready(function() {
            $("#maritimo-tab").on('click', function() {
                detalleMaritimo();
            });
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

            function drawResumenGeneral(data, dataAerea, dataTerrestre) {
                var contenedoresembarcados = 0;
                data.forEach(data => {
                    contenedoresembarcados = contenedoresembarcados + parseFloat(data
                        .contenedores);
                });

                const groupedData = data.reduce((acc, current) => {
                    const destinatario = current.c_destinatario;

                    if (!acc[destinatario]) {
                        acc[destinatario] = {
                            c_destinatario: destinatario,
                            contenedores: 0,
                            meta: current.meta,
                            metacont:current.metacont,
                            cajas: 0,
                        };
                    }

                    acc[destinatario].contenedores += parseFloat(current.contenedores);
                    acc[destinatario].cajas += parseFloat(current.Cajas);
                    return acc;
                }, {});
                const groupedDataAereo = dataAerea.reduce((acc, current) => {
                    const destinatario = current.c_destinatario;

                    if (!acc[destinatario]) {
                        acc[destinatario] = {
                            c_destinatario: destinatario,
                            contenedores: 0,
                            Pallets: 0,
                            Cajas: 0,
                            meta: parseFloat(current.meta) || 0,
                            metacont: parseFloat(current.metacont) || 0,
                        };
                    }
                    acc[destinatario].contenedores += parseFloat(current.contenedores);

                    acc[destinatario].Pallets += parseFloat(current.Pallets);
                    acc[destinatario].Cajas += parseFloat(current.Cajas);
                    if (!acc[destinatario].meta && current.meta) {
                        acc[destinatario].meta = parseFloat(current.meta) || 0;
                    }
                    if (!acc[destinatario].metacont && current.metacont) {
                        acc[destinatario].metacont = parseFloat(current.metacont) || 0;
                    }
                    return acc;
                }, {});
                const groupedDataTerrestre = dataTerrestre.reduce((acc, current) => {
                    const destinatario = current.c_destinatario;

                    if (!acc[destinatario]) {
                        acc[destinatario] = {
                            c_destinatario: destinatario,
                            contenedores: 0,
                            Pallets: 0,
                            Cajas: 0,
                            meta: parseFloat(current.meta) || 0,
                            metacont: parseFloat(current.metacont) || 0,
                        };
                    }
                    acc[destinatario].contenedores += parseFloat(current.contenedores);

                    acc[destinatario].Pallets += parseFloat(current.Pallets);
                    acc[destinatario].Cajas += parseFloat(current.Cajas);
                    if (!acc[destinatario].meta && current.meta) {
                        acc[destinatario].meta = parseFloat(current.meta) || 0;
                    }
                    if (!acc[destinatario].metacont && current.metacont) {
                        acc[destinatario].metacont = parseFloat(current.metacont) || 0;
                    }
                    return acc;
                }, {});



                // Configuración del gráfico

                let totalEmbarcado = 0;
                let totalObjetivo = 0
                const clientesUnicos = new Set([
                    ...Object.keys(groupedData),
                    ...Object.keys(groupedDataAereo),
                    ...Object.keys(groupedDataTerrestre)
                ]);

                var TotalCajasEnviadas = 0;
                const result = Array.from(clientesUnicos).map(cliente => {
                    const maritimos = groupedData[cliente] || {
                        contenedores: 0,
                        meta: 0,
                        metacont:0,
                        Cajas: 0
                    };
                    const aereos = groupedDataAereo[cliente] || {
                        contenedores: 0,
                        Pallets: 0,
                        Cajas: 0,
                        meta: 0,
                        metacont:0,
                    };
                    const terrestres = groupedDataTerrestre[cliente] || {
                        contenedores: 0,
                        Pallets: 0,
                        Cajas: 0,
                        meta: 0,
                        metacont:0,
                    };

                    const metaCajas = Number(maritimos.meta) || Number(aereos.meta) || Number(terrestres.meta) || 0;
                    const metaContenedores = Number(maritimos.metacont) || Number(aereos.metacont) || Number(terrestres.metacont) || 0;

                    return {
                        c_destinatario: cliente,
                        contenedores: Math.round(maritimos.contenedores),
                        contenedoresAereo:aereos.contenedores || 0,
                        contenedoresTerrestre:terrestres.contenedores || 0,
                        meta: metaCajas,
                        metacont:metaContenedores,
                        cajas: parseFloat(maritimos.cajas) || 0,
                        palletsAereo: Math.round(parseFloat(aereos.Pallets)) || 0,
                        cajasAereo: parseFloat(aereos.Cajas) || 0,
                        palletsTerrestre: parseFloat(terrestres.Pallets) || 0,
                        cajasTerrestre: parseFloat(terrestres.Cajas) || 0
                    };

                }).sort((a, b) => a.c_destinatario.localeCompare(b.c_destinatario));
                console.log("aereo");
                console.log(result);
                const subtotales = {
                    cantidadMaritimos: result.reduce((sum, data) => sum + data.contenedores, 0),
                    objetivoMaritimos: result.reduce((sum, data) => sum + data.meta, 0),
                    objetivoCont: result.reduce((sum, data) => sum + data.metacont, 0),
                    cantidadCajasMAritimos: result.reduce((sum, data) => sum + data.cajas, 0),
                    cantidadPallets: result.reduce((sum, data) => sum + data.palletsAereo, 0),
                    cantidadCajas: result.reduce((sum, data) => sum + data.cajasAereo, 0),
                    cantidadPalletsTerrestre: result.reduce((sum, data) => sum + data.palletsTerrestre, 0),
                    cantidadCajasTerrestre: result.reduce((sum, data) => sum + data.cajasTerrestre, 0),
                    cantidadContenedoresAereo:result.reduce((sum, data) => sum + data.contenedoresAereo, 0),
                    cantidadContenedoresTerrestre:result.reduce((sum, data) => sum + data.contenedoresTerrestre, 0)

                };
                TotalCajasEnviadas = subtotales.cantidadCajasMAritimos + subtotales.cantidadCajas + subtotales
                    .cantidadCajasTerrestre;
                var TotalContenedoresEnviados=subtotales.cantidadContenedoresAereo+subtotales.cantidadMaritimos+subtotales.cantidadContenedoresTerrestre;
                var TotalPalletsEnviados = subtotales.cantidadPallets + subtotales.cantidadPalletsTerrestre;
                $("#totalKilosEnviados").html(formatNumber2(TotalPalletsEnviados));
                $("#totalCajasEnviadas").html(formatNumber2(TotalCajasEnviadas));
                $("#totalContenedores").html("~"+formatNumber2(TotalContenedoresEnviados));
                hideLoading();
            }
            let metasChartInstance = null;

            function loadResumenMetas() {
                $.ajax({
                    url: "{{ route('admin.reporteria.ObjetivosEnviosResumenClientes') }}",
                    type: "GET",
                    dataType: "json",
                    success(response) {
                        renderResumenMetas(response.data || []);
                    },
                    error(error) {
                        console.error(error);
                        document.getElementById('tabla-container-metas').innerHTML =
                            '<div class="alert alert-warning mb-0">No fue posible cargar el resumen de metas.</div>';
                    }
                });
            }

            function renderResumenMetas(data) {
                const tablaContainer = document.getElementById('tabla-container-metas');
                if (!data.length) {
                    tablaContainer.innerHTML =
                        '<div class="alert alert-info mb-0">No hay información de metas disponible.</div>';
                    renderMetasChart([]);
                    return;
                }
                const subtotales = data.reduce((acc, row) => {
                    acc.maritimoCont += parseFloat((row.maritimo && row.maritimo.contenedores) || 0);
                    acc.maritimoCajas += parseFloat((row.maritimo && row.maritimo.cajas) || 0);
                    acc.aereoPallets += parseFloat((row.aereo && row.aereo.pallets) || 0);
                    acc.aereoCajas += parseFloat((row.aereo && row.aereo.cajas) || 0);
                    acc.terrestrePallets += parseFloat((row.terrestre && row.terrestre.pallets) || 0);
                    acc.terrestreCajas += parseFloat((row.terrestre && row.terrestre.cajas) || 0);
                    acc.totalCajas += parseFloat(row.total_cajas || 0);
                    acc.totalContenedores += parseFloat(row.total_contenedores || 0);
                    acc.metaCajas += parseFloat(row.meta_cajas || 0);
                    acc.metaCont += parseFloat(row.meta_contenedores || 0);
                    return acc;
                }, {
                    maritimoCont: 0,
                    maritimoCajas: 0,
                    aereoPallets: 0,
                    aereoCajas: 0,
                    terrestrePallets: 0,
                    terrestreCajas: 0,
                    totalCajas: 0,
                    totalContenedores: 0,
                    metaCajas: 0,
                    metaCont: 0
                });

                const tablaHTML = `<div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Cumplimiento de Objetivos
                        </div>
                        <div class="card-body">
                            <table>
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th colspan="6">TIPO DE TRANSPORTE</th>
                                        <th colspan="5">CUMPLIMIENTO</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th colspan="2">MARITIMO</th>
                                        <th colspan="2">AEREO</th>
                                        <th colspan="2">TERRESTRE</th>
                                        <th colspan="2">Cajas</th>
                                        <th colspan="2">Contenedores</th>
                                        <th>%</th>
                                    </tr>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Contenedores</th>
                                        <th>Cajas</th>
                                        <th>Pallets</th>
                                        <th>Cajas</th>
                                        <th>Pallets</th>
                                        <th>Cajas</th>
                                        <th>Objetivo</th>
                                        <th>Total</th>
                                        <th>Objetivo</th>
                                        <th>Total</th>
                                        <th>% Cumplimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.map(row => `
                                        <tr>
                                            <td>${row.cliente}</td>
                                            <td>${formatNumber2((row.maritimo && row.maritimo.contenedores) || 0)}</td>
                                            <td>${formatNumber2((row.maritimo && row.maritimo.cajas) || 0)}</td>
                                            <td>${formatNumber2((row.aereo && row.aereo.pallets) || 0)}</td>
                                            <td>${formatNumber2((row.aereo && row.aereo.cajas) || 0)}</td>
                                            <td>${formatNumber2((row.terrestre && row.terrestre.pallets) || 0)}</td>
                                            <td>${formatNumber2((row.terrestre && row.terrestre.cajas) || 0)}</td>
                                            <td>${formatNumber2(row.meta_cajas || 0)}</td>
                                            <td>${formatNumber2(row.total_cajas || 0)}</td>
                                            <td>${formatNumber2(row.meta_contenedores || 0)}</td>
                                            <td>~${formatNumber2(row.total_contenedores || 0)}</td>
                                            <td>${row.cumplimiento_cajas ? parseFloat(row.cumplimiento_cajas).toFixed(0) : 0}%</td>
                                        </tr>
                                    `).join('')}
                                    <tr>
                                        <td><strong>TOTAL</strong></td>
                                        <td><strong>${formatNumber2(subtotales.maritimoCont)}</strong></td>
                                        <td><strong>${formatNumber2(subtotales.maritimoCajas)}</strong></td>
                                        <td><strong>${formatNumber2(subtotales.aereoPallets)}</strong></td>
                                        <td><strong>${formatNumber2(subtotales.aereoCajas)}</strong></td>
                                        <td><strong>${formatNumber2(subtotales.terrestrePallets)}</strong></td>
                                        <td><strong>${formatNumber2(subtotales.terrestreCajas)}</strong></td>
                                        <td><strong>${formatNumber2(subtotales.metaCajas)}</strong></td>
                                        <td><strong>${formatNumber2(subtotales.totalCajas)}</strong></td>
                                        <td><strong>${formatNumber2(subtotales.metaCont)}</strong></td>
                                        <td><strong>~${formatNumber2(subtotales.totalContenedores)}</strong></td>
                                        <td><strong>${subtotales.metaCajas > 0 ? Math.round((subtotales.totalCajas / subtotales.metaCajas) * 100) : 0}%</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            <small>* El porcentaje de cumplimiento se calcula con base en la cantidad de cajas.</small>
                        </div>
                    </div>
                </div>`;

                tablaContainer.innerHTML = tablaHTML;
                renderMetasChart(data);
            }

            function renderMetasChart(data) {
                const ctx = document.getElementById('MetasxClienteChart').getContext('2d');
                const labels = data.map(row => row.cliente);
                const cantidades = data.map(row => parseFloat(row.total_cajas || 0));
                const metas = data.map(row => parseFloat(row.meta_cajas || 0));

                if (metasChartInstance) {
                    metasChartInstance.destroy();
                }

                metasChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Cantidad',
                                data: cantidades,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Meta',
                                data: metas,
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
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
                                    text: 'Cajas'
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
            }
            showLoading();
            loadResumenMetas();
            var datosMaritimo = [];
            var datosAereo = [];
            $.ajax({
                url: "{{ route('admin.reporteria.ObjetivosEnvios') }}",
                type: "GET",
                dataType: "json",
                success(response) {

                    getAereos(response.data);

                },
                error(error) {}
            });

            function getAereos(datosMaritimo) {
                $.ajax({
                    url: "{{ route('admin.reporteria.ObjetivosEnviosAereos') }}",
                    type: "GET",
                    dataType: "json",
                    success(response) {
                        console.log(response);
                        getTerrestre(datosMaritimo, response.data);
                    },
                    error(error) {}
                });
            }

            function getTerrestre(datosMaritimo, datosAereo) {
                $.ajax({
                    url: "{{ route('admin.reporteria.ObjetivosEnviosTerrestre') }}",
                    type: "GET",
                    dataType: "json",
                    success(response) {
                        console.log(response);

                        drawResumenGeneral(datosMaritimo, datosAereo, response.data);
                    },
                    error(error) {}
                });
            }

            function DatosMaritimos() {
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
                        semana = data.semana;
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
                        semana.forEach(
                            function(value) {
                                $('#filtroSemana').append(new Option(value, value));
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
                                    data: 'n_nave',
                                    title: 'Nave'
                                },
                                {
                                    data: 'contenedor',
                                    title: 'Contenedor'
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
                                    return typeof i === 'string' ? i.replace(/[\$,]/g,
                                            '') *
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
                            const filtroSemana = $('#filtroSemana').val() || [];
                            const variedad = data[
                                12
                            ]; // Ajusta el índice según la posición de la columna en la tabla
                            const Exportadora = data[8]; // Ajusta el índice
                            const etiqueta = data[13]; // Ajusta el índice
                            const cliente = data[3]; // Ajusta el índice
                            const transporte = data[7]; // Ajusta el índice
                            const semana = data[1]; // Ajusta el índice
                            const coincideVariedad = filtroVariedad.length === 0 ||
                                filtroVariedad
                                .includes(variedad);
                            const coincideExportadora = filtroExportadora.length === 0 ||
                                filtroExportadora
                                .includes(Exportadora);
                            const coincideEtiqueta = filtroEtiqueta.length === 0 ||
                                filtroEtiqueta
                                .includes(etiqueta);
                            const coincideCliente = filtroCliente.length === 0 || filtroCliente
                                .includes(cliente);
                            const coincideTransporte = filtroTransporte.length === 0 ||
                                filtroTransporte
                                .includes(transporte);
                            const coincideSemana = filtroSemana.length === 0 || filtroSemana
                                .includes(semana);

                            return coincideVariedad && coincideExportadora &&
                                coincideEtiqueta &&
                                coincideCliente && coincideTransporte && coincideSemana;
                        });




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
            }

            function detalleMaritimo() {
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
                        semana = data.semana;
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
                        semana.forEach(
                            function(value) {
                                $('#filtroSemana').append(new Option(value, value));
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
                                    data: 'n_nave',
                                    title: 'Nave'
                                },
                                {
                                    data: 'contenedor',
                                    title: 'Contenedor'
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
                                    return typeof i === 'string' ? i.replace(/[\$,]/g,
                                            '') *
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
                            const filtroSemana = $('#filtroSemana').val() || [];
                            const variedad = data[
                                12
                            ]; // Ajusta el índice según la posición de la columna en la tabla
                            const Exportadora = data[8]; // Ajusta el índice
                            const etiqueta = data[13]; // Ajusta el índice
                            const cliente = data[3]; // Ajusta el índice
                            const transporte = data[7]; // Ajusta el índice
                            const semana = data[1]; // Ajusta el índice
                            const coincideVariedad = filtroVariedad.length === 0 ||
                                filtroVariedad
                                .includes(variedad);
                            const coincideExportadora = filtroExportadora.length === 0 ||
                                filtroExportadora
                                .includes(Exportadora);
                            const coincideEtiqueta = filtroEtiqueta.length === 0 ||
                                filtroEtiqueta
                                .includes(etiqueta);
                            const coincideCliente = filtroCliente.length === 0 || filtroCliente
                                .includes(cliente);
                            const coincideTransporte = filtroTransporte.length === 0 ||
                                filtroTransporte
                                .includes(transporte);
                            const coincideSemana = filtroSemana.length === 0 || filtroSemana
                                .includes(semana);

                            return coincideVariedad && coincideExportadora &&
                                coincideEtiqueta &&
                                coincideCliente && coincideTransporte && coincideSemana;
                        });




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
            }

    });
    </script>
@endsection
