<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte Control de Acceso</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; font-size: 12px; text-align: left; }
        th { background: #f3f4f6; }
        .muted { color: #6b7280; }
        .badge-no { color: #b91c1c; font-weight: bold; }
        .badge-yes { color: #047857; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Reporte Control de Acceso</h2>
    <p class="muted">
        Rango: {{ $start->format('Y-m-d H:i') }} - {{ $end->format('Y-m-d H:i') }}
    </p>

    <h3>Resumen ejecutivo</h3>
    <table>
        <thead>
        <tr>
            <th>Total personal</th>
            <th>Con asistencia</th>
            <th>Sin asistencia</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $summary['total'] ?? 0 }}</td>
            <td>{{ $summary['con_asistencia'] ?? 0 }}</td>
            <td>{{ $summary['sin_asistencia'] ?? 0 }}</td>
        </tr>
        </tbody>
    </table>

    <h3>Personal por departamento</h3>
    <table>
        <thead>
        <tr>
            <th>Departamento</th>
            <th>Total</th>
            <th>Con asistencia</th>
            <th>Sin asistencia</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($totalsByDepartment as $item)
            <tr>
                <td>{{ $item['departamento'] }}</td>
                <td>{{ $item['total'] }}</td>
                <td>{{ $item['con_asistencia'] }}</td>
                <td>{{ $item['sin_asistencia'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="muted">Sin datos por departamento.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <h3>Detalle</h3>
    <table>
        <thead>
        <tr>
            <th>Fecha</th>
            <th>Código</th>
            <th>RUT</th>
            <th>Nombre</th>
            <th>Departamento</th>
            <th>Primera Entrada</th>
            <th>Última Salida</th>
            <th>Asistencia</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($rows as $row)
            <tr>
                <td>{{ $row->fecha }}</td>
                <td>{{ $row->personal_id }}</td>
                <td>{{ $row->rut }}</td>
                <td>{{ $row->nombre }}</td>
                <td>{{ $row->departamento }}</td>
                <td>{{ $row->primera_marca ? \Illuminate\Support\Carbon::parse($row->primera_marca)->format('H:i') : '' }}</td>
                <td>{{ $row->ultima_salida ? \Illuminate\Support\Carbon::parse($row->ultima_salida)->format('H:i') : '' }}</td>
                <td>
                    @if ($row->sin_asistencia)
                        <span class="badge-no">SIN REGISTRO</span>
                    @else
                        <span class="badge-yes">OK</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="muted">Sin registros para el rango indicado.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
