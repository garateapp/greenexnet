@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="form-row align-items-end">
                    <div class="form-group col-md-3">
                        <label for="date" class="font-weight-bold">Fecha base</label>
                        <input type="date" id="date" name="date" value="{{ $selectedDate }}" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="department" class="font-weight-bold">Departamento</label>
                        <select id="department" name="department" class="form-control">
                            <option value="">Todos</option>
                            @foreach ($departmentOptions as $department)
                                <option value="{{ $department }}" @selected($department === $selectedDepartment)>{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold d-block">Contratistas</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="contractor_only" name="contractor_only" value="1" @checked($onlyContractors)>
                            <label class="form-check-label" for="contractor_only">Mostrar solo contratistas</label>
                        </div>
                    </div>
                    <div class="form-group col-md-2 mt-3 mt-md-0">
                        <button type="submit" class="btn btn-primary">Aplicar</button>
                        <a href="{{ route('admin.control-access.dashboard') }}" class="btn btn-outline-secondary ml-2">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100 border-primary">
                    <div class="card-body">
                        <p class="text-uppercase text-muted font-weight-bold mb-2">Personas en planta</p>
                        <h1 class="display-4 mb-0 text-primary">{{ number_format($totalInside) }}</h1>
                        <small class="text-muted">Entradas sin salida entre 06:00 y 23:59</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <p class="text-uppercase text-muted font-weight-bold mb-2">Turno Día (06:00 - 16:29)</p>
                        <h2 class="mb-0 text-primary">{{ number_format($dayShiftInside) }}</h2>
                        <small class="text-muted">Contratistas: {{ number_format($contractorDayInside) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <p class="text-uppercase text-muted font-weight-bold mb-2">Turno Noche (15:30 - 23:59)</p>
                        <h2 class="mb-0 text-secondary">{{ number_format($nightShiftInside) }}</h2>
                        <small class="text-muted">Contratistas: {{ number_format($contractorNightInside) }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-header">Contratistas vs otros</div>
                    <div class="card-body">
                        <div id="contractor-pie"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 mb-3">
                <div class="card h-100">
                    <div class="card-header">Entradas por hora</div>
                    <div class="card-body">
                        <div id="hourly-line"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                Dotación por departamento (último movimiento del día)
            </div>
            <div class="card-body">
                <div id="department-chart" style="min-height: 320px;"></div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Departamento</th>
                            <th class="text-right">En planta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departmentCounts as $dept)
                            <tr>
                                <td>{{ $dept->departamento ?? 'Sin departamento' }}</td>
                                <td class="text-right">{{ $dept->dentro }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Sin datos para la fecha seleccionada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Últimos movimientos (día seleccionado)
            </div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Personal</th>
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
                                <td>{{ optional($movement->primera_entrada)->format('d-m H:i') ?? '-' }}</td>
                                <td>{{ optional($movement->ultima_salida)->format('d-m H:i') ?? '—' }}</td>
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
                                <td colspan="6" class="text-center text-muted">Sin registros.</td>
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
        const deptChart = @json($deptChart);
        const contractorPie = @json($contractorPie);
        const hourlySeries = @json($hourlySeries);

        new ApexCharts(document.querySelector("#department-chart"), {
            chart: { type: 'bar', height: 320 },
            series: [{ name: 'En planta', data: deptChart.series }],
            xaxis: { categories: deptChart.labels },
            colors: ['#2962FF'],
            plotOptions: { bar: { horizontal: true, barHeight: '70%' } },
            dataLabels: { enabled: false }
        }).render();

        new ApexCharts(document.querySelector("#contractor-pie"), {
            chart: { type: 'donut', height: 320 },
            series: contractorPie.series,
            labels: contractorPie.labels,
            colors: ['#00C853', '#ECEFF1'],
            legend: { position: 'bottom' }
        }).render();

        new ApexCharts(document.querySelector("#hourly-line"), {
            chart: { type: 'line', height: 320 },
            series: [{ name: 'Entradas', data: hourlySeries }],
            xaxis: { categories: Array.from({ length: 24 }, (_, i) => String(i).padStart(2, '0')) },
            stroke: { curve: 'smooth', width: 3 },
            colors: ['#FF9800'],
            dataLabels: { enabled: false }
        }).render();
    </script>
@endsection
