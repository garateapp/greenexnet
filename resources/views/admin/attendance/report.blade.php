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
                    <input type="text" class="form-control date" id="start_date" name="start_date" value="{{ old('start_date', date('d-m-Y')) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="end_date">Fecha Fin</label>
                    <input type="text" class="form-control date" id="end_date" name="end_date" value="{{ old('end_date', date('d-m-Y')) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="location_filter">Ubicación</label>
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

            <div class="row">
                <div class="col-md-6">
                    <h5>Asistencia por Fecha</h5>
                    <div id="attendanceChart"></div>
                </div>
                <div class="col-md-6">
                    <h5>Asistencia por Ubicación</h5>
                    <div id="locationChart"></div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <h5>Asistencia por Ubicación y Fecha</h5>
                    <div id="locationDateChart"></div>
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
                                    <th>Ubicación</th>
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
            dateFormat: 'dd-mm-yy' // Explicitly set dateFormat for consistency
        });
        $('#start_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            language: 'es',
            dateFormat: 'dd-mm-yy' // Explicitly set dateFormat for consistency
        });
        $('.select2').select2();

        let attendanceTable = $('.datatable-attendance-detail').DataTable({
            processing: true,
            serverSide: false, // Data will be loaded via custom AJAX, not server-side DataTable
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

        let attendanceChart = null; // Initialize chart variable for attendance by date
        let locationChart = null; // Initialize chart variable for attendance by location
        let locationDateChart = null; // Initialize chart variable for attendance by location and date

        function renderAttendanceByDateChart(data) {
            let categories = Object.keys(data.chartData).sort();
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
                        text: 'Número de Asistencias'
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
            let categories = Object.keys(data.locationChartData).sort();
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
            let dates = Object.keys(data.locationDateChartData).sort();
            let locations = [...new Set(Object.values(data.locationDateChartData).flatMap(Object.keys))].sort();

            let series = locations.map(loc => {
                return {
                    name: loc,
                    data: dates.map(date => data.locationDateChartData[date][loc] || 0)
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
                        text: 'Número de Asistencias'
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

        $('#generateReport').on('click', function() {
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            let locationFilter = $('#location_filter').val();

            console.log('Sending startDate:', startDate);
            console.log('Sending endDate:', endDate);

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
                    // Update DataTable
                    attendanceTable.clear().rows.add(response.tableData).draw();

                    // Update Charts
                    renderAttendanceByDateChart(response);
                    renderAttendanceByLocationChart(response);
                    renderAttendanceByLocationDateChart(response);
                },
                error: function(xhr) {
                    console.error("Error al generar el reporte:", xhr.responseText);
                    alert("Error al generar el reporte. Por favor, intente de nuevo.");
                }
            });
        });

        // Initial report generation on page load
        $('#generateReport').click();
    });
</script>
@endsection
