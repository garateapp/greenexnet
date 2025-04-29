@extends('layouts.admin')
@section('content')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>

    <div class="content">
        <div class="row">
            <div class="col-md-2">&nbsp;</div>
            <div class="col-md-8">
                <div class="form-group">
                    <label for="filtroFamilia">Productor</label>
                    <select class="form-control select2" id="cboProductor">
                        <option>Seleccione un Productor</option>
                        @foreach ($producers as $producer)
                            <option value="{{ $producer }}">{{ $producer }}</option>
                        @endforeach
                    </select>
                </div>
                <button id="downloadPdf" class="btn btn-success">Descargar PDF</button>
                <div id="charts"></div>
            </div>
            <div class="col-md-2">&nbsp;</div>
        </div>
    </div>
   
  
    <script>
        $(document).ready(function() {
            $("#cboProductor").select2({
                    placeholder: "Seleccione Productor",
                    allowClear: true
                });
            // Datos pasados desde el controlador
            const data = @json($productorData);
           

            $("#cboProductor").on("change", function() {
                const selectedProductor = $(this).val();
                $("#charts").empty();
                $("#downloadPdf").prop('disabled', !selectedProductor);

                if (!selectedProductor) {
                    return;
                }

                // Filtrar datos para el productor seleccionado
                const filteredData = data.filter(item => item.productor === selectedProductor);
                if (filteredData.length > 0) {
                    generateCharts(filteredData);
                } else {
                    console.warn('No data found for productor:', selectedProductor);
                }
            });
            
            //generateCharts(data);

            // Función para agrupar datos por productor y variedad
            function groupByProducerAndVariety(data) {
                const grouped = {};
                data.forEach(item => {
                    const key = `${item.productor}_${item.variedad}`;
                    if (!grouped[key]) {
                        grouped[key] = {
                            productor: item.productor,
                            variedad: item.variedad,
                            data: []
                        };
                    }
                    grouped[key].data.push({
                        calibre: item.calibre,
                        curvacalibre: parseFloat(item.curvacalibre),
                        rnp_kilo: parseFloat(item.rnp_kilo)
                    });
                });
                return Object.values(grouped);
            }

            // Función para calcular el promedio de rnp_kilo
            function calculateAverageRnpKilo(data)
            {            
            const sum = data.reduce((acc, item) => acc + item.rnp_kilo, 0);
            return sum / data.length;
            }

        // Función para ordenar calibres
        function sortCalibres(data) {
            const calibreOrder = ['5J', '4J', '3J', '2J', 'J', 'XL', 'L'];
            return data.sort((a, b) => calibreOrder.indexOf(a.calibre) - calibreOrder.indexOf(b.calibre));
        }

        // Función para generar un gráfico con ApexCharts
        function createChart(productor, variedad, chartData) {
            const sortedData = sortCalibres(chartData);
            const calibres = sortedData.map(item => item.calibre);
            const curvacalibre = sortedData.map(item => item.curvacalibre);
            const rnpKilo = sortedData.map(item => item.rnp_kilo);
            const avgRnpKilo = calculateAverageRnpKilo(sortedData);
            const avgRnpKiloArray = sortedData.map(() => avgRnpKilo);

            const options = {
                chart: {
                    type: 'line',
                    height: 400,
                    //toolbar: { show: true, export: { csv: false, svg: false, png: true } }
                    toolbar: { show: false } ,
                },
                series: [
                    { name: 'Curva Calibre', type: 'column', data: curvacalibre },
                    { name: 'RNP por Kilo', type: 'line', data: rnpKilo },
                    { name: 'Promedio RNP por Kilo', type: 'line', data: avgRnpKiloArray }
                ],
                xaxis: { categories: calibres, title: { text: 'Calibre' } },
                yaxis: [
                    { title: { text: 'Curva Calibre' }, decimalsInFloat: 2 },
                    { opposite: true, title: { text: 'RNP por Kilo' }, decimalsInFloat: 2 }
                ],
                title: { text: `${productor} - ${variedad}`, align: 'center' },
                stroke: { width: [0, 4, 4] },
                colors: ['#1f77b4', '#ff7f0e', '#2ca02c'],
                dataLabels: {
            enabled: true,
            enabledOnSeries: [0, 1],
            formatter: function(val, opts) {
                const seriesIndex = opts.seriesIndex;
                const dataPointIndex = opts.dataPointIndex;
                const allSeries = opts.w.config.series;
                const values = [
                    allSeries[0].data[dataPointIndex] || 0, // Curva Calibre
                    allSeries[1].data[dataPointIndex] || 0, // RNP por Kilo
                    allSeries[2].data[dataPointIndex] || 0  // Promedio RNP por Kilo
                ];

                // Hide label if too close to another series (within 0.5 units)
                if (seriesIndex === 1 && Math.abs(values[1] - values[0]) < 0.2) return '';
                if (seriesIndex === 2 && (Math.abs(values[2] - values[1]) < 0.2 || Math.abs(values[2] - values[0]) < 0.2)) return '';

                return val.toFixed(2);
            },
            style: {
                fontSize: '22px',
                colors: ['#1f77b4', '#ff7f0e', '#2ca02c'] // 2. Match data label colors to series colors
            },
            background: {
                enabled: true, // Background disabled as per original
                foreColor: '#000000',
                padding: 4,
                background: '#FFFFFF',
                borderRadius: 2,
                borderWidth: 1,
                borderColor: '#ffffff',
                opacity: 0.9
            },
            offsetY: -25,
            dropShadow: { 
                enabled: true, 
                top: 1, 
                left: 1, 
                blur: 1, 
                opacity: 0.65 
            }
        },
                tooltip: {
                    y: [{ formatter: val => val.toFixed(2) }, { formatter: val => val.toFixed(2) }, { formatter: val => val.toFixed(2) }]
                }
            };

            const chartId = `chart_${productor}_${variedad}`.replace(/ /g, '_').replace(/[^a-zA-Z0-9_]/g, '');
            $('#charts').append(`<div class="chart-container" id="${chartId}"></div>`);

            const chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
            chart.render();
            return chartId;
        }

        // Función principal para generar todos los gráficos
        function generateCharts(data) {
            const groupedData = groupByProducerAndVariety(data);
            groupedData.forEach(group => {
                createChart(group.productor, group.variedad, group.data);
            });
        }

        // Capturar gráficos como imágenes y enviar al servidor
        $('#downloadPdf').click(function() {
            const chartContainers = $('.chart-container');
            const chartImages = [];
            let processed = 0;

            chartContainers.each(function() {
                const chartId = $(this).attr('id');
                html2canvas(document.querySelector(`#${chartId}`), { scale: 2 }).then(canvas => {
                    chartImages.push({
                        id: chartId,
                        image: canvas.toDataURL('image/png')
                    });
                    processed++;
                    if (processed === chartContainers.length) {
                        // Enviar imágenes al servidor
                        $.ajax({
                            url: '/admin/liq-cx-cabeceras/download-pdf/'+$("#cboProductor").val(),
                            method: 'POST',
                            data: { chartImages: chartImages },
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function(response) {
                                // Descargar el PDF
                                const link = document.createElement('a');
                                link.href = response.url;
                                link.download = response.filename;
                                link.click();
                            },
                            error: function() {
                                alert('Error al generar el PDF.');
                            }
                        });
                    }
                });
            });
        });
    });
    </script>
@endsection