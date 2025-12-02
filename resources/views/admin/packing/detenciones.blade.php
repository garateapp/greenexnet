@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Reporte de Detenciones en Líneas</span>
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.packing.detenciones.export', request()->query()) }}" class="btn btn-outline-success btn-sm mr-3">
                    Exportar Excel
                </a>
                <small class="text-muted mb-0">Última actualización {{ now()->format('d/m/Y H:i') }}</small>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.packing.detenciones') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label class="small text-muted">Desde</label>
                        <input type="date" name="start_date" value="{{ $filters['start_for_input'] ?? now()->subDays(14)->format('Y-m-d') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted">Hasta</label>
                        <input type="date" name="end_date" value="{{ $filters['end_for_input'] ?? now()->format('Y-m-d') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted">Línea</label>
                        <select name="line_name" class="form-control">
                            <option value="">Todas</option>
                            @foreach ($lines as $line)
                                <option value="{{ $line->name }}" {{ ($filters['line_name'] ?? '') === $line->name ? 'selected' : '' }}>
                                    {{ $line->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted">Turno</label>
                        <select name="turno" class="form-control">
                            <option value="">Todos</option>
                            @foreach ($turnOptions as $turnLabel)
                                <option value="{{ $turnLabel }}" {{ ($filters['turno'] ?? '') === $turnLabel ? 'selected' : '' }}>
                                    {{ $turnLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block w-100">
                            Aplicar filtros
                        </button>
                    </div>
                </div>
            </form>

            <div class="row text-center mb-4">
                <div class="col-md-3 mb-3">
                    <div class="border rounded shadow-sm p-3 h-100">
                        <p class="text-muted mb-1 small">Detenciones totales</p>
                        <h3 class="mb-0">{{ number_format($kpis['total_events']) }}</h3>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="border rounded shadow-sm p-3 h-100">
                        <p class="text-muted mb-1 small">Horas detenidas</p>
                        <h3 class="mb-0">{{ number_format($kpis['total_duration_hours'], 1) }}</h3>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="border rounded shadow-sm p-3 h-100">
                        <p class="text-muted mb-1 small">Duración promedio (min)</p>
                        <h3 class="mb-0">{{ number_format($kpis['avg_duration_minutes'], 1) }}</h3>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="border rounded shadow-sm p-3 h-100">
                        <p class="text-muted mb-1 small">% Eventos activos</p>
                        <h3 class="mb-0">{{ number_format($kpis['active_ratio'], 1) }}%</h3>
                        <small class="text-muted">{{ $kpis['active_events'] }} activos</small>
                    </div>
                </div>
            </div>

            @if(!empty($kpis['turn_hours']))
                <div class="row text-center mb-4">
                    @foreach($kpis['turn_hours'] as $turnLabel => $hours)
                        <div class="col-md-3 mb-3">
                            <div class="border rounded shadow-sm p-3 h-100">
                                <p class="text-muted mb-1 small">Horas detenidas - {{ $turnLabel }}</p>
                                <h3 class="mb-0">{{ number_format($hours, 1) }}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="border rounded shadow-sm p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Tendencia diaria</h5>
                            <small class="text-muted">Detenciones vs horas detenidas</small>
                        </div>
                        <div id="trendChart" style="min-height: 320px;"></div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="border rounded shadow-sm p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Motivos</h5>
                            <small class="text-muted">Participación por motivo registrado</small>
                        </div>
                        <div id="motivoChart" style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="border rounded shadow-sm p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Comparativo por línea</h5>
                            <small class="text-muted">Horas detenidas vs cantidad</small>
                        </div>
                        <div id="lineComparisonChart" style="min-height: 320px;"></div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="border rounded shadow-sm p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Top 10 causas</h5>
                            <small class="text-muted">Cantidad de detenciones</small>
                        </div>
                        <div id="topCausesChart" style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="border rounded shadow-sm p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Comparativo por turno</h5>
                            <small class="text-muted">Horas detenidas por turno</small>
                        </div>
                        <div id="turnComparisonChart" style="min-height: 320px;"></div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="border rounded shadow-sm p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Top 10 causas por turno</h5>
                            <small class="text-muted">Comparativo de eventos por turno</small>
                        </div>
                        <div id="topCausesTurnChart" style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-4">
                    <div class="border rounded shadow-sm p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Comparativo diario por línea</h5>
                            <small class="text-muted">Horas detenidas por línea cada día</small>
                        </div>
                        <div id="lineDailyChart" style="min-height: 360px;"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="border rounded shadow-sm p-3 h-100">
                        <h5>Top 10 detenciones por causa</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                <tr>
                                    <th>Causa</th>
                                    <th class="text-right">Detenciones</th>
                                    <th class="text-right">Horas detenidas</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($topCauses as $cause)
                                    <tr>
                                        <td>{{ $cause->cause ?? 'Sin causa' }}</td>
                                        <td class="text-right">{{ number_format($cause->total_events) }}</td>
                                        <td class="text-right">{{ number_format(($cause->total_duration ?? 0) / 60, 1) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Sin datos para el período seleccionado</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="border rounded shadow-sm p-3 h-100">
                        <h5>Resumen por línea</h5>
                        <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                            <table class="table table-sm table-striped mb-0">
                                <thead>
                                <tr>
                                    <th>Línea</th>
                                    <th class="text-right">Detenciones</th>
                                    <th class="text-right">Horas detenidas</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($lineComparison as $line)
                                    <tr>
                                        <td>{{ $line->line ?? 'Sin línea' }}</td>
                                        <td class="text-right">{{ number_format($line->total_events) }}</td>
                                        <td class="text-right">{{ number_format(($line->total_duration ?? 0) / 60, 1) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Sin datos para el período seleccionado</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border rounded shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Últimas detenciones registradas</h5>
                    <small class="text-muted">Se muestran las 250 más recientes</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Línea</th>
                            <th>Fecha evento</th>
                            <th>Fecha activación</th>
                            <th class="text-right">Duración (min)</th>
                                    <th>Motivo</th>
                            <th>Causa</th>
                            <th>Estado</th>
                            <th>Notas</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td>{{ $event->id }}</td>
                                <td>{{ $event->line }}</td>
                                <td>{{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('d-m-Y H:i') : '' }}</td>
                                <td>{{ $event->activation_date ? \Carbon\Carbon::parse($event->activation_date)->format('d-m-Y H:i') : '' }}</td>
                                <td class="text-right">{{ number_format($event->duration, 0) }}</td>
                                <td>{{ $event->motivo }}</td>
                                <td>{{ $event->cause ?? 'Sin causa' }}</td>
                                <td>
                                    @php($isActive = strtolower($event->status ?? '') === 'activo')
                                    <span class="badge badge-{{ $isActive ? 'success' : 'secondary' }}">
                                        {{ $event->status ?? 'Sin estado' }}
                                    </span>
                                </td>
                                <td>{{ $event->notes }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No se registran detenciones para el período seleccionado</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const payload = @json($chartPayload);

            const renderOrMessage = (selector, hasData, message) => {
                if (!hasData) {
                    const el = document.querySelector(selector);
                    if (el) {
                        el.innerHTML = `<div class="text-center text-muted py-5">${message}</div>`;
                    }
                }
                return hasData;
            };

            if (renderOrMessage('#trendChart', payload.trend.categories.length, 'No hay información para mostrar.')) {
                new ApexCharts(document.querySelector('#trendChart'), {
                    chart: { type: 'line', height: 320, toolbar: { show: false } },
                    stroke: { width: [0, 3], curve: 'smooth' },
                    dataLabels: { enabled: true, enabledOnSeries: [1] },
                    series: [
                        { name: 'Detenciones', type: 'column', data: payload.trend.events },
                        { name: 'Horas detenidas', type: 'line', data: payload.trend.duration }
                    ],
                    xaxis: { categories: payload.trend.categories },
                    yaxis: [
                        { title: { text: 'Detenciones' } },
                        { opposite: true, title: { text: 'Horas detenidas' } }
                    ],
                    colors: ['#008FFB', '#FEB019'],
                    legend: { position: 'top' }
                }).render();
            }

            if (renderOrMessage('#lineComparisonChart', payload.lineComparison.categories.length, 'Sin detenciones registradas.')) {
                new ApexCharts(document.querySelector('#lineComparisonChart'), {
                    chart: { type: 'bar', height: 320, stacked: false, toolbar: { show: false } },
                    plotOptions: { bar: { columnWidth: '55%' } },
                    series: [
                        { name: 'Horas detenidas', data: payload.lineComparison.duration },
                        { name: 'Detenciones', data: payload.lineComparison.events }
                    ],
                    xaxis: { categories: payload.lineComparison.categories },
                    colors: ['#FF4560', '#00E396'],
                    dataLabels: { enabled: false },
                    legend: { position: 'top' }
                }).render();
            }

            if (renderOrMessage('#topCausesChart', payload.topCauses.categories.length, 'Sin datos suficientes.')) {
                new ApexCharts(document.querySelector('#topCausesChart'), {
                    chart: { type: 'bar', height: 320, toolbar: { show: false } },
                    plotOptions: { bar: { horizontal: true, barHeight: '65%' } },
                    series: [{ name: 'Detenciones', data: payload.topCauses.counts }],
                    xaxis: { categories: payload.topCauses.categories },
                    colors: ['#775DD0'],
                    dataLabels: { enabled: true }
                }).render();
            }

            if (renderOrMessage('#motivoChart', payload.motivos.labels.length, 'Sin datos de motivos.')) {
                new ApexCharts(document.querySelector('#motivoChart'), {
                    chart: { type: 'donut', height: 320 },
                    labels: payload.motivos.labels,
                    series: payload.motivos.series,
                    legend: { position: 'bottom' },
                    dataLabels: { enabled: true, formatter: (val) => `${val.toFixed(1)}%` }
                }).render();
            }

            if (renderOrMessage('#turnComparisonChart', payload.turnComparison.categories.length, 'Sin datos por turno.')) {
                new ApexCharts(document.querySelector('#turnComparisonChart'), {
                    chart: { type: 'bar', height: 320, toolbar: { show: false } },
                    plotOptions: { bar: { horizontal: false, columnWidth: '45%' } },
                    dataLabels: { enabled: true, formatter: (val) => `${val.toFixed(1)} h` },
                    series: [{
                        name: 'Horas detenidas',
                        data: payload.turnComparison.series
                    }],
                    xaxis: { categories: payload.turnComparison.categories },
                    yaxis: { title: { text: 'Horas' } },
                    colors: ['#FF9F43']
                }).render();
            }

            if (renderOrMessage('#topCausesTurnChart', payload.topCausesTurn.categories.length, 'Sin datos de causas por turno.')) {
                new ApexCharts(document.querySelector('#topCausesTurnChart'), {
                    chart: { type: 'bar', height: 320, stacked: true, toolbar: { show: false } },
                    plotOptions: { bar: { columnWidth: '55%' } },
                    dataLabels: { enabled: false },
                    series: payload.topCausesTurn.series,
                    xaxis: { categories: payload.topCausesTurn.categories },
                    yaxis: { title: { text: 'Detenciones' } },
                    legend: { position: 'top' },
                    colors: ['#1E90FF', '#FF4560', '#95A5A6']
                }).render();
            }

            if (renderOrMessage('#lineDailyChart', (payload.lineDaily.categories.length > 0 && payload.lineDaily.series.length > 0), 'Sin historial diario para mostrar.')) {
                new ApexCharts(document.querySelector('#lineDailyChart'), {
                    chart: { type: 'line', height: 360, toolbar: { show: false } },
                    stroke: { curve: 'smooth', width: 3 },
                    dataLabels: { enabled: false },
                    series: payload.lineDaily.series,
                    xaxis: { categories: payload.lineDaily.categories },
                    yaxis: { title: { text: 'Horas detenidas' } },
                    tooltip: {
                        y: { formatter: (val) => `${val.toFixed(2)} h` }
                    },
                    legend: { position: 'top' },
                    colors: ['#1E90FF', '#FF4560', '#00E396', '#775DD0', '#FEB019', '#546E7A']
                }).render();
            }
        });
    </script>
@endsection
