<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin-top: 40mm;
            margin-bottom: 40mm;
            margin-left: 10mm;
            margin-right: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            break-inside: auto;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: middle;
        }

        thead {
            display: table-header-group;
        }

        tbody {
            display: table-row-group;
        }

        tr {
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

    <!-- Header visible solo en PDF -->
    <div style="text-align: center; font-weight: bold;">
         @php
            $headerImagePath = public_path('/img/cabecera_pdf.jpg');
        @endphp
        @if (file_exists($headerImagePath))
            <img src="{{ $headerImagePath }}" alt="Header" alt="Cabecera" style="max-height: 25mm; width: auto;">
        @else
            <p class="error">Header image not found: {{ $headerImagePath }}</p>
        @endif

    </div>

    <!-- Contenido dinámico de tabs -->
    @foreach ($tabs as $tab)
        <div class="tab-content" style="page-break-before: always;">
            {!! $tab['html'] !!}
        </div>
    @endforeach

    <!-- Gráficos -->
    @if (!empty($chartImages))
        @foreach ($chartImages as $chart)
            @if (!empty($chart['image']))
                <div style="page-break-before: always; text-align: center;">
                    <h3>{{ $chart['id'] }}</h3>
                    <img src="{{ $chart['image'] }}" alt="Gráfico {{ $chart['id'] }}" style="width: 100%; max-width: 700px; height: auto;" />
                </div>
            @endif
        @endforeach
    @endif

    <!-- Footer visible solo en PDF -->
    <div style="position: fixed; bottom: 5mm; left: 10mm; right: 10mm; text-align: center;">
          @php
            $footerImagePath = public_path('/img/footer_pdf.jpg');
        @endphp
        @if (file_exists($footerImagePath))
            <img src="{{ $footerImagePath }}" alt="Footer" style="max-height: 25mm; width: auto;">
        @else
            <p class="error">Footer image not found: {{ $footerImagePath }}</p>
        @endif

    </div>

</body>
</html>
