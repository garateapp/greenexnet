<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Liquidación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            break-inside: avoid;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
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

        .tab-content {
            page-break-before: always;
            break-before: always;
        }

        .header {
            text-align: center;
            font-size: 10px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
        }

        @page {
            margin: 40mm 10mm 40mm 10mm;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        @php
            $headerImagePath = public_path('/img/cabecera_pdf.jpg');
        @endphp
        @if (file_exists($headerImagePath))
            <img src="{{ $headerImagePath }}" alt="Header">
        @else
            <p class="error">Header image not found: {{ $headerImagePath }}</p>
        @endif
    </div>
    <!-- Contenido de pestañas -->
    @foreach ($tabs as $tab)
        <div class="tab-content">
            {!! $tab['html'] !!}
        </div>
    @endforeach
    <div class="footer">
        @php
            $footerImagePath = public_path('/img/footer_pdf.jpg');
        @endphp
        @if (file_exists($footerImagePath))
            <img src="{{ $footerImagePath }}" alt="Footer">
        @else
            <p class="error">Footer image not found: {{ $footerImagePath }}</p>
        @endif
    </div>
    <!-- Imágenes de gráficos -->
    <!-- Header -->
    <div class="header">
        @php
            $headerImagePath = public_path('/img/cabecera_pdf.jpg');
        @endphp
        @if (file_exists($headerImagePath))
            <img src="{{ $headerImagePath }}" alt="Header">
        @else
            <p class="error">Header image not found: {{ $headerImagePath }}</p>
        @endif
    </div>
    @foreach ($chartImages as $chart)
        <div style="page-break-before: always;">
            <h3>{{ $chart['id'] }}</h3>
            <img src="{{ $chart['image'] }}" alt="Gráfico {{ $chart['id'] }}" style="width: 100%; max-width: 800px;" />
        </div>
    @endforeach
    <div class="footer">
        @php
            $footerImagePath = public_path('/img/footer_pdf.jpg');
        @endphp
        @if (file_exists($footerImagePath))
            <img src="{{ $footerImagePath }}" alt="Footer">
        @else
            <p class="error">Footer image not found: {{ $footerImagePath }}</p>
        @endif
    </div>
</body>

</html>
