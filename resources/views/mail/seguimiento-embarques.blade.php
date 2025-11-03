<!DOCTYPE html>
<html>

<head>
    <style>
        /* Agrega estilos CSS personalizados aqui */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        #header {
            text-align: center;
            background-color: #f0f0f0;
            padding: 20px;
        }

        #header img {
            max-width: 200px;
        }

        #contenido {

        }
        .table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}
.table td,
.table th {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    background-color: #048713;
    color: #fff;
}
.table tbody + tbody {
    border-top: 2px solid #dee2e6;
}
.table-sm td,
.table-sm th {
    padding: 0.3rem;
}
.table-bordered,
.table-bordered td,
.table-bordered th {
    border: 1px solid #dee2e6;
}
.table-bordered thead td,
.table-bordered thead th {
    border-bottom-width: 2px;
}
.table-borderless tbody + tbody,
.table-borderless td,
.table-borderless th,
.table-borderless thead th {
    border: 0;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}
.table-hover tbody tr:hover {
    color: #212529;
    background-color: rgba(0, 0, 0, 0.075);
}
.table-primary,
.table-primary > td,
.table-primary > th {
    background-color: #c6e0f5;
}
.table-primary tbody + tbody,
.table-primary td,
.table-primary th,
.table-primary thead th {
    border-color: #95c5ed;
}
.table-hover .table-primary:hover,
.table-hover .table-primary:hover > td,
.table-hover .table-primary:hover > th {
    background-color: #b0d4f1;
}
        /* Agrega un pequeño margen en tablets y escritorio */
        @media screen and (min-width: 768px) {
            #contenido {
                margin: 2px 30px;
                max-width: 600px;
                /* Ancho maximo del contenido */
            }
        }
    </style>
</head>

<body>
    <div id="header">
        <img src="https://net.greenexweb.cl/img/logo_gnx111.png" alt="Logo de Greenex">
    </div>
    <div id="contenido">
        @php
            $totalsByTransportAndClient = $totalsByTransportAndClient ?? collect();
            $totalsByTransport = $totalsByTransport ?? collect();
            $verticalTotals = $totalsByTransport->reduce(function ($carry, $row) {
                $carry['total_pallets'] = ($carry['total_pallets'] ?? 0) + (float) ($row->total_pallets ?? 0);
                $carry['total_cajas'] = ($carry['total_cajas'] ?? 0) + (float) ($row->total_cajas ?? 0);
                $carry['cargas'] = ($carry['cargas'] ?? 0) + (int) ($row->cargas ?? 0);
                return $carry;
            }, ['total_pallets' => 0, 'total_cajas' => 0, 'cargas' => 0]);
        @endphp
        <p>Estimados,</p>
        <p>les enviamos el seguimiento de los embarques a la fecha {{ date('d-m-Y') }}</p>
         @if ($totalsByTransport->isNotEmpty())
            <h4>Resumen por transporte</h4>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Transporte</th>
                        <th>Total Pallets</th>
                        <th>Total Cajas</th>
                        <th>Cargas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($totalsByTransport as $row)
                        <tr>
                            <td>{{ $row->transporte }}</td>
                            <td>{{ number_format($row->total_pallets ?? 0, 0, ',', '.') }}</td>
                            <td>{{ number_format($row->total_cajas ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $row->cargas }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th>{{ number_format($verticalTotals['total_pallets'], 0, ',', '.') }}</th>
                        <th>{{ number_format($verticalTotals['total_cajas'], 0, ',', '.') }}</th>
                        <th>{{ number_format($verticalTotals['cargas'], 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        @endif
        @if ($totalsByTransportAndClient->isNotEmpty())
            <h4>Resumen por transporte y cliente</h4>
            @php
                $transports = $totalsByTransportAndClient->pluck('transporte')->filter()->unique()->sort()->values();
                $clients = $totalsByTransportAndClient
                    ->map(function ($row) {
                        return $row->n_cliente ?? 'Sin nombre';
                    })
                    ->unique()
                    ->sort()
                    ->values();
                $dataMatrix = $totalsByTransportAndClient->reduce(function ($carry, $row) {
                    $client = $row->n_cliente ?? 'Sin nombre';
                    $transport = $row->transporte;
                    $key = $client . '||' . $transport;
                    $carry[$key] = [
                        'total_pallets' => (float) ($row->total_pallets ?? 0),
                        'total_cajas' => (float) ($row->total_cajas ?? 0),
                        'cargas' => (int) ($row->cargas ?? 0),
                    ];
                    return $carry;
                }, []);
                $rowTotals = [];
                $columnTotals = [];
                foreach ($clients as $clientName) {
                    $rowTotals[$clientName] = ['total_pallets' => 0, 'total_cajas' => 0, 'cargas' => 0];
                }
                foreach ($transports as $transportName) {
                    $columnTotals[$transportName] = ['total_pallets' => 0, 'total_cajas' => 0, 'cargas' => 0];
                }
            @endphp
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th rowspan="2">Cliente</th>
                        @foreach ($transports as $transport)
                            <th colspan="3">{{ $transport }}</th>
                        @endforeach
                        <th colspan="3">Total Cliente</th>
                    </tr>
                    <tr>
                        @foreach ($transports as $transport)
                            <th>Total Pallets</th>
                            <th>Total Cajas</th>
                            <th>Cargas</th>
                        @endforeach
                        <th>Total Pallets</th>
                        <th>Total Cajas</th>
                        <th>Cargas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td>{{ $client }}</td>
                            @foreach ($transports as $transport)
                                @php
                                    $key = $client . '||' . $transport;
                                    $cell = $dataMatrix[$key] ?? ['total_pallets' => 0, 'total_cajas' => 0, 'cargas' => 0];
                                    $rowTotals[$client]['total_pallets'] += $cell['total_pallets'];
                                    $rowTotals[$client]['total_cajas'] += $cell['total_cajas'];
                                    $rowTotals[$client]['cargas'] += $cell['cargas'];
                                    $columnTotals[$transport]['total_pallets'] += $cell['total_pallets'];
                                    $columnTotals[$transport]['total_cajas'] += $cell['total_cajas'];
                                    $columnTotals[$transport]['cargas'] += $cell['cargas'];
                                @endphp
                                <td>{{ number_format($cell['total_pallets'], 0, ',', '.') }}</td>
                                <td>{{ number_format($cell['total_cajas'], 0, ',', '.') }}</td>
                                <td>{{ number_format($cell['cargas'], 0, ',', '.') }}</td>
                        @endforeach
                        <td>{{ number_format($rowTotals[$client]['total_pallets'], 0, ',', '.') }}</td>
                        <td>{{ number_format($rowTotals[$client]['total_cajas'], 0, ',', '.') }}</td>
                        <td>{{ number_format($rowTotals[$client]['cargas'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total Transporte</th>
                        @php
                            $grandTotals = ['total_pallets' => 0, 'total_cajas' => 0, 'cargas' => 0];
                        @endphp
                        @foreach ($transports as $transport)
                            @php
                                $grandTotals['total_pallets'] += $columnTotals[$transport]['total_pallets'];
                                $grandTotals['total_cajas'] += $columnTotals[$transport]['total_cajas'];
                                $grandTotals['cargas'] += $columnTotals[$transport]['cargas'];
                            @endphp
                            <td>{{ number_format($columnTotals[$transport]['total_pallets'], 0, ',', '.') }}</td>
                            <td>{{ number_format($columnTotals[$transport]['total_cajas'], 0, ',', '.') }}</td>
                            <td>{{ number_format($columnTotals[$transport]['cargas'], 0, ',', '.') }}</td>
                        @endforeach
                        <td>{{ number_format($grandTotals['total_pallets'], 0, ',', '.') }}</td>
                        <td>{{ number_format($grandTotals['total_cajas'], 0, ',', '.') }}</td>
                        <td>{{ number_format($grandTotals['cargas'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        @endif


        <p>Adjuntamos el detalle completo de embarques en el archivo Excel incluido en este correo.</p>
        <p>Para mayor informacion ingresar a nuestra intranet <a
                href="http://net.greenex.cl">net.greenex.cl</a> menu COMEX opcion embarques</p>
        <p>Saludos,</p>
        <p>Equipo Greenex</p>
    </div>
</body>
