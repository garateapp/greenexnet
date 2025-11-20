@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" id="control-access-filter-form" class="form-row align-items-end">
                    <div class="form-group col-md-3">
                        <label for="date" class="font-weight-bold">Fecha base</label>
                        <input type="date" id="date" name="date" value="{{ $selectedDate }}" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="department" class="font-weight-bold">Departamento</label>
                        <select id="department" name="department" class="form-control">
                            <option value="">Todos</option>
                            @foreach ($departmentOptions as $department)
                                <option value="{{ $department }}" @selected($department === $selectedDepartment)>{{ $department }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-5">
                        <label class="font-weight-bold d-block">Rango de análisis</label>
                        <div class="btn-group btn-group-sm" role="group">
                            @foreach ($rangeOptions as $key => $label)
                                <button type="submit" name="range" value="{{ $key }}"
                                    class="btn {{ $selectedRange === $key ? 'btn-primary' : 'btn-outline-primary' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                        <small class="text-muted d-block mt-2">
                            Desde {{ \Carbon\Carbon::parse($rangeStart)->format('d/m/Y') }} hasta
                            {{ \Carbon\Carbon::parse($rangeEnd)->format('d/m/Y') }}
                        </small>
                    </div>
                </form>
            </div>
        </div>

        <h5 class="text-uppercase text-muted">Resumen Ejecutivo</h5>
        <div class="row mb-4">
            <div class="col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-header border-0">
                        Dotación Actual (En Planta)
                    </div>
                    <div class="card-body">
                        <div id="capacity-gauge" style="min-height: 260px;"></div>
                        <p class="text-center mb-0 text-muted">
                            Aforo referencia {{ number_format($gaugeMax) }} personas
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 mb-3">
                <div class="row h-100">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body">
                                <p class="mb-1 text-uppercase small">Personas únicas hoy</p>
                                <h2 class="mb-1">{{ number_format($uniqueToday) }}</h2>
                                <small>
                                    Promedio semanal: {{ number_format($weeklyAverage, 1) }}
                                    <span class="ml-2">
                                        @if ($uniqueDelta >= 0)
                                            <i class="fas fa-arrow-up"></i> +{{ number_format($uniqueDelta, 1) }}
                                        @else
                                            <i class="fas fa-arrow-down"></i> {{ number_format($uniqueDelta, 1) }}
                                        @endif
                                    </span>
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body">
                                <p class="mb-1 text-uppercase small">Entradas registradas</p>
                                <h2 class="mb-1">{{ number_format($totalEntries) }}</h2>
                                <small>Salidas: {{ number_format($totalExits) }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-dark text-white h-100">
                            <div class="card-body">
                                <p class="mb-1 text-uppercase small">Contratistas hoy</p>
                                <h2 class="mb-1">{{ number_format($donutData['series'][1]) }}</h2>
                                <small>Participación: {{ $contractorRatio }}%</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-auto">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Dotación máxima diaria</span>
                                <div class="btn-group btn-group-sm" id="trend-window-buttons">
                                    @foreach ($trendWindows as $window)
                                        <button type="button"
                                            class="btn btn-outline-secondary {{ $loop->first ? 'active' : '' }}"
                                            data-window="{{ $window }}">
                                            Últimos {{ $window }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="daily-trend-chart" style="min-height: 260px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Comparativo de dotación por departamento</span>
                <small class="text-muted">Dotación actual vs registros totales</small>
            </div>
            <div class="card-body">
                <div id="department-chart" style="min-height: 340px;"></div>
            </div>
        </div>

        <h5 class="text-uppercase text-muted">Detalle Operacional</h5>
        <div class="row mb-4">
            <div class="col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        Distribución por tipo de personal
                    </div>
                    <div class="card-body">
                        <div id="type-distribution-chart" style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 mb-3">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Flujo horario</span>
                        <small class="text-muted">Entradas vs salidas</small>
                    </div>
                    <div class="card-body">
                        <div id="hourly-flow-chart" style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        Top 5 empresas contratistas (dotación actual)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Empresa</th>
                                    <th class="text-right">Personas en planta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topContractors as $contractor)
                                    <tr>
                                        <td>{{ $contractor['name'] }}</td>
                                        <td class="text-right">{{ $contractor['count'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">Sin contratistas en planta.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Detalle por departamento</span>
                        <small class="text-muted">Ordenado por dotación actual</small>
                    </div>
                    <div class="table-responsive" style="max-height: 300px;">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Departamento</th>
                                    <th class="text-right">En planta</th>
                                    <th class="text-right">Registros</th>
                                    <th class="text-right">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($deptStats as $stat)
                                    <tr>
                                        <td>{{ $stat->departamento ?? 'Sin departamento' }}</td>
                                        <td class="text-right">{{ $stat->dentro }}</td>
                                        <td class="text-right">{{ $stat->total_registros }}</td>
                                        <td class="text-right">
                                            {{ $stat->total_registros > 0 ? number_format(($stat->dentro / $stat->total_registros) * 100, 1) : '0.0' }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Sin datos.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="text-uppercase text-muted">Análisis de Contratistas</h5>
        <div class="row mb-4">
            <div class="col-lg-6 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        Promedio de dotación por empresa (rango seleccionado)
                    </div>
                    <div class="card-body">
                        <div id="contractor-bar-chart" style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        Tendencia de permanencia promedio (minutos)
                    </div>
                    <div class="card-body">
                        <div id="contractor-stay-chart" style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Últimos movimientos registrados
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Personal ID</th>
                            <th>Nombre</th>
                            <th>Departamento</th>
                            <th>Primera entrada</th>
                            <th>Última salida</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestMovements as $movement)
                            <tr>
                                <td>{{ $movement->personal_id }}</td>
                                <td>{{ $movement->nombre }}</td>
                                <td>{{ $movement->departamento ?? 'Sin departamento' }}</td>
                                <td>{{ optional($movement->primera_entrada)->format('H:i') ?? '-' }}</td>
                                <td>{{ optional($movement->ultima_salida)->format('H:i') ?? '—' }}</td>
                                <td>
                                    @if ($movement->ultima_salida)
                                        <span class="badge badge-secondary">Fuera</span>
                                    @else
                                        <span class="badge badge-success">Dentro</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Sin registros recientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const dailyTrendSeries = @json($dailyTrendSeries);
        const trendWindows = @json($trendWindows);
        const donutData = @json($donutData);
        const hourlySeries = @json($hourlySeries);
        const deptChart = @json($deptChart);
        const gaugeData = {
            value: {{ (int) $totalInside }},
            max: {{ (int) $gaugeMax }}
        };
        const contractorBarData = @json($contractorBarData);
        const contractorStayTrend = @json($contractorStayTrend);

        const gaugeChart = new ApexCharts(document.querySelector("#capacity-gauge"), {
            chart: {
                type: 'radialBar',
                height: 280,
                toolbar: { show: false }
            },
            series: [Math.round((gaugeData.value / Math.max(gaugeData.max, 1)) * 100)],
            labels: ['% Aforo utilizado'],
            plotOptions: {
                radialBar: {
                    hollow: { size: '60%' },
                    dataLabels: {
                        value: {
                            formatter: () => `${gaugeData.value} pers.`
                        }
                    }
                }
            },
            colors: ['#2E93fA']
        });
        gaugeChart.render();

        new ApexCharts(document.querySelector("#type-distribution-chart"), {
            chart: {
                type: 'donut',
                height: 320
            },
            labels: donutData.labels,
            series: donutData.series,
            colors: ['#2962FF', '#00C853', '#FF7043'],
            legend: { position: 'bottom' }
        }).render();

        const hourlyChart = new ApexCharts(document.querySelector("#hourly-flow-chart"), {
            chart: {
                type: 'bar',
                height: 320,
                stacked: true,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: { columnWidth: '70%' }
            },
            series: [{
                name: 'Entradas',
                data: hourlySeries.entries
            }, {
                name: 'Salidas',
                data: hourlySeries.exits
            }],
            xaxis: {
                categories: hourlySeries.labels,
                title: { text: 'Hora del día' }
            },
            colors: ['#2E93fA', '#E91E63'],
            yaxis: { min: 0 }
        });
        hourlyChart.render();

        new ApexCharts(document.querySelector("#department-chart"), {
            chart: {
                type: 'bar',
                height: 320,
                stacked: true,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: { horizontal: true, barHeight: '60%' }
            },
            series: [{
                name: 'Dentro',
                data: deptChart.inside
            }, {
                name: 'Fuera',
                data: deptChart.outside
            }],
            xaxis: {
                categories: deptChart.labels
            },
            colors: ['#00C853', '#CFD8DC'],
            legend: { position: 'top' }
        }).render();

        const dailyTrendChart = new ApexCharts(document.querySelector("#daily-trend-chart"), {
            chart: {
                type: 'line',
                height: 260,
                toolbar: { show: false }
            },
            stroke: { width: 3, curve: 'smooth' },
            series: [{
                name: 'Personas únicas',
                data: dailyTrendSeries.data
            }],
            xaxis: { categories: dailyTrendSeries.labels },
            colors: ['#FF9800']
        });
        dailyTrendChart.render();

        document.querySelectorAll('#trend-window-buttons button').forEach((button) => {
            button.addEventListener('click', (event) => {
                document.querySelectorAll('#trend-window-buttons button').forEach((btn) => btn.classList.remove('active'));
                event.currentTarget.classList.add('active');

                const days = parseInt(event.currentTarget.dataset.window, 10);
                const seriesData = dailyTrendSeries.data.slice(-days);
                const labels = dailyTrendSeries.labels.slice(-days);

                dailyTrendChart.updateOptions({
                    xaxis: { categories: labels }
                });
                dailyTrendChart.updateSeries([{
                    name: 'Personas únicas',
                    data: seriesData
                }]);
            });
        });

        new ApexCharts(document.querySelector("#contractor-bar-chart"), {
            chart: {
                type: 'bar',
                height: 320,
                toolbar: { show: false }
            },
            series: [{
                name: 'Promedio diario',
                data: contractorBarData.data
            }],
            xaxis: {
                categories: contractorBarData.labels
            },
            colors: ['#7B1FA2']
        }).render();

        new ApexCharts(document.querySelector("#contractor-stay-chart"), {
            chart: {
                type: 'line',
                height: 320,
                toolbar: { show: false }
            },
            stroke: { width: 3, curve: 'smooth' },
            series: [{
                name: 'Permanencia (min)',
                data: contractorStayTrend.data
            }],
            xaxis: {
                categories: contractorStayTrend.labels
            },
            colors: ['#009688']
        }).render();
    </script>
@endsection
