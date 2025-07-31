@extends('layouts.admin')
@section('content')
    <style>
        .font-color {
            color: #000 !important;
        }
    </style>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
        }

        /* th,
                                                                                                            td {
                                                                                                                border: 1px solid #dddddd;
                                                                                                                padding: 8px;
                                                                                                                text-align: left;
                                                                                                            } */

        .currency {
            text-align: right;
        }

        .section-header {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .negative {
            color: red;
        }

        .total-row {
            font-weight: bold;
            background-color: #e8e8e8;
        }

        .number {
            text-align: right;
        }

        .sub-item {
            padding-left: 30px;
        }

        .sub-total {
            background-color: #f9f9f9;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Generador de Liquidaciones
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12">


                    <div class="form-group">
                        <label for="filtroFamilia">Productor</label>
                        <select class="form-control select2" name="productor_id" id="productor_id" required>
                            @foreach ($productors as $id => $entry)
                                <option value="{{ $id }}" {{ old('productor_id') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filtroFamilia">Temporada</label>
                        <select class="form-control select2" name="temporada" id="temporada" required>
                            @foreach ($temporada as $id => $entry)
                                <option value="{{ $entry }}" {{ old('temporada') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filtroFamilia">Especie</label>
                        <select class="form-control select2" name="especie_id" id="especie_id" required multiple>
                            @foreach ($especie as $id => $entry)
                                <option value="{{ $id }}" {{ old('especie_id') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="TC">Valor USD</label>
                        <input type="text" class="form-control" id="TC" name="TC" value="978.07"
                            placeholder="Ingrese el valor del USD" required>
                    </div>
                    <div class="row">

                        <div class="col-md-1">

                            <button id="btnPreview" class="btn btn-success">Vista Previa</button>
                        </div>
                        <div class="col-md-1">

                            <button id="downloadPdf" class="btn btn-success">Descargar PDF</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <ul class="nav nav-tabs col-lg-12" id="CtaCte" role="tablist">

                                    <!-- Pestaña Cuenta Corriente -->
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="CuentaCorriente-tab" data-bs-toggle="tab"
                                            data-bs-target="#CuentaCorriente" type="button" role="tab"
                                            aria-controls="CuentaCorriente" aria-selected="true">
                                            Cuenta Corriente
                                        </button>
                                    </li>
                                    <!-- Pestaña Balance de Masas -->
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="BceMasa-tab" data-bs-toggle="tab"
                                            data-bs-target="#BceMasa" type="button" role="tab" aria-controls="BceMasa"
                                            aria-selected="true">
                                            Balance de Masas
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="Norma-tab" data-bs-toggle="tab" data-bs-target="#Norma"
                                            type="button" role="tab" aria-controls="Norma" aria-selected="true">
                                            Norma
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="NormaSemana-tab" data-bs-toggle="tab"
                                            data-bs-target="#NormaSemana" type="button" role="tab"
                                            aria-controls="NormaSemana" aria-selected="true">
                                            Norma Con Semana
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="FueraNorma-tab" data-bs-toggle="tab"
                                            data-bs-target="#FueraNorma" type="button" role="tab"
                                            aria-controls="FueraNorma" aria-selected="true">
                                            Fuera de Norma
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="Comercial-tab" data-bs-toggle="tab"
                                            data-bs-target="#Comercial" type="button" role="tab"
                                            aria-controls="Comercial" aria-selected="true">
                                            Comercial
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="Graficos-tab" data-bs-toggle="tab"
                                            data-bs-target="#Graficos" type="button" role="tab"
                                            aria-controls="Graficos" aria-selected="true">
                                            Gráficos
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="CtaCtejContent">

                                    <div class="tab-pane fade active show" id="CuentaCorriente" role="tabpanel"
                                        aria-labelledby="CuentaCorriente-tab">
                                        <div id="CuentaCorrienteContent">

                                            <!-- Contenido Cuenta Corriente -->



                                            <table id="ctacteTable">

                                                <tr class="section-header">
                                                    <td colspan="8" style="text-align:center">CUENTA CORRIENTE</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="8">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="8">&nbsp;</td>
                                                </tr>

                                                <tr>
                                                    <td>PRODUCTOR</td>
                                                    <td id="productorRut"></td>
                                                    <td colspan="3" class="productorNombre"></td>
                                                    <td colspan="3">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="8">&nbsp;</td>
                                                </tr>

                                                <!-- Sección Ventas -->
                                                <tr>
                                                    <td colspan="5">Total venta exportación temporada 2024-2025
                                                    </td>
                                                    <td>CAT 1</td>
                                                    <td>US$</td>
                                                    <td class="currency" id="suma-CAT-1"></td>
                                                </tr>

                                                <tr style="display: none;" id="trCATII">
                                                    <td colspan="5">Total venta exportación temporada 2024-2025
                                                    </td>
                                                    <td>CAT 2</td>
                                                    <td>US$</td>
                                                    <td class="currency negative" id="suma-CATII"></td>
                                                </tr>
                                                <tr id="trbonificacion">
                                                    <td colspan="6">Bonificación</td>

                                                    <td>US$</td>
                                                    <td class="currency" id="bonificacion"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="8">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6">&nbsp;</td>
                                                    <td>US$</td>
                                                    <td class="currency" id="valorTotalUsd"></td>
                                                </tr>

                                                <!-- Facturación -->
                                                <tr class="section-header">
                                                    <td colspan="8">Facturación (proformas)</td>
                                                </tr>
                                                <tbody id="anticipos">
                                                </tbody>
                                                <tr id="trinteresanticipo">
                                                    <td colspan="6">Interés</td>
                                                    <td>US$</td>
                                                    <td class="currency" id="interesanticipo"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6">Total facturación (Proformas)</td>
                                                    <td>US$</td>
                                                    <td class="currency" id="valorTotalFacturacion"></td>
                                                </tr>

                                                <!-- Otros Cargos -->
                                                <tr class="section-header">
                                                    <td colspan="8">Otros Cargos</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">Gastos de fruta no exportable</td>
                                                    <td colspan="2">Kilos</td>
                                                    <td id="kilosNoExportable"></td>
                                                    <td>US$</td>
                                                    <td class="currency" id="valorNoExportable"></td>
                                                </tr>
                                                <tr id="trBonifGastoNoExportable">
                                                    <td colspan="6">Bonificación Gasto Fruta no Exportable</td>
                                                    <td>US$</td>
                                                    <td class="currency negative" id="bonificacionGastoNoExportable"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6">Cuenta corriente envases</td>
                                                    <td>US$</td>
                                                    <td class="currency" id="ctacteenvases"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6">Fletes huerto</td>
                                                    <td>US$</td>
                                                    <td class="currency" id="fletehuerto"></td>
                                                </tr>
                                                <tr id="trBonificacionfletehuerto">
                                                    <td colspan="6">Bonificación flete huerto</td>
                                                    <td>US$</td>
                                                    <td class="currency negative" id="bonificacionfletehuerto">
                                                    </td>
                                                </tr>
                                                <tr id="trAnalisisMultiresiduosVirus">
                                                    <td colspan="6">Análisis / Prospecciones</td>
                                                    <td>US$</td>
                                                    <td class="currency" id="multiresiduos"></td>
                                                </tr>
                                                <tr id="trOtrosCargos">
                                                    <td colspan="6">Otros Cargos</td>
                                                    <td>US$</td>
                                                    <td class="currency" id="OtrosCargos"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6">Total Cargos</td>
                                                    <td>US$</td>
                                                    <td class="currency" id="totalOtrosCargos"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="8"><hr></td>
                                                <tr>
                                                    <td colspan="6">Saldo</td>
                                                    <td>US$</td>
                                                    <td class="currency" id="SaldoTotal"></td>
                                                </tr>

                                                <!-- Nota de Débito y Factura -->
                                                <tr>
                                                    <td colspan="4">&nbsp;</td>
                                                    <td colspan="2" id="fecha_tipo_cambio">TC 31-07-2025</td>
                                                    <td colspan="1">$</td>
                                                    <td class="currency" id="TCValor"></td>
                                                </tr>
                                                <tr class="section-header">
                                                    <td colspan="8">NOTA DE DÉBITO</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">Ajuste final exportación</td>
                                                    <td>Neto</td>
                                                    <td>$</td>
                                                    <td class="currency" id="NDVAlorNeto"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">Temporada 2024-2025</td>
                                                    <td>Iva</td>
                                                    <td>$</td>
                                                    <td class="currency" id="NDVAlorIva"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">&nbsp;</td>
                                                    <td>Total</td>
                                                    <td>$</td>
                                                    <td class="currency" id="NDVAlorTotal"></td>
                                                </tr>

                                                <tr class="section-header">
                                                    <td colspan="8">FACTURA</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">Comercial</td>
                                                    <td>Neto</td>
                                                    <td>$</td>
                                                    <td class="currency" id="FVAlorNeto"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">Temporada 2024-2025</td>
                                                    <td>Iva</td>
                                                    <td>$</td>
                                                    <td class="currency" id="FVAlorIva"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">&nbsp;</td>
                                                    <td>Total</td>
                                                    <td>$</td>
                                                    <td class="currency" id="FVAlorTotal"></td>
                                                </tr>
                                            </table>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade " id="BceMasa" role="tabpanel"
                                        aria-labelledby="BceMasa-tab">
                                        <div id="BceMasaContent">



                                            <table>

                                                <tr class="section-header">
                                                    <td colspan="5" style="text-align: center;">BALANCE DE MASAS</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: center;"
                                                        class="productorNombre">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">&nbsp;</td>
                                                </tr>
                                                <tbody id="bce-masas">

                                                </tbody>
                                                <!-- Encabezados de tabla -->

                                            </table>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="Norma" role="tabpanel"
                                        aria-labelledby="Norma-tab">
                                        <div id="NormaContent">



                                            <table>
                                                <tr class="section-header">
                                                    <td colspan="8" style="text-align: center;">EXPORTACIÓN DENTRO DE
                                                        NORMA</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="8" style="text-align: center;"
                                                        class="productorNombre">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="8">&nbsp;</td>
                                                </tr>

                                                <tbody id="norma">
                                                </tbody>

                                            </table>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="NormaSemana" role="tabpanel"
                                        aria-labelledby="NormaSemana-tab">
                                        <div id="NormaSemanaContent">
                                            <table>
                                                <tr class="section-header">
                                                    <td colspan="10" style="text-align: center;">EXPORTACIÓN DENTRO DE
                                                        NORMA</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="10" style="text-align: center;">Detalle por semana de
                                                        embarque</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="10" style="text-align: center;"
                                                        class="productorNombre"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="10">&nbsp;</td>
                                                </tr>

                                                <tbody id="norma-semana">
                                                </tbody>

                                            </table>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="FueraNorma" role="tabpanel"
                                        aria-labelledby="FueraNorma-tab">
                                        <div id="FueraNormaContent">
                                            <table>
                                                <tr class="section-header">
                                                    <td colspan="9" style="text-align: center;">EXPORTACIÓN FUERA DE
                                                        NORMA</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="9" style="text-align: center;"
                                                        class="productorNombre"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="9">&nbsp;</td>
                                                </tr>

                                                <tbody id="fuera-norma">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="Comercial" role="tabpanel"
                                        aria-labelledby="Comercial-tab">
                                        <div id="ComercialContent">
                                            <table>
                                                <tr class="section-header">
                                                    <td colspan="5" style="text-align: center;">COMERCIAL</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: center;"
                                                        class="productorNombre"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">&nbsp;</td>
                                                </tr>


                                                <tbody id="comercial">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="Graficos" role="tabpanel">
                                        <div id="charts" style="
    margin-left: 25%;
"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <!-- html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        $(document).ready(function() {

            if ($("#TC").val() == "" || $("#TC").val() == null || $("#TC").val() == undefined || $("#TC").val() ==
                "0") {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ingrese un Tipo de Cambio.',
                });
            } else {
                $("#TCValor").text($("#TC").val());
                $('#downloadPdf').on('click', function() {

                    generatePdf_pdf();
                });

                //Generación de PDF
                // Función principal para generar el PDF
                // Función principal para generar el PDF

                // Función para generar el PDF
                function generatePdf_pdf() {
                    const tabs = [{
                        name: 'Cuenta Corriente',
                        html: $('#CuentaCorriente').html()
                    }, {
                        name: 'Balance de Masas',
                        html: $('#BceMasa').html()
                    }, {
                        name: 'Norma',
                        html: $('#Norma').html()
                    }, {
                        name: 'Comercial',
                        html: $('#Comercial').html()
                    }];

                    const chartContainers = $('.chart-container');
                    const chartImages = [];

                    let processed = 0;

                    // Asegúrate de que todos los tabs estén visibles
                    $('.tab-pane').addClass('show active');
                    sendData();
                    // chartContainers.each(function() {
                    //     const chartId = $(this).attr('id');
                    //     const container = document.getElementById(chartId);

                    //     // Asegúrate de que el contenedor tenga tamaño
                    //     if (!container || container.offsetWidth === 0 || container.offsetHeight === 0) {
                    //         console.warn(`El gráfico ${chartId} no existe o tiene tamaño 0`);
                    //         processed++;
                    //         if (processed === chartContainers.length) sendData();
                    //         return;
                    //     }

                    //     // Opcional: eliminar tooltips u otros elementos que interfieran
                    //     $(".apexcharts-tooltip").remove();

                    //     // Captura del gráfico
                    //     html2canvas(container, {
                    //         scale: 2,
                    //         useCORS: true, // Si hay imágenes externas
                    //         logging: false
                    //     }).then(canvas => {
                    //         chartImages.push({
                    //             id: chartId,
                    //             image: canvas.toDataURL('image/png')
                    //         });

                    //         processed++;
                    //         if (processed === chartContainers.length) sendData();
                    //     }).catch(err => {
                    //         console.error(`Error al capturar ${chartId}:`, err);
                    //         processed++;
                    //         if (processed === chartContainers.length) sendData();
                    //     });
                    // });

                    function sendData() {
                        $.ajax({
                            url: "{{ route('admin.constructorliquidacion.generatepdf') }}",
                            method: "POST",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                tabs: tabs,
                                //  chartImages: chartImages,
                                productor_id: $('#productor_id').val(),
                                temporada: $('#temporada').val(),
                                especie_id: $('#especie_id').val()
                            },
                            xhrFields: {
                                responseType: 'blob'
                            },
                            success: function(response, status, xhr) {
                                const disposition = xhr.getResponseHeader('Content-Disposition');
                                let filename = 'Liquidación.pdf';

                                if (disposition && disposition.indexOf('filename=') !== -1) {
                                    const filenameRegex = new RegExp('filename="?([^"]+)"?', 'i');
                                    const matches = filenameRegex.exec(disposition);
                                    if (matches && matches[1]) {
                                        filename = matches[1];
                                    }
                                }

                                const link = document.createElement('a');
                                const blob = new Blob([response], {
                                    type: 'application/pdf'
                                });
                                link.href = window.URL.createObjectURL(blob);
                                link.download = filename;
                                link.click();
                                window.URL.revokeObjectURL(link.href);
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'No se pudo generar el PDF.', 'error');
                                console.error(error);
                            }
                        });
                    }
                }

                // Fin de la función generatePdf_pdf
                let productor_nombre = '';
                $('#productor_id').select2();
                $('#temporada').select2();
                $('#especie_id')
                    .select2();

                function resetAllValues() {
                    // Clear table cells in "Cuenta Corriente"
                    $('#productorRut').text('');
                    $('.productorNombre').text('');
                    $('#suma-CAT-1').text('');
                    $('#suma-CATII').text('');
                    $('#bonificacion').text('');
                    $('#valorTotalUsd').text('');
                    $('#interesanticipo').text('');
                    $('#valorTotalFacturacion').text('');
                    $('#kilosNoExportable').text('');
                    $('#valorNoExportable').text('');
                    $('#bonificacionGastoNoExportable').text('');
                    $('#ctacteenvases').text('');
                    $('#fletehuerto').text('');
                    $('#bonificacionfletehuerto').text('');
                    $('#multiresiduos').text('');
                    $('#OtrosCargos').text('');
                    $('#totalOtrosCargos').text('');
                    $('#SaldoTotal').text('');
                    $('#NDVAlorNeto').text('');
                    $('#NDVAlorIva').text('');
                    $('#NDVAlorTotal').text('');
                    $('#FVAlorNeto').text('');
                    $('#FVAlorIva').text('');
                    $('#FVAlorTotal').text('');

                    // Clear table bodies
                    $("#anticipos").html('');
                    $('#bce-masas').html('');
                    $('#norma').html('');
                    $('#norma-semana').html('');
                    $('#fuera-norma').html('');
                    $('#comercial').html('');

                    // Clear charts
                    $('#charts').html('');
                }

                $('#btnPreview').on('click', function() {
                    resetAllValues();
                    var productor_id = $('#productor_id').val();
                    var temporada = $('#temporada').val();
                    var especie_id = $('#especie_id').val();

                    if (productor_id && temporada && especie_id && productor_id !== "" && temporada !==
                        "" &&
                        especie_id !== "") {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('admin.constructorliquidacion.getProcesos') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "productor_id": productor_id,
                                "temporada": temporada,
                                "especie_id": especie_id
                            },
                            dataType: "json",
                            success: function(response) {
                                console.log('Respuesta del servidor:', response);
                                if (response.success) {
                                    // Manejar la respuesta exitosa

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Éxito',
                                        text: 'Datos cargados correctamente.',
                                    });
                                    $('#productorRut').text(response.productor.rut);
                                    $('.productorNombre').text(response.productor.nombre);
                                    productor_nombre = response.productor.nombre;

                                    let sumasPorCategoria = {
                                        'CAT1': {
                                            resultado_kilo: 0,
                                            resultado_total: 0,
                                            total_comercial: 0,
                                            total_kilos: 0,
                                            costo_comercial: 0,
                                            precio_comercial: 0,
                                        },
                                        'CATII': {
                                            resultado_kilo: 0,
                                            resultado_total: 0,
                                            total_comercial: 0,
                                            total_kilos: 0,
                                            costo_comercial: 0,
                                            precio_comercial: 0
                                        },
                                        'COMERCIAL': {
                                            resultado_kilo: 0,
                                            resultado_total: 0,
                                            total_comercial: 0,
                                            total_kilos: 0,
                                            costo_comercial: 0,
                                            precio_comercial: 0
                                        },
                                        'DESECHO': {
                                            resultado_kilo: 0,
                                            resultado_total: 0,
                                            total_comercial: 0,
                                            total_kilos: 0,
                                            costo_comercial: 0,
                                            precio_comercial: 0
                                        },
                                        'PRECALIBRE': {
                                            resultado_kilo: 0,
                                            resultado_total: 0,
                                            total_comercial: 0,
                                            total_kilos: 0,
                                            costo_comercial: 0,
                                            precio_comercial: 0
                                        },
                                        'MERMA': {
                                            resultado_kilo: 0,
                                            resultado_total: 0,
                                            total_comercial: 0,
                                            total_kilos: 0,
                                            costo_comercial: 0,
                                            precio_comercial: 0
                                        },
                                        'SOBRECALIBRE': {
                                            resultado_kilo: 0,
                                            resultado_total: 0,
                                            total_comercial: 0,
                                            total_kilos: 0,
                                            costo_comercial: 0,
                                            precio_comercial: 0
                                        },
                                        'SUPERMERCADO': {
                                            resultado_kilo: 0,
                                            resultado_total: 0,
                                            total_comercial: 0,
                                            total_kilos: 0,
                                            costo_comercial: 0,
                                            precio_comercial: 0
                                        },
                                        'COMERCIALHUERTO': {
                                            resultado_kilo: 0,
                                            resultado_total: 0,
                                            total_comercial: 0,
                                            total_kilos: 0,
                                            costo_comercial: 0,
                                            precio_comercial: 0
                                        }

                                    };

                                    // Iterar sobre los datos obtenidos
                                    $.each(response.result, function(index, item) {
                                        let categoria = item.categoria.replace(" ", "")
                                            .toUpperCase();
                                        //if (categoria == "SUPERMERCADO") {
                                        //categoria = 'CAT1';
                                        //}


                                        // Convertir los valores a números, manejando comas como separador decimal
                                        let resultadoKilo = parseFloat(item
                                            .resultado_kilo
                                            .replace(',', '.')) || 0;
                                        let resultadoTotal = parseFloat(item
                                            .resultado_total
                                            .replace(',', '.')) || 0;
                                        let totalComercial = parseFloat(item
                                            .total_comercial
                                            .replace(',', '.')) || 0;
                                        let totalKilos = parseFloat(item.total_kilos
                                            .replace(',', '.')) || 0;
                                        let costo_comercial = parseFloat(item
                                            .costo_comercial.replace(',', '.')) || 0;
                                        let precio_comercial = parseFloat(item
                                                .precio_comercial.replace(',', '.')) ||
                                            0;

                                        // Sumar solo si la categoría está en el objeto
                                        if (sumasPorCategoria.hasOwnProperty(
                                                categoria)) {
                                            sumasPorCategoria[categoria]
                                                .resultado_kilo +=
                                                resultadoKilo;
                                            sumasPorCategoria[categoria]
                                                .resultado_total +=
                                                resultadoTotal;
                                            sumasPorCategoria[categoria]
                                                .total_comercial +=
                                                totalComercial;
                                            sumasPorCategoria[categoria].total_kilos +=
                                                totalKilos;
                                            sumasPorCategoria[categoria]
                                                .costo_comercial +=
                                                costo_comercial;
                                            sumasPorCategoria[categoria]
                                                .precio_comercial += precio_comercial;
                                        }
                                    });

                                    //Se agrega COMERCIALHUERTO
                                    $.each(sumasPorCategoria, function(categoria, sumas) {
                                        console.log(
                                            `Categoría: ${categoria}, Resultado Kilo: ${sumas.resultado_kilo}, Resultado Total: ${sumas.resultado_total}, Total Comercial: ${sumas.total_comercial}, Total Kilos: ${sumas.total_kilos}, Costo Comercial: ${sumas.costo_comercial}`
                                        );
                                    });
                                    FacturaValorNeto = sumasPorCategoria['COMERCIAL']
                                        .precio_comercial +
                                        sumasPorCategoria['PRECALIBRE'].precio_comercial +
                                        sumasPorCategoria['SOBRECALIBRE'].precio_comercial;

                                    FacturaValorNeto = FacturaValorNeto;// * $("#TC").val();
                                    valorTotal = parseFloat(sumasPorCategoria['CAT1']
                                            .resultado_total) +
                                        parseFloat(sumasPorCategoria['CATII'].resultado_total) +
                                        parseFloat(sumasPorCategoria["SUPERMERCADO"]
                                            .resultado_total);

                                    valorNoExportable = parseFloat(sumasPorCategoria['MERMA']
                                            .costo_comercial) +
                                        parseFloat(sumasPorCategoria['DESECHO']
                                            .costo_comercial) +
                                        parseFloat(
                                            sumasPorCategoria['PRECALIBRE'].costo_comercial) +
                                        parseFloat(
                                            sumasPorCategoria['COMERCIAL'].costo_comercial) +
                                        parseFloat(sumasPorCategoria['SOBRECALIBRE']
                                            .costo_comercial)+
                                        parseFloat(sumasPorCategoria['COMERCIALHUERTO'].costo_comercial);
                                    $("#valorNoExportable").text(valorNoExportable
                                        .toLocaleString(
                                            'es-CL', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }));

                                    kilosNoExportable = parseFloat(sumasPorCategoria['MERMA']
                                            .total_kilos) +
                                        parseFloat(sumasPorCategoria['DESECHO'].total_kilos) +
                                        parseFloat(
                                            sumasPorCategoria['PRECALIBRE'].total_kilos) +
                                        parseFloat(
                                            sumasPorCategoria['COMERCIAL'].total_kilos) +
                                        parseFloat(sumasPorCategoria['SOBRECALIBRE']
                                            .total_kilos)+
                                        parseFloat(sumasPorCategoria['COMERCIALHUERTO'].total_kilos);

                                    $("#kilosNoExportable").text(kilosNoExportable
                                        .toLocaleString(
                                            'es-CL', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }));
                                    $("#suma-CAT-1").text(valorTotal
                                        .toLocaleString('es-CL', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        }));
                                    $("#suma-CATII").text(sumasPorCategoria['CATII']
                                        .resultado_total
                                        .toLocaleString('es-CL', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        }));

                                    //Bonif Gasto FNE
                                    let bonificacionFNE = 0;
                                    $.each(response.bonificacion, function(index, item) {
                                        bonificacionFNE += parseFloat(item.valor);
                                    })
                                    if (bonificacionFNE > 0) {
                                        $("#trbonificacion").show();
                                        $("#bonificacion").text(bonificacionFNE
                                            .toLocaleString(
                                                'es-CL', {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                }));
                                    } else {
                                        $("#trbonificacion").hide();
                                    }

                                    //Fin Bonif Gasto FNE




                                    //facturación anticipos
                                    let valorTotalAnticipos = 0;
                                    $("#anticipos").html('');
                                    $.each(response.anticipos, function(index, item) {
                                        let fecha = item.fecha_documento;
                                        let valor = parseFloat(item.valor) ||
                                            0; // Convertir a número, manejando coma decimal
                                        valorTotalAnticipos += valor;

                                        $("#anticipos").append(
                                            `<tr>
                                                    <td colspan="6" style="text-align: right;">${fecha}</td>
                                                    <td>US$</td>
                                                    <td class="currency">${valor.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                                                </tr>`
                                        );
                                    });
                                    let interesanticipo = 0;
                                    if (response.interesanticipo.length > 0) {
                                        let interesanticipo = 0;

                                        response.interesanticipo.forEach(element => {
                                            interesanticipo += parseFloat(element
                                            .valor);
                                        });

                                        // Aplicamos formato con coma decimal y punto como separador de miles
                                        const formatter = new Intl.NumberFormat('es-CL', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                         valorTotalAnticipos = valorTotalAnticipos + interesanticipo;
                                        $("#interesanticipo").text(
                                            interesanticipo.toLocaleString('es-CL', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }));
                                        $("#trinteresanticipo").show();
                                    } else {
                                        $("#trinteresanticipo").hide();
                                        interesanticipo = 0;
                                    }
                                    //sumamos los intereses
                                    // if(isNaN(parseFloat($("#interesanticipo").text()))){
                                    //     interesanticipo = 0;
                                    // }
                                    valorTotalAnticipos = valorTotalAnticipos + interesanticipo;

                                    if (response.anticipos.length > 0) {
                                        $("#fechaFacturacion").text(response.anticipos[0]
                                            .fecha_documento);
                                        $("#valorFacturacion").text(response.anticipos[0].valor
                                            .toLocaleString('es-CL', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }));
                                        $("#valorTotalFacturacion").text(valorTotalAnticipos
                                            .toLocaleString('es-CL', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }));
                                    }




                                    let valorbonificacion = 0;
                                    if (response.OtroCobro.length > 0) {
                                        response.OtroCobro.forEach(element => {
                                            valorbonificacion += parseFloat(element
                                                .valor);
                                        });
                                        valorbonificacion = valorbonificacion-parseFloat(sumasPorCategoria['COMERCIALHUERTO'].costo_comercial);
                                        $("#bonificacionGastoNoExportable").text(
                                                valorbonificacion.toLocaleString(
                                                'es-CL', {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                }));
                                        if (valorbonificacion != 0) {
                                            $("#trBonifGastoNoExportable").show();
                                        } else {
                                            $("#trBonifGastoNoExportable").hide();
                                        }
                                    } else {
                                        $("#trBonifGastoNoExportable").hide();
                                        $("#bonificacionGastoNoExportable").text("0");
                                    }
                                    $("#valorTotalUsd").text((valorTotal + bonificacionFNE)
                                        .toLocaleString(
                                            'es-CL', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }));
                                    // $("#fletehuerto").text((response.valorflete.valor ? response
                                    //     .valorflete.valor : 0).toLocaleString(
                                    //     'es-CL', {
                                    //         minimumFractionDigits: 2,
                                    //         maximumFractionDigits: 2
                                    //     }));
                                    valorflete = 0;
                                    let bonificacion = 0;
                                    if (response.valorflete.length > 0) {
                                        response.valorflete.forEach(element => {
                                            valorflete += parseFloat(element.valor);
                                            bonificacion = element.condicion;
                                        });
                                        $("#fletehuerto").text((valorflete)
                                            .toLocaleString(
                                                'es-CL', {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                }));



                                        switch (bonificacion) {
                                            case 0:
                                                $("#trBonificacionfletehuerto").hide();
                                                bonificacion = 0;

                                                break;
                                            case 0.5:
                                                $("#trBonificacionfletehuerto").show();
                                                bonificacion = (-1) * valorflete / 2;
                                                break;
                                            case 1:
                                                $("#trBonificacionfletehuerto").show();
                                                bonificacion = (-1) * valorflete;
                                                break;
                                            default:
                                                $("#trBonificacionfletehuerto").hide();
                                                break;
                                        }
                                        $("#bonificacionfletehuerto").text((bonificacion ?
                                            bonificacion : 0).toLocaleString(
                                            'es-CL', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }));




                                    }
                                    else {
                                        $("#trBonificacionfletehuerto").hide();
                                        $("#fletehuerto").text("0");
                                        $("#bonificacionfletehuerto").text("0");
                                    }
                                    let multiresiduos = 0;
                                    //if (response.multiresiduo.length > 0) {
                                    response.multiresiduo.forEach(element => {
                                        multiresiduos += parseFloat(element.valor);
                                    });
                                    $("#multiresiduos").text(multiresiduos ? multiresiduos : 0)
                                        .toLocaleString(
                                            'es-CL', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
                                    // if(multiresiduos==0){
                                    //     $("#trAnalisisMultiresiduosVirus").hide();
                                    // }
                                    // else{
                                    //     $("#trAnalisisMultiresiduosVirus").show();
                                    // }
                                    // }
                                    // else{
                                    //     $("#trAnalisisMultiresiduosVirus").hide();
                                    // }


                                    let OtrosCargos = 0;
                                    if (response.otroscargos.length > 0) {
                                        response.otroscargos.forEach(element => {
                                            OtrosCargos += parseFloat(element.valor);
                                        });
                                        $("#OtrosCargos").text(OtrosCargos.toLocaleString(
                                            'es-CL', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }));
                                        if (OtrosCargos == 0) {
                                            $("#trOtrosCargos").hide();
                                        } else {
                                            $("#trOtrosCargos").show();
                                        }
                                    }
                                    else {
                                        $("#trOtrosCargos").hide();
                                    }


                                    let envases = 0;
                                    if (response.envases.length > 0) {
                                        response.envases.forEach(element => {
                                            envases += parseFloat(element.valor);
                                        });

                                        $("#ctacteenvases").text(envases).toLocaleString(
                                            'es-CL', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
                                    }
                                    else {
                                        $("#ctacteenvases").text(0).toLocaleString(
                                            'es-CL', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
                                    }
                                    totalOtrosCargos = parseFloat(valorflete) +
                                        parseFloat(envases) + parseFloat(valorNoExportable) +
                                        parseFloat(multiresiduos) + parseFloat(bonificacion) +
                                        parseFloat(valorbonificacion) + parseFloat(OtrosCargos);
                                    $("#totalOtrosCargos").text(totalOtrosCargos.toLocaleString(
                                        'es-CL', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        }));
                                    SaldoTotal = parseFloat(valorTotal) - parseFloat(
                                            valorTotalAnticipos) - parseFloat(
                                        totalOtrosCargos) + parseFloat(bonificacionFNE);
                                    $("#SaldoTotal").text(SaldoTotal.toLocaleString('es-CL', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }));
                                    NDValorNeto = parseFloat(SaldoTotal) * $("#TC").val();
                                    $("#NDVAlorNeto").text(NDValorNeto.toLocaleString('es-CL', {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    }));
                                    NDValorIva = NDValorNeto * 0.19;
                                    $("#NDVAlorIva").text(NDValorIva.toLocaleString('es-CL', {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    }));
                                    NDValorTotal = NDValorNeto + NDValorIva;
                                    $("#NDVAlorTotal").text(NDValorTotal.toLocaleString(
                                        'es-CL', {
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        }));
                                    // FacturaValorNeto = sumasPorCategoria['COMERCIAL']
                                    //     .totalComercial;
                                    $("#FVAlorNeto").text(FacturaValorNeto.toLocaleString(
                                        'es-CL', {
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        }));
                                    FVAlorIva = FacturaValorNeto * 0.19;
                                    $("#FVAlorIva").text(FVAlorIva.toLocaleString('es-CL', {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    }));
                                    FValorTotal = FacturaValorNeto + FVAlorIva;
                                    $("#FVAlorTotal").text(FValorTotal.toLocaleString('es-CL', {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    }));
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message ||
                                            'Ocurrió un error en la solicitud.',
                                    });
                                }

                                //Balance de Masas
                                // Objeto para agrupar por variedad
                                let datosAgrupados = {};
                                let totalGeneral = {
                                    cajas_equivalentes: 0,
                                    total_kilos: 0
                                };

                                // Agrupar datos por variedad y categoría
                                $.each(response.result, function(index, item) {
                                    let variedad = item.variedad;
                                    let categoria = item.categoria;
                                    let especie = item.especie.nombre;
                                    switch (especie) {
                                        case "Plums":
                                            especie = "Ciruela";
                                            break;
                                        case "Nectarines":
                                            especie = "Nectarin";
                                            break;
                                        case "Peaches":
                                            especie = "Durazno";
                                    }
                                    let norma = item.norma ||
                                        ''; // Manejar norma null o vacía
                                    let totalKilos = parseFloat(item.total_kilos
                                        .replace(
                                            ',', '.')) || 0;
                                    let cajas = parseFloat(item.cajas.replace(
                                            ',', '.')) || 0;
                                    if (!datosAgrupados[especie]) {
                                        datosAgrupados[especie] = {};
                                    }
                                    if (!datosAgrupados[especie][variedad]) {
                                        datosAgrupados[especie][variedad] = {};
                                    }
                                    if (!datosAgrupados[especie][variedad][categoria]) {
                                        datosAgrupados[especie][variedad][categoria] = {
                                            normas: [],
                                            total_kilos: 0,
                                            cajas: 0
                                        };
                                    }

                                    // Buscar si la norma ya existe
                                    let normaExistente = datosAgrupados[especie][
                                            variedad
                                        ][
                                            categoria
                                        ]
                                        .normas.find(n => n.norma === norma);
                                    if (normaExistente) {
                                        normaExistente.total_kilos += totalKilos;
                                        normaExistente.cajas += cajas;
                                    } else {
                                        datosAgrupados[especie][variedad][categoria]
                                            .normas
                                            .push({
                                                norma: norma,
                                                total_kilos: totalKilos,
                                                cajas:cajas
                                            });
                                    }

                                    // Acumular totales por categoría
                                    datosAgrupados[especie][variedad][categoria]
                                        .total_kilos +=
                                        totalKilos;
                                    datosAgrupados[especie][variedad][categoria]
                                        .cajas += cajas;

                                });

                                // Generar HTML de la tabla
                                let htmlOutput = `
            <table>
                <thead>
                    <tr class="section-header">
                        <th>Especie</th>
                        <th>Variedad</th>
                        <th>Categoría</th>
                        <th>Norma</th>
                        <th>Cajas</th>
                        <th>Kilos Totales</th>
                    </tr>
                </thead>
                <tbody>
        `;

                                // Ordenar variedades alfabéticamente
                                let especiesNorma = Object.keys(datosAgrupados).sort();
                                let variedadesNorma = Object.keys(datosAgrupados).sort();

                                // Iterar sobre cada variedad
                                $.each(especiesNorma, function(index, especie) {
                                    // Ordenar variedades por nombre
                                    variedadesNorma = Object.keys(datosAgrupados[
                                            especie])
                                        .sort();
                                    if (variedadesNorma.length === 0) return;


                                    $.each(variedadesNorma, function(index, variedad) {
                                        let totalVariedad = {
                                            cajas_equivalentes: 0,
                                            total_kilos: 0
                                        };
                                        let categorias = Object.keys(
                                                datosAgrupados[
                                                    especie][variedad])
                                            .sort();

                                        // Iterar sobre cada categoría
                                        $.each(categorias, function(i,
                                            categoria) {
                                            let datosCategoria =
                                                datosAgrupados[especie][
                                                    variedad
                                                ][categoria];
                                            let isFirstRow = true;

                                            // Ordenar normas
                                            datosCategoria.normas.sort((
                                                    a,
                                                    b) => a
                                                .norma < b.norma ? -
                                                1 :
                                                1);

                                            // Generar filas para cada norma
                                            $.each(datosCategoria
                                                .normas,
                                                function(j,
                                                    fila) {
                                                    let cajasEquivalentes =
                                                        parseFloat(fila
                                                            .cajas);
                                                    let variedadCell =
                                                        (
                                                            i ===
                                                            0 &&
                                                            j === 0
                                                        ) ?
                                                        variedad :
                                                        ' ';
                                                    let especieCell =
                                                        (
                                                            i ===
                                                            0 &&
                                                            j === 0
                                                        ) ?
                                                        especie :
                                                        ' ';
                                                    let categoriaCell =
                                                        isFirstRow ?
                                                        categoria :
                                                        ' ';

                                                    htmlOutput += `
                        <tr>
                            <td>${especie}</td>
                            <td>${variedadCell}</td>
                            <td>${categoriaCell}</td>
                            <td>${fila.norma || ' '}</td>
                            <td class="number">${formatInteger(cajasEquivalentes)}</td>
                            <td class="number">${formatInteger(fila.total_kilos.toFixed(0))}</td>
                        </tr>
                    `;
                                                    isFirstRow =
                                                        false;

                                                    // Acumular totales por variedad
                                                    totalVariedad
                                                        .cajas_equivalentes +=
                                                        parseFloat(
                                                            cajasEquivalentes
                                                        );
                                                    totalVariedad
                                                        .total_kilos +=
                                                        fila
                                                        .total_kilos;
                                                });
                                        });


                                        // Fila de total por variedad
                                        htmlOutput += `
                <tr class="total-row">
                    <td></td>
                    <td>Total ${variedad}</td>
                    <td> </td>
                    <td> </td>
                    <td class="number">${formatInteger(totalVariedad.cajas_equivalentes.toFixed(1))}</td>
                    <td class="number">${formatInteger(totalVariedad.total_kilos.toFixed(2))}</td>
                </tr>
            `;

                                        // Acumular al total general
                                        totalGeneral.cajas_equivalentes +=
                                            totalVariedad
                                            .cajas_equivalentes;
                                        totalGeneral.total_kilos +=
                                            totalVariedad
                                            .total_kilos;
                                    });
                                });

                                // Fila de total general
                                htmlOutput += `
            <tr class="total-row">
                <td>Total general</td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td class="number">${formatInteger(totalGeneral.cajas_equivalentes.toFixed(1))}</td>
                <td class="number">${formatInteger(totalGeneral.total_kilos.toFixed(0))}</td>
            </tr>
        `;



                                // Insertar el HTML en el contenedor
                                $('#bce-masas').html(htmlOutput);





                                //norma con semana
                                const ordenCalibres_v2 = ['7J', '6J', '5J', '4J', '3J', '2J',
                                    'J',
                                    'XL', 'L'
                                ];;
                                let datosAgrupados_v2 = {};
                                let totalGeneral_v2 = {
                                    cajas_equivalentes: 0,
                                    total_kilos: 0,
                                    rnp_total: 0,
                                    rnp_kilo_sum: 0,
                                    rnp_kilo_kilos: 0
                                };
                                data_v2 = response.result;
                                // Agrupar datos
                                $.each(data_v2, function(index_v2, item_v2) {
                                    if (item_v2.norma.toUpperCase() === 'CAT 1' ||
                                        item_v2
                                        .norma.toUpperCase() === 'CAT 1') {
                                        let variedad_v2 = item_v2.variedad;
                                        let etiqueta_v2 = item_v2.etiqueta;
                                        let semana_v2 = item_v2.eta_week.toString();
                                        let calibre_v2 = item_v2.calibre;
                                        let color_v2 = item_v2.color || '';
                                        let totalKilos_v2 = parseFloat(item_v2
                                            .total_kilos
                                            .replace(',', '.')) || 0;
                                        let rnpTotal_v2 = parseFloat(item_v2
                                            .resultado_total
                                            .replace(',', '.')) || 0;
                                        let rnpKilo_v2 = parseFloat(item_v2
                                            .resultado_kilo
                                            .replace(',', '.')) || 0;

                                        if (!datosAgrupados_v2[variedad_v2])
                                            datosAgrupados_v2[
                                                variedad_v2] = {};
                                        if (!datosAgrupados_v2[variedad_v2][
                                                etiqueta_v2
                                            ])
                                            datosAgrupados_v2[variedad_v2][
                                                etiqueta_v2
                                            ] = {};
                                        if (!datosAgrupados_v2[variedad_v2][etiqueta_v2]
                                            [
                                                semana_v2
                                            ]) {
                                            datosAgrupados_v2[variedad_v2][etiqueta_v2][
                                                semana_v2
                                            ] = {
                                                calibres: {},
                                                total_kilos: 0,
                                                rnp_total: 0,
                                                rnp_kilo_sum: 0,
                                                rnp_kilo_kilos: 0
                                            };
                                        }
                                        if (!datosAgrupados_v2[variedad_v2][etiqueta_v2]
                                            [
                                                semana_v2
                                            ].calibres[calibre_v2]) {
                                            datosAgrupados_v2[variedad_v2][etiqueta_v2][
                                                semana_v2
                                            ].calibres[calibre_v2] = {
                                                color: color_v2,
                                                total_kilos: 0,
                                                rnp_total: 0,
                                                rnp_kilo: 0
                                            };
                                        }

                                        datosAgrupados_v2[variedad_v2][etiqueta_v2][
                                                semana_v2
                                            ]
                                            .calibres[calibre_v2].total_kilos +=
                                            totalKilos_v2;
                                        datosAgrupados_v2[variedad_v2][etiqueta_v2][
                                                semana_v2
                                            ]
                                            .calibres[calibre_v2].rnp_total +=
                                            rnpTotal_v2;
                                        datosAgrupados_v2[variedad_v2][etiqueta_v2][
                                                semana_v2
                                            ]
                                            .calibres[calibre_v2].rnp_kilo +=
                                            rnpKilo_v2;
                                        datosAgrupados_v2[variedad_v2][etiqueta_v2][
                                                semana_v2
                                            ]
                                            .total_kilos += totalKilos_v2;
                                        datosAgrupados_v2[variedad_v2][etiqueta_v2][
                                                semana_v2
                                            ]
                                            .rnp_total += rnpTotal_v2;
                                        datosAgrupados_v2[variedad_v2][etiqueta_v2][
                                                semana_v2
                                            ]
                                            .rnp_kilo_sum += rnpKilo_v2 * totalKilos_v2;
                                        datosAgrupados_v2[variedad_v2][etiqueta_v2][
                                                semana_v2
                                            ]
                                            .rnp_kilo_kilos += totalKilos_v2;
                                    }
                                });

                                // Generar HTML de la tabla
                                let htmlOutput_v2 = `

                    <tr class="section-header">
                        <th>Variedad</th>
                        <th>Etiqueta</th>
                        <th>Semana</th>
                        <th>Serie</th>
                        <th>Color</th>
                        <th>Curva Calibre</th>
                        <th style="text-align:center;">Cajas</th>
                        <th>Kilos Totales</th>
                        <th>RNP Total</th>
                        <th>RNP Kilo</th>
                    </tr>

        `;

                                // Ordenar variedades
                                let variedades_v2 = Object.keys(datosAgrupados_v2).sort();
                                let totalVariedad_v2 = {};

                                $.each(variedades_v2, function(index_v2, variedad_v2) {
                                    totalVariedad_v2[variedad_v2] = {
                                        cajas_equivalentes: 0,
                                        total_kilos: 0,
                                        rnp_total: 0,
                                        rnp_kilo_sum: 0,
                                        rnp_kilo_kilos: 0
                                    };
                                    let etiquetas_v2 = Object.keys(datosAgrupados_v2[
                                        variedad_v2]).sort();
                                    let rowspanVariedad_v2 = 0;

                                    // Calcular rowspan para la variedad
                                    $.each(etiquetas_v2, function(i_v2, etiqueta_v2) {
                                        let semanas_v2 = Object.keys(
                                            datosAgrupados_v2[variedad_v2][
                                                etiqueta_v2
                                            ]).sort((a, b) => a - b);
                                        $.each(semanas_v2, function(j_v2,
                                            semana_v2) {
                                            let calibres_v2 = Object
                                                .keys(
                                                    datosAgrupados_v2[
                                                        variedad_v2][
                                                        etiqueta_v2
                                                    ][semana_v2]
                                                    .calibres);
                                            rowspanVariedad_v2 +=
                                                calibres_v2.length +
                                                1; // +1 por total semana
                                        });
                                        rowspanVariedad_v2 +=
                                            1; // +1 por total etiqueta
                                    });

                                    let isFirstVariedadRow_v2 = true;

                                    // Iterar sobre etiquetas
                                    $.each(etiquetas_v2, function(i_v2, etiqueta_v2) {
                                        let totalEtiqueta_v2 = {
                                            cajas_equivalentes: 0,
                                            total_kilos: 0,
                                            rnp_total: 0,
                                            rnp_kilo_sum: 0,
                                            rnp_kilo_kilos: 0
                                        };
                                        let semanas_v2 = Object.keys(
                                            datosAgrupados_v2[variedad_v2][
                                                etiqueta_v2
                                            ]).sort((a, b) => a - b);
                                        let rowspanEtiqueta_v2 = 0;

                                        // Calcular rowspan para la etiqueta
                                        $.each(semanas_v2, function(j_v2,
                                            semana_v2) {
                                            let calibres_v2 = Object
                                                .keys(
                                                    datosAgrupados_v2[
                                                        variedad_v2][
                                                        etiqueta_v2
                                                    ][semana_v2]
                                                    .calibres);
                                            rowspanEtiqueta_v2 +=
                                                calibres_v2.length +
                                                1; // +1 por total semana
                                        });

                                        let isFirstEtiquetaRow_v2 = true;

                                        // Iterar sobre semanas
                                        $.each(semanas_v2, function(j_v2,
                                            semana_v2) {
                                            let datosSemana_v2 =
                                                datosAgrupados_v2[
                                                    variedad_v2][
                                                    etiqueta_v2
                                                ][semana_v2];
                                            let calibres_v2 = Object
                                                .keys(
                                                    datosSemana_v2
                                                    .calibres)
                                                .sort((a, b) =>
                                                    ordenCalibres_v2
                                                    .indexOf(a) -
                                                    ordenCalibres_v2
                                                    .indexOf(b));
                                            let rowspanSemana_v2 =
                                                calibres_v2.length;

                                            let isFirstSemanaRow_v2 =
                                                true;

                                            // Generar filas para cada calibre
                                            $.each(calibres_v2,
                                                function(
                                                    k_v2, calibre_v2
                                                ) {
                                                    let datosCalibre_v2 =
                                                        datosSemana_v2
                                                        .calibres[
                                                            calibre_v2
                                                        ];
                                                    let curvaCalibre_v2 =
                                                        datosSemana_v2
                                                        .total_kilos ?
                                                        (
                                                            datosCalibre_v2
                                                            .total_kilos /
                                                            datosSemana_v2
                                                            .total_kilos
                                                        ).toFixed(
                                                            4) :
                                                        '0.0000';
                                                    let cajasEquivalentes_v2 =
                                                        (datosCalibre_v2
                                                            .total_kilos /
                                                            5)
                                                        .toFixed(
                                                            0);
                                                    let rnpClass_v2 =
                                                        datosCalibre_v2
                                                        .rnp_total <
                                                        0 ||
                                                        datosCalibre_v2
                                                        .rnp_kilo <
                                                        0 ?
                                                        'negative' :
                                                        '';
                                                    let variedadCell_v2 =
                                                        isFirstVariedadRow_v2 ?
                                                        `<td rowspan="${rowspanVariedad_v2}">${variedad_v2}</td>` :
                                                        '';
                                                    let etiquetaCell_v2 =
                                                        isFirstEtiquetaRow_v2 ?
                                                        `<td rowspan="${rowspanEtiqueta_v2}">${etiqueta_v2}</td>` :
                                                        '';
                                                    let semanaCell_v2 =
                                                        isFirstSemanaRow_v2 ?
                                                        `<td rowspan="${rowspanSemana_v2}">${semana_v2}</td>` :
                                                        '';

                                                    htmlOutput_v2 += `
                            <tr>
                                ${variedadCell_v2}
                                ${etiquetaCell_v2}
                                ${semanaCell_v2}
                                <td>${calibre_v2}</td>
                                <td>${datosCalibre_v2.color}</td>
                                <td class="number">${curvaCalibre_v2} %</td>
                                <td class="number">${cajasEquivalentes_v2}</td>
                                <td class="number">${datosCalibre_v2.total_kilos.toFixed(2)}</td>
                                <td class="number ${rnpClass_v2}">${datosCalibre_v2.rnp_total.toFixed(2)}</td>
                                <td class="number ${rnpClass_v2}">${datosCalibre_v2.rnp_kilo.toFixed(4)}</td>
                            </tr>
                        `;

                                                    isFirstSemanaRow_v2
                                                        = false;
                                                    isFirstEtiquetaRow_v2
                                                        = false;
                                                    isFirstVariedadRow_v2
                                                        = false;

                                                    // Acumular totales
                                                    totalEtiqueta_v2
                                                        .cajas_equivalentes +=
                                                        parseFloat(
                                                            cajasEquivalentes_v2
                                                        );
                                                    totalEtiqueta_v2
                                                        .total_kilos +=
                                                        datosCalibre_v2
                                                        .total_kilos;
                                                    totalEtiqueta_v2
                                                        .rnp_total +=
                                                        datosCalibre_v2
                                                        .rnp_total;
                                                    totalEtiqueta_v2
                                                        .rnp_kilo_sum +=
                                                        datosCalibre_v2
                                                        .rnp_kilo *
                                                        datosCalibre_v2
                                                        .total_kilos;
                                                    totalEtiqueta_v2
                                                        .rnp_kilo_kilos +=
                                                        datosCalibre_v2
                                                        .total_kilos;
                                                });

                                            // Total por semana
                                            let rnpKiloSemana_v2 =
                                                datosSemana_v2
                                                .rnp_kilo_kilos ? (
                                                    datosSemana_v2
                                                    .rnp_kilo_sum /
                                                    datosSemana_v2
                                                    .rnp_kilo_kilos)
                                                .toFixed(4) : '0.0000';
                                            let cajasEquivalentesSemana_v2 =
                                                (datosSemana_v2
                                                    .total_kilos / 9)
                                                .toFixed(0);
                                            let rnpClassSemana_v2 =
                                                datosSemana_v2
                                                .rnp_total <
                                                0 || parseFloat(
                                                    rnpKiloSemana_v2) <
                                                0 ?
                                                'negative' : '';
                                            htmlOutput_v2 += `
                        <tr class="total-row">

                            <td colspan="2">Total Semana ${semana_v2}</td>
                             <td></td>
                            <td class="number">1.0000</td>

                            <td class="number">${cajasEquivalentesSemana_v2}</td>
                            <td class="number">${datosSemana_v2.total_kilos.toFixed(2)}</td>
                            <td class="number ${rnpClassSemana_v2}">${datosSemana_v2.rnp_total.toFixed(2)}</td>
                            <td class="number ${rnpClassSemana_v2}">${rnpKiloSemana_v2}</td>
                        </tr>
                    `;
                                        });

                                        // Total por etiqueta
                                        let rnpKiloEtiqueta_v2 =
                                            totalEtiqueta_v2
                                            .rnp_kilo_kilos ? (totalEtiqueta_v2
                                                .rnp_kilo_sum / totalEtiqueta_v2
                                                .rnp_kilo_kilos).toFixed(4) :
                                            '0.0000';
                                        totalEtiqueta_v2.cajas_equivalentes = (
                                                totalEtiqueta_v2.total_kilos / 9
                                            )
                                            .toFixed(0);
                                        let rnpClassEtiqueta_v2 =
                                            totalEtiqueta_v2
                                            .rnp_total < 0 || parseFloat(
                                                rnpKiloEtiqueta_v2) < 0 ?
                                            'negative' : '';
                                        htmlOutput_v2 += `
                    <tr class="total-row">

                        <td colspan="2">Total ${etiqueta_v2}</td>
                        <td> </td>
                        <td> </td>
                        <td class="number">1.0000</td>
                        <td class="number">${totalEtiqueta_v2.cajas_equivalentes}</td>
                        <td class="number">${totalEtiqueta_v2.total_kilos.toFixed(2)}</td>
                        <td class="number ${rnpClassEtiqueta_v2}">${totalEtiqueta_v2.rnp_total.toFixed(2)}</td>
                        <td class="number ${rnpClassEtiqueta_v2}">${rnpKiloEtiqueta_v2}</td>
                    </tr>
                `;

                                        // Acumular totales por variedad
                                        totalVariedad_v2[variedad_v2]
                                            .cajas_equivalentes += parseFloat(
                                                totalEtiqueta_v2
                                                .cajas_equivalentes
                                            );
                                        totalVariedad_v2[variedad_v2]
                                            .total_kilos +=
                                            totalEtiqueta_v2.total_kilos;
                                        totalVariedad_v2[variedad_v2]
                                            .rnp_total +=
                                            totalEtiqueta_v2.rnp_total;
                                        totalVariedad_v2[variedad_v2]
                                            .rnp_kilo_sum += totalEtiqueta_v2
                                            .rnp_kilo_sum;
                                        totalVariedad_v2[variedad_v2]
                                            .rnp_kilo_kilos += totalEtiqueta_v2
                                            .rnp_kilo_kilos;
                                    });
                                });

                                // Totales por variedad
                                $.each(variedades_v2, function(index_v2, variedad_v2) {
                                    let rnpKiloVariedad_v2 = totalVariedad_v2[
                                            variedad_v2]
                                        .rnp_kilo_kilos ? (totalVariedad_v2[variedad_v2]
                                            .rnp_kilo_sum / totalVariedad_v2[
                                                variedad_v2]
                                            .rnp_kilo_kilos).toFixed(4) : '0.0000';
                                    let rnpClassVariedad_v2 = totalVariedad_v2[
                                            variedad_v2]
                                        .rnp_total < 0 || parseFloat(
                                            rnpKiloVariedad_v2) <
                                        0 ? 'negative' : '';
                                    htmlOutput_v2 += `
                <tr class="total-row">
                    <td colspan="4">Total ${variedad_v2}</td>
                    <td> </td>
                    <td> </td>
                    <td class="number">${totalVariedad_v2[variedad_v2].cajas_equivalentes.toFixed(0)}</td>
                    <td class="number">${totalVariedad_v2[variedad_v2].total_kilos.toFixed(2)}</td>
                    <td class="number ${rnpClassVariedad_v2}">${totalVariedad_v2[variedad_v2].rnp_total.toFixed(2)}</td>
                    <td class="number ${rnpClassVariedad_v2}">${rnpKiloVariedad_v2}</td>
                </tr>
            `;

                                    // Acumular totales generales
                                    totalGeneral_v2.cajas_equivalentes +=
                                        totalVariedad_v2[
                                            variedad_v2].cajas_equivalentes;
                                    totalGeneral_v2.total_kilos += totalVariedad_v2[
                                        variedad_v2].total_kilos;
                                    totalGeneral_v2.rnp_total += totalVariedad_v2[
                                        variedad_v2].rnp_total;
                                    totalGeneral_v2.rnp_kilo_sum += totalVariedad_v2[
                                        variedad_v2].rnp_kilo_sum;
                                    totalGeneral_v2.rnp_kilo_kilos += totalVariedad_v2[
                                        variedad_v2].rnp_kilo_kilos;
                                });

                                // Total general
                                let rnpKiloGeneral_v2 = totalGeneral_v2.rnp_kilo_kilos ? (
                                    totalGeneral_v2.rnp_kilo_sum / totalGeneral_v2
                                    .rnp_kilo_kilos).toFixed(4) : '0.0000';
                                let rnpClassGeneral_v2 = totalGeneral_v2.rnp_total < 0 ||
                                    parseFloat(rnpKiloGeneral_v2) < 0 ? 'negative' : '';
                                htmlOutput_v2 += `
            <tr class="total-row">
                <td colspan="4">Total general</td>
                <td> </td>
                <td> </td>
                <td class="number">${totalGeneral_v2.cajas_equivalentes.toFixed(0)}</td>
                <td class="number">${totalGeneral_v2.total_kilos.toFixed(2)}</td>
                <td class="number ${rnpClassGeneral_v2}">${totalGeneral_v2.rnp_total.toFixed(2)}</td>
                <td class="number ${rnpClassGeneral_v2}">${rnpKiloGeneral_v2}</td>
            </tr>
        `;



                                // Insertar el HTML en el contenedor
                                $('#norma-semana').html(htmlOutput_v2);

                                // Fuera de Norma

                                const ordenCalibres_fn = ['XL', 'L', 'J', '2J', '3J', '4J'];

                                // Objeto para agrupar por variedad, etiqueta, semana y calibre
                                let datosAgrupados_fn = {};
                                let totalGeneral_fn = {
                                    cajas_equivalentes: 0,
                                    total_kilos: 0,
                                    rnp_total: 0,
                                    rnp_kilo_sum: 0,
                                    rnp_kilo_kilos: 0
                                };
                                let data_fn = response.result;
                                // Agrupar datos
                                $.each(data_fn, function(index_fn, item_fn) {
                                    if (item_fn.norma.toUpperCase() === 'FN') {
                                        let variedad_fn = item_fn.variedad;
                                        let etiqueta_fn = item_fn.etiqueta;
                                        let semana_fn = item_fn.eta_week.toString();
                                        let calibre_fn = item_fn.calibre;
                                        let color_fn = item_fn.color || '';
                                        let totalKilos_fn = parseFloat(item_fn
                                            .total_kilos
                                            .replace(',', '.')) || 0;
                                        let rnpTotal_fn = parseFloat(item_fn
                                            .resultado_total
                                            .replace(',', '.')) || 0;
                                        let rnpKilo_fn = parseFloat(item_fn
                                            .resultado_kilo
                                            .replace(',', '.')) || 0;

                                        if (!datosAgrupados_fn[variedad_fn])
                                            datosAgrupados_fn[variedad_fn] = {};
                                        if (!datosAgrupados_fn[variedad_fn][
                                                etiqueta_fn
                                            ])
                                            datosAgrupados_fn[variedad_fn][
                                                etiqueta_fn
                                            ] = {};
                                        if (!datosAgrupados_fn[variedad_fn][etiqueta_fn]
                                            [
                                                semana_fn
                                            ]) {
                                            datosAgrupados_fn[variedad_fn][etiqueta_fn][
                                                semana_fn
                                            ] = {
                                                calibres: {},
                                                total_kilos: 0,
                                                rnp_total: 0,
                                                rnp_kilo_sum: 0,
                                                rnp_kilo_kilos: 0
                                            };
                                        }
                                        if (!datosAgrupados_fn[variedad_fn][etiqueta_fn]
                                            [
                                                semana_fn
                                            ].calibres[calibre_fn]) {
                                            datosAgrupados_fn[variedad_fn][etiqueta_fn][
                                                semana_fn
                                            ].calibres[calibre_fn] = {
                                                color: color_fn,
                                                total_kilos: 0,
                                                rnp_total: 0,
                                                rnp_kilo: 0
                                            };
                                        }

                                        datosAgrupados_fn[variedad_fn][etiqueta_fn][
                                                semana_fn
                                            ].calibres[calibre_fn].total_kilos +=
                                            totalKilos_fn;
                                        datosAgrupados_fn[variedad_fn][etiqueta_fn][
                                                semana_fn
                                            ].calibres[calibre_fn].rnp_total +=
                                            rnpTotal_fn;
                                        datosAgrupados_fn[variedad_fn][etiqueta_fn][
                                                semana_fn
                                            ].calibres[calibre_fn].rnp_kilo +=
                                            rnpKilo_fn;
                                        datosAgrupados_fn[variedad_fn][etiqueta_fn][
                                            semana_fn
                                        ].total_kilos += totalKilos_fn;
                                        datosAgrupados_fn[variedad_fn][etiqueta_fn][
                                            semana_fn
                                        ].rnp_total += rnpTotal_fn;
                                        datosAgrupados_fn[variedad_fn][etiqueta_fn][
                                            semana_fn
                                        ].rnp_kilo_sum += rnpKilo_fn * totalKilos_fn;
                                        datosAgrupados_fn[variedad_fn][etiqueta_fn][
                                            semana_fn
                                        ].rnp_kilo_kilos += totalKilos_fn;
                                    }
                                });

                                // Contar etiquetas distintas por variedad
                                let etiquetasPorVariedad_fn = {};
                                $.each(datosAgrupados_fn, function(variedad_fn,
                                    datosVariedad_fn) {
                                    let etiquetasUnicas_fn = Object.keys(
                                        datosVariedad_fn);
                                    etiquetasPorVariedad_fn[variedad_fn] = {
                                        numeroEtiquetas: etiquetasUnicas_fn.length,
                                        etiquetas: etiquetasUnicas_fn
                                    };
                                });
                                console.log('Etiquetas distintas por variedad (FN):',
                                    etiquetasPorVariedad_fn);

                                // Generar HTML de la tabla
                                let htmlOutput_fn = `
            <tr class="section-header">
                <th>Variedad</th>
                <th>Etiqueta</th>
                <th>Semana</th>
                <th>Serie</th>
                <th>Color</th>
                <th style="text-align:center;">Cajas</th>
                <th style="text-align:center;">Kilos Totales</th>
                <th style="text-align:center;">RNP Total</th>
                <th style="text-align:center;">RNP Kilo</th>
            </tr>
            `;

                                // Ordenar variedades
                                let variedades_fn = Object.keys(datosAgrupados_fn).sort();
                                let totalVariedad_fn = {};

                                $.each(variedades_fn, function(index_fn, variedad_fn) {
                                    totalVariedad_fn[variedad_fn] = {
                                        cajas_equivalentes: 0,
                                        total_kilos: 0,
                                        rnp_total: 0,
                                        rnp_kilo_sum: 0,
                                        rnp_kilo_kilos: 0
                                    };
                                    let etiquetas_fn = Object.keys(datosAgrupados_fn[
                                        variedad_fn]).sort();
                                    let rowspanVariedad_fn = 0;

                                    // Calcular rowspan para la variedad
                                    $.each(etiquetas_fn, function(i_fn, etiqueta_fn) {
                                        let semanas_fn = Object.keys(
                                            datosAgrupados_fn[variedad_fn][
                                                etiqueta_fn
                                            ]).sort((a, b) => a - b);
                                        $.each(semanas_fn, function(j_fn,
                                            semana_fn) {
                                            let calibres_fn = Object
                                                .keys(
                                                    datosAgrupados_fn[
                                                        variedad_fn][
                                                        etiqueta_fn
                                                    ][semana_fn]
                                                    .calibres);
                                            rowspanVariedad_fn +=
                                                calibres_fn.length +
                                                1; // +1 por total semana
                                        });
                                        rowspanVariedad_fn +=
                                            1; // +1 por total etiqueta
                                    });

                                    let isFirstVariedadRow_fn = true;

                                    // Iterar sobre etiquetas
                                    $.each(etiquetas_fn, function(i_fn, etiqueta_fn) {
                                        let totalEtiqueta_fn = {
                                            cajas_equivalentes: 0,
                                            total_kilos: 0,
                                            rnp_total: 0,
                                            rnp_kilo_sum: 0,
                                            rnp_kilo_kilos: 0
                                        };
                                        let semanas_fn = Object.keys(
                                            datosAgrupados_fn[variedad_fn][
                                                etiqueta_fn
                                            ]).sort((a, b) => a - b);
                                        let rowspanEtiqueta_fn = 0;

                                        // Calcular rowspan para la etiqueta
                                        $.each(semanas_fn, function(j_fn,
                                            semana_fn) {
                                            let calibres_fn = Object
                                                .keys(
                                                    datosAgrupados_fn[
                                                        variedad_fn][
                                                        etiqueta_fn
                                                    ][semana_fn]
                                                    .calibres);
                                            rowspanEtiqueta_fn +=
                                                calibres_fn.length +
                                                1; // +1 por total semana
                                        });

                                        let isFirstEtiquetaRow_fn = true;

                                        // Iterar sobre semanas
                                        $.each(semanas_fn, function(j_fn,
                                            semana_fn) {
                                            let datosSemana_fn =
                                                datosAgrupados_fn[
                                                    variedad_fn][
                                                    etiqueta_fn
                                                ][semana_fn];
                                            let calibres_fn = Object
                                                .keys(
                                                    datosSemana_fn
                                                    .calibres)
                                                .sort((a, b) =>
                                                    ordenCalibres_fn
                                                    .indexOf(a) -
                                                    ordenCalibres_fn
                                                    .indexOf(b));
                                            let rowspanSemana_fn =
                                                calibres_fn.length;

                                            let isFirstSemanaRow_fn =
                                                true;

                                            // Generar filas para cada calibre
                                            $.each(calibres_fn,
                                                function(
                                                    k_fn, calibre_fn
                                                ) {
                                                    let datosCalibre_fn =
                                                        datosSemana_fn
                                                        .calibres[
                                                            calibre_fn
                                                        ];
                                                    let cajasEquivalentes_fn =
                                                        (datosCalibre_fn
                                                            .total_kilos /
                                                            5)
                                                        .toFixed(
                                                            0);
                                                    let rnpClass_fn =
                                                        datosCalibre_fn
                                                        .rnp_total <
                                                        0 ||
                                                        datosCalibre_fn
                                                        .rnp_kilo <
                                                        0 ?
                                                        'negative' :
                                                        '';
                                                    let variedadCell_fn =
                                                        isFirstVariedadRow_fn ?
                                                        `<td rowspan="${rowspanVariedad_fn}">${variedad_fn}</td>` :
                                                        '';
                                                    let etiquetaCell_fn =
                                                        isFirstEtiquetaRow_fn ?
                                                        `<td rowspan="${rowspanEtiqueta_fn}">${etiqueta_fn}</td>` :
                                                        '';
                                                    let semanaCell_fn =
                                                        isFirstSemanaRow_fn ?
                                                        `<td rowspan="${rowspanSemana_fn}">${semana_fn}</td>` :
                                                        '';

                                                    htmlOutput_fn += `
                    <tr>
                        ${variedadCell_fn}
                        ${etiquetaCell_fn}
                        ${semanaCell_fn}
                        <td>${calibre_fn}</td>
                        <td>${datosCalibre_fn.color}</td>
                        <td class="number" style="text-align:center;">${cajasEquivalentes_fn}</td>
                        <td class="number">${datosCalibre_fn.total_kilos.toFixed(2)}</td>
                        <td class="number ${rnpClass_fn}">${datosCalibre_fn.rnp_total.toFixed(2)}</td>
                        <td class="number ${rnpClass_fn}">${datosCalibre_fn.rnp_kilo.toFixed(4)}</td>
                    </tr>
                `;

                                                    isFirstSemanaRow_fn
                                                        = false;
                                                    isFirstEtiquetaRow_fn
                                                        = false;
                                                    isFirstVariedadRow_fn
                                                        = false;

                                                    // Acumular totales
                                                    totalEtiqueta_fn
                                                        .cajas_equivalentes +=
                                                        parseFloat(
                                                            cajasEquivalentes_fn
                                                        );
                                                    totalEtiqueta_fn
                                                        .total_kilos +=
                                                        datosCalibre_fn
                                                        .total_kilos;
                                                    totalEtiqueta_fn
                                                        .rnp_total +=
                                                        datosCalibre_fn
                                                        .rnp_total;
                                                    totalEtiqueta_fn
                                                        .rnp_kilo_sum +=
                                                        datosCalibre_fn
                                                        .rnp_kilo *
                                                        datosCalibre_fn
                                                        .total_kilos;
                                                    totalEtiqueta_fn
                                                        .rnp_kilo_kilos +=
                                                        datosCalibre_fn
                                                        .total_kilos;
                                                });

                                            // Total por semana
                                            let rnpKiloSemana_fn =
                                                datosSemana_fn
                                                .rnp_kilo_kilos ? (
                                                    datosSemana_fn
                                                    .rnp_kilo_sum /
                                                    datosSemana_fn
                                                    .rnp_kilo_kilos)
                                                .toFixed(4) : '0.0000';
                                            let cajasEquivalentesSemana_fn =
                                                (datosSemana_fn
                                                    .total_kilos / 9)
                                                .toFixed(0);
                                            let rnpClassSemana_fn =
                                                datosSemana_fn
                                                .rnp_total <
                                                0 || parseFloat(
                                                    rnpKiloSemana_fn) <
                                                0 ?
                                                'negative' : '';
                                            htmlOutput_fn += `
                <tr class="total-row">

                    <td colspan="3">Total Semana ${semana_fn}</td>

                    <td class="number" style="text-align:center;">${cajasEquivalentesSemana_fn}</td>
                    <td class="number">${datosSemana_fn.total_kilos.toFixed(2)}</td>
                    <td class="number ${rnpClassSemana_fn}">${datosSemana_fn.rnp_total.toFixed(2)}</td>
                    <td class="number ${rnpClassSemana_fn}">${rnpKiloSemana_fn}</td>
                </tr>
            `;
                                        });

                                        // Total por etiqueta
                                        let rnpKiloEtiqueta_fn =
                                            totalEtiqueta_fn
                                            .rnp_kilo_kilos ? (totalEtiqueta_fn
                                                .rnp_kilo_sum / totalEtiqueta_fn
                                                .rnp_kilo_kilos).toFixed(4) :
                                            '0.0000';
                                        totalEtiqueta_fn.cajas_equivalentes = (
                                                totalEtiqueta_fn.total_kilos / 9
                                            )
                                            .toFixed(0);
                                        let rnpClassEtiqueta_fn =
                                            totalEtiqueta_fn
                                            .rnp_total < 0 || parseFloat(
                                                rnpKiloEtiqueta_fn) < 0 ?
                                            'negative' : '';
                                        htmlOutput_fn += `
            <tr class="total-row">

                <td colspan="2">Total ${etiqueta_fn}</td>
                <td> </td>
                <td> </td>
                <td class="number" style="text-align:center;">${totalEtiqueta_fn.cajas_equivalentes}</td>
                <td class="number">${totalEtiqueta_fn.total_kilos.toFixed(2)}</td>
                <td class="number ${rnpClassEtiqueta_fn}">${totalEtiqueta_fn.rnp_total.toFixed(2)}</td>
                <td class="number ${rnpClassEtiqueta_fn}">${rnpKiloEtiqueta_fn}</td>
            </tr>
        `;

                                        // Acumular totales por variedad
                                        totalVariedad_fn[variedad_fn]
                                            .cajas_equivalentes += parseFloat(
                                                totalEtiqueta_fn
                                                .cajas_equivalentes
                                            );
                                        totalVariedad_fn[variedad_fn]
                                            .total_kilos +=
                                            totalEtiqueta_fn.total_kilos;
                                        totalVariedad_fn[variedad_fn]
                                            .rnp_total +=
                                            totalEtiqueta_fn.rnp_total;
                                        totalVariedad_fn[variedad_fn]
                                            .rnp_kilo_sum += totalEtiqueta_fn
                                            .rnp_kilo_sum;
                                        totalVariedad_fn[variedad_fn]
                                            .rnp_kilo_kilos += totalEtiqueta_fn
                                            .rnp_kilo_kilos;
                                    });

                                    // Total por variedad
                                    let rnpKiloVariedad_fn = totalVariedad_fn[
                                            variedad_fn]
                                        .rnp_kilo_kilos ? (totalVariedad_fn[variedad_fn]
                                            .rnp_kilo_sum / totalVariedad_fn[
                                                variedad_fn]
                                            .rnp_kilo_kilos).toFixed(4) : '0.0000';
                                    let rnpClassVariedad_fn = totalVariedad_fn[
                                            variedad_fn]
                                        .rnp_total < 0 || parseFloat(
                                            rnpKiloVariedad_fn) <
                                        0 ? 'negative' : '';
                                    htmlOutput_fn += `
        <tr class="total-row">
            <td colspan="4">Total ${variedad_fn}</td>
            <td> </td>
            <td class="number" style="text-align:center;">${totalVariedad_fn[variedad_fn].cajas_equivalentes.toFixed(0)}</td>
            <td class="number">${totalVariedad_fn[variedad_fn].total_kilos.toFixed(2)}</td>
            <td class="number ${rnpClassVariedad_fn}">${totalVariedad_fn[variedad_fn].rnp_total.toFixed(2)}</td>
            <td class="number ${rnpClassVariedad_fn}">${rnpKiloVariedad_fn}</td>
        </tr>
    `;

                                    // Acumular totales generales
                                    totalGeneral_fn.cajas_equivalentes +=
                                        totalVariedad_fn[
                                            variedad_fn].cajas_equivalentes;
                                    totalGeneral_fn.total_kilos += totalVariedad_fn[
                                        variedad_fn].total_kilos;
                                    totalGeneral_fn.rnp_total += totalVariedad_fn[
                                        variedad_fn].rnp_total;
                                    totalGeneral_fn.rnp_kilo_sum += totalVariedad_fn[
                                        variedad_fn].rnp_kilo_sum;
                                    totalGeneral_fn.rnp_kilo_kilos += totalVariedad_fn[
                                        variedad_fn].rnp_kilo_kilos;
                                });

                                // Total general
                                let rnpKiloGeneral_fn = totalGeneral_fn.rnp_kilo_kilos ? (
                                    totalGeneral_fn.rnp_kilo_sum / totalGeneral_fn
                                    .rnp_kilo_kilos).toFixed(4) : '0.0000';
                                let rnpClassGeneral_fn = totalGeneral_fn.rnp_total < 0 ||
                                    parseFloat(rnpKiloGeneral_fn) < 0 ? 'negative' : '';
                                htmlOutput_fn += `
    <tr class="total-row">
        <td colspan="5">Total general</td>
        <td class="number" style="text-align:center;">${totalGeneral_fn.cajas_equivalentes.toFixed(0)}</td>
        <td class="number">${totalGeneral_fn.total_kilos.toFixed(2)}</td>
        <td class="number ${rnpClassGeneral_fn}">${totalGeneral_fn.rnp_total.toFixed(2)}</td>
        <td class="number ${rnpClassGeneral_fn}">${rnpKiloGeneral_fn}</td>
    </tr>
`;



                                // Insertar el HTML en el contenedor
                                $('#fuera-norma').html(htmlOutput_fn);





                                llenarNorma(response);

                                //Comercial
                                llenarComercial(response);
                                //Fin Comercial

                            },
                            error: function(xhr, status, error) {
                                console.error('Error en la solicitud AJAX:', error);
                                console.error('Error AJAX:', xhr.status, error);
                                console.error('Respuesta del servidor:', xhr.responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Ocurrió un error al procesar la solicitud. Por favor, intenta de nuevo.',
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Campos incompletos',
                            text: 'Por favor, seleccione todos los campos requeridos.',
                        });
                    }
                });
                let groupedDataChart = [];

                function llenarNorma(response) {
                    // Definir orden de calibres
                    const ordenCalibres = ['7J', '6J', '5J', '4J', '3J', '2J', 'J', 'XL'];

                    // Objeto para agrupar por especie, variedad y etiqueta
                    let datosAgrupadosNorma = {};
                    let totalGeneralNorma = {
                        cajas: 0,
                        total_kilos: 0,
                        rnp_total: 0,
                        rnp_kilo_sum: 0,
                        rnp_kilo_kilos: 0
                    };
                    let groupedData = [];
                    // Agrupar datos por especie, variedad y etiqueta
                    $.each(response.result, function(index, item) {
                        if (item.categoria.toUpperCase() === 'CAT 1' || item.categoria.toUpperCase() === 'SUPER MERCADO') {
                            let variedad = item.variedad;
                            let etiqueta = item.etiqueta;
                            let calibre = item.calibre;
                            let especie = item.especie.nombre;

                            // Renombrar especies según normativa
                            switch (especie) {
                                case "Plums":
                                    especie = "Ciruela";
                                    break;
                                case "Nectarines":
                                    especie = "Nectarín";
                                    break;
                                case "Peaches":
                                    especie = "Durazno";
                                    break;
                            }

                            let totalKilos = parseFloat(item.total_kilos.replace(',', '.')) || 0;
                            let rnpTotal = parseFloat(item.resultado_total.replace(',', '.')) || 0;
                            let rnpKilo = parseFloat(item.resultado_kilo.replace(',', '.')) || 0;
                            let cajas=parseFloat(item.cajas) || 0;




                            if (!datosAgrupadosNorma[especie]) {
                                datosAgrupadosNorma[especie] = {};
                            }
                            if (!datosAgrupadosNorma[especie][variedad]) {
                                datosAgrupadosNorma[especie][variedad] = {};
                            }
                            if (!datosAgrupadosNorma[especie][variedad][etiqueta]) {
                                datosAgrupadosNorma[especie][variedad][etiqueta] = {
                                    calibres: {},
                                    total_kilos: 0,
                                    rnp_total: 0,
                                    rnp_kilo_sum: 0,
                                    rnp_kilo_kilos: 0,
                                    cajas:0
                                };
                            }
                            if (!datosAgrupadosNorma[especie][variedad][etiqueta].calibres[calibre]) {
                                datosAgrupadosNorma[especie][variedad][etiqueta].calibres[calibre] = {
                                    total_kilos: 0,
                                    rnp_total: 0,
                                    rnp_kilo: 0,
                                    cajas:0
                                };
                            }

                            datosAgrupadosNorma[especie][variedad][etiqueta].calibres[calibre]
                                .total_kilos +=
                                totalKilos;
                            datosAgrupadosNorma[especie][variedad][etiqueta].calibres[calibre].rnp_total +=
                                rnpTotal;
                            datosAgrupadosNorma[especie][variedad][etiqueta].calibres[calibre].rnp_kilo +=
                                rnpKilo;
                            datosAgrupadosNorma[especie][variedad][etiqueta].calibres[calibre].cajas +=
                                cajas;

                            datosAgrupadosNorma[especie][variedad][etiqueta].total_kilos += totalKilos;
                            datosAgrupadosNorma[especie][variedad][etiqueta].rnp_total += rnpTotal;
                            datosAgrupadosNorma[especie][variedad][etiqueta].rnp_kilo_sum += rnpKilo *
                                totalKilos;
                            datosAgrupadosNorma[especie][variedad][etiqueta].rnp_kilo_kilos += totalKilos;
                            datosAgrupadosNorma[especie][variedad][etiqueta].cajas += cajas;

                        }
                    });

                    // Generar HTML de la tabla
                    let htmlOutput = `
        <table>
            <thead>
                <tr class="section-header">
                    <th>Especie</th>
                    <th>Variedad</th>
                    <th>Etiqueta</th>
                    <th>Calibre</th>
                    <th>Curva Calibre</th>
                    <th>Cajas</th>
                    <th>Kilos Totales</th>
                    <th>RNP Total</th>
                    <th>RNP Kilo</th>
                </tr>
            </thead>
            <tbody>
    `;

                    // Ordenar especies y variedades
                    let especies = Object.keys(datosAgrupadosNorma).sort();
                    let totalPorVariedad = {};

                    $.each(especies, function(i_especie, especie) {
                        let variedades = Object.keys(datosAgrupadosNorma[especie]).sort();

                        $.each(variedades, function(i_variedad, variedad) {
                            let etiquetas = Object.keys(datosAgrupadosNorma[especie][variedad])
                                .sort();
                            let rowspanVariedad = 0;

                            // Calcular rowspan total por variedad //
                            $.each(etiquetas, function(i_etiqueta, etiqueta) {
                                let calibres = Object.keys(datosAgrupadosNorma[especie][
                                    variedad
                                ][etiqueta].calibres);
                                rowspanVariedad += calibres.length;
                            });

                            let isFirstVariedadRow = true;
                            let totalVariedad = {
                                cajas: 0,
                                total_kilos: 0,
                                rnp_total: 0,
                                rnp_kilo_sum: 0,
                                rnp_kilo_kilos: 0
                            };

                            $.each(etiquetas, function(i_etiqueta, etiqueta) {
                                let datosEtiqueta = datosAgrupadosNorma[especie][variedad][
                                    etiqueta
                                ];
                                let calibres = Object.keys(datosEtiqueta.calibres).sort((a,
                                        b) =>
                                    ordenCalibres.indexOf(a) - ordenCalibres.indexOf(b)
                                );
                                let rowspanEtiqueta = calibres.length;
                                let isFirstEtiquetaRow = true;

                                $.each(calibres, function(i_calibre, calibre) {
                                    let datosCalibre = datosEtiqueta.calibres[
                                        calibre];

                                    let curvaCalibre = datosEtiqueta.total_kilos ?
                                        (datosCalibre.total_kilos / datosEtiqueta
                                            .total_kilos).toFixed(4) : '0.0000';
                                    curvaCalibre = (curvaCalibre * 100);
                                    curvaCalibre = curvaCalibre + ' %';
                                    let cajasEquivalentes = parseFloat(datosCalibre
                                        .cajas);

                                    let rnpKilo = datosCalibre.rnp_kilo.toFixed(2);
                                    let rnpClass = datosCalibre.rnp_total < 0 ||
                                        datosCalibre.rnp_kilo < 0 ? 'negative' : '';

                                    let especieCell = i_variedad === 0 &&
                                        i_etiqueta ===
                                        0 && i_calibre === 0 ?
                                        `<td>${especie}</td>` : '<td></td>';
                                    let variedadCell = isFirstEtiquetaRow &&
                                        i_calibre === 0 ?
                                        `<td>${variedad}</td>` : '<td></td>';
                                    let etiquetaCell = isFirstEtiquetaRow &&
                                        i_calibre === 0 ?
                                        `<td>${etiqueta}</td>` : '<td></td>';

                                    htmlOutput += `
                        <tr>
                            ${especieCell}
                            ${variedadCell}
                            ${etiquetaCell}
                            <td>${calibre}</td>
                            <td class="number">${formatCurrency(curvaCalibre)} %</td>
                            <td class="number">${cajasEquivalentes.toLocaleString('es-CL', {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    })}</td>
                            <td class="number">${formatInteger(datosCalibre.total_kilos.toFixed(0))}</td>
                            <td class="number ${rnpClass}">US$ ${formatCurrency(datosCalibre.rnp_total.toFixed(2))}</td>
                            <td class="number ${rnpClass}">US$ ${formatCurrency(datosCalibre.rnp_total.toFixed(2)/datosCalibre.total_kilos.toFixed(0))}</td>
                        </tr>
                    `;
                                    groupedData.push({
                                        especie: especie,
                                        variedad: variedad,
                                        etiqueta: etiqueta,
                                        calibre: calibre,
                                        curva_calibre: curvaCalibre,
                                        cajas_equivalentes: cajasEquivalentes,
                                        total_kilos: datosCalibre
                                            .total_kilos,
                                        rnp_total: datosCalibre.rnp_total,
                                        rnp_kilo: (datosCalibre.rnp_total /
                                            datosCalibre.total_kilos)
                                    });
                                    //generamos un objeto para la grafica

                                    isFirstEtiquetaRow = false;
                                    isFirstVariedadRow = false;

                                    // Acumular totales por etiqueta

                                    // Acumular totales
                                    totalVariedad.cajas += parseFloat(
                                        cajasEquivalentes);
                                    totalVariedad.total_kilos += datosCalibre
                                        .total_kilos;
                                    totalVariedad.rnp_total += datosCalibre
                                        .rnp_total;
                                    totalVariedad.rnp_kilo_sum += datosCalibre
                                        .rnp_kilo * datosCalibre.total_kilos;
                                    totalVariedad.rnp_kilo_kilos += datosCalibre
                                        .total_kilos;
                                });

                                // Fila de total por etiqueta
                                let rnpKiloEtiqueta = datosEtiqueta.rnp_kilo_kilos ?
                                    (datosEtiqueta.rnp_kilo_sum / datosEtiqueta
                                        .rnp_kilo_kilos)
                                    .toFixed(4) : '0.0000';
                                let cajasEtiqueta = (datosEtiqueta.cajas);
                                let rnpClassEtiqueta = datosEtiqueta.rnp_total < 0 ||
                                    parseFloat(rnpKiloEtiqueta) < 0 ? 'negative' : '';


                                htmlOutput += `
                    <tr class="total-row">
                        <td></td>
                        <td></td>
                        <td>Total ${etiqueta}</td>
                        <td></td>
                        <td class="number">1.0000</td>
                        <td class="number">${cajasEtiqueta.toLocaleString('es-CL', {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    })}</td>
                        <td class="number">${formatInteger(datosEtiqueta.total_kilos.toFixed(2))}</td>
                        <td class="number ${rnpClassEtiqueta}">US$ ${formatCurrency(datosEtiqueta.rnp_total.toFixed(2))}</td>
                        <td class="number ${rnpClassEtiqueta}">US$ ${formatCurrency(datosEtiqueta.rnp_total.toFixed(2)/datosEtiqueta.total_kilos.toFixed(0))}</td>
                    </tr>
                `;
                            });

                            // Fila de total por variedad
                            let rnpKiloVariedad = totalVariedad.rnp_kilo_kilos ?
                                (totalVariedad.rnp_kilo_sum / totalVariedad.rnp_kilo_kilos).toFixed(
                                    4) :
                                '0.0000';
                            let rnpClassVariedad = totalVariedad.rnp_total < 0 || parseFloat(
                                rnpKiloVariedad) < 0 ? 'negative' : '';

                            htmlOutput += `
                <tr class="total-row">
                    <td></td>
                    <td>Total ${variedad}</td>

                    <td></td>
                    <td></td>
                    <td class="number">1.0000</td>
                    <td class="number">${formatInteger(totalVariedad.cajas)}</td>
                    <td class="number">${formatInteger(totalVariedad.total_kilos.toFixed(0))}</td>
                    <td class="number ${rnpClassVariedad}">US$ ${formatCurrency(totalVariedad.rnp_total.toFixed(2))}</td>
                    <td class="number ${rnpClassVariedad}">US$ ${formatCurrency(totalVariedad.rnp_total.toFixed(2)/totalVariedad.total_kilos.toFixed(0))}</td>
                </tr>
            `;

                            // Acumular al total general
                            totalGeneralNorma.cajas += totalVariedad
                                .cajas;
                            totalGeneralNorma.total_kilos += totalVariedad.total_kilos;
                            totalGeneralNorma.rnp_total += totalVariedad.rnp_total;
                            totalGeneralNorma.rnp_kilo_sum += totalVariedad.rnp_kilo_sum;
                            totalGeneralNorma.rnp_kilo_kilos += totalVariedad.rnp_kilo_kilos;
                        });
                    });

                    // Fila de total general
                    let rnpKiloGeneral = totalGeneralNorma.rnp_kilo_kilos ?
                        (totalGeneralNorma.rnp_kilo_sum / totalGeneralNorma.rnp_kilo_kilos).toFixed(4) : '0.0000';
                    let rnpClassGeneral = totalGeneralNorma.rnp_total < 0 || parseFloat(rnpKiloGeneral) < 0 ?
                        'negative' : '';

                    htmlOutput += `
                <tr class="total-row">
                    <td>Total General</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="number">1.0000</td>
                    <td class="number">${formatInteger(totalGeneralNorma.cajas)}</td>
                    <td class="number">${formatInteger(totalGeneralNorma.total_kilos.toFixed(0))}</td>
                    <td class="number ${rnpClassGeneral}">US$ ${formatCurrency(totalGeneralNorma.rnp_total.toFixed(2))}</td>
                    <td class="number ${rnpClassGeneral}">US$ ${formatCurrency((totalGeneralNorma.rnp_total.toFixed(2)/totalGeneralNorma.total_kilos.toFixed(0)).toFixed(4))}</td>
                </tr>
            </tbody>
        </table>
    `;
                    console.log(Object.values(groupedData));
                    $('#norma').html(htmlOutput); // Insertar en contenedor
                    //generateCharts(Object.values(groupedData)); // Llamar a la función de gráficos
                    const uniqueGroups = getUniqueGroups(groupedData);

                    uniqueGroups.forEach(group => {
                        generateChart(group.especie, group.variedad, group.etiqueta, groupedData);
                    });
                }



                function llenarComercial(response) {
                    const categoriasPermitidas = ['Comercial', 'Pre Calibre', 'Desecho', 'Merma', 'Sobre Calibre','Comercial Huerto'];

                    // Objeto para agrupar por especie, variedad y categoría
                    let datosAgrupados = {};
                    let totalGeneral = {
                        total_kilos: 0,
                        precio_total: 0,
                        precio_kilo_sum: 0,
                        precio_kilo_kilos: 0
                    };

                    // Agrupar datos por especie, variedad y categoría
                    $.each(response.result, function(index, item) {
                        if (categoriasPermitidas.includes(item.categoria)) {
                            let especie = item.especie.nombre;
                            switch (especie) {
                                case "Plums":
                                    especie = "Ciruela";
                                    break;
                                case "Nectarines":
                                    especie = "Nectarín";
                                    break;
                                case "Peaches":
                                    especie = "Durazno";
                                    break;
                            }

                            let variedad = item.variedad;
                            let categoria = item.categoria;

                            let totalKilos = parseFloat(item.total_kilos.replace(',', '.')) || 0;
                            let precioTotal = parseFloat(item.total_comercial.replace(',', '.')) || 0;
                            let precioKilo = parseFloat(item.precio_comercial.replace(',', '.')) || 0;

                            // Inicializar estructura si no existe
                            if (!datosAgrupados[especie]) {
                                datosAgrupados[especie] = {};
                            }
                            if (!datosAgrupados[especie][variedad]) {
                                datosAgrupados[especie][variedad] = {};
                            }
                            if (!datosAgrupados[especie][variedad][categoria]) {
                                datosAgrupados[especie][variedad][categoria] = {
                                    total_kilos: 0,
                                    precio_total: 0,
                                    precio_kilo_sum: 0,
                                    precio_kilo_kilos: 0
                                };
                            }

                            // Acumular valores
                            datosAgrupados[especie][variedad][categoria].total_kilos += totalKilos;
                            datosAgrupados[especie][variedad][categoria].precio_kilo_sum += precioKilo;
                            datosAgrupados[especie][variedad][categoria].precio_total += precioTotal *
                                totalKilos;
                            datosAgrupados[especie][variedad][categoria].precio_kilo_kilos += totalKilos;

                            // Totales generales
                            totalGeneral.total_kilos += totalKilos;
                            totalGeneral.precio_total += precioTotal * totalKilos;
                            totalGeneral.precio_kilo_sum += precioKilo;
                            totalGeneral.precio_kilo_kilos += totalKilos;
                        }
                    });
                    console.log("total general", totalGeneral);
                    console.log("datosAgrupados", datosAgrupados);

                    // Generar HTML de la tabla
                    let htmlOutput = `
        <table>
            <thead>
                <tr class="section-header">
                    <th style="text-align:center">Especie</th>
                    <th style="text-align:center">Variedad</th>
                    <th style="text-align:center">Categoría</th>
                    <th style="text-align:center">Kilos</th>
                    <th style="text-align:center">Precio Comercial Total</th>
                    <th style="text-align:center">Precio Comercial Kilo</th>
                </tr>
            </thead>
            <tbody>
    `;

                    // Ordenar especies alfabéticamente
                    let especies = Object.keys(datosAgrupados).sort();

                    $.each(especies, function(i_especie, especie) {
                        let datosPorEspecie = datosAgrupados[especie];
                        let variedades = Object.keys(datosPorEspecie).sort();
                        let rowspanEspecie = 0;

                        // Contar cantidad de filas necesarias para este rowspan
                        $.each(variedades, function(i_var, var_nombre) {
                            let categorias = Object.keys(datosPorEspecie[var_nombre]);
                            rowspanEspecie += categorias.length + 1; // +1 para fila de subtotal
                        });

                        let isFirstVariedadRow = true;

                        $.each(variedades, function(i_variedad, variedad) {
                            let datosPorVariedad = datosPorEspecie[variedad];
                            let categorias = Object.keys(datosPorVariedad).sort();
                            let rowspanVariedad = categorias.length;

                            let totalVariedad = {
                                total_kilos: 0,
                                precio_total: 0,
                                precio_kilo_sum: 0,
                                precio_kilo_kilos: 0
                            };

                            $.each(categorias, function(i_categoria, categoria) {
                                let datosCategoria = datosPorVariedad[categoria];
                                console.log("datosCategoria", datosCategoria);
                                let precioKilo = datosCategoria.total_kilos ?
                                    (datosCategoria.precio_kilo_sum / datosCategoria
                                        .total_kilos).toFixed(2) : '0.00';

                                let especieCell = i_categoria === 0 && isFirstVariedadRow ?
                                    `<td rowspan="${rowspanEspecie}">${especie}</td>` : '';
                                let variedadCell = i_categoria === 0 ?
                                    `<td rowspan="${rowspanVariedad}">${variedad}</td>` :
                                    '';

                                htmlOutput += `
                    <tr>
                        ${especieCell}
                        ${variedadCell}
                        <td style="text-align:center">${categoria}</td>
                        <td class="number">${formatInteger(datosCategoria.total_kilos.toFixed(0))}</td>
                        <td class="number">${formatInteger(datosCategoria.precio_kilo_sum.toFixed(0))}</td>
                        <td class="number">${formatInteger(precioKilo)}</td>
                    </tr>
                `;

                                // Acumular totales por variedad
                                totalVariedad.total_kilos += datosCategoria.total_kilos;
                                totalVariedad.precio_total += datosCategoria
                                    .precio_kilo_sum;
                                totalVariedad.precio_kilo_sum += precioKilo;
                                totalVariedad.precio_kilo_kilos += datosCategoria
                                    .precio_kilo_kilos;

                                isFirstVariedadRow = false;
                            });

                            // Subtotal por variedad
                            let precioKiloVariedad = totalVariedad.total_kilos > 0 ?
                                (totalVariedad.precio_total / totalVariedad.total_kilos)
                                .toFixed(0) : '0';
                            htmlOutput += `
                <tr class="total-row">
                    <td>Total ${variedad}</td>
                    <td></td>
                    <td class="number">${formatInteger(totalVariedad.total_kilos.toFixed(0))}</td>
                    <td class="number">$ ${formatInteger(totalVariedad.precio_total.toFixed(0))}</td>
                    <td class="number">$ ${formatInteger(precioKiloVariedad)}</td>
                </tr>
            `;
                        });
                    });

                    // Total general
                    let precioKiloGeneral = totalGeneral.precio_kilo_kilos ?
                        (totalGeneral.precio_total / totalGeneral.total_kilos).toFixed(0) : '0';

                    htmlOutput += `
                <tr class="total-row">
                    <td>Total general</td>
                    <td></td>
                    <td></td>
                    <td class="number">${formatInteger(totalGeneral.total_kilos.toFixed(0))}</td>
                    <td class="number">$ ${formatInteger(totalGeneral.precio_total.toFixed(0))}</td>
                    <td class="number">$ ${formatInteger(precioKiloGeneral)}</td>
                </tr>
            </tbody>
        </table>
    `;

                    $('#comercial').html(htmlOutput);
                }

                function formatCurrency(value) {
                    return parseFloat(value).toLocaleString('es-CL', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }

                function formatInteger(value) {
                    return parseFloat(value).toLocaleString('es-CL', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }



                // Función para calcular el promedio de rnp_kilo
                function calculateAverageRnpKilo(data) {
                    const sum = data.reduce((acc, item) => acc + item.rnp_kilo, 0);
                    return sum / data.length;
                }

                // Función para ordenar calibres
                function sortCalibres(data) {
                    // const calibreOrder = ['5J', '4J', '3J', '2J', 'J', 'XL', 'L'];
                    return data;
                }

                // Función para generar un gráfico con ApexCharts
                function createChart(especie, variedad, chartData) {
                    const sortedData = sortCalibres(chartData);
                    const calibres = sortedData.map(item => item.calibre);
                    const curvacalibre = sortedData.map(item => item.curvaCalibre);
                    const rnpKilo = sortedData.map(item => item.rnp_kilo);
                    const avgRnpKilo = calculateAverageRnpKilo(sortedData);
                    const avgRnpKiloArray = sortedData.map(() => avgRnpKilo);

                    const options = {
                        chart: {
                            type: 'line',
                            height: 600,
                            //toolbar: { show: true, export: { csv: false, svg: false, png: true } }
                            width: 800,
                            toolbar: {
                                show: false
                            },
                            animations: {
                                enabled: false // Desactiva todas las animaciones
                            }
                        },
                        series: [{
                                name: 'Curva Calibre',
                                type: 'column',
                                data: curvacalibre
                            },
                            {
                                name: 'RNP por Kilo',
                                type: 'line',
                                data: rnpKilo
                            },
                            {
                                name: 'Promedio RNP por Kilo',
                                type: 'line',
                                data: avgRnpKiloArray
                            }
                        ],
                        xaxis: {
                            categories: calibres,
                            title: {
                                text: 'Calibre'
                            }
                        },
                        yaxis: [{
                                title: {
                                    text: 'Curva Calibre'
                                },
                                decimalsInFloat: 2
                            },
                            {
                                opposite: true,
                                title: {
                                    text: 'RNP por Kilo'
                                },
                                decimalsInFloat: 2
                            }
                        ],
                        title: {
                            text: `${especie} - ${variedad}`,
                            align: 'center'
                        },
                        stroke: {
                            width: [0, 4, 4]
                        },
                        colors: ['#1f77b4', '#ff7f0e', '#2ca02c'],
                        dataLabels: {
                            enabled: true,
                            enabledOnSeries: [0, 1],
                            formatter: function(val, opts) {
                                const seriesIndex = opts.seriesIndex;
                                const dataPointIndex = opts.dataPointIndex;
                                const allSeries = opts.w.config.series;
                                const values = [
                                    allSeries[0].data[dataPointIndex] || 0, // Curva Calibre
                                    allSeries[1].data[dataPointIndex] || 0, // RNP por Kilo
                                    allSeries[2].data[dataPointIndex] || 0 // Promedio RNP por Kilo
                                ];

                                // Hide label if too close to another series (within 0.5 units)
                                if (seriesIndex === 1 && Math.abs(values[1] - values[0]) < 0.2) return '';
                                if (seriesIndex === 2 && (Math.abs(values[2] - values[1]) < 0.2 || Math.abs(
                                        values[2] - values[0]) < 0.2)) return '';

                                return val.toFixed(2);
                            },
                            style: {
                                fontSize: '22px',
                                colors: ['#1f77b4', '#ff7f0e',
                                    '#2ca02c'
                                ] // 2. Match data label colors to series colors
                            },
                            background: {
                                enabled: true, // Background disabled as per original
                                foreColor: '#000000',
                                padding: 4,
                                background: '#FFFFFF',
                                borderRadius: 2,
                                borderWidth: 1,
                                borderColor: '#ffffff',
                                opacity: 0.9
                            },
                            offsetY: -25,
                            dropShadow: {
                                enabled: true,
                                top: 1,
                                left: 1,
                                blur: 1,
                                opacity: 0.65
                            }
                        },
                        tooltip: {
                            y: [{
                                formatter: val => val.toFixed(2)
                            }, {
                                formatter: val => val.toFixed(2)
                            }, {
                                formatter: val => val.toFixed(2)
                            }]
                        }
                    };

                    const chartId = `chart_${especie}_${variedad}`.replace(/ /g, '_').replace(/[^a-zA-Z0-9_]/g,
                        '');
                    $('#charts').append(`<div class="chart-container" id="${chartId}"></div>`);

                    const chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
                    chart.render();
                    return chartId;
                }

                function getUniqueGroups(groupedData) {
                    const seen = new Set();
                    const uniqueGroups = [];

                    groupedData.forEach(item => {
                        const key = `${item.especie}-${item.variedad}-${item.etiqueta}`;
                        if (!seen.has(key)) {
                            seen.add(key);
                            uniqueGroups.push({
                                especie: item.especie,
                                variedad: item.variedad,
                                etiqueta: item.etiqueta
                            });
                        }
                    });

                    return uniqueGroups;
                }

                function processData(rawData) {
                    const groups = {};
                    let categories = new Set();

                    // Agrupar por 'variedad + etiqueta'
                    rawData.forEach(item => {
                        const key = `${item.variedad} / ${item.etiqueta}`;
                        if (!groups[key]) groups[key] = {};

                        groups[key][item.calibre] = item.rnp_kilo;
                        categories.add(item.calibre);
                    });

                    // Ordenar calibres numéricamente
                    categories = Array.from(categories).sort((a, b) => a - b);

                    // Convertir grupos en series
                    const series = Object.keys(groups).map(key => {
                        return {
                            name: key,
                            data: categories.map(calibre => groups[key][calibre] || 0)
                        };
                    });

                    return {
                        categories,
                        series
                    };
                }

                function getDataGroup(rawData, variedad, etiqueta) {
                    return rawData.filter(item => item.variedad === variedad && item.etiqueta === etiqueta);
                }

                function extractSeries(dataGroup) {
                    const calibres = dataGroup.map(item => item.calibre);
                    const curvaCalibre = dataGroup.map(item => parseFloat(item.curva_calibre));
                    const rnpKilo = dataGroup.map(item => parseFloat(item.rnp_kilo));

                    // Calculamos el promedio global de rnp_kilo
                    const avgRnpKilo = rnpKilo.reduce((sum, val) => sum + val, 0) / rnpKilo.length;
                    const avgRnpKiloArray = new Array(rnpKilo.length).fill(avgRnpKilo);

                    return {
                        calibres,
                        curvaCalibre,
                        rnpKilo,
                        avgRnpKiloArray
                    };
                }
                // Función principal para generar todos los gráficos
                function generateChart(especie, variedad, etiqueta, rawData) {
                    const dataGroup = getDataGroup(rawData, variedad, etiqueta);
                    if (!dataGroup.length) return null;

                    const {
                        calibres,
                        curvaCalibre,
                        rnpKilo,
                        avgRnpKiloArray
                    } = extractSeries(dataGroup);

                    const options = {
                        chart: {
                            type: 'line',
                            height: 600,
                            width: 800,
                            toolbar: {
                                show: false
                            },
                        },
                        series: [{
                                name: 'Curva Calibre',
                                type: 'column',
                                data: curvaCalibre
                            },
                            {
                                name: 'RNP por Kilo',
                                type: 'line',
                                data: rnpKilo
                            },
                            {
                                name: 'Promedio RNP por Kilo',
                                type: 'line',
                                data: avgRnpKiloArray
                            }
                        ],
                        xaxis: {
                            categories: calibres,
                            title: {
                                text: 'Calibre'
                            }
                        },
                        yaxis: [{
                                title: {
                                    text: 'Curva Calibre (%)'
                                },
                                decimalsInFloat: 2
                            },
                            {
                                opposite: true,
                                title: {
                                    text: 'RNP por Kilo'
                                },
                                decimalsInFloat: 2
                            }
                        ],
                        title: {
                            text: `${especie} - ${variedad} / ${etiqueta}`,
                            align: 'center'
                        },
                        stroke: {
                            width: [0, 4, 4]
                        },
                        colors: ['#1f77b4', '#ff7f0e', '#2ca02c'],
                        dataLabels: {
                            enabled: true,
                            enabledOnSeries: [0, 1],
                            formatter: function(val, opts) {
                                const seriesIndex = opts.seriesIndex;
                                const dataPointIndex = opts.dataPointIndex;
                                const allSeries = opts.w.config.series;
                                const values = [
                                    allSeries[0].data[dataPointIndex] || 0, // Curva Calibre
                                    allSeries[1].data[dataPointIndex] || 0, // RNP por Kilo
                                    allSeries[2].data[dataPointIndex] || 0 // Promedio RNP por Kilo
                                ];

                                // Ocultar label si muy cerca de otra serie
                                if (seriesIndex === 1 && Math.abs(values[1] - values[0]) < 0.2) return '';
                                if (seriesIndex === 2 && (Math.abs(values[2] - values[1]) < 0.2 || Math.abs(
                                        values[2] - values[0]) < 0.2)) return '';

                                return val.toFixed(2);
                            },
                            style: {
                                fontSize: '22px',
                                colors: ['#1f77b4', '#ff7f0e', '#2ca02c']
                            },
                            background: {
                                enabled: true,
                                foreColor: '#000000',
                                padding: 4,
                                background: '#FFFFFF',
                                borderRadius: 2,
                                borderWidth: 1,
                                borderColor: '#ffffff',
                                opacity: 0.9
                            },
                            offsetY: -25,
                            dropShadow: {
                                enabled: true,
                                top: 1,
                                left: 1,
                                blur: 1,
                                opacity: 0.65
                            }
                        },
                        tooltip: {
                            y: [{
                                formatter: val => val.toFixed(2)
                            }, {
                                formatter: val => val.toFixed(2)
                            }, {
                                formatter: val => val.toFixed(2)
                            }]
                        }
                    };

                    const chartId = `chart_${especie}_${variedad}_${etiqueta}`.replace(/ /g, '_').replace(
                        /[^a-zA-Z0-9_]/g, '');
                    $('#charts').append(`<div class="chart-container" id="${chartId}"></div>`);

                    const chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
                    chart.render();

                    return chartId;
                }
            }
        });
    </script>
@endsection
