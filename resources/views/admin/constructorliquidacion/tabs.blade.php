<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 40mm 10mm 40mm 10mm;
            /* Match wkhtmltopdf */
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            break-inside: auto;
            font-size: 10px;
        }

        th,
        td {
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
            break-after: auto;
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

        .number.negative {
            color: red;
        }

        .productor-nombre {
            font-weight: bold;
            text-align: center;
        }

        .tab-content {
            display: block;
            page-break-before: always !important;
            break-before: always !important;
            clear: both;
            min-height: 1px;
            margin-top: 40mm;
            /* Prevent header overlap */
        }

        .tab-content:first-child {
            page-break-before: avoid !important;
            break-before: avoid !important;
            margin-top: 0;
        }

        .header {
            position: fixed;
            top: 5mm;
            left: 10mm;
            right: 10mm;
            height: 30mm;
            text-align: center;
            z-index: 1000;
        }

        .footer {
            position: fixed;
            bottom: 5mm;
            left: 10mm;
            right: 10mm;
            height: 30mm;
            text-align: center;
            z-index: 1000;
        }

        .header img,
        .footer img {
            max-height: 25mm;
            width: auto;
            vertical-align: middle;
        }

        .content-wrapper {
            padding: 40mm 10mm 40mm 10mm;
            /* Prevent overlap */
        }

        .table-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 12px;
        }

        td[rowspan],
        td[colspan] {
            border: 1px solid #000 !important;
            box-sizing: border-box;
        }
    </style>
</head>

<body>


    <!-- Footer -->


    <!-- Content -->
    <div class="content-wrapper">
        @foreach ($tabs as $index => $tab)
            <!-- Header -->

            <div class="tab-content">
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

                {!! $tab['html'] !!}
                <!-- Footer -->
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

            </div>
            @foreach ($chartImages as $chart)
                <div style="page-break-before: always;">
                    <h3>{{ $chart['id'] }}</h3>
                    <img src="{{ $chart['image'] }}" alt="Gráfico {{ $chart['id'] }}"
                        style="width: 100%; max-width: 800px; height: auto;" />
                </div>
            @endforeach
    </div>
</body>

</html>
