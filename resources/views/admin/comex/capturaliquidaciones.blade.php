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

                        Previsualización de Liquidación Instructivo - {{ $instructivo }}

                    </div>

                    <div class="card-body">
                        <p>Fecha Arribo: {{ $datosExcel->fecha_arribo }}</p>
                            <p>Fecha Venta: {{ $datosExcel->fecha_venta  }}</p>
                            <p>Fecha Liquidación:{{ $datosExcel->fecha_liquidacion }}</p>

                        <!-- Sección de Cabecera -->

                        <table border="1" cellpadding="5" cellspacing="0">
                            <thead>
                                <tr>
                                    @foreach ($cabecera as $item)

                                        <th>{{ $item['propiedad'] }}</th>

                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                @foreach ($cabecera as $item)

                                        <td>{{ $item['valor'] }}</td>

                                @endforeach
                            </tr>
                            </tbody>
                        </table>
                        <hr/>
                        <!-- Sección de Items -->

                        <div style="overflow-x: auto;">
                            <table border="1" cellpadding="5" cellspacing="0"
                                style="border-collapse: collapse; text-align: center;">

                                <thead>
                                    <tr>
                                        @php

                                            $headers = [];
                                            $head = '';

                                            if (count($items) > 0) {
                                                foreach ($items as $header) {
                                                    foreach ($header as $th) {
                                                        if ($th['propiedad'] != $head) {
                                                            $headers[] = $th['propiedad'];
                                                            $head = $th['propiedad'];
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp

                                        @foreach ($headers as $header)
                                            <th>{{ $header }}</th>
                                        @endforeach
                                        <th>Total RMB</th>
                                        <th>Total USD</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @php

                                    preg_match('/(\D+)/',$items[0][0]['coordenada'], $matches);
                                    $letraInicial=$matches[1];
                                    $UltimaLetra= $matches[1];

                                    $tabla = [];
                                    $cantidades = [];
                                    $precios = [];
                                    $totalGeneral = 0;
                                    $totalesColumna = [];

                                    // Procesar datos de los items

                                    foreach ($items as $fila) {
                                        foreach ($fila as $columna) {
                                            $coordenada = $columna['coordenada'];
                                            $valor = $columna['valor'];
                                            $propiedad = $columna['propiedad'];

                                            $col = preg_replace('/[0-9]/', '', $coordenada); // Extraer letras (columna)
                                            $row = preg_replace('/[A-Z]/', '', $coordenada); // Extraer números (fila)

                                            $tabla[$row][$col][$propiedad] = $valor;
                                            // if($row==39 && $col=='K'){
                                            //     dd($tabla);
                                            // }
                                            // Determinar la última letra
                                            if (strcmp($col, $UltimaLetra) > 0) {
                                                $UltimaLetra = $col;
                                            }

                                            // Almacenar cantidades y precios por fila
                                            if ($propiedad === 'Cantidad') {
                                                $cantidades[$row] = $valor;
                                            } elseif ($propiedad === 'Precio Unitario') {
                                                $precios[$row] = $valor;
                                            }

                                            // Calcular totales por columna
                                            if (!isset($totalesColumna[$col])) {
                                                $totalesColumna[$col] = 0;
                                            }
                                            $totalesColumna[$col] += (float)$valor;

                                        }
                                    }

                                @endphp

                                {{-- Mostrar filas y calcular totales --}}

                                @foreach ($tabla as $row => $columnas)
                                    @php
                                        $totalFila = 0;
                                        if (isset($cantidades[$row]) && isset($precios[$row])) {
                                            (float)$totalFila = (float)$cantidades[$row] * (float)$precios[$row];
                                            (float)$totalGeneral += (float)$totalFila;
                                        }
                                    @endphp
                                    <tr>

                                        @foreach ($columnas as $col => $valores) {{-- Solo procesa columnas presentes en $columnas --}}
                                        <td>
                                            @foreach ($valores as $propiedad => $valor)




                                                {{ $valor }}

                                            @endforeach
                                        </td>
                                    @endforeach
                                        <td><strong>{{ number_format($totalFila, 2) }}</strong></td>
                                        <td><strong>{{ number_format(($totalFila/$tasa), 2) }}</strong></td>
                                    </tr>
                                @endforeach

                                {{-- Totales de columnas y total general --}}
                                <tr>
                                    <td colspan="{{ count($columnas) }}"><strong>Total Columnas</strong></td>
                                    <td>
                                        <strong>{{ number_format($totalGeneral, 2) }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ number_format(($totalGeneral/$tasa), 2) }}</strong>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr/>
                        <!-- Sección de Costos -->
                        <h2>Costos</h2>
                        <table border="1" cellpadding="5" cellspacing="0">
                            <thead>
                                <tr>

                                    <th>Costos</th>
                                    <th>Valor RMB</th>
                                    <th>Valor USD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $TotalCostos = 0;
                                @endphp

                                @foreach ($costos as $costo)
                                    <tr>
                                        <td>{{ $costo['propiedad'] }}</td>

                                        <td>{{ number_format((float)$costo['valor']) }}</td>
                                        <td>{{ number_format((float)$costo['valor']/$tasa) }}</td>

                                    </tr>
                                    @php
                                    if( $costo['propiedad'] =="Otros Ingresos"){
                                        $TotalCostos =(float)$TotalCostos - (float)$costo['valor'];
                                    }
                                    else{
                                        $TotalCostos =(float)$TotalCostos + (float)$costo['valor'];
                                    }
                                    @endphp

                                                                   @endforeach
                                                                   <tr>
                                                                    <td><strong>Total Costos</strong></td>
                                                                    <td><strong>{{ number_format($TotalCostos, 2) }}</strong></td>
                                                                    <td><strong>{{ number_format($TotalCostos/$tasa, 2) }}</strong></td>
                                                                   </tr>
                            </tbody>
                        </table>
                        <div>
                            <h2>Total Liquidación RMB
                            {{ number_format($totalGeneral - $TotalCostos, 2) }}</h2>
                            <h2>Total Liquidación USD
                            {{ number_format((($totalGeneral - $TotalCostos) /$tasa), 2) }}</h2>

                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group  d-flex align-items-center gap-2">
        <form action="{{ route('admin.comex.guardaliquidacion') }}" method="POST">
            @csrf
            <input type="hidden" name="instructivo" value="{{ $instructivo }}">
            <input type="hidden" name="tasa" value="{{ $tasa }}">
            <input type="hidden" name="fecha_arribo" value="{{ $datosExcel->fecha_arribo }}">
            <input type="hidden" name="fecha_venta" value="{{ $datosExcel->fecha_venta }}">
            <input type="hidden" name="fecha_liquidacion" value="{{ $datosExcel->fecha_liquidacion }}">
            <button type="submit" class="btn btn-primary">Guardar</button>

        </form>
        <form action="{{ route('admin.comex.eliminardatosExcel') }}" method="POST">
            @csrf
            <input type="hidden" name="instructivo" value="{{ $instructivo }}">
            <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>

    </div>
@endsection
@section('scripts')
    @parent
@endsection
