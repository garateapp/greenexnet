@extends('layouts.admin')
@section('styles')
    <style>
        .kanban-board {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            padding-bottom: 12px;
        }
        .kanban-column {
            background: #f8f9fa;
            border-radius: 6px;
            min-width: 260px;
            max-width: 320px;
            flex: 0 0 280px;
            border: 1px solid #dee2e6;
        }
        .kanban-column-color {
            border-top: 4px solid #6c757d;
            background: linear-gradient(180deg, rgba(108, 117, 125, 0.12) 0%, rgba(108, 117, 125, 0.02) 60%, #f8f9fa 100%);
        }
        .kanban-column-header {
            padding: 10px 12px;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .kanban-items {
            min-height: 120px;
            padding: 10px;
        }
        .kanban-card {
            background: #fff;
            border: 1px solid #ced4da;
            border-left: 4px solid #6c757d;
            border-radius: 6px;
            padding: 8px 10px;
            margin-bottom: 10px;
            cursor: grab;
        }
        .kanban-card small {
            color: #6c757d;
        }
        .kanban-pill {
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 12px;
            background: #e9ecef;
        }
    </style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.solicitudCompra.kanban') }}
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="centroCostoFilter">{{ trans('cruds.solicitudCompra.fields.centro_costo') }}</label>
                <select id="centroCostoFilter" class="form-control select2">
                    <option value="">{{ trans('global.all') }}</option>
                    @foreach($centroCostos as $centro)
                        @php
                            $label = trim(($centro->c_centrocosto ? $centro->c_centrocosto . ' - ' : '') . $centro->n_centrocosto);
                            $entidad = $centro->entidad ? $centro->entidad->nombre : '';
                        @endphp
                        <option value="{{ $centro->id }}">{{ $label }}{{ $entidad ? ' (' . $entidad . ')' : '' }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="kanban-board" id="kanbanBoard">
            @foreach($estados as $estado)
                @php
                    $items = $solicitudes->get($estado->id, collect());
                @endphp
                <div class="kanban-column kanban-column-color" data-estado-id="{{ $estado->id }}" data-color="{{ $estado->color ?: '#6c757d' }}" style="border-top-color: {{ $estado->color ?: '#6c757d' }}; background: linear-gradient(180deg, {{ $estado->color ?: '#6c757d' }}22 0%, {{ $estado->color ?: '#6c757d' }}08 60%, #f8f9fa 100%);">
                    <div class="kanban-column-header" style="background-color: {{ $estado->color ?: '#6c757d' }}20; border-bottom-color: {{ $estado->color ?: '#6c757d' }};">
                        <span>{{ $estado->nombre }}</span>
                        <span class="kanban-pill" style="background-color: {{ $estado->color ?: '#6c757d' }}; color: #fff;">{{ $items->count() }}</span>
                    </div>
                    <div class="kanban-items" data-estado-id="{{ $estado->id }}">
                        @foreach($items as $solicitud)
                            <div class="kanban-card" data-centro-id="{{ $solicitud->centro_costo_id }}" data-update-url="{{ route('admin.solicitud-compras.updateEstado', $solicitud) }}" style="border-left-color: {{ $estado->color ?: '#6c757d' }}; box-shadow: inset 0 0 0 1px {{ $estado->color ?: '#6c757d' }}22;">
                                <div><strong>#{{ $solicitud->id }}</strong> {{ $solicitud->titulo }}</div>
                                <small>{{ $solicitud->solicitante ? $solicitud->solicitante->name : '' }}</small>
                                <div class="mt-1">
                                    <small>
                                        {{ trans('cruds.solicitudCompra.fields.cotizaciones_requeridas') }}:
                                        {{ $solicitud->cotizaciones->count() }}/{{ $solicitud->cotizaciones_requeridas }}
                                    </small>
                                </div>
                                <div class="mt-1">
                                    <a href="{{ route('admin.solicitud-compras.show', $solicitud) }}">{{ trans('global.view') }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    (function () {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        document.querySelectorAll('.kanban-items').forEach(function (column) {
            new Sortable(column, {
                group: 'solicitudes',
                animation: 150,
                onEnd: function (evt) {
                    const card = evt.item;
                    const destino = evt.to.closest('.kanban-column');
                    const estadoId = destino.dataset.estadoId;
                    const url = card.dataset.updateUrl;

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ estado_id: estadoId })
                    })
                    .then(function (response) {
                        if (response.ok) {
                            return response.json();
                        }
                        return response.json().then(function (data) {
                            throw data;
                        });
                    })
                    .then(function () {
                        const fromCount = evt.from.closest('.kanban-column').querySelector('.kanban-pill');
                        const toCount = destino.querySelector('.kanban-pill');
                        fromCount.textContent = evt.from.children.length;
                        toCount.textContent = evt.to.children.length;
                        const color = destino.dataset.color || '#6c757d';
                        card.style.borderLeftColor = color;
                        card.style.boxShadow = 'inset 0 0 0 1px ' + color + '22';
                    })
                    .catch(function (error) {
                        evt.from.appendChild(card);
                        alert(error.message || 'No se pudo actualizar el estado.');
                    });
                }
            });
        });

        const filter = $('#centroCostoFilter');
        filter.select2();
        filter.on('change', function () {
            const selected = $(this).val();
            document.querySelectorAll('.kanban-card').forEach(function (card) {
                const centroId = card.dataset.centroId || '';
                const show = !selected || selected === centroId;
                card.style.display = show ? '' : 'none';
            });
            document.querySelectorAll('.kanban-column').forEach(function (column) {
                const count = Array.from(column.querySelectorAll('.kanban-card')).filter(function (card) {
                    return card.style.display !== 'none';
                }).length;
                const pill = column.querySelector('.kanban-pill');
                if (pill) {
                    pill.textContent = count;
                }
            });
        });
    })();
</script>
@endsection
