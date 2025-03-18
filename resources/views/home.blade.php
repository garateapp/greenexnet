@extends('layouts.admin')
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
</style>
@section('content')
    <div class="content">
        <div class="alert alert-success" id="msgOK" style="display:none;">

        </div>

        <div class="alert alert-danger" id="msgKO" style="display:none;">

        </div>
        <div class="row">
            <div id="loading-animation"
                style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
                <video autoplay loop muted style="width: 200px; height: auto;">
                    <source src="{{ asset('img/transito.webm') }}" type="video/webm">
                    Your browser does not support the video tag.
                </video>
                <br />
                <div class="text-white text-opacity-75 text-end" id="loading-animation-text">Obteniendo Instructivos,
                    Espera por favor..... :)</div>
            </div>
        </div>
        <div class="row">

            <div class="col-lg-12">
                @can('control_panel')
                    <div class="card">
                        <div class="card-header">
                            Liquidaciones Temporada 2024 -2025
                        </div>

                        <div class="card-body">
                            <div class="row">
                                {{-- <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Liquidaciones Cherries
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="card">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <span>Liquidaciones Cargadas</span>
                                                            <span class="badge bg-primary" id="totalInstructivosBadge"
                                                                style="color:#FFF;font-weight: bold;font-size: x-large;">0</span>
                                                        </div>
                                                        <div class="card-body" style="height: 300px; overflow-y: scroll;">
                                                            <div id="lstLiquidacionesCargadas">
                                                                <table class="table table-bordered"
                                                                    id="tblLiquidacionesCargadas">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>
                                                                                Instructivos
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tbodyLiquidacionesCargadas">
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="card">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <span>Liquidaciones NO Cargadas</span>
                                                            <span class="badge bg-primary" id="totalInstructivosNoCargadosBadge"
                                                                style="color:#FFF;font-weight: bold;font-size: x-large;">0</span>
                                                        </div>
                                                        <div class="card-body" style="height: 300px; overflow-y: scroll;">
                                                            <div id="lstLiquidacionesNoCargadas">
                                                                <table class="table table-bordered"
                                                                    id="tblLiquidacionesNOCargadas">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>
                                                                                Instructivos No Cargados
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tbodyLiquidacionesNoCargadas">
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="card">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <span>Liquidaciones Con FOB 100%</span>
                                                            <span class="badge bg-primary" id="totalInstructivosConFOBBadge"
                                                                style="color:#FFF;font-weight: bold;font-size: x-large;">0</span>
                                                        </div>
                                                        <div class="card-body" style="height: 300px; overflow-y: scroll;">
                                                            <div id="lstLiquidacionesCargadasFOB">
                                                                <table class="table table-bordered"
                                                                    id="tblLiquidacionesCargadas">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>
                                                                                Instructivos Con FOB
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tbodyLiquidacionesCargadasConFOB">
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="card">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <span>Liquidaciones Sin FOB o Con Problemas</span>
                                                            <span class="badge bg-primary" id="totalInstructivosSinFOBsBadge"
                                                                style="color:#FFF;font-weight: bold;font-size: x-large;">0</span>
                                                        </div>
                                                        <div class="card-body" style="height: 300px; overflow-y: scroll;">
                                                            <div id="lstLiquidacionesNoCargadas">
                                                                <table class="table table-bordered" id="tblLiquidacionesSinFOB">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>
                                                                                Instructivos Sin FOB Completo
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tbodyLiquidacionesSinFOB">
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
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            COMEX
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <button class="btn btn-secondary" id="btn_"
                                                        style="margin-bottom: 5px; width:230px; text-align: left;">
                                                        <a href="{{ route('admin.reporteria.compartivoliquidacionescx') }}"
                                                            style="color:white">
                                                            <i class="fa-fw fas fa-chart-line" aria-hidden="true"></i>
                                                            Comparativo Liquidaciones
                                                        </a>
                                                    </button>
                                                    <button class="btn btn-danger" id="btnActualizaGD"
                                                        style="margin-bottom: 5px; width:200px;text-align: left;">
                                                        Actualizar FOB masivo
                                                    </button>
                                                </div>
                                                <div class="col-lg-6">
                                                    <button class="btn btn-secondary" id="btn_procesar"
                                                        style="margin-bottom: 5px; width:200px; text-align: left;">
                                                        <a href="{{ route('admin.reporteria.ObtieneDatosFOB') }}"
                                                            style="color:white">
                                                            <i class="fa-fw fas fa-box" aria-hidden="true"></i>
                                                            Capturar Embalajes
                                                        </a>
                                                    </button>
                                                    <br>
                                                    <button class="btn btn-secondary" id="btn_procesar"
                                                        style="margin-bottom: 5px; width:200px; text-align: left;">
                                                        <a href="{{ route('admin.reporteria.obtieneFolio') }}"
                                                            style="color:white">
                                                            <i class="fa-fw fas fa-file-excel-o" aria-hidden="true"></i>
                                                            Capturar Folios
                                                        </a>
                                                    </button>
                                                    <br>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="col-lg-2" id="divFiltros">
                                    <h3>Filtros</h3>
                                    <label for="filtroCliente">Clientes</label>
                                    <select id="filtroCliente" class="form-control select2" multiple="multiple"></select>
                                    <label for="filtroMercado">Mercado</label>
                                    <select id="filtroMercado" class="form-control select2" multiple="multiple"></select>
                                    <label for="filtroCliente">Clientes</label>
                                    <select id="filtroCliente" class="form-control select2" multiple="multiple"></select>
                                    <h3>Visualización</h3>
                                    <label for="filtroAgrupación">Agrupación</label>
                                    <select id="filtroAgrupación" class="form-control select2" multiple="multiple"></select>
                                    <label for="filtroVista">Tipo de Vista</label>
                                    <select id="filtroVista" class="form-control select2" multiple="multiple"></select>
                                    <label for="filtroKgoCaja">Clientes</label>
                                    <select id="filtroKgoCaja" class="form-control select2" multiple="multiple"></select>
                                </div>
                                <div class="col-lg-10" id="divGraficos">
                                    <!-- Sección REsumen General-->
                                    <div class="row">
                                        <div class="card col-lg-3" style="margin-right: 15px;">
                                            <div class="card-body">
                                                <div class="fs-4 fw-bold text-center">
                                                    <p><i class="fas fa-file-contract fa-3x "></i></p>
                                                    <p class="indicador">449</p>
                                                </div>

                                                <div class="text-center">Liquidaciones Cargadas</div>
                                                <div class="progress progress-thin my-2">
                                                    <div class="progress-bar bg-success text-white text-center"
                                                        role="progressbar" style="width: 100%" aria-valuenow="100"
                                                        aria-valuemin="0" aria-valuemax="100">100%</div>
                                                    <div class="text-body-primary">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card col-lg-3" style="margin-right: 15px;">
                                            <div class="card-body">
                                                <div class="fs-4 fw-bold text-center">
                                                    <p><i class="fas fa-money-check fa-3x "></i></p>
                                                    <p class="indicador">$1.000.000.000.000</p>
                                                </div>

                                                <div class="text-center">FOB TOTAL</div>

                                                <div class="text-body-primary">

                                                </div>
                                            </div>
                                        </div>

                                        <div class="card col-lg-3" style="margin-right: 15px;">
                                            <div class="card-body">
                                                <div class="fs-4 fw-bold text-center">
                                                    <p><i class="fas fa-boxes fa-3x "></i></p>
                                                    <p class="indicador">$18,75</p>
                                                </div>

                                                <div class="text-center">PROMEDIO FOB CAJA EQ.</div>

                                                <div class="text-body-primary">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="card col-lg-3" style="margin-right: 15px;">
                                            <div class="card-body">
                                                <div class="fs-4 fw-bold text-center">
                                                    <p><i class="fas fa-boxes fa-3x "></i></p>
                                                    <p class="indicador">$18,75</p>
                                                </div>

                                                <div class="text-center">PROMEDIO FOB Kilo</div>

                                                <div class="text-body-primary">

                                                </div>
                                            </div>
                                        </div>

                                        <div class="card col-lg-8" style="margin-right: 15px;">
                                            <div class="card-body">
                                                <div class="fs-4 fw-bold text-center">
                                                    <p><i class="fas fa-boxes fa-3x "></i></p>
                                                    <p class="indicador"></p>
                                                </div>

                                                <div class="text-center">RNP</div>

                                                <div class="text-body-primary">
                                                    <div class="row g-3 align-items-end">
                                                        <!-- g-3 agrega un gutter (margen) entre columnas -->
                                                        {{-- <div class="col-auto">
                                                            <label for="cboFlete" class="form-label">Tipo de Flete</label>
                                                            <select id="cboFlete" class="form-control select2">
                                                                <option value="0">Seleccione Tipo de Flete</option>
                                                                <option value="1">Marítimo</option>
                                                                <option value="2">Aéreo</option>
                                                            </select>
                                                        </div> --}}
                                                        <div class="col-auto">
                                                            <label for="CostoKg" class="form-label">Costo por Kg</label>
                                                            <input type="text" class="form-control" id="CostoKg"
                                                                name="CostoKg" placeholder="Costo Kg" value="2.19"/>
                                                        </div>
                                                        <div class="col-auto">
                                                            <label for="Comision" class="form-label">Comisión (%)</label>
                                                            <input type="text" class="form-control" id="Comision"
                                                                name="Comision" placeholder="Comisión" value="8" />
                                                        </div>
                                                        <div class="col-auto">
                                                            <button type="button" class="btn btn-primary"
                                                                id="btnCalculaRNP">Calcular</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <p>
                                            <h5>FOB ACUMULADO</h5>
                                            </p>
                                        </div>
                                        <div class="row">
                                            <ul class="nav nav-tabs col-lg-12" id="ComparativaTabs" role="tablist">
                                                <!-- Pestaña Comparativa General -->
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="FobVariedad-tab" data-bs-toggle="tab"
                                                        data-bs-target="#FobVariedad" type="button" role="tab"
                                                        aria-controls="FobVariedad" aria-selected="false">
                                                        Por Variedad
                                                    </button>
                                                </li>
                                                <!-- Pestaña Comparativa x Cliente -->
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="FobFormato-tab" data-bs-toggle="tab"
                                                        data-bs-target="#FobFormato" type="button" role="tab"
                                                        aria-controls="FobFormato" aria-selected="true">
                                                        Por Formato
                                                    </button>
                                                </li>
                                                <!-- Pestaña de Rendimiento -->
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="FobCliente-tab" data-bs-toggle="tab"
                                                        data-bs-target="#FobCliente" type="button" role="tab"
                                                        aria-controls="FobCliente" aria-selected="false">
                                                        Por Cliente
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="FobColor-tab" data-bs-toggle="tab"
                                                        data-bs-target="#FobColor" type="button" role="tab"
                                                        aria-controls="FobColor" aria-selected="false">
                                                        Por Color
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="FobEtiqueta-tab" data-bs-toggle="tab"
                                                        data-bs-target="#FobEtiqueta" type="button" role="tab"
                                                        aria-controls="FobEtiqueta" aria-selected="false">
                                                        Por Etiqueta
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="FobFlete-tab" data-bs-toggle="tab"
                                                        data-bs-target="#FobFlete" type="button" role="tab"
                                                        aria-controls="FobFlete" aria-selected="false">
                                                        Por tipo de Flete
                                                    </button>
                                                </li>

                                            </ul>
                                            <div class="tab-content col-lg-12" id="reporteTabsContent">
                                                <!-- Pestaña FOB VARIEDAD -->
                                                <div class="tab-pane fade show active" id="FobVariedad" role="tabpanel"
                                                    aria-labelledby="FobVariedad-tab">
                                                    <div class="row">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    filtros
                                                                </div>
                                                                <div class="row">
                                                                    <div id="chartContainerFobVariedad">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Pestaña FOB FORMATO -->

                                                <div class="tab-pane fade" id="FobFormato" role="tabpanel"
                                                    aria-labelledby="FobFormato-tab">

                                                    <div class="row">

                                                        <div class="card">

                                                            <div class="card-body">
                                                                <div class="row">
                                                                    filtros
                                                                </div>
                                                                <div class="row">
                                                                    <div id="chartContainerFobFormato">

                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Pestaña FOB CLIENTE -->

                                                <div class="tab-pane fade" id="FobCliente" role="tabpanel"
                                                    aria-labelledby="FobCliente-tab">
                                                    <div class="row">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    filtros
                                                                </div>
                                                                <div class="row">
                                                                    <div id="chartContainerFobCliente">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Pestaña FOB COLOR -->

                                                <div class="tab-pane fade" id="FobColor" role="tabpanel"
                                                    aria-labelledby="FobColor-tab">
                                                    <div class="row">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    filtros
                                                                </div>
                                                                <div class="row">
                                                                    <div id="chartContainerFobColor">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Pestaña FOB ETIQUETA -->

                                                <div class="tab-pane fade" id="FobEtiqueta" role="tabpanel"
                                                    aria-labelledby="FobEtiqueta-tab">
                                                    <div class="row">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    filtros
                                                                </div>
                                                                <div class="row">
                                                                    <div id="chartContainerFobEtiqueta">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Pestaña FOB FLETE -->

                                                <div class="tab-pane fade" id="FobFlete" role="tabpanel"
                                                    aria-labelledby="FobFlete-tab">
                                                    <div class="row">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    filtros
                                                                </div>
                                                                <div class="row">
                                                                    <div id="chartContainerFobFlete">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>


                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        let liquidacionesData = []; // Variable global para almacenar los 8500 registros

        document.addEventListener("DOMContentLoaded", function() {

            $("#cboFlete").select2({
                placeholder: "Seleccione Tipo de Flete",
                allowClear: true
            });

            // Evento para el botón Calcular
            $("#btnCalculaRNP").on("click", function() {
                calcularRNP();
            });
            // Mostrar animación de carga
            $("#loading-animation").show();

            $.ajax({
                url: "{{ route('admin.reporteria.SabanaLiquidaciones') }}",
                type: "GET",
                success: function(response) {
                    liquidacionesData =
                        response; // Asumiendo que response es un array con los 8500 registros
                    console.log("Datos cargados:", liquidacionesData.length);
                    console.log(liquidacionesData);
                    // Ocultar animación de carga
                    $("#loading-animation").hide();

                    // Inicializar la página
                    inicializarFiltros();
                    actualizarResumenGeneral();
                    calcularRNP();
                    // inicializarGraficos();
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar datos:", error);
                    $("#msgKO").text("Error al cargar los datos. Intenta de nuevo.").show();
                    $("#loading-animation").hide();
                }
            });

            function inicializarFiltros() {
                // Extraer clientes únicos
                const clientesUnicos = [...new Set(liquidacionesData.map(item => item.cliente))];
                const mercadosUnicos = [...new Set(liquidacionesData.map(item => item.Pais))];
                const variedadesUnicas = [...new Set(liquidacionesData.map(item => item.variedad))];

                // Llenar select de clientes
                $("#filtroCliente").select2({
                    data: clientesUnicos.map(cliente => ({
                        id: cliente,
                        text: cliente
                    })),
                    placeholder: "Selecciona clientes",
                    allowClear: true
                });


                // Llenar select de mercados
                // $("#filtroMercado").select2({
                //     data: mercadosUnicos.map(mercado => ({
                //         id: mercado,
                //         text: mercado
                //     })),
                //     placeholder: "Selecciona mercados",
                //     allowClear: true
                // });

                // Agregar eventos de cambio para filtrar datos
                // $("#filtroCliente, #filtroMercado").on("change", function() {
                //     actualizarGraficos();
                // });
            }

            function actualizarResumenGeneral() {
                // 1. Liquidaciones Cargadas: Número de instructivos distintos
                const instructivosUnicos = [...new Set(liquidacionesData.map(item => item.Liquidacion))].filter(
                    Boolean);
                const totalLiquidaciones = instructivosUnicos.length;

                // 2. FOB Total: Suma de FOB_TO_USD
                const fobTotal = liquidacionesData.reduce((sum, item) => sum + (item.FOB_TO_USD || 0), 0);
                // 3. Promedio FOB Caja
                // Fórmula 1: Suma de FOB_Equivalente / Cantidad de registros
                const sumaFobEquivalente = liquidacionesData.reduce((sum, item) => sum + (item
                    .FOB_Equivalente || 0), 0);
                const promedioFobCaja1 = liquidacionesData.length > 0 ? (sumaFobEquivalente / liquidacionesData
                    .length).toFixed(2) : 0;

                // Fórmula 2: (Suma de FOB_TO_USD) / Suma de Kilos_total * 5
                const sumaFobUsdPorCajas = liquidacionesData.reduce((sum, item) => sum + ((item.FOB_TO_USD || 0)),
                    0);
                const sumaKilosTotal = liquidacionesData.reduce((sum, item) => sum + (item.Kilos_total || 0),
                    0);
                const promedioFobCaja2 = sumaKilosTotal > 0 ? ((sumaFobUsdPorCajas / sumaKilosTotal) * 5)
                    .toFixed(2) : 0;

                //1.- (Suma de FOB_TO_USD) / Suma de Kilos_total
                const sumaFobUsdPorKilo = liquidacionesData.reduce((sum, item) => sum + ((item.FOB_TO_USD || 0)),
                    0);
                const promedioFobKilo = sumaKilosTotal > 0 ? ((sumaFobUsdPorKilo / sumaKilosTotal))
                    .toFixed(2) : 0;



                // Actualizar las calugas en la interfaz
                $(".indicador").eq(0).text(totalLiquidaciones); // Liquidaciones Cargadas
                $(".indicador").eq(1).text(
                    `$${fobTotal.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
                ); // FOB Total
                $(".indicador").eq(2).text(`$${promedioFobCaja2}`); // Promedio FOB Caja Formula 2
                $(".indicador").eq(3).text(`$${promedioFobKilo}`); // Promedio FOB Kilo
            }

            function calcularRNP() {

                const costoKgInput = $("#CostoKg").val().trim();
                const comisionInput = $("#Comision").val().trim();



                // Validación 2: Campos no vacíos
                if (!costoKgInput || !comisionInput) {
                    alert("Por favor, completa ambos campos: Costo por Kg y Comisión.");
                    return;
                }

                // Validación 3: Campos son números válidos
                const costoKg = parseFloat(costoKgInput);
                const comision = parseFloat(comisionInput);
                if (isNaN(costoKg) || isNaN(comision)) {
                    alert("Costo por Kg y Comisión deben ser números válidos.");
                    return;
                }

                // Validación 4: Valores positivos o cero
                if (costoKg < 0 || comision < 0) {
                    alert("Costo por Kg y Comisión deben ser valores positivos o cero.");
                    return;
                }

                // // Filtrar datos según el tipo de flete
                // let datosFiltrados = [];
                // if (tipoFlete === "1") { // Marítimo: registros con valor en 'nave'
                //     datosFiltrados = liquidacionesData.filter(item => item.nave && item.nave.trim() !== "");
                // } else if (tipoFlete === "2") { // Aéreo: registros sin valor en 'nave'
                //     datosFiltrados = liquidacionesData.filter(item => !item.nave || item.nave.trim() === "");
                // }

                // if (datosFiltrados.length === 0) {
                //     $(".indicador").eq(3).text("No hay datos para este flete");
                //     return;
                // }
                const sumaFobUsdPorKilo = liquidacionesData.reduce((sum, item) => sum + ((item.FOB_TO_USD || 0)),
                    0);
                    const sumaKilosTotal = liquidacionesData.reduce((sum, item) => sum + (item.Kilos_total || 0),
                    0);
                // const promedioFobKilo = sumaKilosTotal > 0 ? ((sumaFobUsdPorKilo / sumaKilosTotal))
                //     .toFixed(2) : 0;
                // // Calcular RNP por kilo
                // const sumaFobToUsd = datosFiltrados.reduce((sum, item) => sum + (item.FOB_TO_USD || 0), 0);
                // const sumaKilosTotal = datosFiltrados.reduce((sum, item) => sum + (item.Kilos_total || 0), 0);

                let rnpKilo = 0;
                if (sumaFobUsdPorKilo > 0) {
                    costofinalestimado=((sumaFobUsdPorKilo / sumaKilosTotal) * (comision / 100)) + costoKg;
                    rnpKilo =(sumaFobUsdPorKilo / sumaKilosTotal)-costofinalestimado;
                }

                // Actualizar la caluga con el resultado
                $(".indicador").eq(4).text(
                    `$${rnpKilo.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
                    );
            }
        });
    </script>
    </div>
@endsection
@section('scripts')
    @parent
@endsection
