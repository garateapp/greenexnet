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
                                    <div class="col-sm-12">
                                        <div class="row text-center">

                                            <!-- Columna vacía para espacio -->
                                            <div class="col-1 d-none d-md-block"></div>

                                            <!-- Asistencia Mes -->
                                            <div class="col-12 col-md-2 mb-3">
                                                <div class="border rounded p-3"
                                                    style="border-color: #ff7313; border-style: double;">
                                                    <div class="text-center text-6xl"
                                                        style="font-weight: 600; font-size: 18px;">
                                                        Asistencia Mes
                                                    </div>
                                                    <div class="text-center font-weight-bold">
                                                        <i class="fas fa-user"
                                                            style="color: #8bc34a; font-size: xx-large;"></i>
                                                        <span id="divAsistenciaMes"
                                                            style="color: #8bc34a; font-size: xx-large;"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Asistencia Día -->
                                            <div class="col-12 col-md-2 mb-3">
                                                <div class="border rounded p-3"
                                                    style="border-color: #ff7313; border-style: double;">
                                                    <div class="text-center text-6xl"
                                                        style="font-weight: 600; font-size: 18px;">
                                                        Asistencia Día
                                                    </div>
                                                    <div class="text-center font-weight-bold">
                                                        <i class="fas fa-sun"
                                                            style="color: #8bc34a; font-size: xx-large;"></i>
                                                        <span id="divAsistenciaDia"
                                                            style="color: #8bc34a; font-size: xx-large;"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Asistencia Anual -->
                                            <div class="col-12 col-md-2 mb-3">
                                                <div class="border rounded p-3"
                                                    style="border-color: #ff7313; border-style: double;">
                                                    <div class="text-center" style="font-weight: 600; font-size: 18px;">
                                                        Asist. Anual
                                                    </div>
                                                    <div class="text-center font-weight-bold">
                                                        <i class="fas fa-calendar-alt"
                                                            style="color: #8bc34a; font-size: xx-large;"></i>
                                                        <span id="divAsistenciaAnual"
                                                            style="color: #8bc34a; font-size: xx-large;"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- % Ausencia Día -->
                                            <div class="col-12 col-md-2 mb-3">
                                                <div class="border rounded p-3"
                                                    style="border-color: #ff7313; border-style: double;">
                                                    <div class="text-center" style="font-weight: 600; font-size: 18px;">
                                                        % Ausencia Día
                                                    </div>
                                                    <div class="text-center font-weight-bold">
                                                        <i class="fas fa-chart-bar"
                                                            style="color: #8bc34a; font-size: xx-large;"></i>
                                                        <span id="divPorcentajeAusencia"
                                                            style="color: #8bc34a; font-size: xx-large;"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- % Cobertura -->
                                            <div class="col-12 col-md-2 mb-3">
                                                <div class="border rounded p-3"
                                                    style="border-color: #ff7313; border-style: double;">
                                                    <div class="text-center" style="font-weight: 600; font-size: 18px;">
                                                        % Cobertura
                                                    </div>
                                                    <div class="text-center font-weight-bold">
                                                        <i class="fas fa-calendar-alt"
                                                            style="color: #8bc34a; font-size: xx-large;"></i>
                                                        <span id="divPorcCobertura"
                                                            style="color: #8bc34a; font-size: xx-large;"></span>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <!-- /.row-->
                                <hr />
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-header">
                                                Cumplimiento semanal por Día
                                            </div>
                                            <div class="card-body">
                                                <div class="col-12" id="progressContainer">


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col-->
                                <div class="col-sm-6">
                                    <div class="row">

                                        <!-- /.col-->
                                    </div>
                                    <div class="col-6">

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
                                            <canvas id="attendanceByTurnChart" width="400" height="200"></canvas>
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>

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
                            $("#divPorcentajeAusencia").html(response.porcentajeAusencias);
                            $("#divPorcCobertura").html(response.porcentajeCobertura);
                            const totalEsperado = response
                                .cantPersonalEsperado; // Ajusta este valor según el total de personas esperado

                            // Contenedor donde se generará el contenido
                            const progressContainer = document.getElementById('progressContainer');

                            // Función para traducir días al español (opcional)
                            const translateDay = {
                                Monday: 'Lunes',
                                Tuesday: 'Martes',
                                Wednesday: 'Miércoles',
                                Thursday: 'Jueves',
                                Friday: 'Viernes',
                                Saturday: 'Sábado',
                                Sunday: 'Domingo'
                            };
                            const asistenciasPorDia = response.asistenciasPorDia;
                            // Genera el HTML dinámicamente
                            asistenciasPorDia.forEach((asistencia) => {
                                console.log(asistencia);
                                const porcentaje = parseFloat((asistencia.total / totalEsperado) * 100).toFixed(2);

                                const progressGroup = document.createElement('div');
                                progressGroup.className = 'progress-group mb-4';

                                // Día de la semana
                                const progressGroupPrepend = document.createElement('div');
                                progressGroupPrepend.className = 'progress-group-prepend';
                                progressGroupPrepend.innerHTML =
                                    `<span class="text-body-secondary small">${translateDay[asistencia.dia] || asistencia.dia}</span>`;

                                // Barras de progreso
                                const progressGroupBars = document.createElement('div');
                                progressGroupBars.className = 'progress-group-bars';

                                const progressThinInfo = document.createElement('div');
                                progressThinInfo.className = 'progress progress-thin';

                                const progressBarInfo = document.createElement('div');
                                progressBarInfo.className = 'progress-bar bg-info';
                                progressBarInfo.role = 'progressbar';
                                progressBarInfo.style.width = `${porcentaje}%`;
                                progressBarInfo.setAttribute('aria-valuenow', porcentaje);
                                progressBarInfo.setAttribute('aria-valuemin', 0);
                                progressBarInfo.setAttribute('aria-valuemax', 100);

                                const progressValueInfo = document.createElement('span');
                                progressValueInfo.className = 'progress-value';
                                progressValueInfo.style.color = '#000000';
                                progressValueInfo.style.paddingLeft = '5px';
                                progressValueInfo.innerText = `${Math.round(porcentaje)}%`;

                                progressBarInfo.appendChild(progressValueInfo);
                                progressThinInfo.appendChild(progressBarInfo);

                                // Segunda barra (puedes ajustar el color y el porcentaje según tus datos)
                                const progressThinDanger = document.createElement('div');
                                progressThinDanger.className = 'progress progress-thin';

                                const progressBarDanger = document.createElement('div');
                                progressBarDanger.className = 'progress-bar bg-danger';
                                progressBarDanger.role = 'progressbar';
                                progressBarDanger.style.width = `${100 - porcentaje}%`;
                                progressBarDanger.setAttribute('aria-valuenow', 100 - porcentaje);
                                progressBarDanger.setAttribute('aria-valuemin', 0);
                                progressBarDanger.setAttribute('aria-valuemax', 100);

                                const progressValueDanger = document.createElement('span');
                                progressValueDanger.className = 'progress-value';
                                progressValueDanger.style.color = 'white';
                                progressValueDanger.style.paddingLeft = '5px';
                                progressValueDanger.innerText = `${Math.round(100 - porcentaje)}%`;

                                progressBarDanger.appendChild(progressValueDanger);
                                progressThinDanger.appendChild(progressBarDanger);

                                // Agrega las barras al contenedor
                                progressGroupBars.appendChild(progressThinInfo);
                                progressGroupBars.appendChild(progressThinDanger);

                                // Agrega el día y las barras al contenedor principal
                                progressGroup.appendChild(progressGroupPrepend);
                                progressGroup.appendChild(progressGroupBars);
                                progressContainer.appendChild(progressGroup);
                            });
                        } else {

                        }

                    })
                    .fail(function(response) {
                        console.log(response);
                    });
            </script>
        @endsection
