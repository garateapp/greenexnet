<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráficos PDF</title>
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
</head>
<body>
       <!-- Header -->
       <div class="header">
        @php
            $headerImagePath = storage_path('app/public/cabecera_pdf.jpg');
        @endphp
        @if (file_exists($headerImagePath))
            <img src="{{ $headerImagePath }}" alt="Header">
        @else
            <p class="error">Header image not found: {{ $headerImagePath }}</p>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        @php
            $footerImagePath = storage_path('app/public/footer_pdf.jpg');
        @endphp
        @if (file_exists($footerImagePath))
            <img src="{{ $footerImagePath }}" alt="Footer">
        @else
            <p class="error">Footer image not found: {{ $footerImagePath }}</p>
        @endif
    </div>

    @php
        $groupedData = [];
        foreach ($productorData as $item) {
            $key = $item->productor . '_' . $item->variedad;
            if (!isset($groupedData[$key])) {
                $groupedData[$key] = [
                    'productor' => $item->productor,
                    'variedad' => $item->variedad,
                    'data' => []
                ];
            }
            $groupedData[$key]['data'][] = [
                'calibre' => $item->calibre,
                'curvacalibre' => floatval($item->curvacalibre),
                'rnp_kilo' => floatval($item->rnp_kilo)
            ];
        }
        $groupedData = array_values($groupedData);
        $chartsPerPage = 3;
        $chartGroups = array_chunk($groupedData, $chartsPerPage);
  
    @endphp
   
    @foreach ($chartGroups as $group)
        <div class="page">
            @foreach ($group as $chart)
                @php
                    $chartId = 'chart_' . str_replace(' ', '_', $chart['productor'] . '_' . $chart['variedad']);
                    $chartImage = collect($chartImages)->firstWhere('id', $chartId)['image'] ?? '';

                @endphp
                <div class="chart-container">
                    <h2>{{ $chart['productor'] }} - {{ $chart['variedad'] }}</h2>
                    <img src="{{ $chartImage }}" alt="Chart">
                </div>
            @endforeach
        </div>
    @endforeach
    
</body>
</html>