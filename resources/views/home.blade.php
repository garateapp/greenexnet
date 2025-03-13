@extends('layouts.admin')
<style>
    table.pvtTable {
        font-size: 8pt;
        text-align: left;
        border-collapse: collapse;
    }

    table.pvtTable thead tr th,
    table.pvtTable tbody tr th {
        background-color: #e6eeee;
        border: 1px solid #cdcdcd;
        font-size: 8pt;
        padding: 5px;
    }

    table.pvtTable .pvtColLabel {
        text-align: center;
    }

    table.pvtTable .pvtTotalLabel {
        text-align: right;
    }

    table.pvtTable tbody tr td {
        color: #3d3d3d;
        padding: 5px;
        background-color: #fff;
        border: 1px solid #cdcdcd;
        vertical-align: top;
        text-align: right;
    }

    .pvtUi {
        color: #333;
    }

    .pvtTotal,
    .pvtGrandTotal {
        font-weight: bold;
    }

    .pvtVals {
        text-align: center;
        white-space: nowrap;
    }

    .pvtRowOrder,
    .pvtColOrder {
        cursor: pointer;
        width: 15px;
        margin-left: 5px;
        display: inline-block;
    }

    .pvtAggregator {
        margin-bottom: 5px;
    }

    .pvtAxisContainer,
    .pvtVals {
        border: 1px solid gray;
        background: #eee;
        padding: 5px;
        min-width: 20px;
        min-height: 20px;

        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -khtml-user-select: none;
        -ms-user-select: none;
    }

    .pvtAxisContainer li {
        padding: 8px 6px;
        list-style-type: none;
        cursor: move;
    }

    .pvtAxisContainer li.pvtPlaceholder {
        -webkit-border-radius: 5px;
        padding: 3px 15px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        border: 1px dashed #aaa;
    }

    .pvtAxisContainer li span.pvtAttr {
        -webkit-text-size-adjust: 100%;
        background: #f3f3f3;
        border: 1px solid #dedede;
        padding: 2px 5px;
        white-space: nowrap;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }

    .pvtTriangle {
        cursor: pointer;
        color: grey;
    }

    .pvtHorizList li {
        display: inline;
    }

    .pvtVertList {
        vertical-align: top;
    }

    .pvtFilteredAttribute {
        font-style: italic;
    }

    .pvtFilterBox {
        z-index: 100;
        width: 300px;
        border: 1px solid gray;
        background-color: #fff;
        position: absolute;
        text-align: center;
    }

    .pvtFilterBox h4 {
        margin: 15px;
    }

    .pvtFilterBox p {
        margin: 10px auto;
    }

    .pvtFilterBox label {
        font-weight: normal;
    }

    .pvtFilterBox input[type="checkbox"] {
        margin-right: 10px;
        margin-left: 10px;
    }

    .pvtFilterBox input[type="text"] {
        width: 230px;
    }

    .pvtFilterBox .count {
        color: gray;
        font-weight: normal;
        margin-left: 3px;
    }

    .pvtCheckContainer {
        text-align: left;
        font-size: 14px;
        white-space: nowrap;
        overflow-y: scroll;
        width: 100%;
        max-height: 250px;
        border-top: 1px solid lightgrey;
        border-bottom: 1px solid lightgrey;
    }

    .pvtCheckContainer p {
        margin: 5px;
    }

    .pvtRendererArea {
        padding: 5px;
    }

    .flip-card {
        perspective: 1000px;
        width: 100%;
        height: 100%;
    }

    .flip-card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.6s;
        transform-style: preserve-3d;
        cursor: pointer;
    }

    .flip-card.flipped .flip-card-inner {
        transform: rotateY(180deg);
    }

    .flip-card-front,
    .flip-card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .flip-card-back {
        background-color: #17a2b8;
        transform: rotateY(180deg);
    }
</style>
@section('content')
    <div class="content">
        <div class="alert alert-success" id="msgOK" style="display:none;">

        </div>

        <div class="alert alert-danger" id="msgKO" style="display:none;">

        </div>
        <div class="row">
            <div class="col-lg-12">
                @can('control_panel')
                    <div class="card">
                        <div class="card-header">
                            Panel de Control
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
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
                                </div>
                            </div>
                        </div>

                    </div>
                @endcan
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                $("#btnActualizaGD").on("click", async function(event) {
                    event.preventDefault(); // Evita recarga si el botón está dentro de un formulario

                    let btn = $(this); // Guardamos referencia al botón
                    btn.prop("disabled", true); // Deshabilitamos el botón

                    $("#msgOK, #msgKO").hide(); // Ocultamos mensajes previos

                    for (const instructivo of InstructivosFXNoProcesadosCompleto) {
                        if (instructivo.id != null) {
                            await actualizaFOB(instructivo.id, instructivo.Numero_Embarque);
                        }
                    }

                    btn.prop("disabled",
                        false); // Volvemos a habilitar el botón al finalizar todas las peticiones
                });

                async function actualizaFOB(id, numero_embarque) {
                    const url = `/admin/liq-cx-cabeceras/actualizarValorGD_Unitario/${id}`;

                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: url,
                            method: "GET",
                            success: function(response) {
                                $("#msgOK").html("Datos embarque " + numero_embarque +
                                    " actualizados !! ").show();
                                $("#msgKO").hide();
                                resolve();
                            },
                            error: function() {
                                $("#msgKO").html("Error al actualizar los datos embarque " +
                                    numero_embarque + "!!").show();
                                $("#msgOK").hide();
                                reject();
                            }
                        });
                    });
                }

                let instructivoxsubir = [];

                let instructivoSubidos = []; // Aseguramos que haya datos
                let instructivosNoCargados = {}; // Convertir en array

                let InstructivosFXNoProcesadosCompleto = {};
                let InstructivosFXProcesadosCompleto = {};

                function obtieneReporteInstructivo() {
                    let url = "{{ route('admin.reporteria.getReporteInstructivos') }}";
                    $.ajax({
                        url: url,
                        method: "GET",
                        success: function(response) {
                            instructivoxsubir = response.InstructivosSinSubir || [];

                            instructivoSubidos = response.Instructivos || []; // Aseguramos que haya datos
                            instructivosNoCargados = Object.values(response.InstructivosSinSubir ||
                            {}); // Convertir en array

                            InstructivosFXNoProcesadosCompleto = Object.values(response
                                .InstructivosFXNoProcesadosCompleto || {});
                            InstructivosFXProcesadosCompleto = Object.values(response
                                .InstructivosFXProcesadosCompleto || {});
                            console.log(InstructivosFXNoProcesadosCompleto);
                            console.log(InstructivosFXProcesadosCompleto);
                            let tbodyNoCargados = $("#tbodyLiquidacionesNoCargadas");
                            let tbody = $("#tbodyLiquidacionesCargadas");
                            let tbodySinFOB = $("#tbodyLiquidacionesSinFOB");
                            let tbodyConFOB = $("#tbodyLiquidacionesCargadasConFOB");
                            tbody.empty(); // Limpiamos antes de agregar nuevos datos
                            tbodyNoCargados.empty();

                            // Contar los instructivos
                            let totalInstructivos = instructivoSubidos.length;
                            let totalInstructivosNoCargados = instructivosNoCargados.length;
                            let totalGeneral = totalInstructivos + totalInstructivosNoCargados;
                            let totalSinFOB = InstructivosFXNoProcesadosCompleto.length;
                            let totalConFOB = InstructivosFXProcesadosCompleto.length;

                            console.log("Total instructivos cargados:", totalInstructivos);
                            console.log("Total instructivos no cargados:", totalInstructivosNoCargados);

                            console.log("Total general:", totalGeneral);

                            instructivoSubidos.forEach(function(instructivo) {
                                let row = `<tr>
                              <td><a target="_blank" href="admin/liq-cx-cabeceras/${instructivo.id}/edit">${instructivo.instructivo}</a></td>
                           </tr>`;
                                tbody.append(row);
                            });




                            // Iterar sobre los instructivos y agregarlos a la tabla
                            instructivosNoCargados.forEach(function(instructivo) {
                                let row = `<tr>
                  <td><a target="_blank" href="admin/liq-cx-cabeceras/${instructivo.id}/edit">${instructivo.Numero_Embarque}</a></td>
               </tr>`;
                                tbodyNoCargados.append(row);
                            });
                            InstructivosFXNoProcesadosCompleto.forEach(function(instructivo) {
                                if (instructivo.id == null) {
                                    let row = `<tr>
                          <td><a target="_blank" href="admin/liq-cx-cabeceras/${instructivo.id}/edit">${instructivo.Numero_Embarque}</a></td>
                    </tr>`;
                                    tbodySinFOB.append(row);
                                }
                            });
                            InstructivosFXProcesadosCompleto.forEach(function(instructivo) {
                                let row = `<tr>
                          <td><a target="_blank" href="admin/liq-cx-cabeceras/${instructivo.id}/edit">${instructivo.Numero_Embarque}</a></td>
                    </tr>`;
                                tbodyConFOB.append(row);
                            });

                            // Actualizar el badge en el header
                            $("#totalInstructivosBadge").text(totalInstructivos);
                            $("#totalInstructivosNoCargadosBadge").text(totalInstructivosNoCargados);
                            $("#totalInstructivosSinFOBsBadge").text(totalSinFOB);
                            $("#totalInstructivosConFOBBadge").text(totalConFOB);



                            instructivoconfoliosmultiples = response.InstructivosConFoliosMultiples
                        },
                        error: function() {
                            instructivoxsubir = 0;
                            instructivoconfoliosmultiples = 0;
                        }
                    });

                }

                obtieneReporteInstructivo();
                // Manejar errores de conexión

            });
        </script>
    </div>
@endsection
@section('scripts')
    @parent
@endsection
