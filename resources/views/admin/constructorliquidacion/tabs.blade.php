<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
         @page {
            margin: 40mm 10mm 40mm 10mm; /* Match wkhtmltopdf */
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
            margin-top: 40mm; /* Prevent header overlap */
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
        .header img, .footer img {
            max-height: 25mm;
            width: auto;
            vertical-align: middle;
        }
        .content-wrapper {
            padding: 40mm 10mm 40mm 10mm; /* Prevent overlap */
        }
        .table-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 12px;
        }
        td[rowspan], td[colspan] {
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
                <div class="header">
                    <img src="{{ $logo_path }}" alt="Logo">
                </div>
              
                    {!! $tab['html'] !!}
                    <div class="footer">
                        <img src="{{ $footer_path }}" alt="Footer">
                    </div>
            </div>
           
        @endforeach
    </div>
</body>

</html>
