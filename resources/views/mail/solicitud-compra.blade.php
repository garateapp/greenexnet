<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Solicitud de compra</title>
</head>
<body>
    <h2>
        @if($tipo === 'created')
            Nueva solicitud de compra
        @else
            Cambio de estado de solicitud
        @endif
    </h2>

    <p><strong>ID:</strong> {{ $solicitud->id }}</p>
    <p><strong>Titulo:</strong> {{ $solicitud->titulo }}</p>
    <p><strong>Solicitante:</strong> {{ $solicitud->solicitante ? $solicitud->solicitante->name : '' }}</p>
    <p><strong>Monto estimado:</strong> {{ number_format($solicitud->monto_estimado, 0, ',', '.') }}</p>
    <p><strong>Moneda:</strong> {{ $solicitud->moneda ? $solicitud->moneda->nombre : '' }}</p>
    <p><strong>Cotizaciones requeridas:</strong> {{ $solicitud->cotizaciones_requeridas }}</p>

    @if($tipo === 'status')
        <p><strong>Estado anterior:</strong> {{ $estadoAnterior }}</p>
        <p><strong>Estado nuevo:</strong> {{ $estadoNuevo }}</p>
    @else
        <p><strong>Estado actual:</strong> {{ $solicitud->estado ? $solicitud->estado->nombre : '' }}</p>
    @endif

    <p>
        <a href="{{ route('admin.solicitud-compras.show', $solicitud) }}">Ver solicitud</a>
    </p>
</body>
</html>
