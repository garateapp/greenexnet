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

    .select2 {
        flex: 1;
        /* Hace que los selects ocupen el mismo ancho */
        min-width: 150px;
        /* Evita que sean demasiado pequeños */
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


                                <div class="col-lg-2" id="divFiltros">
                                    <h3>Filtros</h3>
                                    <label for="filtroFamilia">Familia</label>
                                    <select id="filtroFamilia" class="form-control select2" multiple="multiple">

                                        <option value="1">Carozos</option>
                                        <option value="2">Cherries</option>
                                        <option value="3">Cítricos</option>
                                        <option value="4">Pomáceas</option>
                                    </select>

                                    <label for="filtroEspecie">Especie</label>
                                    <select id="filtroEspecie" class="form-control select2" multiple="multiple">

                                    </select>
                                    <label for="filtroVariedad">Variedad</label>
                                    <select id="filtroVariedad" class="form-control select2" multiple="multiple">
                                    </select>
                                    <label for="filtroCliente">Clientes</label>
                                    <select id="filtroCliente" class="form-control select2" multiple="multiple"></select>
                                    <label for="filtroTransporte">Transporte</label>
                                    <select id="filtroTransporte" class="form-control select2" multiple="multiple"></select>
                                    {{-- <h3>Visualización</h3>
                                    <label for="filtroAgrupación">Agrupación</label>
                                    <select id="filtroAgrupación" class="form-control select2">
                                        <option value="0">Seleccione Agrupación</option>
                                        <option value="1">Kg</option>
                                        <option value="2">Caja</option>

                                    </select>
                                    <label for="filtroVista">Tipo de Vista</label>
                                    <select id="filtroVista" class="form-control select2">
                                        <option value="0" selected>Seleccione Vista</option>
                                        <option value="1">Tabla</option>
                                        <option value="2">Gráfico</option>

                                    </select> --}}

                                </div>
                                <div class="col-lg-10" id="divGraficos" style="overflow-y: scroll; height: 800px;">
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
                                                    <p class="indicador"></p>
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
                                                    <p class="indicador"></p>
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
                                                    <p class="indicador"></p>
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
                                                                name="CostoKg" placeholder="Costo Kg" value="2.19" />
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
                                    </div>
                                    <div class="row">
                                        <div class="card col-lg-5" style="margin-right: 10px;">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>CLIENTE</th>
                                                        <th>LIQUIDACIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbodyCxLiquidaciones">

                                                </tbody>

                                            </table>
                                        </div>

                                        <div class="card col-lg-6" style="margin-right: 10px;">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>VARIEDAD</th>
                                                        <th>KILOS</th>
                                                        <th>FOB/KG EQ</th>
                                                        <th>RNP Estimado</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbodyVariedadFOBKG">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="card col-lg-12">
                                            <div class="card-header">
                                                FOB ACUMULADO
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs col-lg-12" id="ComparativaTabs" role="tablist">
                                                    <!-- Pestaña Comparativa General -->
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active" id="FobVariedad-tab"
                                                            data-bs-toggle="tab" data-bs-target="#FobVariedad" type="button"
                                                            role="tab" aria-controls="FobVariedad" aria-selected="false">
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
                                                        <button class="nav-link" id="FobCalibre-tab" data-bs-toggle="tab"
                                                            data-bs-target="#FobCalibre" type="button" role="tab"
                                                            aria-controls="FobCalibre" aria-selected="false">
                                                            Por Calibre
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
                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col">
                                                        <select id="filtroFobVariedadEspecie" class="form-control select2"
                                                            multiple="multiple"></select>
                                                    </div>
                                                    <div class="col">
                                                        <select id="filtroFobVariedadCalibre" class="form-control select2"
                                                            multiple="multiple"></select>
                                                    </div>
                                                    <div class="col">
                                                        <select id="filtroFobVariedadVariedad" class="form-control select2"
                                                            multiple="multiple"></select>
                                                    </div>
                                                    <div class="col">
                                                        <select id="filtroFobVariedadFormato" class="form-control select2"
                                                            multiple="multiple"></select>
                                                    </div>

                                                    <div class="col">
                                                        <select id="filtroFobVariedadEtiqueta" class="form-control select2"
                                                            multiple="multiple"></select>
                                                    </div>
                                                    <div class="col">
                                                        <select id="filtroFobVariedadClientes" class="form-control select2"
                                                            multiple="multiple"></select>
                                                    </div>
                                                </div>
                                                <div class="tab-content col-lg-12" id="reporteTabsContent">
                                                    <!-- Pestaña FOB VARIEDAD -->
                                                    <div class="tab-pane fade show active  col-lg-12" id="FobVariedad"
                                                        role="tabpanel" aria-labelledby="FobVariedad-tab">

                                                        <div class="row">
                                                            <div id="chartContainerFobVariedad" class="col-lg-12"
                                                                style="width: 100%; height: 600px;">

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Pestaña FOB FORMATO -->

                                                    <div class="tab-pane fade col-lg-12" id="FobFormato" role="tabpanel"
                                                        aria-labelledby="FobFormato-tab">

                                                        <div class="row">
                                                            <div id="chartContainerFobFormato" class="col-lg-12"
                                                                style="width: 100%; height: 600px;">

                                                            </div>



                                                        </div>
                                                    </div>
                                                    <!-- Pestaña FOB CLIENTE -->

                                                    <div class="tab-pane fade" id="FobCliente" role="tabpanel"
                                                        aria-labelledby="FobCliente-tab">
                                                        <div class="row">


                                                            <div id="chartContainerFobCliente"
                                                                style="width: 100%; height: 600px;">

                                                            </div>

                                                        </div>
                                                    </div>

                                                    <!-- Pestaña FOB COLOR -->

                                                    <div class="tab-pane fade" id="FobCalibre" role="tabpanel"
                                                        aria-labelledby="FobCalibre-tab">


                                                        <div class="row">
                                                            <div id="chartContainerFobCalibre"
                                                                style="width: 100%; height: 600px;">

                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- Pestaña FOB ETIQUETA -->

                                                    <div class="tab-pane fade" id="FobEtiqueta" role="tabpanel"
                                                        aria-labelledby="FobEtiqueta-tab">

                                                        <div class="row">
                                                            <div id="chartContainerFobEtiqueta"
                                                                style="width: 100%; height: 600px;">

                                                            </div>
                                                        </div>
                                                    </div>



                                                    <!-- Pestaña FOB FLETE -->

                                                    <div class="tab-pane fade" id="FobFlete" role="tabpanel"
                                                        aria-labelledby="FobFlete-tab">




                                                        <div class="row">
                                                            <div id="chartContainerFobTransporte"
                                                                style="width: 100%; height: 600px;">

                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="card col-lg-12">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <span>Desempeño Clientes</span>
                                                <select class="form-select w-auto" id="cboDesempenoMedida">
                                                    <option value="1">Volumen</option>
                                                    <option value="2">FOB</option>
                                                </select>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>ClIENTE</th>
                                                            <th>VS CLIENTES</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tBodyDesempeño">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="card col-lg-12">
                                            <div class="card-header">
                                                Saldos Comparativos
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>CLIENTE</th>
                                                            <th>POSITIVO</th>
                                                            <th>NEGATIVO</th>
                                                            <th>SALDO</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tBodySaldos">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="card col-lg-12">
                                            <div class="card-header">
                                                Costos por Cliente
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th colspan="2" style="text-align: center;">COSTOS
                                                                VARIABLES
                                                            </th>
                                                            <th colspan="2" style="text-align: center;">COSTOS
                                                                FIJOS
                                                            </th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>
                                                            <th>CLIENTE</th>
                                                            <th>COMISIÓN</th>
                                                            <th>IMPUESTOS</th>
                                                            <th>COSTOS FIJOS MARITIMOS</th>
                                                            <th>COSTOS FIJOS AÉREOS</th>
                                                            <th>OTROS COSTOS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tBodyCostos">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="card col-lg-12">
                                            <div class="card-header">
                                                Resultados FOB Acumulados por Calibre y Color
                                            </div>
                                            <div class="card-body" style="overflow-x: auto;">
                                                <table class="table table-striped table-hover">
                                                    <thead id="tHeadResultadosColorCalibre">
                                                    </thead>
                                                    <tbody id="tBodyResultadosColorCalibre">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="card col-lg-12">
                                            <div class="card-header">
                                                Resultados FOB Acumulados por Semana y Variedad
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-striped table-hover">
                                                    <thead id="tHeadFOBPorSemanaVariedad"></thead>
                                                    <tbody id="tBodyFOBPorSemanaVariedad"></tbody>
                                                </table>
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
                        const variedadesSel = ($("#filtroVariedad").val() || []).map(val => val.toUpperCase());
                        const transporteSel = ($("#filtroTransporte").val() || []).map(val => val.toUpperCase());
                        const coloresSel = ($("#filtroFobVariedadColor").val() || []).map(val => val.toUpperCase());
                        //const etiquetasSel = ($("#filtroFobVariedadEtiqueta").val() || []).map(val => val.toUpperCase());
                        const clientesSel = ($("#filtroCliente").val() || []).map(val => val.toUpperCase());
                        const especiesSel = ($("#filtroEspecie").val() || []).map(val => val.toUpperCase());
                        const agrupacionSel = $("#filtroAgrupación").val();
                        const vistaSel = $("#filtroVista").val();

                        // Filtrar liquidacionesData
                        let datosFiltrados = liquidacionesData.filter(item => {
                            return (
                                (transporteSel.length === 0 || transporteSel.includes((item.Transporte.toUpperCase() || "")
                                    .toUpperCase())) &&
                                (variedadesSel.length === 0 || variedadesSel.includes((item.variedad.toUpperCase() || "")
                                    .toUpperCase())) &&
                                // (etiquetasSel.length === 0 || etiquetasSel.includes((item.etiqueta.toUpperCase() || "")
                                //     .toUpperCase())) &&
                                (clientesSel.length === 0 || clientesSel.includes((item.cliente.toUpperCase() || "")
                                    .toUpperCase())) &&
                                (especiesSel.length === 0 || especiesSel.includes((item.especie.toUpperCase() || "")
                                    .toUpperCase()))
                            );
                        });
                        calcularRNP(datosFiltrados);
                    });
                    // Mostrar animación de carga
                    $("#loading-animation").show();
                    // <select id="filtroFamilia" class="form-control select2" multiple="multiple">
                    //                 <option value="0">Seleccione Familia</option>
                    //                 <option value="1">Carozos</option>
                    //                 <option value="2">Cherries</option>
                    //                 <option value="3">Cítricos</option>
                    //                 <option value="4">Pomáceas</option>
                    //             </select>


                    $("#filtroFamilia").on("change", function() {
                        var familiaSeleccionada = parseInt($(this).val()); // Convertir a número si es necesario
                        var especies = [];

                        switch (familiaSeleccionada) {
                            case 1:
                                especies = ["PLUMS", "NECTARINES", "PEACHES"];
                                break;
                            case 2:
                                especies = ["CHERRIES"];
                                break;

                            case 4:
                                especies = ["APPLES", "PEARS", "MEMBRILLOS"];
                                break;
                            default:
                                especies = [];
                                break;
                        }

                        $("#filtroEspecie").val(especies).trigger("change"); // Actualizar Select2
                        $("#filtroFobVariedadEspecie").val(especies).trigger("change"); // Actualizar Select2
                    });

                    $.ajax({
                        url: "{{ route('admin.reporteria.SabanaLiquidaciones') }}",
                        type: "GET",
                        success: function(response) {
                            liquidacionesData =
                                response; // Asumiendo que response es un array con los 8500 registros

                            // Ocultar animación de carga
                            $("#loading-animation").hide();

                            // Inicializar la página

                            actualizarResumenGeneral(liquidacionesData);
                            calcularRNP(liquidacionesData);
                            actualizarTablaLiquidacionesPorCliente(liquidacionesData);
                            actualizarTablaVariedadFOBKG(liquidacionesData);
                            actualizarTablaDesempenoClientes(liquidacionesData);
                            actualizarTablaSaldosComparativos(liquidacionesData);
                            actualizarTablaCostosPorCliente(liquidacionesData);
                            actualizarTablaResultadosColorCalibre(liquidacionesData);
                            actualizarTablaFOBPorSemanaVariedad(liquidacionesData);
                            // inicializarGraficos

                            actualizarGraficoFobVariedad(liquidacionesData);
                            actualizarGraficoFobFormato(liquidacionesData);
                            actualizarGraficoFobCliente(liquidacionesData);
                            actualizarGraficoFobCalibre(liquidacionesData);
                            actualizarGraficoFobEtiqueta(liquidacionesData);
                            actualizarGraficoFobTransporte(liquidacionesData);

                            inicializarFiltros();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al cargar datos:", error);
                            $("#msgKO").text("Error al cargar los datos. Intenta de nuevo.").show();
                            $("#loading-animation").hide();
                        }
                    });
                    // Escuchar cambios en los filtros
                    $("#filtroEspecie, #filtroVariedad, #filtroCliente, #filtroTransporte, #filtroFamilia").on("change",
                        function() {
                            filtrarYActualizar();
                        });
                    $("#filtroFobVariedadCalibre, #filtroFobVariedadVariedad, #filtroFobVariedadFormato," +
                        "#filtroFobVariedadColor, #filtroFobVariedadEtiqueta, #filtroFobVariedadClientes, " +
                        "#filtroAgrupación, #filtroVista, #filtroFobVariedadEspecie").on('change', function() {
                        filtrarYActualizarGraficos();
                    });
                    // Función para filtrar los datos y actualizar visualizaciones
                    function filtrarYActualizar() {
                        // Obtener valores seleccionados de los filtros
                        
                        const variedadesSel = ($("#filtroVariedad").val() || []).map(val => val.toUpperCase());
                        const transporteSel = ($("#filtroTransporte").val() || []).map(val => val.toUpperCase());
                        const coloresSel = ($("#filtroFobVariedadColor").val() || []).map(val => val.toUpperCase());
                        //const etiquetasSel = ($("#filtroFobVariedadEtiqueta").val() || []).map(val => val.toUpperCase());
                        const clientesSel = ($("#filtroCliente").val() || []).map(val => val.toUpperCase());
                        const especiesSel = ($("#filtroEspecie").val() || []).map(val => val.toUpperCase());
                        const agrupacionSel = $("#filtroAgrupación").val();
                        const vistaSel = $("#filtroVista").val();

                        // Filtrar liquidacionesData
                        let datosFiltrados = liquidacionesData.filter(item => {
                            return (
                                (transporteSel.length === 0 || transporteSel.includes((item.Transporte.toUpperCase() || "")
                                    .toUpperCase())) &&
                                (variedadesSel.length === 0 || variedadesSel.includes((item.variedad.toUpperCase() || "")
                                    .toUpperCase())) &&
                                // (etiquetasSel.length === 0 || etiquetasSel.includes((item.etiqueta.toUpperCase() || "")
                                //     .toUpperCase())) &&
                                (clientesSel.length === 0 || clientesSel.includes((item.cliente.toUpperCase() || "")
                                    .toUpperCase())) &&
                                (especiesSel.length === 0 || especiesSel.includes((item.especie.toUpperCase() || "")
                                    .toUpperCase()))
                            );
                        });


                        console.log("Datos filtrados:", datosFiltrados.length);

                        // Actualizar visualizaciones con los datos filtrados
                        actualizarVisualizaciones(datosFiltrados, agrupacionSel, vistaSel);
                    }

                    function filtrarYActualizarGraficos() {
                        // Obtener valores seleccionados de los filtros
                        const especiesSel = ($("#filtroFobVariedadEspecie").val() || []).map(val => val.toUpperCase());
                        const calibresSel = ($("#filtroFobVariedadCalibre").val() || []).map(val => val.toUpperCase());
                        const variedadesSel = ($("#filtroFobVariedadVariedad").val() || []).map(val => val.toUpperCase());
                        const formatosSel = ($("#filtroFobVariedadFormato").val() || []).map(val => val.toUpperCase());
                        const coloresSel = ($("#filtroFobVariedadColor").val() || []).map(val => val.toUpperCase());
                        const etiquetasSel = ($("#filtroFobVariedadEtiqueta").val() || []).map(val => val.toUpperCase());
                        const clientesSel = ($("#filtroFobVariedadClientes").val() || []).map(val => val.toUpperCase());
                        const agrupacionSel = $("#filtroAgrupación").val() || "0"; // Si existe
                        const vistaSel = $("#filtroVista").val() || "0";

                        // Filtrar liquidacionesData
                        let datosFiltrados = liquidacionesData.filter(item => {
                            return (
                                (calibresSel.length === 0 || calibresSel.includes((item.Calibre.toUpperCase() || "")
                                    .toUpperCase())) &&
                                (variedadesSel.length === 0 || variedadesSel.includes((item.variedad.toUpperCase() || "")
                                    .toUpperCase())) &&
                                (formatosSel.length === 0 || formatosSel.includes((item.Peso_neto || "")
                                    .toUpperCase())) &&
                                (coloresSel.length === 0 || coloresSel.includes(
                                    (item.calibre || item.Calibre || "").toUpperCase().endsWith("d") ?
                                    "dark" : "light"
                                )) &&
                                (etiquetasSel.length === 0 || etiquetasSel.includes((item.etiqueta.toUpperCase() || "")
                                    .toUpperCase())) &&
                                (clientesSel.length === 0 || clientesSel.includes((item.cliente.toUpperCase() || "")
                                    .toUpperCase())) &&
                                (especiesSel.length === 0 || especiesSel.includes((item.especie.toUpperCase() || "")
                                    .toUpperCase()))
                            );
                        });


                        console.log("Datos filtrados:", datosFiltrados.length);

                        // Actualizar visualizaciones con los datos filtrados
                        actualizarVisualizacionesGraficos(datosFiltrados, agrupacionSel, vistaSel);
                    }
                    // Función para actualizar todas las visualizaciones
                    function actualizarVisualizaciones(datos = liquidacionesData, agrupacion = "0", vista = "0") {
                        actualizarResumenGeneral(datos);
                        calcularRNP(datos);
                        actualizarTablaLiquidacionesPorCliente(datos);
                        actualizarTablaVariedadFOBKG(datos);
                        actualizarTablaDesempenoClientes(datos);
                        actualizarTablaSaldosComparativos(datos);
                        actualizarTablaCostosPorCliente(datos);
                        actualizarTablaResultadosColorCalibre(datos);
                        actualizarTablaFOBPorSemanaVariedad(datos);
                        actualizarGraficoFobVariedad(datos);
                        actualizarGraficoFobFormato(datos);
                        actualizarGraficoFobCliente(datos);
                        actualizarGraficoFobCalibre(datos);
                        actualizarGraficoFobEtiqueta(datos);
                        actualizarGraficoFobTransporte(datos);
                        // Actualizar gráficos según la agrupación y vista seleccionada



                        // Según el tipo de vista seleccionado
                        if (vista === "1") {
                            // Mostrar tabla
                            $("#tablaVisualizacion").show();
                            $("#graficoVisualizacion").hide();
                            actualizarTabla(datos, agrupacion);
                        } else if (vista === "2") {
                            // Mostrar gráfico
                            $("#tablaVisualizacion").hide();
                            $("#graficoVisualizacion").show();
                            actualizarGrafico(datos, agrupacion);
                        }
                    }

                    function actualizarVisualizacionesGraficos(datos = liquidacionesData, agrupacion = "0", vista = "0") {

                        actualizarGraficoFobVariedad(datos, agrupacion);
                        actualizarGraficoFobFormato(datos, agrupacion);
                        actualizarGraficoFobCliente(datos, agrupacion);
                        actualizarGraficoFobCalibre(datos, agrupacion);
                        actualizarGraficoFobEtiqueta(datos, agrupacion);
                        actualizarGraficoFobTransporte(datos, agrupacion);

                    }
                    // Ejemplo de función para actualizar una tabla (ajusta según tu estructura)
                    function actualizarTabla(datos, agrupacion) {
                        // Lógica para renderizar la tabla según 'agrupacion' (Kg o Caja)
                        console.log("Actualizando tabla con", datos.length, "registros y agrupación", agrupacion);
                    }

                    // Ejemplo de función para actualizar un gráfico (ajusta según tu librería de gráficos)
                    function actualizarGrafico(datos, agrupacion) {
                        // Lógica para renderizar el gráfico (puedes usar Chart.js, por ejemplo)
                        console.log("Actualizando gráfico con", datos.length, "registros y agrupación", agrupacion);
                    }

                    function inicializarFiltros() {
                        // Extraer clientes únicos
                        const clientesUnicos = [...new Set(liquidacionesData.map(item => (item.cliente || "")
                        .toUpperCase()))];
                        const mercadosUnicos = [...new Set(liquidacionesData.map(item => (item.Pais || "").toUpperCase()))];
                        const variedadesUnicas = [...new Set(liquidacionesData.map(item => (item.variedad || "")
                            .toUpperCase()))];
                        const especiesUnicas = [...new Set(liquidacionesData.map(item => (item.especie || "")
                        .toUpperCase()))];
                        const transportesUnicos = [...new Set(liquidacionesData.map(item => (item.Transporte || "")
                            .toUpperCase()))];

                        const filtroFobVariedadCalibre = [...new Set(liquidacionesData.map(item => (item.Calibre || "")
                            .toUpperCase()))];
                        const filtroFobVariedadVariedad = [...new Set(liquidacionesData.map(item => (item.variedad || "")
                            .toUpperCase()))];
                        const filtroFobVariedadFormato = [...new Set(liquidacionesData.map(item => (item.Peso_neto ||
                        "")))];
                        const filtroFobVariedadColor = [...new Set(liquidacionesData.map(item => {
                            const calibre = item.calibre || item.Calibre || "";
                            return calibre.toUpperCase().endsWith("d") ? "dark" :
                            "light"; // Normalizar aquí también
                        }))];
                        const filtroFobVariedadEtiqueta = [...new Set(liquidacionesData.map(item => (item.etiqueta || "")
                            .toUpperCase()))];
                        const filtroFobVariedadClientes = [...new Set(liquidacionesData.map(item => (item.cliente || "")
                            .toUpperCase()))];
                        const filtroFobVariedadEspecie = [...new Set(liquidacionesData.map(item => (item.especie || "")
                            .toUpperCase()))];

                        // Llenar select de clientes
                        $("#filtroCliente").select2({
                            data: clientesUnicos.map(cliente => ({
                                id: cliente,
                                text: cliente
                            })),
                            placeholder: "Selecciona clientes",
                            allowClear: true
                        });
                        $("#filtroVariedad").select2({
                            data: variedadesUnicas.map(variedad => ({
                                id: variedad,
                                text: variedad
                            })),
                            placeholder: "Selecciona Variedades",
                            allowClear: true
                        });
                        $("#filtroEspecie").select2({
                            data: especiesUnicas.map(especie => ({
                                id: especie,
                                text: especie
                            })),
                            placeholder: "Selecciona Especies",
                            allowClear: true
                        });
                        $("#filtroTransporte").select2({
                            data: transportesUnicos.map(transporte => ({
                                id: transporte,
                                text: transporte
                            })),
                            placeholder: "Selecciona Transportes",
                            allowClear: true
                        });
                        $("#filtroFobVariedadCalibre").select2({
                            data: filtroFobVariedadCalibre.map(calibre => ({
                                id: calibre,
                                text: calibre
                            })),
                            placeholder: "Selecciona Calibres",
                            allowClear: true
                        });
                        $("#filtroFobVariedadVariedad").select2({
                            data: filtroFobVariedadVariedad.map(variedad => ({
                                id: variedad,
                                text: variedad
                            })),
                            placeholder: "Selecciona Variedades",
                            allowClear: true
                        });
                        $("#filtroFobVariedadFormato").select2({
                            data: filtroFobVariedadFormato.map(formato => ({
                                id: formato,
                                text: formato
                            })),
                            placeholder: "Selecciona Formatos",
                            allowClear: true
                        });
                        $("#filtroFobVariedadColor").select2({
                            data: filtroFobVariedadColor.map(color => ({
                                id: color,
                                text: color
                            })),
                            placeholder: "Selecciona Colores",
                            allowClear: true
                        });
                        $("#filtroFobVariedadEtiqueta").select2({
                            data: filtroFobVariedadEtiqueta.map(etiqueta => ({
                                id: etiqueta,
                                text: etiqueta
                            })),
                            placeholder: "Selecciona Etiquetas",
                            allowClear: true
                        });
                        $("#filtroFobVariedadClientes").select2({
                            data: filtroFobVariedadClientes.map(cliente => ({
                                id: cliente,
                                text: cliente
                            })),
                            placeholder: "Selecciona Clientes",
                            allowClear: true
                        });
                        $("#filtroFobVariedadEspecie").select2({
                            data: filtroFobVariedadEspecie.map(especie => ({
                                id: especie,
                                text: especie
                            })),
                            placeholder: "Selecciona Especies",
                            allowClear: true
                        });
                        $("#filtroFamilia").val(1).trigger("change");

                    }

                    function actualizarResumenGeneral(datos) {
                        // 1. Liquidaciones Cargadas: Número de instructivos distintos
                        const instructivosUnicos = [...new Set(datos.map(item => item.Liquidacion))].filter(
                            Boolean);
                        const totalLiquidaciones = instructivosUnicos.length;

                        // 2. FOB Total: Suma de FOB_TO_USD
                        const fobTotal = datos.reduce((sum, item) => sum + (item.FOB_TO_USD || 0), 0);
                       

                        // Fórmula 2: (Suma de FOB_TO_USD) / Suma de Kilos_total * 5
                        const sumaFobUsdPorCajas = datos.reduce((sum, item) => sum + ((item.FOB_TO_USD || 0)),
                            0);
                        const sumaKilosTotal = datos.reduce((sum, item) => sum + (item.Kilos_Total || 0),
                            0);

                        console.log(sumaKilosTotal);

                         // 3. Promedio FOB Caja
                        // Fórmula 1: Suma de FOB_Equivalente / Cantidad Kilos
                        const sumaFobEquivalente = datos.reduce((sum, item) => sum + ((item
                            .FOB_TO_USD) || 0), 0);

                        const promedioFobCaja1 = datos.length > 0 ? (sumaFobEquivalente / sumaKilosTotal
                            .length).toFixed(2) : 0;


                        const promedioFobKilo = sumaKilosTotal > 0 ? ((sumaFobUsdPorCajas / sumaKilosTotal))
                            .toFixed(2) : 0;

                        //1.- (Suma de FOB_TO_USD) / Suma de Kilos_total
                        const sumaFobUsdPorKilo = liquidacionesData.reduce((sum, item) => sum + ((item.FOB_TO_USD || 0)),
                            0);
                        const promedioFobCaja2 = sumaKilosTotal > 0 ? ((sumaFobUsdPorKilo / sumaKilosTotal))
                            .toFixed(2) : 0;



                        // Actualizar las calugas en la interfaz
                        $(".indicador").eq(0).text(totalLiquidaciones); // Liquidaciones Cargadas
                        $(".indicador").eq(1).text(
                            `$${fobTotal.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
                        ); // FOB Total
                        if($("#filtroFamilia").val() == 2){
                        $(".indicador").eq(2).text(`$${promedioFobCaja2*5}`); // Promedio FOB Caja Formula 2
                        $("#CostoKg").val("2.19");
                        }
                        else{
                            $("#CostoKg").val("0.61");
                            $(".indicador").eq(2).text(`$${promedioFobKilo*9}`); // Promedio FOB Caja Formula 2
                        }

                        $(".indicador").eq(3).text(`$${promedioFobKilo}`); // Promedio FOB Kilo
                    }

                    function calcularRNP(datos) {

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
                        const sumaFobUsdPorKilo = datos.reduce((sum, item) => sum + ((item.FOB_TO_USD || 0)),
                            0);
                        const sumaKilosTotal = datos.reduce((sum, item) => sum + (item.Kilos_Total || 0),
                            0);
                        // const promedioFobKilo = sumaKilosTotal > 0 ? ((sumaFobUsdPorKilo / sumaKilosTotal))
                        //     .toFixed(2) : 0;
                        // // Calcular RNP por kilo
                        // const sumaFobToUsd = datosFiltrados.reduce((sum, item) => sum + (item.FOB_TO_USD || 0), 0);
                        // const sumaKilosTotal = datosFiltrados.reduce((sum, item) => sum + (item.Kilos_total || 0), 0);

                        let rnpKilo = 0;
                        if (sumaFobUsdPorKilo > 0) {
                            costofinalestimado = ((sumaFobUsdPorKilo / sumaKilosTotal) * (comision / 100)) + costoKg;
                            rnpKilo = (sumaFobUsdPorKilo / sumaKilosTotal) - costofinalestimado;
                        }

                        // Actualizar la caluga con el resultado
                        $(".indicador").eq(4).text(
                            `$${rnpKilo.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
                        );
                        const variedadesSel = ($("#filtroVariedad").val() || []).map(val => val.toUpperCase());
                        const transporteSel = ($("#filtroTransporte").val() || []).map(val => val.toUpperCase());
                        const coloresSel = ($("#filtroFobVariedadColor").val() || []).map(val => val.toUpperCase());
                        //const etiquetasSel = ($("#filtroFobVariedadEtiqueta").val() || []).map(val => val.toUpperCase());
                        const clientesSel = ($("#filtroCliente").val() || []).map(val => val.toUpperCase());
                        const especiesSel = ($("#filtroEspecie").val() || []).map(val => val.toUpperCase());
                        const agrupacionSel = $("#filtroAgrupación").val();
                        const vistaSel = $("#filtroVista").val();

                        // Filtrar liquidacionesData
                        let datosFiltrados = liquidacionesData.filter(item => {
                            return (
                                (transporteSel.length === 0 || transporteSel.includes((item.Transporte.toUpperCase() || "")
                                    .toUpperCase())) &&
                                (variedadesSel.length === 0 || variedadesSel.includes((item.variedad.toUpperCase() || "")
                                    .toUpperCase())) &&
                                // (etiquetasSel.length === 0 || etiquetasSel.includes((item.etiqueta.toUpperCase() || "")
                                //     .toUpperCase())) &&
                                (clientesSel.length === 0 || clientesSel.includes((item.cliente.toUpperCase() || "")
                                    .toUpperCase())) &&
                                (especiesSel.length === 0 || especiesSel.includes((item.especie.toUpperCase() || "")
                                    .toUpperCase()))
                            );
                        });
                        actualizarTablaVariedadFOBKG(datosFiltrados);
                    }

                    function actualizarTablaLiquidacionesPorCliente(datos) {
                        // Agrupar liquidaciones únicas por cliente
                        const liquidacionesPorCliente = {};

                        datos.forEach(item => {
                            const cliente = (item.cliente || "Sin cliente").toUpperCase(); // Manejar casos sin cliente
                            if (!liquidacionesPorCliente[cliente]) {
                                liquidacionesPorCliente[cliente] = new Set(); // Usar Set para liquidaciones únicas
                            }
                            if (item.Liquidacion) {
                                liquidacionesPorCliente[cliente].add(item.Liquidacion);
                            }
                        });

                        // Generar las filas para la tabla
                        let htmlFilas = "";
                        for (const [cliente, liquidacionesSet] of Object.entries(liquidacionesPorCliente)) {
                            const cantidadLiquidaciones = liquidacionesSet.size;
                            htmlFilas += `
            <tr>
                <td>${cliente}</td>
                <td>${cantidadLiquidaciones}</td>
            </tr>
        `;
                        }

                        // Insertar las filas en el tbody
                        $("#tbodyCxLiquidaciones").html(htmlFilas);
                    }

                    function actualizarTablaVariedadFOBKG(datos) {
                        // Agrupar datos por variedad (homologando a mayúsculas)
                        const datosPorVariedad = {};

                        datos.forEach(item => {
                            const variedad = (item.variedad || "Sin variedad")
                                .toUpperCase(); // Normalizar a mayúsculas
                            if (!datosPorVariedad[variedad]) {
                                datosPorVariedad[variedad] = {
                                    kilosTotal: 0,
                                    sumaFobEquivalente: 0,
                                    totalCajas: 0
                                };
                            }
                            datosPorVariedad[variedad].kilosTotal += item.Kilos_Total || 0;
                            datosPorVariedad[variedad].sumaFobEquivalente += item.FOB_TO_USD || 0;
                            datosPorVariedad[variedad].totalCajas += item.Cajas || 0;
                        });

                        // Generar las filas para la tabla
                        let htmlFilas = "";
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
                        for (const [variedad, datos] of Object.entries(datosPorVariedad)) {
                            const kilos = datos.kilosTotal.toLocaleString('es-CL', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                            const fobCajaEq = datos.kilosTotal > 0 ?
                                (datos.sumaFobEquivalente / datos.kilosTotal).toLocaleString('es-CL', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) :
                                "0,00";
                                const RnpEstimado = datos.kilosTotal > 0 ?
                                ((datos.sumaFobEquivalente/ datos.kilosTotal)-costoKg-((datos.sumaFobEquivalente/datos.kilosTotal)*(comision/100))).toLocaleString('es-CL', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) :
                                "0,00";

                            htmlFilas += `
            <tr>
                <td>${variedad}</td>
                <td>${kilos}</td>
                <td>$${fobCajaEq}</td>
                <td>$${RnpEstimado}</td>
            </tr>
        `;
                        }

                        // Insertar las filas en el tbody
                        $("#tbodyVariedadFOBKG").html(htmlFilas);
                    }
                    $("#cboDesempenoMedida").on("change", function() {
                        const medida = $(this).val();
                        const variedadesSel = ($("#filtroVariedad").val() || []).map(val => val.toUpperCase());
                        const transporteSel = ($("#filtroTransporte").val() || []).map(val => val.toUpperCase());
                        const coloresSel = ($("#filtroFobVariedadColor").val() || []).map(val => val.toUpperCase());
                        //const etiquetasSel = ($("#filtroFobVariedadEtiqueta").val() || []).map(val => val.toUpperCase());
                        const clientesSel = ($("#filtroCliente").val() || []).map(val => val.toUpperCase());
                        const especiesSel = ($("#filtroEspecie").val() || []).map(val => val.toUpperCase());
                        const agrupacionSel = $("#filtroAgrupación").val();
                        const vistaSel = $("#filtroVista").val();

                        // Filtrar liquidacionesData
                        let datosFiltrados = liquidacionesData.filter(item => {
                            return (
                                (transporteSel.length === 0 || transporteSel.includes((item.Transporte.toUpperCase() || "")
                                    .toUpperCase())) &&
                                (variedadesSel.length === 0 || variedadesSel.includes((item.variedad.toUpperCase() || "")
                                    .toUpperCase())) &&
                                // (etiquetasSel.length === 0 || etiquetasSel.includes((item.etiqueta.toUpperCase() || "")
                                //     .toUpperCase())) &&
                                (clientesSel.length === 0 || clientesSel.includes((item.cliente.toUpperCase() || "")
                                    .toUpperCase())) &&
                                (especiesSel.length === 0 || especiesSel.includes((item.especie.toUpperCase() || "")
                                    .toUpperCase()))
                            );
                        });
                        actualizarTablaDesempenoClientes(datosFiltrados);
                    });

                    function actualizarTablaDesempenoClientes(datos) {
                        const medida = $("#cboDesempenoMedida").val(); // 1 = Volumen, 2 = FOB
                        const campoMedida = medida === "1" ? "Kilos_Total" : "FOB_kg";

                        // Agrupar datos por cliente
                        const datosPorCliente = {};
                        datos.forEach(item => {
                            const cliente = (item.cliente || "Sin cliente").toUpperCase();
                            if (!datosPorCliente[cliente]) {
                                datosPorCliente[cliente] = 0;
                            }
                            datosPorCliente[cliente] += item[campoMedida] || 0;
                        });

                        // Calcular el total general para el promedio del resto
                        const totalGeneral = Object.values(datosPorCliente).reduce((sum, valor) => sum + valor, 0);
                        const totalClientes = Object.keys(datosPorCliente).length;

                        // Generar las filas para la tabla
                        let htmlFilas = "";
                        const clientesOrdenados = Object.entries(datosPorCliente).sort((a, b) => b[1] - a[1]);
                        for (const [cliente, valorCliente] of clientesOrdenados) {
                            //if (cliente != "Fruit Fortune") {
                                // Promedio del resto (excluyendo al cliente actual)
                                console.log("cliente: " + cliente + " valor: " + valorCliente + " total: " + totalGeneral +
                                    " totalClientes: " + totalClientes);
                                const sumaResto = totalGeneral - valorCliente;
                                const promedioResto = totalClientes > 1 ? sumaResto / (totalClientes - 1) : 0;

                                // Porcentaje de diferencia
                                let porcentajeDiferencia = promedioResto > 0 ? ((valorCliente - promedioResto) /
                                    promedioResto *
                                    100) : 0;
                                porcentajeDiferencia = porcentajeDiferencia.toFixed(2); // Dos decimales
                                const signo = porcentajeDiferencia >= 0 ? "+" : "";
                                const claseColor = porcentajeDiferencia >= 0 ? "text-success" : "text-danger";

                                htmlFilas += `
            <tr>
                <td>${cliente}</td>
                <td class="${claseColor}">${signo}${porcentajeDiferencia}%</td>
            </tr>
        `;
                            //}
                        }

                        // Insertar las filas en el tbody
                        $("#tBodyDesempeño").html(htmlFilas);
                    }

                    function actualizarTablaSaldosComparativos(datos) {
                        // Filtrar liquidaciones con tipo de cambio válido
                        const datosFiltrados = datos.filter(item => item.TC && item.TC > 0);

                        // Agrupar por nave y luego por cliente y producto
                        const datosPorNave = {};
                        datosFiltrados.forEach(item => {
                            const nave = item.nave || "Sin nave";
                            const cliente = item.cliente || "Sin cliente";
                            const variedad = (item.variedad || "Sin variedad").toUpperCase();
                            const calibre = item.calibre || "Sin calibre";
                            const color = calibre.endsWith("D") ? "Dark" : calibre.endsWith("L") ? "Light" : "N/A";
                            const formato = item.embalaje || "Sin formato";

                            const productoKey = `${variedad}-${calibre}-${color}-${formato}`;

                            if (!datosPorNave[nave]) {
                                datosPorNave[nave] = {};
                            }
                            if (!datosPorNave[nave][cliente]) {
                                datosPorNave[nave][cliente] = {};
                            }
                            if (!datosPorNave[nave][cliente][productoKey]) {
                                datosPorNave[nave][cliente][productoKey] = {
                                    kilos: 0,
                                    fob: 0
                                };
                            }

                            datosPorNave[nave][cliente][productoKey].kilos += item.Kilos_Total || 0;
                            datosPorNave[nave][cliente][productoKey].fob += item.FOB_TO_USD || 0;
                        });

                        // Calcular diferencias por cliente
                        const saldosPorCliente = {};
                        for (const nave in datosPorNave) {
                            const clientesNave = datosPorNave[nave];
                            for (const cliente in clientesNave) {
                                if (!saldosPorCliente[cliente]) {
                                    saldosPorCliente[cliente] = {
                                        positivo: 0,
                                        negativo: 0
                                    };
                                }
                                const productosCliente = clientesNave[cliente];

                                for (const productoKey in productosCliente) {
                                    const {
                                        kilos,
                                        fob
                                    } = productosCliente[productoKey];
                                    const fobKgCliente = kilos > 0 ? fob / kilos : 0;

                                    // Calcular FOB/kg de otros clientes para este producto en esta nave
                                    let kilosOtros = 0;
                                    let fobOtros = 0;
                                    for (const otroCliente in clientesNave) {
                                        if (otroCliente !== cliente && clientesNave[otroCliente][productoKey]) {
                                            kilosOtros += clientesNave[otroCliente][productoKey].kilos;
                                            fobOtros += clientesNave[otroCliente][productoKey].fob;
                                        }
                                    }
                                    const fobKgOtros = kilosOtros > 0 ? fobOtros / kilosOtros : 0;

                                    // Calcular diferencia
                                    const diferencia = (fobKgCliente - fobKgOtros) * kilos;
                                    if (diferencia > 0) {
                                        saldosPorCliente[cliente].positivo += diferencia;
                                    } else if (diferencia < 0) {
                                        saldosPorCliente[cliente].negativo += diferencia;
                                    }
                                }
                            }
                        }

                        // Generar las filas para la tabla
                        let htmlFilas = "";
                        for (const [cliente, saldos] of Object.entries(saldosPorCliente)) {
                            const positivo = saldos.positivo.toLocaleString('es-CL', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            const negativo = saldos.negativo.toLocaleString('es-CL', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            const saldoTotal = (saldos.positivo + saldos.negativo).toLocaleString('es-CL', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            const claseSaldo = saldoTotal >= 0 ? "text-success" : "text-danger";

                            htmlFilas += `
            <tr>
                <td>${cliente}</td>
                <td>$${positivo}</td>
                <td>$${negativo}</td>
                <td class="${claseSaldo}">$${saldoTotal}</td>
            </tr>
        `;
                        }

                        // Insertar las filas en el tbody
                        $("#tBodySaldos").html(htmlFilas);
                    }

                    function actualizarTablaCostosPorCliente(datos) {
                        // Agrupar datos por cliente
                        const datosPorCliente = {};
                        datos.forEach(item => {
                            const cliente = (item.cliente || "Sin cliente").toUpperCase();
                            if (!datosPorCliente[cliente]) {
                                datosPorCliente[cliente] = {
                                    fobToUsd: 0,
                                    kilosTotal: 0,
                                    comToUsd: 0,
                                    impDestinoUsdTo: 0,
                                    fleteMaritUsdTo: 0,
                                    fleteAereoTo: 0,
                                    costoLogUsdTo: 0,
                                    costosUsdTo: 0,
                                    otrosCostosUsdTo: 0,
                                    otrosCostosDestUsd: 0
                                };
                            }
                            datosPorCliente[cliente].fobToUsd += item.FOB_TO_USD || 0;
                            datosPorCliente[cliente].kilosTotal += item.Kilos_Total || 0;
                            datosPorCliente[cliente].comToUsd += item.Com_TO_USD || 0;
                            datosPorCliente[cliente].impDestinoUsdTo += item.Imp_destino_USD_TO || 0;
                            datosPorCliente[cliente].fleteMaritUsdTo += item.Flete_Marit_USD_TO || 0;
                            datosPorCliente[cliente].fleteAereoTo += item.Flete_Aereo_TO || 0;
                            datosPorCliente[cliente].costoLogUsdTo += item.Costo_log_USD_TO || 0;
                            datosPorCliente[cliente].costosUsdTo += item.Costos_USD_TO || 0;
                            datosPorCliente[cliente].otrosCostosUsdTo += item.Otros_costos_USD_TO || 0;
                            datosPorCliente[cliente].otrosCostosDestUsd += item.Otros_costos_dest_USD || 0;
                        });

                        // Generar las filas para la tabla
                        let htmlFilas = "";
                        for (const [cliente, datos] of Object.entries(datosPorCliente)) {
                            const fobKg = datos.kilosTotal > 0 ? datos.fobToUsd / datos.kilosTotal : 0;
                            if (fobKg === 0) continue; // Evitar división por cero o FOB nulo

                            const costoVariable = datos.kilosTotal > 0 ? datos.comToUsd / datos.kilosTotal : 0;
                            const costoImpuesto = datos.kilosTotal > 0 ? datos.impDestinoUsdTo / datos.kilosTotal : 0;
                            const costoFijoMaritimo = datos.kilosTotal > 0 ? datos.fleteMaritUsdTo / datos.kilosTotal : 0;
                            const costoFijoAereo = datos.kilosTotal > 0 ? datos.fleteAereoTo / datos.kilosTotal : 0;
                            const otrosCostos = datos.kilosTotal > 0 ? (
                                (datos.costoLogUsdTo / datos.kilosTotal) +
                                (datos.costosUsdTo / datos.kilosTotal) +
                                (datos.otrosCostosUsdTo / datos.kilosTotal) +
                                (datos.otrosCostosDestUsd / datos.kilosTotal)
                            ) : 0;

                            const porcVariable = (costoVariable / fobKg * 100).toFixed(2);
                            const porcImpuesto = (costoImpuesto / fobKg * 100).toFixed(2);
                            const porcFijoMaritimo = (costoFijoMaritimo / fobKg * 100).toFixed(2);
                            const porcFijoAereo = (costoFijoAereo / fobKg * 100).toFixed(2);
                            const porcOtrosCostos = (otrosCostos / fobKg * 100).toFixed(2);

                            htmlFilas += `
            <tr>
                <td>${cliente}</td>
                <td>${porcVariable}%</td>
                <td>${porcImpuesto}%</td>
                <td>${porcFijoMaritimo}%</td>
                <td>${porcFijoAereo}%</td>
                <td>${porcOtrosCostos}%</td>
            </tr>
        `;
                        }

                        // Insertar las filas en el tbody
                        $("#tBodyCostos").html(htmlFilas);
                    }

                    function actualizarTablaResultadosColorCalibre(datos) {
                        // Agrupar datos por variedad y calibre para determinar calibres con valores
                        const datosPorVariedad = {};
                        const totalesPorCalibre = {};
                        datos.forEach(item => {
                            const variedad = (item.variedad || "Sin variedad").toUpperCase();
                            const calibre = item.calibre || "Sin calibre";
                            const fob = item.FOB_TO_USD || 0;
                            const kilos = item.Kilos_Total || 0;

                            if (kilos === 0) return; // Ignorar si kilos es 0

                            if (!datosPorVariedad[variedad]) {
                                datosPorVariedad[variedad] = {};
                            }
                            if (!datosPorVariedad[variedad][calibre]) {
                                datosPorVariedad[variedad][calibre] = {
                                    fob: 0,
                                    kilos: 0
                                };
                            }
                            datosPorVariedad[variedad][calibre].fob += fob;
                            datosPorVariedad[variedad][calibre].kilos += kilos;

                            if (!totalesPorCalibre[calibre]) {
                                totalesPorCalibre[calibre] = {
                                    fob: 0,
                                    kilos: 0
                                };
                            }
                            totalesPorCalibre[calibre].fob += fob;
                            totalesPorCalibre[calibre].kilos += kilos;
                        });

                        // Filtrar calibres con valores no nulos
                        const calibresConValores = Object.keys(totalesPorCalibre).filter(calibre => {
                            const {
                                fob,
                                kilos
                            } = totalesPorCalibre[calibre];
                            return kilos > 0 && fob / kilos > 0;
                        }).sort();

                        // Si no hay calibres con valores, no generamos la tabla
                        if (calibresConValores.length === 0) {
                            $("#tHeadResultadosColorCalibre").html("<tr><th>No hay datos disponibles</th></tr>");
                            $("#tBodyResultadosColorCalibre").html("");
                            return;
                        }

                        // Generar el thead dinámico con solo calibres relevantes
                        let theadHtml = `
        <tr>
            <th>VARIEDAD</th>
    `;
                        calibresConValores.forEach(calibre => {
                            theadHtml += `<th>${calibre}</th>`;
                        });
                        theadHtml += `<th>PROMEDIO</th></tr>`;
                        $("#tHeadResultadosColorCalibre").html(theadHtml);

                        // Generar el tbody
                        let tbodyHtml = "";

                        // Fila Acumulado Total
                        tbodyHtml += "<tr><td>Acumulado Total</td>";
                        let sumaTotalFob = 0;
                        let sumaTotalKilos = 0;
                        calibresConValores.forEach(calibre => {
                            const {
                                fob,
                                kilos
                            } = totalesPorCalibre[calibre];
                            const valor = kilos > 0 ? fob / kilos : 0;
                            tbodyHtml +=
                                `<td class="font-bold">$${valor.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>`;
                            sumaTotalFob += fob;
                            sumaTotalKilos += kilos;
                        });
                        const promedioTotal = sumaTotalKilos > 0 ? sumaTotalFob / sumaTotalKilos : 0;
                        tbodyHtml +=
                            `<td>$${promedioTotal.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td></tr>`;

                        // Filas por variedad
                        for (const [variedad, datos] of Object.entries(datosPorVariedad)) {
                            tbodyHtml += `<tr><td>${variedad}</td>`;
                            let sumaFobVariedad = 0;
                            let sumaKilosVariedad = 0;

                            calibresConValores.forEach(calibre => {
                                const {
                                    fob,
                                    kilos
                                } = datos[calibre] || {
                                    fob: 0,
                                    kilos: 0
                                };
                                const valor = kilos > 0 ? fob / kilos : 0;
                                tbodyHtml +=
                                    `<td>$${valor.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>`;
                                sumaFobVariedad += fob;
                                sumaKilosVariedad += kilos;
                            });
                            const promedioVariedad = sumaKilosVariedad > 0 ? sumaFobVariedad / sumaKilosVariedad : 0;
                            tbodyHtml +=
                                `<td>$${promedioVariedad.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td></tr>`;
                        }

                        $("#tBodyResultadosColorCalibre").html(tbodyHtml);
                    }

                    function actualizarTablaFOBPorSemanaVariedad(datos) {
                        // Función para obtener el número de semana ISO a partir de una fecha
                        function getISOWeek(date) {
                            const d = new Date(date);
                            d.setHours(0, 0, 0, 0);
                            d.setDate(d.getDate() + 4 - (d.getDay() || 7));
                            const yearStart = new Date(d.getFullYear(), 0, 1);
                            const weekNo = Math.ceil(((d - yearStart) / 86400000 + 1) / 7);
                            return `${d.getFullYear()}-W${weekNo.toString().padStart(2, '0')}`;
                        }

                        // Agrupar datos por variedad y semana
                        const datosPorVariedad = {};
                        const totalesPorSemana = {};
                        datos.forEach(item => {
                            const variedad = (item.variedad || "Sin variedad").toUpperCase();
                            const eta = item.ETA; // Asumimos que ETA es una fecha válida
                            if (!eta) return; // Ignorar si no hay ETA
                            const semana = getISOWeek(eta);
                            const fob = item.FOB_TO_USD || 0;
                            const kilos = item.Kilos_Total || 0;

                            if (kilos === 0) return; // Ignorar si kilos es 0

                            if (!datosPorVariedad[variedad]) {
                                datosPorVariedad[variedad] = {};
                            }
                            if (!datosPorVariedad[variedad][semana]) {
                                datosPorVariedad[variedad][semana] = {
                                    fob: 0,
                                    kilos: 0
                                };
                            }
                            datosPorVariedad[variedad][semana].fob += fob;
                            datosPorVariedad[variedad][semana].kilos += kilos;

                            if (!totalesPorSemana[semana]) {
                                totalesPorSemana[semana] = {
                                    fob: 0,
                                    kilos: 0
                                };
                            }
                            totalesPorSemana[semana].fob += fob;
                            totalesPorSemana[semana].kilos += kilos;
                        });

                        // Filtrar semanas con valores no nulos
                        const semanasConValores = Object.keys(totalesPorSemana).filter(semana => {
                            const {
                                fob,
                                kilos
                            } = totalesPorSemana[semana];
                            return kilos > 0 && fob / kilos > 0;
                        }).sort();

                        // Si no hay semanas con valores, no generamos la tabla
                        if (semanasConValores.length === 0) {
                            $("#tHeadFOBPorSemanaVariedad").html("<tr><th>No hay datos disponibles</th></tr>");
                            $("#tBodyFOBPorSemanaVariedad").html("");
                            return;
                        }

                        // Generar el thead dinámico con solo semanas relevantes
                        let theadHtml = `
        <tr>
            <th>VARIEDAD</th>
    `;
                        semanasConValores.forEach(semana => {
                            theadHtml += `<th>${semana}</th>`;
                        });
                        theadHtml += `<th>PROMEDIO</th></tr>`;
                        $("#tHeadFOBPorSemanaVariedad").html(theadHtml);

                        // Generar el tbody
                        let tbodyHtml = "";

                        // Fila Acumulado Total
                        tbodyHtml += "<tr><td>Acumulado Total</td>";
                        let sumaTotalFob = 0;
                        let sumaTotalKilos = 0;
                        semanasConValores.forEach(semana => {
                            const {
                                fob,
                                kilos
                            } = totalesPorSemana[semana];
                            const valor = kilos > 0 ? fob / kilos : 0;
                            tbodyHtml +=
                                `<td>$${valor.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>`;
                            sumaTotalFob += fob;
                            sumaTotalKilos += kilos;
                        });
                        const promedioTotal = sumaTotalKilos > 0 ? sumaTotalFob / sumaTotalKilos : 0;
                        tbodyHtml +=
                            `<td>$${promedioTotal.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td></tr>`;

                        // Filas por variedad
                        for (const [variedad, datos] of Object.entries(datosPorVariedad)) {
                            tbodyHtml += `<tr><td>${variedad}</td>`;
                            let sumaFobVariedad = 0;
                            let sumaKilosVariedad = 0;

                            semanasConValores.forEach(semana => {
                                const {
                                    fob,
                                    kilos
                                } = datos[semana] || {
                                    fob: 0,
                                    kilos: 0
                                };
                                const valor = kilos > 0 ? fob / kilos : 0;
                                tbodyHtml +=
                                    `<td>$${valor.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>`;
                                sumaFobVariedad += fob;
                                sumaKilosVariedad += kilos;
                            });
                            const promedioVariedad = sumaKilosVariedad > 0 ? sumaFobVariedad / sumaKilosVariedad : 0;
                            tbodyHtml +=
                                `<td>$${promedioVariedad.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td></tr>`;
                        }

                        $("#tBodyFOBPorSemanaVariedad").html(tbodyHtml);
                    }
                    //Gráficos 
                    function actualizarGraficoFobVariedad(datos = liquidacionesData, agrupacion = "0") {
                        // Destruir gráfico existente si hay uno
                        if (window.chartInstanceFobVariedad) {
                            window.chartInstanceFobVariedad.destroy();
                        }

                        // Agrupar datos por ETA_Week y variedad
                        const groupedData = datos.reduce((acc, item) => {
                            const week = item.ETA_Week + "-" + item.ETA.substring(0, 4) ||
                                "Sin semana"; // Manejar valores nulos
                            const variedad = (item.variedad || "Sin variedad")
                        .toUpperCase(); // Homologar a mayúsculas
                            if (!acc[week]) {
                                acc[week] = {};
                            }
                            if (!acc[week][variedad]) {
                                acc[week][variedad] = {
                                    totalFOB: 0,
                                    count: 0
                                };
                            }
                            acc[week][variedad].totalFOB += parseFloat(item.FOB_kg) || 0;
                            acc[week][variedad].count += 1;
                            return acc;
                        }, {});

                        // Preparar datos para el gráfico
                        const weeks = Object.keys(groupedData).sort((a, b) => {
                            const [yearA, weekA] = a.split('-W');
                            const [yearB, weekB] = b.split('-W');
                            return yearA - yearB || weekA - weekB;
                        });
                        const variedades = [...new Set(datos.map(item => (item.variedad ||
                            "Sin variedad").toUpperCase()))]; // Variedades únicas
                        const series = variedades.map(variedad => {
                            return {
                                name: variedad,
                                data: weeks.map(week => {
                                    const data = groupedData[week][variedad];
                                    return data ? (data.totalFOB / data.count).toFixed(2) :
                                        0; // Promedio por semana
                                })
                            };
                        });

                        // Configuración del gráfico con ApexCharts
                        const options = {
                            chart: {
                                type: 'line', // Gráfico de líneas para evolución temporal
                                height: 600,
                                zoom: {
                                    enabled: true,
                                    type: 'x',
                                    autoScaleYaxis: true
                                }
                            },
                            series: series,
                            xaxis: {
                                categories: weeks,
                                title: {
                                    text: 'Semana (ETA Week)'
                                },
                                labels: {
                                    rotate: -45, // Rotar etiquetas si son muchas
                                    trim: true
                                }
                            },
                            yaxis: {
                                title: {
                                    text: 'Precio por kg (FOB_kg)'
                                },
                                labels: {
                                    formatter: function(val) {
                                        return val.toLocaleString('es-CL', {
                                            minimumFractionDigits: 2
                                        });
                                    }
                                }
                            },
                            title: {
                                text: 'Evolución del Precio por kg por Variedad',
                                align: 'center'
                            },
                            stroke: {
                                curve: 'smooth', // Suavizar las líneas
                                width: 2
                            },
                            markers: {
                                size: 4 // Puntos en cada dato
                            },
                            tooltip: {
                                x: {
                                    format: 'dd/MM/yy' // Ajusta si ETA_Week tiene formato específico
                                },
                                y: {
                                    formatter: function(val) {
                                        return val.toLocaleString('es-CL') + ' USD/kg';
                                    }
                                }
                            },
                            legend: {
                                position: 'top',
                                horizontalAlign: 'left'
                            }
                        };

                        // Renderizar el gráfico
                        window.chartInstanceFobVariedad = new ApexCharts(document.querySelector(
                                "#chartContainerFobVariedad"),
                            options);
                        window.chartInstanceFobVariedad.render();
                    }

                    function actualizarGraficoFobFormato(datos = liquidacionesData, agrupacion = "0") {
                        // Destruir gráfico existente si hay uno
                        if (window.chartInstanceFobFormato) {
                            window.chartInstanceFobFormato.destroy();
                        }


                        // Preparar datos para el gráfico

                        // Agrupar datos por ETA_Week y embalaje_id (formato)
                        const groupedData = datos.reduce((acc, item) => {
                            const week = item.ETA_Week + "-" + item.ETA.substring(0, 4) ||
                                "Sin semana"; // Manejar valores nulos
                            const formato = item.Peso_neto || "Sin formato"; // Usar embalaje_id como formato
                            if (!acc[week]) {
                                acc[week] = {};
                            }
                            if (!acc[week][formato]) {
                                acc[week][formato] = {
                                    totalFOB: 0,
                                    count: 0
                                };
                            }
                            acc[week][formato].totalFOB += parseFloat(item.FOB_kg) || 0;
                            acc[week][formato].count += 1;
                            return acc;
                        }, {});

                        // Preparar datos para el gráfico
                        const weeks = Object.keys(groupedData).sort((a, b) => {
                            const [yearA, weekA] = a.split('-W');
                            const [yearB, weekB] = b.split('-W');
                            return yearA - yearB || weekA - weekB;
                        });
                        const formatos = [...new Set(datos.map(item => item.Peso_neto ||
                            "Sin formato"))]; // Formatos únicos
                        const series = formatos.map(formato => {
                            return {
                                name: formato,
                                data: weeks.map(week => {
                                    const data = groupedData[week][formato];
                                    return data ? (data.totalFOB / data.count).toFixed(2) :
                                        0; // Promedio por semana
                                })
                            };
                        });

                        // Configuración del gráfico con ApexCharts
                        const options = {
                            chart: {
                                type: 'line', // Gráfico de líneas para evolución temporal
                                height: 600,
                                width: '100%',
                                zoom: {
                                    enabled: true,
                                    type: 'x',
                                    autoScaleYaxis: true
                                }
                            },
                            series: series,
                            xaxis: {
                                categories: weeks,
                                title: {
                                    text: 'Semana (ETA_Week)'
                                },
                                labels: {
                                    rotate: -45, // Rotar etiquetas si son muchas
                                    trim: true
                                }
                            },
                            yaxis: {
                                title: {
                                    text: 'Precio por kg (FOB_kg)'
                                },
                                labels: {
                                    formatter: function(val) {
                                        return val.toLocaleString('es-CL', {
                                            minimumFractionDigits: 2
                                        });
                                    }
                                }
                            },
                            title: {
                                text: 'Evolución del Precio por kg por Formato',
                                align: 'center'
                            },
                            stroke: {
                                curve: 'smooth', // Suavizar las líneas
                                width: 2
                            },
                            markers: {
                                size: 4 // Puntos en cada dato
                            },
                            tooltip: {
                                x: {
                                    format: 'dd/MM/yy' // Ajusta si ETA_Week tiene formato específico
                                },
                                y: {
                                    formatter: function(val) {
                                        return val.toLocaleString('es-CL') + ' USD/kg';
                                    }
                                }
                            },
                            legend: {
                                position: 'top',
                                horizontalAlign: 'left'
                            }
                        };

                        // Renderizar el gráfico
                        window.chartInstanceFobFormato = new ApexCharts(document.querySelector("#chartContainerFobFormato"),
                            options);
                        window.chartInstanceFobFormato.render();
                    }

                    function actualizarGraficoFobCliente(datos = liquidacionesData, agrupacion = "0") {
                        // Destruir gráfico existente si hay uno
                        if (window.chartInstanceFobCliente) {
                            window.chartInstanceFobCliente.destroy();
                        }


                        // Preparar datos para el gráfico

                        // Agrupar datos por ETA_Week y embalaje_id (formato)
                        const groupedData = datos.reduce((acc, item) => {
                            const week = item.ETA_Week + "-" + item.ETA.substring(0, 4) ||
                                "Sin semana"; // Manejar valores nulos
                            const formato = (item.cliente || "Sin formato")
                        .toUpperCase(); // Usar embalaje_id como formato
                            if (!acc[week]) {
                                acc[week] = {};
                            }
                            if (!acc[week][formato]) {
                                acc[week][formato] = {
                                    totalFOB: 0,
                                    count: 0
                                };
                            }
                            acc[week][formato].totalFOB += parseFloat(item.FOB_kg) || 0;
                            acc[week][formato].count += 1;
                            return acc;
                        }, {});

                        // Preparar datos para el gráfico
                        const weeks = Object.keys(groupedData).sort((a, b) => {
                            const [yearA, weekA] = a.split('-W');
                            const [yearB, weekB] = b.split('-W');
                            return yearA - yearB || weekA - weekB;
                        });
                        const formatos = [...new Set(datos.map(item => (item.cliente ||
                            "Sin formato").toUpperCase()))]; // Formatos únicos
                        const series = formatos.map(formato => {
                            return {
                                name: formato,
                                data: weeks.map(week => {
                                    const data = groupedData[week][formato];
                                    return data ? (data.totalFOB / data.count).toFixed(2) :
                                        0; // Promedio por semana
                                })
                            };
                        });

                        // Configuración del gráfico con ApexCharts
                        const options = {
                            chart: {
                                type: 'line', // Gráfico de líneas para evolución temporal
                                height: 600,
                                width: '100%',
                                zoom: {
                                    enabled: true,
                                    type: 'x',
                                    autoScaleYaxis: true
                                }
                            },
                            series: series,
                            xaxis: {
                                categories: weeks,
                                title: {
                                    text: 'Semana (ETA_Week)'
                                },
                                labels: {
                                    rotate: -45, // Rotar etiquetas si son muchas
                                    trim: true
                                }
                            },
                            yaxis: {
                                title: {
                                    text: 'Precio por kg (FOB_kg)'
                                },
                                labels: {
                                    formatter: function(val) {
                                        return val.toLocaleString('es-CL', {
                                            minimumFractionDigits: 2
                                        });
                                    }
                                }
                            },
                            title: {
                                text: 'Evolución del Precio por kg por cliente',
                                align: 'center'
                            },
                            stroke: {
                                curve: 'smooth', // Suavizar las líneas
                                width: 2
                            },
                            markers: {
                                size: 4 // Puntos en cada dato
                            },
                            tooltip: {
                                x: {
                                    format: 'dd/MM/yy' // Ajusta si ETA_Week tiene formato específico
                                },
                                y: {
                                    formatter: function(val) {
                                        return val.toLocaleString('es-CL') + ' USD/kg';
                                    }
                                }
                            },
                            legend: {
                                position: 'top',
                                horizontalAlign: 'left'
                            }
                        };

                        // Renderizar el gráfico
                        window.chartInstanceFobCliente = new ApexCharts(document.querySelector("#chartContainerFobCliente"),
                            options);
                        window.chartInstanceFobCliente.render();
                    }

                    function actualizarGraficoFobCalibre(datos = liquidacionesData, agrupacion = "0") {
                        // Destruir gráfico existente si hay uno
                        if (window.chartInstanceFobCalibre) {
                            window.chartInstanceFobCalibre.destroy();
                        }


                        // Preparar datos para el gráfico

                        // Agrupar datos por ETA_Week y Calibre (calibre)
                        const groupedData = datos.reduce((acc, item) => {
                            const week = item.ETA_Week + "-" + item.ETA.substring(0, 4) ||
                                "Sin semana"; // Manejar valores nulos
                            const Calibre = (item.Calibre || "Sin formato")
                        .toUpperCase(); // Usar embalaje_id como formato
                            if (!acc[week]) {
                                acc[week] = {};
                            }
                            if (!acc[week][Calibre]) {
                                acc[week][Calibre] = {
                                    totalFOB: 0,
                                    count: 0
                                };
                            }
                            acc[week][Calibre].totalFOB += parseFloat(item.FOB_kg) || 0;
                            acc[week][Calibre].count += 1;
                            return acc;
                        }, {});

                        // Preparar datos para el gráfico
                        const weeks = Object.keys(groupedData).sort((a, b) => {
                            const [yearA, weekA] = a.split('-W');
                            const [yearB, weekB] = b.split('-W');
                            return yearA - yearB || weekA - weekB;
                        });
                        const Calibre = [...new Set(datos.map(item => (item.Calibre ||
                            "Sin CALIBRE").toUpperCase()))]; // Formatos únicos
                        const series = Calibre.map(Calibre => {
                            return {
                                name: Calibre,
                                data: weeks.map(week => {
                                    const data = groupedData[week][Calibre];
                                    return data ? (data.totalFOB / data.count).toFixed(2) :
                                        0; // Promedio por semana
                                })
                            };
                        });

                        // Configuración del gráfico con ApexCharts
                        const options = {
                            chart: {
                                type: 'line', // Gráfico de líneas para evolución temporal
                                height: 600,
                                width: '100%',
                                zoom: {
                                    enabled: true,
                                    type: 'x',
                                    autoScaleYaxis: true
                                }
                            },
                            series: series,
                            xaxis: {
                                categories: weeks,
                                title: {
                                    text: 'Semana (ETA_Week)'
                                },
                                labels: {
                                    rotate: -45, // Rotar etiquetas si son muchas
                                    trim: true
                                }
                            },
                            yaxis: {
                                title: {
                                    text: 'Precio por kg (FOB_kg)'
                                },
                                labels: {
                                    formatter: function(val) {
                                        return val.toLocaleString('es-CL', {
                                            minimumFractionDigits: 2
                                        });
                                    }
                                }
                            },
                            title: {
                                text: 'Evolución del Precio por kg por Calibre',
                                align: 'center'
                            },
                            stroke: {
                                curve: 'smooth', // Suavizar las líneas
                                width: 2
                            },
                            markers: {
                                size: 4 // Puntos en cada dato
                            },
                            tooltip: {
                                x: {
                                    format: 'dd/MM/yy' // Ajusta si ETA_Week tiene formato específico
                                },
                                y: {
                                    formatter: function(val) {
                                        return val.toLocaleString('es-CL') + ' USD/kg';
                                    }
                                }
                            },
                            legend: {
                                position: 'top',
                                horizontalAlign: 'left'
                            }
                        };

                        // Renderizar el gráfico
                        window.chartInstanceFobCalibre = new ApexCharts(document.querySelector("#chartContainerFobCalibre"),
                            options);
                        window.chartInstanceFobCalibre.render();
                    }

                    function actualizarGraficoFobEtiqueta(datos = liquidacionesData, agrupacion = "0") {
                        // Destruir gráfico existente si hay uno
                        if (window.chartInstanceFobEtiqueta) {
                            window.chartInstanceFobEtiqueta.destroy();
                        }


                        // Preparar datos para el gráfico

                        // Agrupar datos por ETA_Week y Calibre (calibre)
                        const groupedData = datos.reduce((acc, item) => {
                            const week = item.ETA_Week + "-" + item.ETA.substring(0, 4) ||
                                "Sin semana"; // Manejar valores nulos
                            const etiqueta = (item.etiqueta || "Sin Etiqueta")
                        .toUpperCase(); // Usar embalaje_id como formato
                            if (!acc[week]) {
                                acc[week] = {};
                            }
                            if (!acc[week][etiqueta]) {
                                acc[week][etiqueta] = {
                                    totalFOB: 0,
                                    count: 0
                                };
                            }
                            acc[week][etiqueta].totalFOB += parseFloat(item.FOB_kg) || 0;
                            acc[week][etiqueta].count += 1;
                            return acc;
                        }, {});

                        // Preparar datos para el gráfico
                        const weeks = Object.keys(groupedData).sort((a, b) => {
                            const [yearA, weekA] = a.split('-W');
                            const [yearB, weekB] = b.split('-W');
                            return yearA - yearB || weekA - weekB;
                        });
                        const etiqueta = [...new Set(datos.map(item => (item.etiqueta ||
                            "Sin ETIQUETA").toUpperCase()))]; // Formatos únicos
                        const series = etiqueta.map(etiqueta => {
                            return {
                                name: etiqueta,
                                data: weeks.map(week => {
                                    const data = groupedData[week][etiqueta];
                                    return data ? (data.totalFOB / data.count).toFixed(2) :
                                        0; // Promedio por semana
                                })
                            };
                        });

                        // Configuración del gráfico con ApexCharts
                        const options = {
                            chart: {
                                type: 'line', // Gráfico de líneas para evolución temporal
                                height: 600,
                                width: '100%',
                                zoom: {
                                    enabled: true,
                                    type: 'x',
                                    autoScaleYaxis: true
                                }
                            },
                            series: series,
                            xaxis: {
                                categories: weeks,
                                title: {
                                    text: 'Semana (ETA_Week)'
                                },
                                labels: {
                                    rotate: -45, // Rotar etiquetas si son muchas
                                    trim: true
                                }
                            },
                            yaxis: {
                                title: {
                                    text: 'Precio por kg (FOB_kg)'
                                },
                                labels: {
                                    formatter: function(val) {
                                        return val.toLocaleString('es-CL', {
                                            minimumFractionDigits: 2
                                        });
                                    }
                                }
                            },
                            title: {
                                text: 'Evolución del Precio por kg por Etiqueta',
                                align: 'center'
                            },
                            stroke: {
                                curve: 'smooth', // Suavizar las líneas
                                width: 2
                            },
                            markers: {
                                size: 4 // Puntos en cada dato
                            },
                            tooltip: {
                                x: {
                                    format: 'dd/MM/yy' // Ajusta si ETA_Week tiene formato específico
                                },
                                y: {
                                    formatter: function(val) {
                                        return val.toLocaleString('es-CL') + ' USD/kg';
                                    }
                                }
                            },
                            legend: {
                                position: 'top',
                                horizontalAlign: 'left'
                            }
                        };

                        // Renderizar el gráfico
                        window.chartInstanceFobEtiqueta = new ApexCharts(document.querySelector(
                                "#chartContainerFobEtiqueta"),
                            options);
                        window.chartInstanceFobEtiqueta.render();
                    }

                    function actualizarGraficoFobTransporte(datos = liquidacionesData, agrupacion = "0") {
                        // Destruir gráfico existente si hay uno
                        if (window.chartInstanceFobTransporte) {
                            window.chartInstanceFobTransporte.destroy();
                        }


                        // Preparar datos para el gráfico

                        // Agrupar datos por ETA_Week y Calibre (calibre)
                        const groupedData = datos.reduce((acc, item) => {
                            const week = item.ETA_Week + "-" + item.ETA.substring(0, 4) ||
                                "Sin semana"; // Manejar valores nulos
                            const Transporte = item.Transporte || "Sin Transporte"; // Usar embalaje_id como formato
                            if (!acc[week]) {
                                acc[week] = {};
                            }
                            if (!acc[week][Transporte]) {
                                acc[week][Transporte] = {
                                    totalFOB: 0,
                                    count: 0
                                };
                            }
                            acc[week][Transporte].totalFOB += parseFloat(item.FOB_kg) || 0;
                            acc[week][Transporte].count += 1;
                            return acc;
                        }, {});

                        // Preparar datos para el gráfico
                        const weeks = Object.keys(groupedData).sort((a, b) => {
                            const [yearA, weekA] = a.split('-W');
                            const [yearB, weekB] = b.split('-W');
                            return yearA - yearB || weekA - weekB;
                        });
                        const Transporte = [...new Set(datos.map(item => item.Transporte ||
                            "Sin formato"))]; // Formatos únicos
                        const series = Transporte.map(Transporte => {
                            return {
                                name: Transporte,
                                data: weeks.map(week => {
                                    const data = groupedData[week][Transporte];
                                    return data ? (data.totalFOB / data.count).toFixed(2) :
                                        0; // Promedio por semana
                                })
                            };
                        });

                        // Configuración del gráfico con ApexCharts
                        const options = {
                            chart: {
                                type: 'line', // Gráfico de líneas para evolución temporal
                                height: 600,
                                width: '100%',
                                zoom: {
                                    enabled: true,
                                    type: 'x',
                                    autoScaleYaxis: true
                                }
                            },
                            series: series,
                            xaxis: {
                                categories: weeks,
                                title: {
                                    text: 'Semana (ETA_Week)'
                                },
                                labels: {
                                    rotate: -45, // Rotar etiquetas si son muchas
                                    trim: true
                                }
                            },
                            yaxis: {
                                title: {
                                    text: 'Precio por kg (FOB_kg)'
                                },
                                labels: {
                                    formatter: function(val) {
                                        return val.toLocaleString('es-CL', {
                                            minimumFractionDigits: 2
                                        });
                                    }
                                }
                            },
                            title: {
                                text: 'Evolución del Precio por kg por Tipo de Transporte',
                                align: 'center'
                            },
                            stroke: {
                                curve: 'smooth', // Suavizar las líneas
                                width: 2
                            },
                            markers: {
                                size: 4 // Puntos en cada dato
                            },
                            tooltip: {
                                x: {
                                    format: 'dd/MM/yy' // Ajusta si ETA_Week tiene formato específico
                                },
                                y: {
                                    formatter: function(val) {
                                        return val.toLocaleString('es-CL') + ' USD/kg';
                                    }
                                }
                            },
                            legend: {
                                position: 'top',
                                horizontalAlign: 'left'
                            }
                        };

                        // Renderizar el gráfico
                        window.chartInstanceFobTransporte = new ApexCharts(document.querySelector(
                                "#chartContainerFobTransporte"),
                            options);
                        window.chartInstanceFobTransporte.render();
                    }

                });
            </script>
        </div>
    @endsection
    @section('scripts')
        @parent
    @endsection
