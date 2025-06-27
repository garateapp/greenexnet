<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
   <style>
        @page {
            margin-top: 40mm;
            margin-bottom: 40mm;
            margin-left: 20mm;
            margin-right: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            font-size: 10px;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            page-break-inside: auto; /* Permite saltar de página dentro de la tabla */
            margin: 0 auto;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: middle;
        }

        thead {
            display: table-header-group; /* Repite el encabezado en cada página */
        }

        tbody {
            display: table-row-group;
        }

        tr {
            page-break-inside: avoid; /* Evita que una fila se corte entre páginas */
            break-inside: avoid;
        }

        .section-header {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background-color: #e0e0e0;
        }

        .number {
            text-align: right;
        }

        .negative {
            color: red;
        }

        .productor-nombre {
            font-weight: bold;
            text-align: center;
        }

        .table-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 12px;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="tab-content" style="page-break-before: always;">
        <img src="{{ $portada }}" alt="Cabecera" style="width: auto; height:1410px;">
    </div>


    <!-- Contenido dinámico de tabs -->
    @foreach ($tabs as $tab)
        <div class="tab-content" style="page-break-before: always;">
            <!-- Header visible solo en PDF -->
            <div style="text-align: center; font-weight: bold;">
                <img src="{{ $logo_path }}" alt="Cabecera" style="width: 210mm !important;">
            </div>
            <div style="margin-left:20px;margin-top:25px;">
            {!! $tab['html'] !!}
            </div>
            <!-- Footer visible solo en PDF -->
            {{-- <div style="position: fixed; bottom: 5mm; left: 10mm; right: 10mm; text-align: center;">
        <img src="{{ $footer_path }}" alt="Footer" style="max-height: 25mm; width: auto;">
    </div> --}}
        </div>
    @endforeach

    <!-- Gráficos -->
    @if (!empty($chartImages))

        @foreach ($chartImages as $chart)
            @if (!empty($chart['image']))
                <div style="page-break-before: always; text-align: center;">
                    <div style="text-align: center; font-weight: bold;">
                        <img src="{{ $logo_path }}" alt="Cabecera" style="width: 210mm;">
                    </div>
                    <div style="text-align: center; font-weight: bold; margin-top:4cm;margin-left:25%;">
                        <img src="{{ $chart['image'] }}" alt="Gráfico {{ $chart['id'] }}"
                            style="width: 150%;" />
                    </div>
                </div>
                {{-- <div style="position: fixed; bottom: 5mm; left: 10mm; right: 10mm; text-align: center;">
        <img src="{{ $footer_path }}" alt="Footer" style="max-height: 25mm; width: auto;">
    </div> --}}
            @endif
        @endforeach
    @endif



</body>

</html>
