@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Reporte de Asistencia
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="start_date">Fecha Inicio</label>
                    <input type="text" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', date('d-m-Y')) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="end_date">Fecha Fin</label>
                    <input type="text" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', date('d-m-Y')) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="location_filter">Ubicacion</label>
                    <select class="form-control select2" id="location_filter" name="location_filter">
                        <option value="">Todas las Ubicaciones</option>
                        @foreach($locations as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12 text-right">
                    <button class="btn btn-primary" id="generateReport">Generar Reporte</button>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary h-100">
                        <div class="card-body">
                            <h6 class="card-title mb-1">Asistencias hoy</h6>
                            <h3 class="mb-0" id="kpiTodayTotal">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body">
                            <h6 class="card-title mb-1">Personas unicas hoy</h6>
                            <h3 class="mb-0" id="kpiTodayUnique">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body">
                            <h6 class="card-title mb-1">Registros en rango</h6>
                            <h3 class="mb-0" id="kpiRangeTotal">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-dark h-100">
                        <div class="card-body">
                            <h6 class="card-title mb-1">Personas unicas rango</h6>
                            <h3 class="mb-0" id="kpiRangeUnique">0</h3>
                            <small id="kpiLocations" class="d-block mt-1 text-light-50">Ubicaciones: 0</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h5>Asistencia por Fecha</h5>
                    <div id="attendanceChart"></div>
                </div>
                <div class="col-md-6">
                    <h5>Asistencia por Ubicacion</h5>
                    <div id="locationChart"></div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <h5>Asistencia por Ubicacion y Fecha</h5>
                    <div id="locationDateChart"></div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <h5>Asistencia por Ubicacion y Entidad</h5>
                    <div id="locationDepartmentChart"></div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <h4>Dotacion vs Asistencia por Departamento</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="departmentCrossTable">
                            <thead>
                                <tr>
                                    <th>Departamento</th>
                                    <th>Debe estar (Control Acceso)</th>
                                    <th>Registrados en Asistencia</th>
                                    <th>Diferencia</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <h4>Detalle de Asistencia</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover datatable datatable-attendance-detail">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Personal</th>
                                    <th>RUT</th>
                                    <th>Ubicacion</th>
                                    <th>Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
        $('#end_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            language: 'es',
            dateFormat: 'dd-mm-yy'
        });
        $('#start_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            language: 'es',
            dateFormat: 'dd-mm-yy'
        });
        $('.select2').select2();

        let attendanceTable = $('.datatable-attendance-detail').DataTable({
            processing: true,
            serverSide: false,
            info: false,
            searching: false,
            paging: false,
            order: [[ 0, 'desc' ]],
            columns: [
                { data: 'date' },
                { data: 'personal_name' },
                { data: 'personal_rut' },
                { data: 'location_name' },
                { data: 'time' }
            ]
        });

        let attendanceChart = null;
        let locationChart = null;
        let locationDateChart = null;
        let locationDepartmentChart = null;

        function renderDepartmentCrossTable(data) {
            const tbody = $('#departmentCrossTable tbody');
            tbody.empty();

            const rows = data.departmentCrossData || [];

            if (!rows.length) {
                tbody.append('<tr><td colspan="4" class="text-center text-muted">Sin datos para mostrar</td></tr>');
                return;
            }

            rows.forEach(row => {
                const differenceClass = row.difference > 0 ? 'text-danger' : (row.difference < 0 ? 'text-success' : '');
                tbody.append(`
                    <tr>
                        <td>${row.department}</td>
                        <td>${row.expected}</td>
                        <td>${row.attendance}</td>
                        <td class="${differenceClass}">${row.difference}</td>
                    </tr>
                `);
            });
        }

        function updateKpis(kpis) {
            $('#kpiTodayTotal').text((kpis && kpis.today_total) ? kpis.today_total : 0);
            $('#kpiTodayUnique').text((kpis && kpis.today_unique) ? kpis.today_unique : 0);
            $('#kpiRangeTotal').text((kpis && kpis.range_total) ? kpis.range_total : 0);
            $('#kpiRangeUnique').text((kpis && kpis.range_unique) ? kpis.range_unique : 0);
            $('#kpiLocations').text('Ubicaciones: ' + ((kpis && kpis.locations_with_attendance) ? kpis.locations_with_attendance : 0));
        }

        function renderAttendanceByDateChart(data) {
            let categories = Object.keys(data.chartData || {}).sort();
            let seriesData = categories.map(cat => data.chartData[cat]);

            let options = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                series: [{
                    name: 'Asistencias',
                    data: seriesData
                }],
                xaxis: {
                    categories: categories,
                },
                yaxis: {
                    title: {
                        text: 'Numero de Asistencias'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " asistencias"
                        }
                    }
                }
            };

            if (attendanceChart) {
                attendanceChart.updateOptions(options);
            } else {
                attendanceChart = new ApexCharts(document.querySelector("#attendanceChart"), options);
                attendanceChart.render();
            }
        }

        function renderAttendanceByLocationChart(data) {
            let categories = Object.keys(data.locationChartData || {}).sort();
            let seriesData = categories.map(cat => data.locationChartData[cat]);

            let options = {
                chart: {
                    type: 'pie',
                    height: 350
                },
                labels: categories,
                series: seriesData,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            if (locationChart) {
                locationChart.updateOptions(options);
            } else {
                locationChart = new ApexCharts(document.querySelector("#locationChart"), options);
                locationChart.render();
            }
        }

        function renderAttendanceByLocationDateChart(data) {
            let chartSource = data.locationDateChartData || {};
            let dates = Object.keys(chartSource).sort();
            let locations = [...new Set(Object.values(chartSource).flatMap(obj => Object.keys(obj)))].sort();

            let series = locations.map(loc => {
                return {
                    name: loc,
                    data: dates.map(date => chartSource[date][loc] || 0)
                };
            });

            let options = {
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                    },
                },
                stroke: {
                    width: 1,
                    colors: ['#fff']
                },
                xaxis: {
                    categories: dates,
                },
                yaxis: {
                    title: {
                        text: 'Numero de Asistencias'
                    }
                },
                fill: {
                    opacity: 1
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    offsetX: 40
                },
                series: series
            };

            if (locationDateChart) {
                locationDateChart.updateOptions(options);
            } else {
                locationDateChart = new ApexCharts(document.querySelector("#locationDateChart"), options);
                locationDateChart.render();
            }
        }

        function renderAttendanceByLocationDepartmentChart(data) {
            let chartSource = data.locationDepartmentChartData || {};
            let locations = Object.keys(chartSource).sort();
            let entities = [...new Set(Object.values(chartSource).flatMap(obj => Object.keys(obj)))].sort();

            let series = entities.map(entity => {
                return {
                    name: entity,
                    data: locations.map(loc => (chartSource[loc] && chartSource[loc][entity]) ? chartSource[loc][entity] : 0)
                };
            });

            let options = {
                chart: {
                    type: 'bar',
                    height: 380,
                    stacked: true
                },
                plotOptions: {
                    bar: {
                        horizontal: false
                    }
                },
                xaxis: {
                    categories: locations
                },
                yaxis: {
                    title: {
                        text: 'Numero de Asistencias'
                    }
                },
                legend: {
                    position: 'top'
                },
                series: series
            };

            if (locationDepartmentChart) {
                locationDepartmentChart.updateOptions(options);
            } else {
                locationDepartmentChart = new ApexCharts(document.querySelector("#locationDepartmentChart"), options);
                locationDepartmentChart.render();
            }
        }

        $('#generateReport').on('click', function() {
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            let locationFilter = $('#location_filter').val();

            $.ajax({
                url: "{{ route('admin.attendance.generateReport') }}",
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    start_date: startDate,
                    end_date: endDate,
                    location_filter: locationFilter
                },
                success: function(response) {
                    attendanceTable.clear().rows.add(response.tableData).draw();

                    updateKpis(response.kpis);
                    renderAttendanceByDateChart(response);
                    renderAttendanceByLocationChart(response);
                    renderAttendanceByLocationDateChart(response);
                    renderAttendanceByLocationDepartmentChart(response);
                    renderDepartmentCrossTable(response);
                },
                error: function(xhr) {
                    console.error("Error al generar el reporte:", xhr.responseText);
                    alert("Error al generar el reporte. Por favor, intente de nuevo.");
                }
            });
        });

        $('#generateReport').click();
    });
</script>
@endsection
