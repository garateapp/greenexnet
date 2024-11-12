@extends('layouts.admin')
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        Panel de Control
                    </div>
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">Resumen Asistencia</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-5"
                                                style="border: 2px;border-color: #ff7313; border-radius: 6px; border-style: double;padding-top:10px; ">
                                                <div class="border-start border-start-4 border-start-info px-3 mb-3">
                                                    <div class="text-center text-6xl"
                                                        style="font-weight: 600;font-size: 20px;">
                                                        Asistencias
                                                        Mes</div>

                                                    <div class=" text-center font-weight-bold">
                                                        <i class="fas fa-user"
                                                            style="color: #8bc34a;font-size:xx-large;"></i>
                                                        <span style="color: #8bc34a;font-size:xx-large;"
                                                            id="divAsistenciaMes"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col-->
                                            <div class="col-1"></div>
                                            <div class="col-5"
                                                style="border: 2px;border-color: #ff7313; border-radius: 6px; border-style: double;padding-top:10px; ">
                                                <div class="border-start border-start-4 border-start-danger px-3 mb-3">
                                                    <div class="text-center text-6xl"
                                                        style="font-weight: 600;font-size: 20px;">Asistencia
                                                        Día
                                                    </div>
                                                    <div class=" text-center font-weight-bold">
                                                        <i class="fas fa-sun"
                                                            style="color: #8bc34a;font-size:xx-large;"></i>
                                                        <span style="color: #8bc34a;font-size:xx-large;"
                                                            id="divAsistenciaDia">
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-1"></div>
                                                <!-- /.col-->
                                            </div>
                                        </div>
                                        <!-- /.row-->
                                        <hr class="mt-0">
                                        <div class="progress-group mb-4">
                                            <div class="progress-group-prepend"><span
                                                    class="text-body-secondary small">Lunes</span></div>
                                            <div class="progress-group-bars ">
                                                <div class="progress progress-thin ">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width: 34%"
                                                        aria-valuenow="34" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="progress-value"
                                                            style="color: white; padding-left: 5px;">34%</span>
                                                    </div>
                                                </div>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                        style="width: 78%" aria-valuenow="78" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <span class="progress-value"
                                                            style="color: white; padding-left: 5px;">78%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress-group mb-4">
                                            <div class="progress-group-prepend"><span
                                                    class="text-body-secondary small">Martes</span></div>
                                            <div class="progress-group-bars">
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width: 56%"
                                                        aria-valuenow="56" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="progress-value"
                                                            style="color: white; padding-left: 5px;">56%</span>
                                                    </div>
                                                </div>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                        style="width: 94%" aria-valuenow="94" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <span class="progress-value"
                                                            style="color: white; padding-left: 5px;">94%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress-group mb-4">
                                            <div class="progress-group-prepend"><span
                                                    class="text-body-secondary small">Miércoles</span></div>
                                            <div class="progress-group-bars">
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width: 12%"
                                                        aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                        style="width: 67%" aria-valuenow="67" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress-group mb-4">
                                            <div class="progress-group-prepend"><span
                                                    class="text-body-secondary small">Jueves</span></div>
                                            <div class="progress-group-bars">
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                        style="width: 43%" aria-valuenow="43" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                        style="width: 91%" aria-valuenow="91" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress-group mb-4">
                                            <div class="progress-group-prepend"><span
                                                    class="text-body-secondary small">Viernes</span></div>
                                            <div class="progress-group-bars">
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                        style="width: 22%" aria-valuenow="22" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                        style="width: 73%" aria-valuenow="73" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress-group mb-4">
                                            <div class="progress-group-prepend"><span
                                                    class="text-body-secondary small">Sábado</span></div>
                                            <div class="progress-group-bars">
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                        style="width: 53%" aria-valuenow="53" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                        style="width: 82%" aria-valuenow="82" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress-group mb-4">
                                            <div class="progress-group-prepend"><span
                                                    class="text-body-secondary small">Domingo</span></div>
                                            <div class="progress-group-bars">
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                        style="width: 9%" aria-valuenow="9" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                        style="width: 69%" aria-valuenow="69" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-->
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-5"
                                                style="border: 2px;border-color: #ff7313; border-radius: 6px; border-style: double;padding-top:10px; ">
                                                <div class="border-start border-start-4 border-start-warning px-3 mb-3">
                                                    <div class="small text-body-secondary text-truncate"
                                                        style="font-weight: 600;font-size: 20px;">
                                                        Asistencia
                                                        Anual</div>

                                                    <div class=" text-center font-weight-bold">
                                                        <i class="fas fa-calendar-alt"
                                                            style="color: #8bc34a;font-size:xx-large;"></i>
                                                        <span style="color: #8bc34a;font-size:xx-large;"
                                                            id="divAsistenciaAnual">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1"></div>
                                            <!-- /.col-->
                                            <div class="col-5"
                                                style="border: 2px;border-color: #ff7313; border-radius: 6px; border-style: double;padding-top:10px;padding-top:10px;">
                                                <div class="border-start border-start-4 border-start-success px-3 mb-3">
                                                    <div class="small text-body-secondary text-truncate"
                                                        style="font-weight: 600;font-size: 20px;">% de Ausencia
                                                        Dia
                                                    </div>

                                                    <div class=" text-center font-weight-bold">
                                                        <i class="fas fa-chart-bar"
                                                            style="color: #8bc34a;font-size:xx-large;"></i>
                                                        <span style="color: #8bc34a;font-size:xx-large;"
                                                            id="divPorcentajeAusencia">
                                                        </span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-1"></div>
                                            <!-- /.col-->
                                        </div>
                                        <!-- /.row-->

                                        <!-- /.row--><br>

                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                Asistencia por Ubicación
                                            </div>
                                            <div class="card-body">
                                                <canvas id="attendanceChart" width="400" height="200"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="card">
                                            <div class="card-header">
                                                Asistencias Diarias
                                            </div>
                                            <div class="card-body">
                                                <canvas id="dailyAttendanceChart" width="400" height="200"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="card">
                                            <div class="card-header">
                                                Distribución por Turno
                                            </div>
                                            <div class="card-body">
                                                <canvas id="attendanceByTurnChart" width="400"
                                                    height="200"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                Gráfico Dispersión de Asistencia
                                            </div>
                                            <div class="card-body">
                                                <canvas id="scatterPlot" width="400" height="200"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="card">
                                            <div class="card-header">
                                                Asistencia por Turno y Ubicación
                                            </div>
                                            <div class="card-body">
                                                <canvas id="turnoLocacionChart" width="900" height="400"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <canvas id="turnoLocacionChart"></canvas>
                                    <script>
                                        fetch('/admin/datos-cajas/getAttendanceData')
                                            .then(response => response.json())
                                            .then(data => {
                                                const locationCounts = {};

                                                // Contar asistencias por locación
                                                data.forEach(item => {
                                                    if (locationCounts[item.locacion_id]) {
                                                        locationCounts[item.locacion_id]++;
                                                    } else {
                                                        locationCounts[item.locacion_id] = 1;
                                                    }
                                                });

                                                const labels = Object.keys(locationCounts);
                                                const values = Object.values(locationCounts);

                                                const ctx1 = document.getElementById('attendanceChart').getContext('2d');
                                                new Chart(ctx1, {
                                                    type: 'bar',
                                                    data: {
                                                        labels: labels,
                                                        datasets: [{
                                                            label: 'Asistencias por Ubicación',
                                                            data: values,
                                                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                            borderColor: 'rgba(75, 192, 192, 1)',
                                                            borderWidth: 1
                                                        }]
                                                    },
                                                    options: {
                                                        scales: {
                                                            y: {
                                                                beginAtZero: true
                                                            }
                                                        }
                                                    }
                                                });
                                            });
                                    </script>
                                    <script>
                                        async function fetchAttendanceData() {
                                            const response = await fetch("{{ url('/admin/datos-cajas/daily') }}");
                                            const json = await response.json();
                                            return json;
                                        }

                                        async function createDailyAttendanceChart() {
                                            const attendanceData = await fetchAttendanceData();
                                            const ctx = document.getElementById('dailyAttendanceChart').getContext('2d');

                                            new Chart(ctx, {
                                                type: 'line',
                                                data: {
                                                    labels: attendanceData.labels, // Fechas
                                                    datasets: [{
                                                        label: 'Asistencias Diarias',
                                                        data: attendanceData.data, // Cantidad de asistencias por día
                                                        borderColor: 'rgba(75, 192, 192, 1)',
                                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                        borderWidth: 2,
                                                        fill: true,
                                                    }]
                                                },
                                                options: {
                                                    responsive: true,
                                                    scales: {
                                                        x: {
                                                            type: 'time',
                                                            time: {
                                                                unit: 'day'
                                                            },
                                                            title: {
                                                                display: true,
                                                                text: 'Fecha'
                                                            }
                                                        },
                                                        y: {
                                                            title: {
                                                                display: true,
                                                                text: 'Asistencias'
                                                            },
                                                            beginAtZero: true
                                                        }
                                                    },
                                                    plugins: {
                                                        legend: {
                                                            position: 'top',
                                                        },
                                                        title: {
                                                            display: true,
                                                            text: 'Asistencias Diarias'
                                                        }
                                                    }
                                                }
                                            });
                                        }

                                        createDailyAttendanceChart();
                                    </script>
                                    <script>
                                        async function fetchAttendanceByTurnData() {
                                            const response = await fetch("/admin/datos-cajas/by-turn");
                                            const json = await response.json();
                                            return json;
                                        }

                                        async function createAttendanceByTurnChart() {
                                            const attendanceData = await fetchAttendanceByTurnData();
                                            const ctx2 = document.getElementById('attendanceByTurnChart').getContext('2d');

                                            new Chart(ctx2, {
                                                type: 'pie',
                                                data: {
                                                    labels: '', // Nombres de los turnos
                                                    datasets: [{
                                                        label: 'Asistencias por Turno',
                                                        data: attendanceData.data, // Cantidad de asistencias por turno
                                                        backgroundColor: [
                                                            'rgba(255, 99, 132, 0.6)',
                                                            'rgba(54, 162, 235, 0.6)',
                                                            'rgba(255, 206, 86, 0.6)',
                                                            'rgba(75, 192, 192, 0.6)',
                                                            'rgba(153, 102, 255, 0.6)',
                                                            'rgba(255, 159, 64, 0.6)',
                                                            'rgba(199, 199, 199, 0.6)'
                                                        ],
                                                        borderColor: [
                                                            'rgba(255, 99, 132, 1)',
                                                            'rgba(54, 162, 235, 1)',
                                                            'rgba(255, 206, 86, 1)',
                                                            'rgba(75, 192, 192, 1)',
                                                            'rgba(153, 102, 255, 1)',
                                                            'rgba(255, 159, 64, 1)',
                                                            'rgba(199, 199, 199, 1)'
                                                        ],
                                                        borderWidth: 1
                                                    }]
                                                },
                                                options: {
                                                    responsive: true,
                                                    plugins: {
                                                        legend: {
                                                            position: 'top',
                                                        },
                                                        title: {
                                                            display: true,
                                                            text: 'Distribución de Asistencias por Turno'
                                                        }
                                                    }
                                                }
                                            });
                                        }

                                        createAttendanceByTurnChart();
                                    </script>
                                    <script>
                                        // Recibir los datos procesados desde el backend
                                        async function fetchAttendanceData() {
                                            const response = await fetch("/admin/datos-cajas/getAttendanceData");
                                            const json = await response.json();
                                            return json;
                                        }
                                        async function createAttendanceChart() {
                                            const data = await fetchAttendanceData();


                                            // Preparar etiquetas y datos
                                            const locaciones = Object.keys(data); // Locaciones (x-axis)
                                            const turnos = [...new Set(Object.values(data).flatMap(turnoData => Object.keys(
                                                turnoData)))]; // Turnos únicos

                                            // Preparar datasets
                                            const datasets = turnos.map(turno => {
                                                return {
                                                    label: `Turno ${turno}`,
                                                    data: locaciones.map(loc => data[loc][turno] ||
                                                        0), // Cantidad de asistencias por turno en cada locación
                                                    backgroundColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.5)`,
                                                    borderWidth: 1
                                                };
                                            });

                                            // Configurar el gráfico
                                            const ctx = document.getElementById('turnoLocacionChart').getContext('2d');
                                            new Chart(ctx, {
                                                type: 'bar',
                                                data: {
                                                    labels: locaciones,
                                                    datasets: datasets
                                                },
                                                options: {
                                                    responsive: true,
                                                    plugins: {
                                                        legend: {
                                                            position: 'top'
                                                        },
                                                        tooltip: {
                                                            callbacks: {
                                                                label: function(context) {
                                                                    const label = context.dataset.label || '';
                                                                    return `${label}: ${context.raw} asistencias`;
                                                                }
                                                            }
                                                        }
                                                    },
                                                    scales: {
                                                        x: {
                                                            stacked: true,
                                                            title: {
                                                                display: true,
                                                                text: 'Locación'
                                                            }
                                                        },
                                                        y: {
                                                            stacked: true,
                                                            title: {
                                                                display: true,
                                                                text: 'Cantidad de Asistencias'
                                                            },
                                                            beginAtZero: true
                                                        }
                                                    }
                                                }
                                            });
                                        }
                                        createAttendanceChart();
                                    </script>
                                    <script>
                                        async function fetchScatterData() {
                                            const response = await fetch("/admin/datos-cajas/getScatterPlotData");
                                            const json = await response.json();
                                            return json;
                                        }
                                        async function createScatterChart() {
                                            const scatterData = await fetchScatterData();


                                            var ctx = document.getElementById('scatterPlot').getContext('2d');
                                            var scatterPlot = new Chart(ctx, {
                                                type: 'scatter',
                                                data: {
                                                    datasets: [{
                                                        label: 'Asistencia en Turnos y Ubicaciones',
                                                        data: scatterData.map(function(item) {
                                                            return {
                                                                x: item.x, // personal_id
                                                                y: item.y, // locacion_id
                                                                r: 5, // tamaño del punto
                                                                backgroundColor: `rgba(${item.turno * 10}, ${item.turno * 20}, ${item.turno * 30}, 0.5)`, // color dependiendo del turno
                                                            };
                                                        }),
                                                        borderColor: 'rgba(0, 0, 0, 1)',
                                                        borderWidth: 1
                                                    }]
                                                },
                                                options: {
                                                    responsive: true,
                                                    scales: {
                                                        x: {
                                                            type: 'linear',
                                                            position: 'bottom',
                                                            title: {
                                                                display: true,
                                                                text: 'Personal ID'
                                                            }
                                                        },
                                                        y: {
                                                            title: {
                                                                display: true,
                                                                text: 'Ubicación ID'
                                                            }
                                                        }
                                                    }
                                                }
                                            });
                                        }
                                        createScatterChart();
                                    </script>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endsection
            @section('scripts')
                @parent
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
                <script>
                    $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'GET',
                            url: '/admin/reporteria/getDatosgenerales',

                        })
                        .done(function(response) {

                            if (response) {
                                console.log(response);
                                $("#divAsistenciaMes").html(response.asistenciaMes);
                                $("#divAsistenciaDia").html(response.asistenciaDia);
                                $("#divAsistenciaAnual").html(response.asistenciaYear)
                                $("#divPorcentajeAusencia").html(response.porcentajeAusencias)

                            } else {

                            }

                        })
                        .fail(function(response) {
                            console.log(response);
                        });
                </script>
            @endsection
