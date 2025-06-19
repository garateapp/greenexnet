<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Liquidación</title>
    <style>
         <style>
       @page {
            margin: 80px 20px 80px 20px; /* Adjust margins to accommodate header/footer */
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .header, .footer {
            position: fixed;
            left: 0;
            right: 0;
            width: 100%;
        }
        .header {
            top: -60px; /* Adjust to fit within margin */
            height: 60px;
        }
        .footer {
            bottom: -60px; /* Adjust to fit within margin */
            height: 60px;
        }
        .header img, .footer img {
            width: 100%;
            height: auto;
        }
        .page {
            page-break-after: always;
            margin-top: 80px; /* Space for header */
            margin-bottom: 80px; /* Space for footer */
        }
        .chart-container {
            margin: 20px 0;
            text-align: center;
        }
        .chart-container img {
            max-width: 100%;
            height: auto;
        }
        h2 {
            text-align: center;
            font-size: 18pt;
        }
    </style>
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
