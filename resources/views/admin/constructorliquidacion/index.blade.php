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
                                                <tr>
                                                    <td colspan="8">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7">&nbsp;</td>
                                                    <td class="currency" id="valorTotalUsd"></td>
                                                </tr>

                                                <!-- Facturación -->
                                                <tr class="section-header">
                                                    <td colspan="8">Facturación (proformas)</td>
                                                </tr>
                                                <tbody id="anticipos">
                                                </tbody>
                                                <tr>
                                                    <td colspan="8">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7">Total facturación (Proformas)</td>
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
                                                <tr>
                                                    <td colspan="6">Análisis multiresiduos/virus</td>
                                                    <td>US$</td>
                                                    <td class="currency"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7">Total Cargos</td>
                                                    <td class="currency" id="totalOtrosCargos"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7">Saldo</td>
                                                    <td class="currency" id="SaldoTotal"></td>
                                                </tr>

                                                <!-- Nota de Débito y Factura -->
                                                <tr>
                                                    <td colspan="4">&nbsp;</td>
                                                    <td colspan="2" id="fecha_tipo_cambio">TC 30-06-2025</td>
                                                    <td colspan="1">$</td>
                                                    <td class="currency" id="TC"></td>
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
    <script>
        $(document).ready(function() {


            $('#downloadPdf').on('click', function() {
                generatePdf_pdf();
            });

            //Generación de PDF
            // Función principal para generar el PDF
            // Función principal para generar el PDF

            // Función para generar el PDF
            function generatePdf_pdf() {
                // Lista de pestañas
                const tabs_pdf = [{
                        id: '#CuentaCorriente',
                        name: 'Cuenta Corriente'
                    },
                    {
                        id: '#BceMasa',
                        name: 'Balance de Masas'
                    },
                    {
                        id: '#Norma',
                        name: 'Norma'
                    },
                    {
                        id: '#NormaSemana',
                        name: 'Norma Con Semana'
                    },
                    {
                        id: '#FueraNorma',
                        name: 'Fuera de Norma'
                    },
                    {
                        id: '#Comercial',
                        name: 'Comercial'
                    }
                ];

                // Capturar el HTML de cada pestaña
                const tabContents_pdf = tabs_pdf.map(tab_pdf => {
                    const $tabPane_pdf = $(tab_pdf.id);
                    const content_pdf = $tabPane_pdf.html();
                    if (!content_pdf) {
                        console.warn(`El tab ${tab_pdf.name} está vacío.`);
                    }
                    return {
                        name: tab_pdf.name,
                        html: content_pdf
                    };
                });

                // Enviar el contenido al backend
                $.ajax({
                    url: "{{ route('admin.constructorliquidacion.generatepdf') }}", // Ruta del controlador
                    method: 'POST',
                    data: {
                        tabs: tabContents_pdf,
                        productor_id: $('#productor_id').val(),
                        productornombre: $(".productorNombre").text(),
                        temporada: $('#temporada').val(),
                        especie_id: $('#especie_id').val(),
                        _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                    },
                    success: function(response_pdf) {
                        // Crear un enlace para descargar el PDF
                        const blob_pdf = new Blob([response_pdf], {
                            type: 'application/pdf'
                        });
                        const link_pdf = document.createElement('a');
                        link_pdf.href = window.URL.createObjectURL(blob_pdf);
                        link_pdf.download = 'Liquidacion-' + productor_nombre + '-' + $('#temporada')
                            .val() + '.pdf';
                        link_pdf.click();
                        window.URL.revokeObjectURL(link_pdf.href);
                    },
                    error: function(xhr_pdf, status_pdf, error_pdf) {
                        console.error('Error al generar el PDF:', error_pdf);
                        alert('Error al generar el PDF. Por favor, intenta de nuevo.');
                    },
                    xhrFields: {
                        responseType: 'blob' // Necesario para manejar el archivo PDF
                    }
                });
            }
            // Fin de la función generatePdf_pdf
            let productor_nombre = '';
            $('#productor_id').select2();
            $('#temporada').select2();
            $('#especie_id').select2();
            $('#btnPreview').on('click', function() {
                var productor_id = $('#productor_id').val();
                var temporada = $('#temporada').val();
                var especie_id = $('#especie_id').val();

                if (productor_id && temporada && especie_id && productor_id !== "" && temporada !== "" &&
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
                                        costo_comercial: 0
                                    },
                                    'CATII': {
                                        resultado_kilo: 0,
                                        resultado_total: 0,
                                        total_comercial: 0,
                                        total_kilos: 0,
                                        costo_comercial: 0
                                    },
                                    'Comercial': {
                                        resultado_kilo: 0,
                                        resultado_total: 0,
                                        total_comercial: 0,
                                        total_kilos: 0,
                                        costo_comercial: 0
                                    },
                                    'Desecho': {
                                        resultado_kilo: 0,
                                        resultado_total: 0,
                                        total_comercial: 0,
                                        total_kilos: 0,
                                        costo_comercial: 0
                                    },
                                    'Precalibre': {
                                        resultado_kilo: 0,
                                        resultado_total: 0,
                                        total_comercial: 0,
                                        total_kilos: 0,
                                        costo_comercial: 0
                                    },
                                    'Merma': {
                                        resultado_kilo: 0,
                                        resultado_total: 0,
                                        total_comercial: 0,
                                        total_kilos: 0,
                                        costo_comercial: 0
                                    }
                                };

                                // Iterar sobre los datos obtenidos
                                $.each(response.result, function(index, item) {
                                    let categoria = item.categoria.replace(" ", "")
                                        .toUpperCase();
                                    if (categoria == "SUPERMERCADO") {
                                        categoria = 'CAT1';
                                    }


                                    // Convertir los valores a números, manejando comas como separador decimal
                                    let resultadoKilo = parseFloat(item.resultado_kilo
                                        .replace(',', '.')) || 0;
                                    let resultadoTotal = parseFloat(item.resultado_total
                                        .replace(',', '.')) || 0;
                                    let totalComercial = parseFloat(item.total_comercial
                                        .replace(',', '.')) || 0;
                                    let totalKilos = parseFloat(item.total_kilos
                                        .replace(',', '.')) || 0;
                                    let costo_comercial = parseFloat(item
                                        .costo_comercial.replace(',', '.')) || 0;

                                    // Sumar solo si la categoría está en el objeto
                                    if (sumasPorCategoria.hasOwnProperty(categoria)) {
                                        sumasPorCategoria[categoria].resultado_kilo +=
                                            resultadoKilo;
                                        sumasPorCategoria[categoria].resultado_total +=
                                            resultadoTotal;
                                        sumasPorCategoria[categoria].total_comercial +=
                                            totalComercial;
                                        sumasPorCategoria[categoria].total_kilos +=
                                            totalKilos;
                                        sumasPorCategoria[categoria].costo_comercial +=
                                            costo_comercial;
                                    }
                                });

                                $.each(sumasPorCategoria, function(categoria, sumas) {

                                });

                                valorTotal = parseFloat(sumasPorCategoria['CAT1']
                                        .resultado_total) +
                                    parseFloat(sumasPorCategoria['CATII'].resultado_total);
                                $("#valorTotalUsd").text(valorTotal.toLocaleString('es-CL', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));
                                valorNoExportable = parseFloat(sumasPorCategoria['Merma']
                                        .costo_comercial) +
                                    parseFloat(sumasPorCategoria['Desecho'].costo_comercial) +
                                    parseFloat(
                                        sumasPorCategoria['Precalibre'].costo_comercial) +
                                    parseFloat(
                                        sumasPorCategoria['Comercial'].costo_comercial);
                                $("#valorNoExportable").text(valorNoExportable.toLocaleString(
                                    'es-CL', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }));
                                kilosNoExportable = parseFloat(sumasPorCategoria['Merma']
                                        .total_kilos) +
                                    parseFloat(sumasPorCategoria['Desecho'].total_kilos) +
                                    parseFloat(
                                        sumasPorCategoria['Precalibre'].total_kilos) +
                                    parseFloat(
                                        sumasPorCategoria['Comercial'].total_kilos);
                                $("#kilosNoExportable").text(kilosNoExportable.toLocaleString(
                                    'es-CL', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }));
                                $("#suma-CAT-1").text(sumasPorCategoria['CAT1'].resultado_total
                                    .toLocaleString('es-CL', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }));
                                $("#suma-CATII").text(sumasPorCategoria['CATII'].resultado_total
                                    .toLocaleString('es-CL', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }));



                                //facturación anticipos
                                let valorTotalAnticipos = 0;
                                $.each(response.anticipos, function(index, item) {
                                    let fecha = item.fecha_documento;
                                    let valor = parseFloat(item.valor) ||
                                        0; // Convertir a número, manejando coma decimal
                                    valorTotalAnticipos += valor;

                                    $("#anticipos").append(
                                        `<tr>
                                                    <td colspan="7" style="text-align: right;">${fecha}</td>
                                                    <td class="currency">${valor.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                                                </tr>`
                                    );
                                });
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


                                // $("#fletehuerto").text((response.valorflete.valor ? response
                                //     .valorflete.valor : 0).toLocaleString(
                                //     'es-CL', {
                                //         minimumFractionDigits: 2,
                                //         maximumFractionDigits: 2
                                //     }));
                                valorflete = 0;
                                if (response.valorflete.length > 0) {
                                    response.valorflete.forEach(element => {
                                        valorflete += parseFloat(element.valor);
                                    });
                                    $("#fletehuerto").text(valorflete ? valorflete : 0)
                                        .toLocaleString(
                                            'es-CL', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
                                    $("#trBonificacionfletehuerto").show();
                                    bonificacion = response.valorflete.condicion * response
                                        .valorflete.valor * (-1);

                                    $("#bonificacionfletehuerto").text(bonificacion ?
                                        bonificacion : 0).toLocaleString(
                                        'es-CL', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });

                                } else {
                                    $("#trBonificacionfletehuerto").hide();
                                }






                                $("#ctacteenvases").text(response.envases.valor ? response
                                    .envases.valor : 0).toLocaleString(
                                    'es-CL', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                totalOtrosCargos = parseFloat(response.valorflete.valor ?
                                        response.valorflete.valor : 0) +
                                    parseFloat(response.envases.valor ? response.envases.valor :
                                        0);
                                $("#totalOtrosCargos").text(totalOtrosCargos.toLocaleString(
                                    'es-CL', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }));
                                SaldoTotal = parseFloat(valorTotal) - parseFloat(
                                    valorTotalAnticipos) - parseFloat(totalOtrosCargos);
                                $("#SaldoTotal").text(SaldoTotal.toLocaleString('es-CL', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));
                                NDValorNeto = parseFloat(SaldoTotal) * 936.74;
                                $("#NDVAlorNeto").text(NDValorNeto.toLocaleString('es-CL', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));
                                NDValorIva = NDValorNeto * 0.19;
                                $("#NDVAlorIva").text(NDValorIva.toLocaleString('es-CL', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));
                                NDValorTotal = NDValorNeto + NDValorIva;
                                $("#NDVAlorTotal").text(NDValorTotal.toLocaleString('es-CL', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));
                                FacturaValorNeto = sumasPorCategoria['Comercial']
                                    .resultado_total;
                                $("#FVAlorNeto").text(FacturaValorNeto.toLocaleString('es-CL', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));
                                FVAlorIva = sumasPorCategoria['Comercial'].resultado_total *
                                    0.19;
                                $("#FVAlorIva").text(FVAlorIva.toLocaleString('es-CL', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));
                                FValorTotal = FacturaValorNeto + FVAlorIva;
                                $("#FVAlorTotal").text(FValorTotal.toLocaleString('es-CL', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
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
                                let totalKilos = parseFloat(item.total_kilos.replace(
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
                                        cajas_equivalentes: 0
                                    };
                                }

                                // Buscar si la norma ya existe
                                let normaExistente = datosAgrupados[especie][variedad][
                                        categoria
                                    ]
                                    .normas.find(n => n.norma === norma);
                                if (normaExistente) {
                                    normaExistente.total_kilos += totalKilos;
                                } else {
                                    datosAgrupados[especie][variedad][categoria].normas
                                        .push({
                                            norma: norma,
                                            total_kilos: totalKilos
                                        });
                                }

                                // Acumular totales por categoría
                                datosAgrupados[especie][variedad][categoria]
                                    .total_kilos +=
                                    totalKilos;
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
                        <th>Cajas Equivalentes</th>
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
                                variedadesNorma = Object.keys(datosAgrupados[especie])
                                    .sort();
                                if (variedadesNorma.length === 0) return;


                                $.each(variedadesNorma, function(index, variedad) {
                                    let totalVariedad = {
                                        cajas_equivalentes: 0,
                                        total_kilos: 0
                                    };
                                    let categorias = Object.keys(datosAgrupados[
                                            especie][variedad])
                                        .sort();

                                    // Iterar sobre cada categoría
                                    $.each(categorias, function(i, categoria) {
                                        let datosCategoria =
                                            datosAgrupados[especie][
                                                variedad
                                            ][categoria];
                                        let isFirstRow = true;

                                        // Ordenar normas
                                        datosCategoria.normas.sort((a,
                                                b) => a
                                            .norma < b.norma ? -1 :
                                            1);

                                        // Generar filas para cada norma
                                        $.each(datosCategoria.normas,
                                            function(j,
                                                fila) {
                                                let cajasEquivalentes =
                                                    (fila
                                                        .total_kilos /
                                                        9)
                                                    .toFixed(1);
                                                let variedadCell = (
                                                        i === 0 &&
                                                        j === 0) ?
                                                    variedad :
                                                    ' ';
                                                let especieCell = (
                                                        i === 0 &&
                                                        j === 0) ?
                                                    especie :
                                                    ' ';
                                                let categoriaCell =
                                                    isFirstRow ?
                                                    categoria : ' ';

                                                htmlOutput += `
                        <tr>
                            <td>${especie}</td>
                            <td>${variedadCell}</td>
                            <td>${categoriaCell}</td>
                            <td>${fila.norma || ' '}</td>
                            <td class="number">${cajasEquivalentes}</td>
                            <td class="number">${fila.total_kilos.toFixed(2)}</td>
                        </tr>
                    `;
                                                isFirstRow = false;

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
                    <td class="number">${totalVariedad.cajas_equivalentes.toFixed(1)}</td>
                    <td class="number">${totalVariedad.total_kilos.toFixed(2)}</td>
                </tr>
            `;

                                // Acumular al total general
                                totalGeneral.cajas_equivalentes += totalVariedad
                                    .cajas_equivalentes;
                                totalGeneral.total_kilos += totalVariedad.total_kilos;
                            });
                             });

                            // Fila de total general
                            htmlOutput += `
            <tr class="total-row">
                <td>Total general</td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td class="number">${totalGeneral.cajas_equivalentes.toFixed(1)}</td>
                <td class="number">${totalGeneral.total_kilos.toFixed(2)}</td>
            </tr>
        `;



                            // Insertar el HTML en el contenedor
                            $('#bce-masas').html(htmlOutput);





                            //norma con semana
                            const ordenCalibres_v2 = ['7J', '6J', '5J', '4J', '3J', '2J', 'J',
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
                                if (item_v2.norma.toUpperCase() === 'CAT 1' || item_v2
                                    .norma.toUpperCase() === 'CAT 1') {
                                    let variedad_v2 = item_v2.variedad;
                                    let etiqueta_v2 = item_v2.etiqueta;
                                    let semana_v2 = item_v2.eta_week.toString();
                                    let calibre_v2 = item_v2.calibre;
                                    let color_v2 = item_v2.color || '';
                                    let totalKilos_v2 = parseFloat(item_v2.total_kilos
                                        .replace(',', '.')) || 0;
                                    let rnpTotal_v2 = parseFloat(item_v2.resultado_total
                                        .replace(',', '.')) || 0;
                                    let rnpKilo_v2 = parseFloat(item_v2.resultado_kilo
                                        .replace(',', '.')) || 0;

                                    if (!datosAgrupados_v2[variedad_v2])
                                        datosAgrupados_v2[
                                            variedad_v2] = {};
                                    if (!datosAgrupados_v2[variedad_v2][etiqueta_v2])
                                        datosAgrupados_v2[variedad_v2][
                                            etiqueta_v2
                                        ] = {};
                                    if (!datosAgrupados_v2[variedad_v2][etiqueta_v2][
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
                                    if (!datosAgrupados_v2[variedad_v2][etiqueta_v2][
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
                                        .calibres[calibre_v2].rnp_total += rnpTotal_v2;
                                    datosAgrupados_v2[variedad_v2][etiqueta_v2][
                                            semana_v2
                                        ]
                                        .calibres[calibre_v2].rnp_kilo += rnpKilo_v2;
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
                        <th style="text-align:center;">Cajas Equivalentes</th>
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
                                        let calibres_v2 = Object.keys(
                                            datosAgrupados_v2[
                                                variedad_v2][
                                                etiqueta_v2
                                            ][semana_v2].calibres);
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
                                        let calibres_v2 = Object.keys(
                                            datosAgrupados_v2[
                                                variedad_v2][
                                                etiqueta_v2
                                            ][semana_v2].calibres);
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
                                        let calibres_v2 = Object.keys(
                                                datosSemana_v2.calibres)
                                            .sort((a, b) =>
                                                ordenCalibres_v2
                                                .indexOf(a) -
                                                ordenCalibres_v2
                                                .indexOf(b));
                                        let rowspanSemana_v2 =
                                            calibres_v2.length;

                                        let isFirstSemanaRow_v2 = true;

                                        // Generar filas para cada calibre
                                        $.each(calibres_v2, function(
                                            k_v2, calibre_v2) {
                                            let datosCalibre_v2 =
                                                datosSemana_v2
                                                .calibres[
                                                    calibre_v2];
                                            let curvaCalibre_v2 =
                                                datosSemana_v2
                                                .total_kilos ? (
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
                                                    5).toFixed(
                                                    0);
                                            let rnpClass_v2 =
                                                datosCalibre_v2
                                                .rnp_total <
                                                0 ||
                                                datosCalibre_v2
                                                .rnp_kilo < 0 ?
                                                'negative' : '';
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
                                <td class="number">${curvaCalibre_v2}</td>
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
                                            datosSemana_v2.rnp_total <
                                            0 || parseFloat(
                                                rnpKiloSemana_v2) < 0 ?
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
                                    let rnpKiloEtiqueta_v2 = totalEtiqueta_v2
                                        .rnp_kilo_kilos ? (totalEtiqueta_v2
                                            .rnp_kilo_sum / totalEtiqueta_v2
                                            .rnp_kilo_kilos).toFixed(4) :
                                        '0.0000';
                                    totalEtiqueta_v2.cajas_equivalentes = (
                                            totalEtiqueta_v2.total_kilos / 9)
                                        .toFixed(0);
                                    let rnpClassEtiqueta_v2 = totalEtiqueta_v2
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
                                            totalEtiqueta_v2.cajas_equivalentes
                                        );
                                    totalVariedad_v2[variedad_v2].total_kilos +=
                                        totalEtiqueta_v2.total_kilos;
                                    totalVariedad_v2[variedad_v2].rnp_total +=
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
                                let rnpKiloVariedad_v2 = totalVariedad_v2[variedad_v2]
                                    .rnp_kilo_kilos ? (totalVariedad_v2[variedad_v2]
                                        .rnp_kilo_sum / totalVariedad_v2[variedad_v2]
                                        .rnp_kilo_kilos).toFixed(4) : '0.0000';
                                let rnpClassVariedad_v2 = totalVariedad_v2[variedad_v2]
                                    .rnp_total < 0 || parseFloat(rnpKiloVariedad_v2) <
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
                                totalGeneral_v2.cajas_equivalentes += totalVariedad_v2[
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
                                    let totalKilos_fn = parseFloat(item_fn.total_kilos
                                        .replace(',', '.')) || 0;
                                    let rnpTotal_fn = parseFloat(item_fn.resultado_total
                                        .replace(',', '.')) || 0;
                                    let rnpKilo_fn = parseFloat(item_fn.resultado_kilo
                                        .replace(',', '.')) || 0;

                                    if (!datosAgrupados_fn[variedad_fn])
                                        datosAgrupados_fn[variedad_fn] = {};
                                    if (!datosAgrupados_fn[variedad_fn][etiqueta_fn])
                                        datosAgrupados_fn[variedad_fn][
                                            etiqueta_fn
                                        ] = {};
                                    if (!datosAgrupados_fn[variedad_fn][etiqueta_fn][
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
                                    if (!datosAgrupados_fn[variedad_fn][etiqueta_fn][
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
                                    ].calibres[calibre_fn].rnp_total += rnpTotal_fn;
                                    datosAgrupados_fn[variedad_fn][etiqueta_fn][
                                        semana_fn
                                    ].calibres[calibre_fn].rnp_kilo += rnpKilo_fn;
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
                            $.each(datosAgrupados_fn, function(variedad_fn, datosVariedad_fn) {
                                let etiquetasUnicas_fn = Object.keys(datosVariedad_fn);
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
                <th style="text-align:center;">Cajas Equivalentes</th>
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
                                        let calibres_fn = Object.keys(
                                            datosAgrupados_fn[
                                                variedad_fn][
                                                etiqueta_fn
                                            ][semana_fn].calibres);
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
                                        let calibres_fn = Object.keys(
                                            datosAgrupados_fn[
                                                variedad_fn][
                                                etiqueta_fn
                                            ][semana_fn].calibres);
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
                                        let calibres_fn = Object.keys(
                                                datosSemana_fn.calibres)
                                            .sort((a, b) =>
                                                ordenCalibres_fn
                                                .indexOf(a) -
                                                ordenCalibres_fn
                                                .indexOf(b));
                                        let rowspanSemana_fn =
                                            calibres_fn.length;

                                        let isFirstSemanaRow_fn = true;

                                        // Generar filas para cada calibre
                                        $.each(calibres_fn, function(
                                            k_fn, calibre_fn) {
                                            let datosCalibre_fn =
                                                datosSemana_fn
                                                .calibres[
                                                    calibre_fn];
                                            let cajasEquivalentes_fn =
                                                (datosCalibre_fn
                                                    .total_kilos /
                                                    5).toFixed(
                                                    0);
                                            let rnpClass_fn =
                                                datosCalibre_fn
                                                .rnp_total <
                                                0 ||
                                                datosCalibre_fn
                                                .rnp_kilo < 0 ?
                                                'negative' : '';
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
                                            datosSemana_fn.rnp_total <
                                            0 || parseFloat(
                                                rnpKiloSemana_fn) < 0 ?
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
                                    let rnpKiloEtiqueta_fn = totalEtiqueta_fn
                                        .rnp_kilo_kilos ? (totalEtiqueta_fn
                                            .rnp_kilo_sum / totalEtiqueta_fn
                                            .rnp_kilo_kilos).toFixed(4) :
                                        '0.0000';
                                    totalEtiqueta_fn.cajas_equivalentes = (
                                            totalEtiqueta_fn.total_kilos / 9)
                                        .toFixed(0);
                                    let rnpClassEtiqueta_fn = totalEtiqueta_fn
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
                                            totalEtiqueta_fn.cajas_equivalentes
                                        );
                                    totalVariedad_fn[variedad_fn].total_kilos +=
                                        totalEtiqueta_fn.total_kilos;
                                    totalVariedad_fn[variedad_fn].rnp_total +=
                                        totalEtiqueta_fn.rnp_total;
                                    totalVariedad_fn[variedad_fn]
                                        .rnp_kilo_sum += totalEtiqueta_fn
                                        .rnp_kilo_sum;
                                    totalVariedad_fn[variedad_fn]
                                        .rnp_kilo_kilos += totalEtiqueta_fn
                                        .rnp_kilo_kilos;
                                });

                                // Total por variedad
                                let rnpKiloVariedad_fn = totalVariedad_fn[variedad_fn]
                                    .rnp_kilo_kilos ? (totalVariedad_fn[variedad_fn]
                                        .rnp_kilo_sum / totalVariedad_fn[variedad_fn]
                                        .rnp_kilo_kilos).toFixed(4) : '0.0000';
                                let rnpClassVariedad_fn = totalVariedad_fn[variedad_fn]
                                    .rnp_total < 0 || parseFloat(rnpKiloVariedad_fn) <
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
                                totalGeneral_fn.cajas_equivalentes += totalVariedad_fn[
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


                            //Comercial
                            const categoriasPermitidas_cat = ['Comercial', 'Pre Calibre',
                                'Desecho', 'Merma'
                            ];

                            // Objeto para agrupar por variedad y categoría
                            let datosAgrupados_cat = {};
                            let totalGeneral_cat = {
                                total_kilos: 0,
                                precio_total: 0,
                                precio_kilo_sum: 0,
                                precio_kilo_kilos: 0
                            };
                            let etiquetasPorVariedad_cat = {};

                            // Agrupar datos
                            let data_cat = response.result;
                            $.each(data_cat, function(index_cat, item_cat) {
                                let categoria_cat = item_cat.categoria;
                                if (categoriasPermitidas_cat.includes(categoria_cat)) {
                                    let variedad_cat = item_cat.variedad;
                                    let etiqueta_cat = item_cat.etiqueta ||
                                    ''; // Para contar etiquetas
                                    let totalKilos_cat = parseFloat(item_cat.total_kilos
                                        .replace(',', '.')) || 0;
                                    let precioTotal_cat = parseFloat(item_cat
                                        .total_comercial.replace(',', '.')) || 0;
                                    let precioKilo_cat = parseFloat(item_cat
                                        .precio_comercial.replace(',', '.')) || 0;

                                    // Inicializar estructuras
                                    if (!datosAgrupados_cat[variedad_cat]) {
                                        datosAgrupados_cat[variedad_cat] = {};
                                        etiquetasPorVariedad_cat[variedad_cat] =
                                            new Set();
                                    }
                                    if (!datosAgrupados_cat[variedad_cat][
                                        categoria_cat]) {
                                        datosAgrupados_cat[variedad_cat][
                                            categoria_cat] = {
                                                total_kilos: 0,
                                                precio_total: 0,
                                                precio_kilo_sum: 0,
                                                precio_kilo_kilos: 0
                                            };
                                    }
                                    if (etiqueta_cat) {
                                        etiquetasPorVariedad_cat[variedad_cat].add(
                                            etiqueta_cat);
                                    }

                                    // Acumular valores
                                    datosAgrupados_cat[variedad_cat][categoria_cat]
                                        .total_kilos += totalKilos_cat;
                                    datosAgrupados_cat[variedad_cat][categoria_cat]
                                        .precio_total += precioTotal_cat;
                                    datosAgrupados_cat[variedad_cat][categoria_cat]
                                        .precio_kilo_sum += precioKilo_cat *
                                        totalKilos_cat;
                                    datosAgrupados_cat[variedad_cat][categoria_cat]
                                        .precio_kilo_kilos += totalKilos_cat;

                                    // Acumular total general
                                    totalGeneral_cat.total_kilos += totalKilos_cat;
                                    totalGeneral_cat.precio_total += precioTotal_cat;
                                    totalGeneral_cat.precio_kilo_sum += precioKilo_cat *
                                        totalKilos_cat;
                                    totalGeneral_cat.precio_kilo_kilos +=
                                    totalKilos_cat;
                                }
                            });

                            // Convertir Sets a arrays y contar etiquetas
                            $.each(etiquetasPorVariedad_cat, function(variedad_cat,
                                etiquetasSet_cat) {
                                etiquetasPorVariedad_cat[variedad_cat] = {
                                    numeroEtiquetas: etiquetasSet_cat.size,
                                    etiquetas: Array.from(etiquetasSet_cat)
                                };
                            });
                            console.log('Etiquetas distintas por variedad (Categorías):',
                                etiquetasPorVariedad_cat);

                            // Generar HTML de la tabla
                            let htmlOutput_cat = `

                    <tr class="section-header">
                        <th style="text-align: center">Variedad</th>
                        <th style="text-align: center">Categoría</th>
                        <th style="text-align: center">Kilos</th>
                        <th style="text-align: center">Precio Comercial Total</th>
                        <th style="text-align: center">Precio Comercial Kilo</th>
                    </tr>


        `;

                            // Ordenar variedades
                            let variedades_cat = Object.keys(datosAgrupados_cat).sort();
                            let totalVariedad_cat = {};

                            $.each(variedades_cat, function(index_cat, variedad_cat) {
                                totalVariedad_cat[variedad_cat] = {
                                    total_kilos: 0,
                                    precio_total: 0,
                                    precio_kilo_sum: 0,
                                    precio_kilo_kilos: 0
                                };
                                let categorias_cat = Object.keys(datosAgrupados_cat[
                                    variedad_cat]).sort();
                                let rowspanVariedad_cat = categorias_cat.length;

                                let isFirstVariedadRow_cat = true;

                                // Iterar sobre categorías
                                $.each(categorias_cat, function(i_cat, categoria_cat) {
                                    let datosCategoria_cat = datosAgrupados_cat[
                                        variedad_cat][categoria_cat];
                                    let precioKilo_cat = datosCategoria_cat
                                        .precio_kilo_kilos ? (datosCategoria_cat
                                            .precio_kilo_sum /
                                            datosCategoria_cat.precio_kilo_kilos
                                            ).toFixed(2) : '0.00';
                                    let variedadCell_cat =
                                        isFirstVariedadRow_cat ?
                                        `<td rowspan="${rowspanVariedad_cat}">${variedad_cat}</td>` :
                                        '';

                                    htmlOutput_cat += `
                    <tr>
                        ${variedadCell_cat}
                        <td style="text-align: center">${categoria_cat}</td>
                        <td class="number">${datosCategoria_cat.total_kilos.toFixed(2)}</td>
                        <td class="number">${datosCategoria_cat.precio_total.toFixed(2)}</td>
                        <td class="number">${precioKilo_cat}</td>
                    </tr>
                `;

                                    isFirstVariedadRow_cat = false;

                                    // Acumular totales por variedad
                                    totalVariedad_cat[variedad_cat]
                                        .total_kilos += datosCategoria_cat
                                        .total_kilos;
                                    totalVariedad_cat[variedad_cat]
                                        .precio_total += datosCategoria_cat
                                        .precio_total;
                                    totalVariedad_cat[variedad_cat]
                                        .precio_kilo_sum += datosCategoria_cat
                                        .precio_kilo_sum;
                                    totalVariedad_cat[variedad_cat]
                                        .precio_kilo_kilos += datosCategoria_cat
                                        .precio_kilo_kilos;
                                });

                                // Total por variedad
                                let precioKiloVariedad_cat = totalVariedad_cat[
                                        variedad_cat].precio_kilo_kilos ? (
                                        totalVariedad_cat[variedad_cat]
                                        .precio_kilo_sum / totalVariedad_cat[
                                            variedad_cat].precio_kilo_kilos).toFixed(
                                    2) : '0.00';
                                htmlOutput_cat += `
                <tr class="total-row">
                    <td>Total ${variedad_cat}</td>
                    <td> </td>
                    <td class="number">${totalVariedad_cat[variedad_cat].total_kilos.toFixed(0)}</td>
                    <td class="number">${totalVariedad_cat[variedad_cat].precio_total.toFixed(2)}</td>
                    <td class="number">${precioKiloVariedad_cat}</td>
                </tr>
            `;
                            });

                            // Total general
                            let precioKiloGeneral_cat = totalGeneral_cat.precio_kilo_kilos ? (
                                totalGeneral_cat.precio_kilo_sum / totalGeneral_cat
                                .precio_kilo_kilos).toFixed(2) : '0.00';
                            htmlOutput_cat += `
            <tr class="total-row">
                <td>Total general</td>
                <td> </td>
                <td class="number">${totalGeneral_cat.total_kilos.toFixed(0)}</td>
                <td class="number">${totalGeneral_cat.precio_total.toFixed(2)}</td>
                <td class="number">${precioKiloGeneral_cat}</td>
            </tr>
        `;



                            // Insertar el HTML en el contenedor
                            $('#comercial').html(htmlOutput_cat);
                            //Comercial
                        llenarNorma(response);

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
            function llenarNorma(response) {
    // Definir orden de calibres
    const ordenCalibres = ['7J', '6J', '5J', '4J', '3J', '2J', 'J', 'XL'];

    // Objeto para agrupar por especie, variedad y etiqueta
    let datosAgrupadosNorma = {};
    let totalGeneralNorma = {
        cajas_equivalentes: 0,
        total_kilos: 0,
        rnp_total: 0,
        rnp_kilo_sum: 0,
        rnp_kilo_kilos: 0
    };

    // Agrupar datos por especie, variedad y etiqueta
    $.each(response.result, function(index, item) {
        if (item.categoria.toUpperCase() === 'CAT 1') {
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
                    rnp_kilo_kilos: 0
                };
            }
            if (!datosAgrupadosNorma[especie][variedad][etiqueta].calibres[calibre]) {
                datosAgrupadosNorma[especie][variedad][etiqueta].calibres[calibre] = {
                    total_kilos: 0,
                    rnp_total: 0,
                    rnp_kilo: 0
                };
            }

            datosAgrupadosNorma[especie][variedad][etiqueta].calibres[calibre].total_kilos += totalKilos;
            datosAgrupadosNorma[especie][variedad][etiqueta].calibres[calibre].rnp_total += rnpTotal;
            datosAgrupadosNorma[especie][variedad][etiqueta].calibres[calibre].rnp_kilo += rnpKilo;

            datosAgrupadosNorma[especie][variedad][etiqueta].total_kilos += totalKilos;
            datosAgrupadosNorma[especie][variedad][etiqueta].rnp_total += rnpTotal;
            datosAgrupadosNorma[especie][variedad][etiqueta].rnp_kilo_sum += rnpKilo * totalKilos;
            datosAgrupadosNorma[especie][variedad][etiqueta].rnp_kilo_kilos += totalKilos;
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
                    <th>Curva Calibre</th>
                    <th>Cajas Equivalentes</th>
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
            let etiquetas = Object.keys(datosAgrupadosNorma[especie][variedad]).sort();
            let rowspanVariedad = 0;

            // Calcular rowspan total por variedad
            $.each(etiquetas, function(i_etiqueta, etiqueta) {
                let calibres = Object.keys(datosAgrupadosNorma[especie][variedad][etiqueta].calibres);
                rowspanVariedad += calibres.length;
            });

            let isFirstVariedadRow = true;
            let totalVariedad = {
                cajas_equivalentes: 0,
                total_kilos: 0,
                rnp_total: 0,
                rnp_kilo_sum: 0,
                rnp_kilo_kilos: 0
            };

            $.each(etiquetas, function(i_etiqueta, etiqueta) {
                let datosEtiqueta = datosAgrupadosNorma[especie][variedad][etiqueta];
                let calibres = Object.keys(datosEtiqueta.calibres).sort((a, b) =>
                    ordenCalibres.indexOf(a) - ordenCalibres.indexOf(b)
                );
                let rowspanEtiqueta = calibres.length;
                let isFirstEtiquetaRow = true;

                $.each(calibres, function(i_calibre, calibre) {
                    let datosCalibre = datosEtiqueta.calibres[calibre];

                    let curvaCalibre = datosEtiqueta.total_kilos ?
                        (datosCalibre.total_kilos / datosEtiqueta.total_kilos).toFixed(4) : '0.0000';
                    let cajasEquivalentes = (datosCalibre.total_kilos / 9).toFixed(0);

                    let rnpKilo = datosCalibre.rnp_kilo.toFixed(4);
                    let rnpClass = datosCalibre.rnp_total < 0 || datosCalibre.rnp_kilo < 0 ? 'negative' : '';

                    let especieCell = i_variedad === 0 && i_etiqueta === 0 && i_calibre === 0 ?
                        `<td>${especie}</td>` : '<td></td>';
                    let variedadCell = isFirstEtiquetaRow && i_calibre === 0 ?
                        `<td>${variedad}</td>` : '<td></td>';
                    let etiquetaCell = isFirstEtiquetaRow && i_calibre === 0 ?
                        `<td>${etiqueta}</td>` : '<td></td>';

                    htmlOutput += `
                        <tr>
                            ${especieCell}
                            ${variedadCell}
                            ${etiquetaCell}
                            <td>${calibre}</td>
                            <td class="number">${curvaCalibre}</td>
                            <td class="number">${cajasEquivalentes}</td>
                            <td class="number">${datosCalibre.total_kilos.toFixed(2)}</td>
                            <td class="number ${rnpClass}">${datosCalibre.rnp_total.toFixed(2)}</td>
                            <td class="number ${rnpClass}">${rnpKilo}</td>
                        </tr>
                    `;

                    isFirstEtiquetaRow = false;
                    isFirstVariedadRow = false;

                    // Acumular totales
                    totalVariedad.cajas_equivalentes += parseFloat(cajasEquivalentes);
                    totalVariedad.total_kilos += datosCalibre.total_kilos;
                    totalVariedad.rnp_total += datosCalibre.rnp_total;
                    totalVariedad.rnp_kilo_sum += datosCalibre.rnp_kilo * datosCalibre.total_kilos;
                    totalVariedad.rnp_kilo_kilos += datosCalibre.total_kilos;
                });

                // Fila de total por etiqueta
                let rnpKiloEtiqueta = datosEtiqueta.rnp_kilo_kilos ?
                    (datosEtiqueta.rnp_kilo_sum / datosEtiqueta.rnp_kilo_kilos).toFixed(4) : '0.0000';
                let cajasEtiqueta = (datosEtiqueta.total_kilos / 9).toFixed(0);
                let rnpClassEtiqueta = datosEtiqueta.rnp_total < 0 || parseFloat(rnpKiloEtiqueta) < 0 ? 'negative' : '';

                htmlOutput += `
                    <tr class="total-row">
                        <td></td>
                        <td colspan="2">Total ${etiqueta}</td>
                        <td></td>
                        <td class="number">1.0000</td>
                        <td class="number">${cajasEtiqueta}</td>
                        <td class="number">${datosEtiqueta.total_kilos.toFixed(2)}</td>
                        <td class="number ${rnpClassEtiqueta}">${datosEtiqueta.rnp_total.toFixed(2)}</td>
                        <td class="number ${rnpClassEtiqueta}">${rnpKiloEtiqueta}</td>
                    </tr>
                `;
            });

            // Fila de total por variedad
            let rnpKiloVariedad = totalVariedad.rnp_kilo_kilos ?
                (totalVariedad.rnp_kilo_sum / totalVariedad.rnp_kilo_kilos).toFixed(4) : '0.0000';
            let rnpClassVariedad = totalVariedad.rnp_total < 0 || parseFloat(rnpKiloVariedad) < 0 ? 'negative' : '';

            htmlOutput += `
                <tr class="total-row">
                    <td>Total ${variedad}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="number">1.0000</td>
                    <td class="number">${totalVariedad.cajas_equivalentes.toFixed(0)}</td>
                    <td class="number">${totalVariedad.total_kilos.toFixed(2)}</td>
                    <td class="number ${rnpClassVariedad}">${totalVariedad.rnp_total.toFixed(2)}</td>
                    <td class="number ${rnpClassVariedad}">${rnpKiloVariedad}</td>
                </tr>
            `;

            // Acumular al total general
            totalGeneralNorma.cajas_equivalentes += totalVariedad.cajas_equivalentes;
            totalGeneralNorma.total_kilos += totalVariedad.total_kilos;
            totalGeneralNorma.rnp_total += totalVariedad.rnp_total;
            totalGeneralNorma.rnp_kilo_sum += totalVariedad.rnp_kilo_sum;
            totalGeneralNorma.rnp_kilo_kilos += totalVariedad.rnp_kilo_kilos;
        });
    });

    // Fila de total general
    let rnpKiloGeneral = totalGeneralNorma.rnp_kilo_kilos ?
        (totalGeneralNorma.rnp_kilo_sum / totalGeneralNorma.rnp_kilo_kilos).toFixed(4) : '0.0000';
    let rnpClassGeneral = totalGeneralNorma.rnp_total < 0 || parseFloat(rnpKiloGeneral) < 0 ? 'negative' : '';

    htmlOutput += `
                <tr class="total-row">
                    <td>Total General</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="number">1.0000</td>
                    <td class="number">${totalGeneralNorma.cajas_equivalentes.toFixed(0)}</td>
                    <td class="number">${totalGeneralNorma.total_kilos.toFixed(2)}</td>
                    <td class="number ${rnpClassGeneral}">${totalGeneralNorma.rnp_total.toFixed(2)}</td>
                    <td class="number ${rnpClassGeneral}">${rnpKiloGeneral}</td>
                </tr>
            </tbody>
        </table>
    `;

    $('#norma').html(htmlOutput); // Insertar en contenedor
}

        });
    </script>
@endsection
