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
                                            Liquidaciones
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    Cantidad de Embarques : {{ $total }}<br>
                                                    Cantidad de Liquidaciones Cargadas : {{ $liqs }}<br>
                                                    Cantidad de Liquidaciones Sin Procesar : {{ $total - $liqs }}<br>
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
                                                    <button class="btn btn-secondary" id="btnActualizaGD"
                                                        style="margin-bottom: 5px; width:200px;text-align: left;">

                                                        <i class="fa-fw fas fa-hand-holding-usd" aria-hidden="true"></i>
                                                        Actualizar Valores FOB

                                                    </button>
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
                                                    <button class="btn btn-secondary" id="btn_procesar"
                                                        style="margin-bottom: 5px; width:200px;text-align: left;">
                                                        <a href="{{ route('admin.comex.actualizarValorGD_en_fx') }}"
                                                            style="color:white">
                                                            <i class="fa-fw fas fa-hand-holding-usd" aria-hidden="true"></i>
                                                            Actualizar Valores FOB
                                                        </a>
                                                    </button>
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
          document.addEventListener("DOMContentLoaded", function () {

                $("#btnActualizaGD").on("click", function(event) {
                    event.preventDefault(); // Evita recarga si el botón está dentro de un formulario

                    let btn = $(this); // Guardamos referencia al botón
                    btn.prop("disabled", true); // Deshabilitamos el botón

                    $("#msgOK, #msgKO").hide(); // Ocultamos mensajes previos

                    $.ajax({
                        url: "{{ route('admin.comex.actualizarValorGD_en_fx') }}",
                        method: "GET",
                        success: function(response) {
                            $("#msgOK").html("Datos actualizados correctamente!!").show();
                        },
                        error: function() {
                            $("#msgKO").html("Error al actualizar los datos!!").show();
                        },
                        complete: function() {
                            btn.prop("disabled",
                                false
                                ); // Volvemos a habilitar el botón al finalizar la petición
                        }
                    });

                });


                // Manejar errores de conexión

            });
        </script>
    </div>
@endsection
@section('scripts')
    @parent
@endsection
