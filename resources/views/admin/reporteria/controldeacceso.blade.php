<!-- resources/views/access/report.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Control de Acceso</title>
</head>
<body>
    <h1>Control de Entrada</h1>
    <table>
        <thead>
            <tr>
                <th>Fecha y Hora</th>
                <th>Nombre</th>
                <th>RUT</th>
                <th>Teléfono</th>
                <th>Empresa</th>
                <th>Patente</th>
                <th>N° Guía</th>
                <th>Área Destino</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entryRecords as $record)
                <tr>
                    <td>{{ $record['fecha_hora_entrada'] }}</td>
                    <td>{{ $record['nombre'] }}</td>
                    <td>{{ $record['rut'] }}</td>
                    <td>{{ $record['telefono'] }}</td>
                    <td>{{ $record['empresa'] }}</td>
                    <td>{{ $record['patente'] }}</td>
                    <td>{{ $record['n_guia_despacho'] }}</td>
                    <td>{{ $record['area_destino'] }}</td>
                    <td>{{ $record['motivo_ingreso'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h1>Control de Salida</h1>
    <table>
        <thead>
            <tr>
                <th>Fecha y Hora</th>
                <th>Nombre</th>
                <th>RUT</th>
                <th>Empresa</th>
                <th>Patente</th>
                <th>N° Guía</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exitRecords as $record)
                <tr>
                    <td>{{ $record['fecha_hora_salida'] }}</td>
                    <td>{{ $record['nombre'] }}</td>
                    <td>{{ $record['rut'] }}</td>
                    <td>{{ $record['empresa'] }}</td>
                    <td>{{ $record['patente'] }}</td>
                    <td>{{ $record['n_guia_despacho'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>