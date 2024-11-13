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
                                            Distribución por Turno
                                        </div>
                                        <div class="card-body">
                                            <canvas id="cumplimientoChart" width="400" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-header">
                                            Asistencias Ubicación
                                        </div>
                                        <div class="card-body">
                                            <canvas id="AsistenciaxUbicacion" width="400" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">


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
                            //BarChart
                            const labels = response.asistenciaPieChart.map(a => translateDay[a.dia]); // Días de la semana
                            const dataValues = response.asistenciaPieChart.map(a => a.total); // Total de asistencias por día

                            // Configurar el gráfico con los datos obtenidos
                            const ctx = document.getElementById('attendanceChart').getContext('2d');
                            new Chart(ctx, {
                                type: 'bar', // Puedes cambiar 'bar' por otro tipo de gráfico
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Asistencias por día',
                                        data: dataValues,
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color del fondo
                                        borderColor: 'rgba(75, 192, 192, 1)', // Color del borde
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        y: {
                                            beginAtZero: true, // Para que la escala y empiece desde 0
                                            max: 1800 // Ajusta este valor según el total de personal esperado
                                        }
                                    }
                                }
                            });
                            //Asistencia por ubicación
                            const locaciones = response.asistenciasxUbicacion.map(a =>
                                `Ubicación ${a.locacion}`); // Etiquetas para el eje X
                            const totales = response.asistenciasxUbicacion.map(a => a
                                .total); // Los totales de asistencias para el eje Y

                            // Crear el gráfico
                            const ctx2 = document.getElementById('AsistenciaxUbicacion').getContext('2d');
                            new Chart(ctx2, {
                                type: 'bar', // Tipo de gráfico de barras
                                data: {
                                    labels: locaciones, // Las locaciones estarán en el eje X
                                    datasets: [{
                                        label: 'Asistencias',
                                        data: totales, // Los totales de asistencia estarán en el eje Y
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color de las barras
                                        borderColor: 'rgba(75, 192, 192, 1)', // Color del borde de las barras
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        x: {
                                            beginAtZero: true
                                        },
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                            // Asistencia por turno
                            const turnos = response.asistenciasConCumplimiento.map(a =>
                            `Turno ${a.turno}`); // Etiquetas para el eje X
                            const cumplimientos = response.asistenciasConCumplimiento.map(a => a
                            .cumplimiento); // Porcentaje de cumplimiento para el eje Y

                            // Crear el gráfico
                            const ctx3 = document.getElementById('cumplimientoChart').getContext('2d');
                            new Chart(ctx3, {
                                type: 'bar', // Tipo de gráfico de barras
                                data: {
                                    labels: turnos, // Los turnos estarán en el eje X
                                    datasets: [{
                                        label: '% Cumplimiento',
                                        data: cumplimientos, // Los porcentajes de cumplimiento estarán en el eje Y
                                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color de las barras
                                        borderColor: 'rgba(54, 162, 235, 1)', // Color del borde de las barras
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        x: {
                                            beginAtZero: true
                                        },
                                        y: {
                                            beginAtZero: true,
                                            max: 100 // El valor máximo en el eje Y es 100% de cumplimiento
                                        }
                                    }
                                }
                            });

                        } else {

                        }

                    })
                    .fail(function(response) {
                        console.log(response);
                    });
            </script>
        @endsection
