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
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        Capturador de liquidaciones
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form method="POST" action="{{ route('admin.comex.capturadorexcel') }}"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">

                                        <label for="name">Seleccione Plantilla</label>
                                        <select class="form-control select2" id="plantilla" name="plantilla">
                                            <option value="">Seleccione una Plantilla</option>
                                            @foreach ($Capturador as $cx)
                                                <option value="{{ $cx->id }}">{{ $cx->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha">Instructivo</label>
                                        <input type="text" name="instructivo" id="instructivo" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha">Tasa de Cambio</label>
                                        <input type="text" name="tasa" id="tasa" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_arribo">Fecha de Arribo</label>
                                        <input type="text" name="fecha_arribo" id="fecha_arribo"  class="form-control date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_venta">Fecha de Venta</label>
                                        <input type="text" name="fecha_venta" id="fecha_venta"  class="form-control date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_venta">Fecha de Liquidación</label>
                                        <input type="text" name="fecha_liquidacion" id="fecha_liquidacion"  class="form-control date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="fila_costos">Fila en que inician los costos</label>
                                        <input type="number" name="fila_costos" id="fila_costos" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="file">Selecciona archivo</label>
                                        <input type="file" name="file" id="file" required class="form-control">

                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Capturar Liquidación</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        @parent
    @endsection
