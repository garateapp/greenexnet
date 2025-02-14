@extends('layouts.admin')
@section('content')
    <style>
        tr.group {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }

        .group-header {
            font-weight: bold;
            cursor: pointer;
            background-color: #f2f2f2;
        }

        .details-table {
            margin: 10px 0;
            width: 100%;
            border-collapse: collapse;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .text-bold {
            font-weight: bolder;
        }


        .bg-info {
            background-color: #81b940 !important;
        }

        .bg-danger {
            background-color: #ff7313 !important;
        }

        #cerrarFiltros {
            cursor: pointer;
        }

        #chart-container {
            position: relative;
            width: 90%;
            /* Ajusta el ancho del gráfico */
            margin: auto;
            /* Centra el contenedor */
        }

        canvas {
            display: block;
            max-width: 100%;
            /* Asegura que el canvas no se desborde */
            height: auto !important;
            /* Mantiene la proporción del gráfico */
        }

        /* Estilo para hacer el gráfico responsivo */
        #chart-container {
            position: relative;
            width: 90%;
            /* Ajusta el ancho del gráfico */
            margin: auto;
            /* Centra el contenedor */
        }

        canvas {
            display: block;
            max-width: 100%;
            /* Asegura que el canvas no se desborde */
            /* height: auto !important; */
            /* Mantiene la proporción del gráfico */
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 90%;
        }

        th,
        td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: #f4f4f4;
        }

        .total-row {
            font-weight: bold;
            background-color: #e8f0fe;
        }

        #kilosPorDia {
            width: 100%;
            /* El tamaño que necesites */
            height: 400px;
            /* Establece un tamaño fijo o máximo */
            max-height: 600px;
            /* Evita el crecimiento infinito */
            overflow: auto;
            /* Permite desplazamiento si el contenido es más grande */
        }

        #tableContainer {
            width: 100%;
            overflow-x: auto;
        }

        #rendimientoTable {
            width: 100%;
            white-space: nowrap;
        }

        /* Agrega bordes a la izquierda de cada conjunto de columnas de cliente */
        .cliente-header {
            border-left: 2px solid #000;
            /* Borde izquierdo negro */
            background-color: #f8f9fa;
            /* Color de fondo opcional */
            text-align: center;
        }

        /* Agrega bordes en los datos de cada cliente */
        td:nth-child(n+10) {
            /* Suponiendo que los datos de clientes empiezan en la columna 10 */
            border-left: 2px solid #000;
        }

        /* Borde izquierdo para la primera columna del grupo */
        .inicio-cliente {
            border-left: 3px solid black !important;
        }

        /* Borde derecho para la última columna del grupo */
        .fin-cliente {
            border-right: 3px solid black !important;
        }

        /* Borde entre columnas de cada cliente */
        [id="rendimientoTable"] th,
        [id="rendimientoTable"] td {
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }

        /* Separación entre clientes */
        [id="rendimientoTable"] td[class^="cliente-"] {
            border-left: 1px solid #ccc;
        }

        [id="rendimientoTable"] th[class^="cliente-"] {
            border-left: 1px solid #ccc;
        }
    </style>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div id="filters">
                <div class="card">
                    <div class="card-header">
                        Filtro General
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-3">
                                <label for="filtroWeek">Semana Arribo</label>
                                <select id="filtroWeek" class="form-control select2" multiple="multiple"></select>
                            </div>
                            <div class="col-3">
                                <label for="filtroNave">Nave</label>
                                <select id="filtroNave" class="form-control select2" multiple="multiple"></select>
                            </div>
                            <div class="col-3">
                                <label for="filtroCliente">Cliente</label>
                                <select id="filtroCliente" class="form-control select2" multiple="multiple"></select>
                            </div>

                            <div class="col-3">
                                <label for="filtroVariedad">Variedad</label>
                                <select id="filtroVariedad" class="form-control select2" multiple="multiple"></select>
                            </div>
                            <div class="col-3">
                                <label for="filtroCalibre">Calibre</label>
                                <select id="filtroCalibre" class="form-control select2" multiple="multiple"></select>
                            </div>
                            <div class="col-3">
                                <label for="filtroEtiqueta">Etiqueta</label>
                                <select id="filtroEtiqueta" class="form-control select2" multiple="multiple"></select>
                            </div>
                            <div class="col-3">
                                <label for="filtroEmbalaje">Embalaje</label>
                                <select id="filtroEmbalaje" class="form-control select2" multiple="multiple"></select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    Total FOB USD por Cliente
                </div>
                <div class="card-body">
                    <canvas id="graficoFOB" style="display: none;"></canvas>
                    <canvas id="graficoPieFOB" style="max-height: 350px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    FOB Promedio por Caja
                </div>
                <div class="card-body">
                    <canvas id="graficoPromedioFOB"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">


    </div>


    <div class="card">
        <div class="card-header">
            Comparativo General
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="ComparativaTabs" role="tablist">
                <!-- Pestaña Comparativa General -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="ranking-tab" data-bs-toggle="tab" data-bs-target="#ranking" type="button"
                        role="tab" aria-controls="ranking" aria-selected="false">
                        Comparativas Generales
                    </button>
                </li>
                <!-- Pestaña Comparativa x Cliente -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="comparativa-tab" data-bs-toggle="tab" data-bs-target="#comparativa"
                        type="button" role="tab" aria-controls="comparativa" aria-selected="true">
                        Comparativa por Clientes
                    </button>
                </li>
                <!-- Pestaña de Rendimiento -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rendimiento-tab" data-bs-toggle="tab" data-bs-target="#rendimiento"
                        type="button" role="tab" aria-controls="rendimiento" aria-selected="false">
                        Análisis de Rendimiento
                    </button>
                </li>

            </ul>
            <div class="tab-content" id="reporteTabsContent">
                <div class="tab-pane fade show active" id="ranking" role="tabpanel" aria-labelledby="ranking-tab">

                    <div class="row">

                        <div class="card">
                            <div class="card-header">Rendimiento por Cliente a Nivel General</div>
                            <div class="card-body">

                                <div id="tableContainer">
                                    <table id="rendimientoGeneralTable" class="table table-striped table-bordered"
                                        style="width:100%">
                                        <thead>
                                            <tr id="RGtotalsRow" style="font-weight: bold; background-color: #f2f2f2;">
                                            </tr>
                                            <tr id="RGheaderRow"></tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>





                <!-- Contenido de la Pestaña  -->
                <div class="tab-pane fade" id="comparativa" role="tabpanel" aria-labelledby="comparativa-tab">
                    <div class="row">
                        <div class="col-12">
                            <table id="comparativaTable" class="table table-striped table-bordered">
                                <thead>
                                    <th></th>
                                    <th>Nave</th>
                                    <th>Semana ETA</th>
                                    <th>Cliente</th>
                                    <th>Etiqueta</th>
                                    <th>Embalaje</th>
                                    <th>Variedad</th>
                                    <th>Calibre</th>
                                    <th>Cantidad</th>
                                    <th>FOB USD</th>
                                    <th>Prom. x Caja</th>
                                    {{-- <th>Monto USD</th> --}}
                                </thead>
                                <tbody>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="rendimiento" role="tabpanel" aria-labelledby="rendimiento-tab">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    Filtros
                                </div>
                                <div class="body">
                                    <div class="col-4">
                                        <label for="filtroClientePrincipal">Cliente Principal</label>
                                        <select id="filtroClientePrincipal" class="form-control"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card">
                                    <div class="card-header">
                                        Rendimiento por Cliente
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div id="resumenContainer"></div>
                                        </div>
                                        <div id="tableContainer">
                                            <table id="rendimientoTable" class="display nowrap" style="width:100%">
                                                <thead>
                                                    <tr id="totalsRow"
                                                        style="font-weight: bold; background-color: #f2f2f2;">

                                                    </tr> <!-- Fila de totales -->
                                                    <tr id="headerRow"></tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <!-- JS de FixedColumns -->

            </script>
            <script>
                document.addEventListener("DOMContentLoaded", async () => {
                    let originalData = []; // Datos originales sin filtrar
                    let GroupedData = []; //DAtos Agrupados
                    // Función para obtener valores únicos de una columna específica
                    async function getUniqueValues(column) {
                        const uniqueValues = new Set();
                        originalData.forEach(item => {
                            const value = item[column];
                            if (value) uniqueValues.add(value);
                        });
                        return Array.from(uniqueValues).filter(value => value !== null && value !== "");
                    }
                    // Obtener los datos de las liquidaciones

                    async function loadData() {
                        try {
                            const response = await fetch(
                                '{{ route('admin.reporteria.obtenerliquidacionesagrupadas') }}');
                            data = await response.json(); // Guardar los datos originales
                            originalData = data.data;
                            GroupedData = data.grouped;
                            groupedGral = data.groupedGral;
                            console.log(data);
                            //groupedData = data.agrupacionComparativa;
                            await llenarFiltrosClientes();
                            filterTable();
                            actualizarGraficos();
                            // actualizarGraficosComparacion();
                            updateComparacionFOB();
                            cargarTabla(GroupedData);
                            cargaRendimientoGeneral(GroupedData);
                            // cargarTabla(originalData); // Llenar la tabla con todos los datos
                            // calcularTotales(originalData); // Calcular totales iniciales
                            await fillSelects(); // Llenar los selectores

                        } catch (error) {
                            console.error("Error al cargar datos:", error);
                        }
                    }

                    function cargarTabla(datos) {
                        let table = $('#comparativaTable').DataTable({
                            destroy: true, // Elimina la tabla previa si existe
                            data: datos,
                            columns: [{
                                    className: 'dt-control',
                                    orderable: false,
                                    data: null,
                                    defaultContent: ''
                                },


                                {
                                    data: 'nave',
                                    name: 'nave'
                                },
                                {
                                    data: 'ETA_Week',
                                    name: 'ETA_Week'
                                },
                                {
                                    data: 'cliente',
                                    name: 'Cliente'
                                },
                                {
                                    data: 'etiqueta',
                                    name: 'Etiqueta'
                                },
                                {
                                    data: 'embalaje',
                                    name: 'Embalaje'
                                },
                                {
                                    data: 'variedad',
                                    name: 'variedad'
                                },
                                {
                                    data: 'calibre',
                                    name: 'calibre'
                                },

                                {
                                    data: 'Cantidad',
                                    name: 'Cantidad'
                                },
                                {
                                    data: 'FOB_USD',
                                    name: 'FOB_USD',
                                    render: function(data) {
                                        return parseFloat(data).toLocaleString();
                                    }
                                },

                                {
                                    data: 'PromedioFOBxCaja',
                                    name: 'PromedioFOBxCaja',
                                    render: function(data) {
                                        return parseFloat(data).toLocaleString();
                                    }
                                }
                            ],
                            columnDefs: [{
                                target: 0,
                                orderable: false
                            }],
                            select: false,
                        });
                        // Manejar clic en el botón de detalle
                        $('#comparativaTable tbody').on('click', 'td.dt-control', function() {
                            let tr = $(this).closest('tr');
                            let row = table.row(tr);

                            if (row.child.isShown()) {
                                row.child.hide();
                                tr.removeClass('shown');
                            } else {
                                let data = row.data(); // Datos de la fila principal

                                // Filtrar GroupedData según los criterios
                                let datosRelacionados = GroupedData.filter(item =>
                                    item.variedad === data.variedad &&
                                    item.calibre === data.calibre &&
                                    item.embalaje === data.embalaje &&
                                    item.nave === data.nave
                                );

                                // Construcción de la subtabla
                                // Construcción de la subtabla + contenedor de gráfico
                                let subTable = `<div style="display: flex;">
                                <table class="table table-bordered" style="width: 60%;">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>FOB USD</th>
                                            <th>Costo por Oportunidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                                let labels = [],
                                    costos = [],
                                    colores = [];

                                datosRelacionados.forEach(item => {
                                    let costoOportunidad = parseFloat(data
                                        .PromedioFOBxCaja)-parseFloat(item.PromedioFOBxCaja);

                                    // Guardar datos para el gráfico
                                    labels.push(item.cliente);
                                    costos.push(costoOportunidad);
                                    colores.push(costoOportunidad >= 0 ? 'green' :
                                        'red'); // Color según valor

                                    // Añadir a la tabla
                                    subTable += `<tr>
                                <td>${item.cliente}</td>
                                <td>${parseFloat(item.FOB_USD).toLocaleString()}</td>
                                <td style="color:${costoOportunidad >= 0 ? 'green' : 'red'};">
                                    ${costoOportunidad.toLocaleString()}
                                </td>
                            </tr>`;
                                });

                                subTable += `</tbody></table>
                            <div style="width: 40%;">
                                <canvas id="chart_${data.cliente.replace(/\s+/g, '_')}"></canvas>
                            </div>
                        </div>`;

                                // Mostrar la tabla anidada y el gráfico
                                row.child(subTable).show();
                                tr.addClass('shown');

                                // Renderizar el gráfico después de mostrar el HTML
                                setTimeout(() => {
                                    let ctxmini = document.getElementById(
                                        `chart_${data.cliente.replace(/\s+/g, '_')}`).getContext(
                                        '2d');
                                    new Chart(ctxmini, {
                                        type: 'bar',
                                        data: {
                                            labels: labels,
                                            datasets: [{
                                                label: 'Costo por Oportunidad (USD)',
                                                data: costos,
                                                backgroundColor: colores
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                legend: {
                                                    display: false
                                                }
                                            },
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
                                }, 300); // Pequeño delay para asegurar que el canvas se ha creado
                            }
                        });
                    }

                    function filterTable() {
                        let filteredData = originalData;

                        const filters = {
                            variedad: $("#filtroVariedad").val() || [],
                            calibre: $("#filtroCalibre").val() || [],
                            etiqueta: $("#filtroEtiqueta").val() || [],
                            nave: $("#filtroNave").val() || [],
                            nombre_fantasia: $("#filtroCliente").val() || [],
                            ETA_Week: $("#filtroWeek").val() || [],
                            embalaje: $("#filtroEmbalaje").val() || []

                        };

                        // Convertir valores en arrays en caso de que sean únicos
                        Object.keys(filters).forEach(key => {
                            if (!Array.isArray(filters[key])) {
                                filters[key] = [filters[key]];
                            }
                        });


                        // Aplicar filtros solo si hay valores seleccionados
                        filteredData = originalData.filter(item => {
                            return Object.entries(filters).every(([key, values]) => {
                                let itemValue = item[key];

                                // Solo para embalaje, si es null o undefined, lo cambiamos por "Sin Embalaje"
                                if (key === "embalaje" && (itemValue === null || itemValue ===
                                        undefined)) {
                                    itemValue = "Sin Embalaje";
                                }

                                // Convertimos a string solo si existe un valor
                                itemValue = itemValue !== undefined && itemValue !== null ? itemValue
                                    .toString() : "";

                                return values.length === 0 || values.includes(itemValue);
                            });
                        });
                        actualizarGraficoPieFOB(filteredData);
                        // cargarTabla(filteredData);
                        // calcularTotales(filteredData);
                    }
                    async function fillSelects() {
                        try {
                            const fields = {
                                variedad: "filtroVariedad",
                                calibre: "filtroCalibre",
                                etiqueta: "filtroEtiqueta",
                                nave: "filtroNave",
                                cliente: "filtroCliente",
                                ETA_Week: "filtroWeek",
                                embalaje: "filtroEmbalaje"

                            };

                            for (const [field, selectId] of Object.entries(fields)) {
                                const values = await getUniqueValues(field);
                                const selectElement = document.getElementById(selectId);

                                // Limpiar opciones previas
                                selectElement.innerHTML = '<option value="">Todos</option>';

                                values.forEach(value => {
                                    const option = document.createElement("option");
                                    option.value = value;
                                    option.textContent = value;
                                    selectElement.appendChild(option);
                                });

                                // Inicializar Select2 en el select si aún no está inicializado
                                if (!$(selectElement).hasClass("select2-hidden-accessible")) {
                                    $(selectElement).select2();
                                }

                                // Escuchar cambios en cada select para filtrar la tabla
                                $(selectElement).on("change", filterTable);
                            }
                        } catch (error) {
                            console.error("Error llenando los selectores:", error);
                        }
                    }

                    function formatNumber2(number) {
                        return new Intl.NumberFormat('es-CL', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(number);
                    }
                    loadData();
                    $(".select2").on("change", function() {
                        actualizarGraficos();
                        if ($("#filtroClientePrincipal").val()) {
                            // actualizarGraficosComparacion();
                            cargaRendimiento(GroupedData);
                            updateComparacionFOB();

                        }
                    });

                    function obtenerFiltrosSeleccionados() {
                        return {
                            nave: $("#filtroNave").val() || [],
                            cliente: $("#filtroCliente").val() || [],
                            variedad: $("#filtroVariedad").val() || [],
                            calibre: $("#filtroCalibre").val() || [],
                            etiqueta: $("#filtroEtiqueta").val() || [],
                            ETA_Week: $("#filtroWeek").val() || [],
                            embalaje: $("#filtroEmbalaje").val() || []
                        };
                    }

                    function filtrarDatos() {
                        let filtros = obtenerFiltrosSeleccionados();
                        // console.log(originalData.map(item => item.embalaje)); // 📌 Esto imprimirá todos los valores de "embalaje"

                        return originalData.filter(d =>
                            (filtros.nave.length === 0 || filtros.nave.includes(d.nave)) &&
                            (filtros.cliente.length === 0 || filtros.cliente.includes(d.cliente)) &&
                            (filtros.variedad.length === 0 || filtros.variedad.includes(d.variedad)) &&
                            (filtros.calibre.length === 0 || filtros.calibre.includes(d.calibre)) &&
                            (filtros.etiqueta.length === 0 || filtros.etiqueta.includes(d.etiqueta)) &&
                            (filtros.ETA_Week.length === 0 || filtros.ETA_Week.includes(d.ETA_Week.toString()))
                            //&& (filtros.embalaje.lenght === 0 || filtros.embalaje.includes(d.embalaje))
                        );
                    }
                    var graficoFOB, graficoPromedio;



                    function actualizarGraficos() {
                        let datosFiltrados = filtrarDatos();

                        let clientes = [...new Set(datosFiltrados.map(d => d.cliente))];
                        let fobTotales = clientes.map(cliente => {
                            return datosFiltrados
                                .filter(d => d.cliente === cliente)
                                .reduce((sum, d) => sum + d.FOB_TO_USD, 0);
                        });

                        let promedioFOBxCaja = clientes.map(cliente => {
                            let cantItems = datosFiltrados.filter(d => d.cliente === cliente).length;
                            let totalFOB = datosFiltrados
                                .filter(d => d.cliente === cliente)
                                .reduce((sum, d) => sum + d.FOB_USD, 0);
                            return totalFOB / cantItems;

                        });

                        // Si los gráficos existen, los destruimos antes de crear nuevos
                        if (graficoFOB) graficoFOB.destroy();
                        if (graficoPromedio) graficoPromedio.destroy();

                        var ctxFOB = document.getElementById("graficoFOB").getContext("2d");
                        var ctxPromedio = document.getElementById("graficoPromedioFOB").getContext("2d");

                        graficoFOB = new Chart(ctxFOB, {
                            type: "bar",
                            data: {
                                labels: clientes,
                                datasets: [{
                                    label: "FOB Total (USD)",
                                    data: fobTotales,
                                    backgroundColor: "rgba(75, 192, 192, 0.6)",
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });

                        graficoPromedio = new Chart(ctxPromedio, {
                            type: "bar",
                            data: {
                                labels: clientes,
                                datasets: [{
                                    label: "Promedio FOB por Caja",
                                    data: promedioFOBxCaja,
                                    backgroundColor: "rgba(255, 99, 132, 0.6)",
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    }

                    function calcularFOBPorCliente(filteredData) {
                        let fobPorCliente = {};

                        filteredData.forEach(item => {
                            let cliente = item.cliente || "Sin Cliente"; // Manejar valores null o undefined
                            let fob = parseFloat(item.FOB_TO_USD) || 0;

                            if (!fobPorCliente[cliente]) {
                                fobPorCliente[cliente] = 0;
                            }

                            fobPorCliente[cliente] += fob;
                        });

                        return Object.entries(fobPorCliente).map(([cliente, totalFOB]) => ({
                            cliente,
                            totalFOB
                        }));
                    }

                    function actualizarGraficoPieFOB(filteredData) {
                        let datosPie = calcularFOBPorCliente(filteredData);

                        let ctx = document.getElementById("graficoPieFOB").getContext("2d");
                        if (window.pieChartFOB) {
                            window.pieChartFOB.destroy(); // Eliminar el gráfico previo si existe
                        }

                        window.pieChartFOB = new Chart(ctx, {
                            type: "pie",
                            data: {
                                labels: datosPie.map(d => d.cliente),
                                datasets: [{
                                    label: "FOB Total USD",
                                    data: datosPie.map(d => d.totalFOB),
                                    backgroundColor: [
                                        "#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF",
                                        "#FF9F40"
                                    ]
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: "bottom"
                                    }
                                }
                            }
                        });
                    }

                    function cargaRendimiento(GroupedData) {
                        let clientePrincipal = $("#filtroClientePrincipal").val();

                        // Obtener solo los datos del cliente principal
                        let datosCx = GroupedData.filter(d => d.cliente === clientePrincipal);

                        // Obtener lista de clientes únicos excluyendo el cliente principal
                        let clientes = [...new Set(GroupedData.map(d => d.cliente))].filter(cliente => cliente !==
                            clientePrincipal);

                        // Limpiar y agregar nuevas columnas dinámicamente


                        let headerTotales = $("#totalsRow");
                        headerTotales.empty();

                        let headerRow = $("#headerRow");
                        headerRow.empty();
                        headerRow.append(`<th></th>
        <th>Nave</th>
        <th>Etiqueta</th>
        <th>Embalaje</th>
        <th>Variedad</th>
        <th>Calibre</th>
        <th>Kilos Total</th>
        <th>FOB TO USD</th>
        <th>FOB Kg</th>
    `);

                        clientes.forEach(cliente => {
                            headerRow.append(`
            <th class="cliente-header inicio-cliente">${cliente} - FOB Kg</th>
            <th class="cliente-header">${cliente} - Suma de Kilos</th>
            <th class="cliente-header">${cliente} - Diferencia</th>
            <th class="cliente-header fin-cliente">${cliente} - Total Diferencia</th>
        `);
                        });

                        // Enriquecer los datos con los valores de los otros clientes
                        let datosFinales = datosCx.map(dato => {
                            let row = {
                                ...dato,
                                clientes: {}
                            };

                            clientes.forEach(cliente => {
                                let match = GroupedData.find(c =>
                                    c.cliente === cliente &&
                                    c.nave === dato.nave &&
                                    c.etiqueta === dato.etiqueta &&
                                    c.embalaje === dato.embalaje &&
                                    c.variedad === dato.variedad &&
                                    c.calibre === dato.calibre
                                );

                                row.clientes[cliente] = match ? {
                                    FOB_kg: match.FOB_kg || 0,
                                    kilos_total: match.kilos_total || 0,
                                    diferencia: parseFloat(((dato.FOB_kg || 0) - (match.FOB_kg ||
                                        0)).toFixed(2)),
                                    total_diferencia: parseFloat(((dato.kilos_total || 0) - (match
                                        .kilos_total || 0)).toFixed(2))
                                } : {
                                    FOB_kg: 0,
                                    kilos_total: 0,
                                    diferencia: 0,
                                    total_diferencia: 0
                                };
                            });

                            return row;
                        });

                        // Aplicar DataTables con scroll horizontal
                        $('#rendimientoTable').DataTable({
                            destroy: true, // Para evitar duplicados
                            data: datosFinales,
                            columns: [{
                                    className: 'dt-control',
                                    orderable: false,
                                    data: null,
                                    defaultContent: ''
                                },
                                {
                                    data: 'nave'
                                },
                                {
                                    data: 'etiqueta'
                                },
                                {
                                    data: 'embalaje'
                                },
                                {
                                    data: 'variedad'
                                },
                                {
                                    data: 'calibre'
                                },
                                {
                                    data: 'kilos_total'
                                },
                                {
                                    data: 'FOB_TO_USD'
                                },
                                {
                                    data: 'FOB_kg'
                                },
                                ...clientes.flatMap(cliente => ([{
                                        data: `clientes.${cliente}.FOB_kg`,
                                        className: `cliente-${cliente} inicio-cliente`
                                    },
                                    {
                                        data: `clientes.${cliente}.kilos_total`,
                                        className: `cliente-${cliente}`
                                    },
                                    {
                                        data: `clientes.${cliente}.diferencia`,
                                        className: `cliente-${cliente}`
                                    },
                                    {
                                        data: `clientes.${cliente}.total_diferencia`,
                                        className: `cliente-${cliente} fin-cliente`
                                    }
                                ]))
                            ],
                            scrollX: true, // Habilitar scroll horizontal
                            scrollCollapse: true, // Ajusta el tamaño si es necesario
                            fixedColumns: {
                                leftColumns: 9 // Mantiene fijas las primeras 8 columnas
                            },

                        });
                    }



                    $("#filtroClientePrincipal, #filtroClientesComparar").on("change", function() {
                        cargaRendimiento(GroupedData);
                        // actualizarGraficosComparacion();
                        //updateComparacionFOB();
                    });

                    async function llenarFiltrosClientes() {
                        let clientes = [...new Set(originalData.map(d => d.cliente))]; // Obtener clientes únicos

                        let selectPrincipal = $("#filtroClientePrincipal");
                        let selectComparar = $("#filtroClientesComparar");

                        // Limpiamos los selects antes de llenarlos
                        selectPrincipal.empty();
                        selectComparar.empty();

                        // Agregamos una opción por defecto en "Cliente Principal"
                        selectPrincipal.append('<option value="">Seleccione un cliente</option>');

                        // Llenamos los selects con los clientes
                        clientes.forEach(cliente => {
                            selectPrincipal.append(
                                `<option value="${cliente}">${cliente}</option>`);
                            selectComparar.append(`<option value="${cliente}">${cliente}</option>`);
                        });

                        // Refrescamos los select2
                        selectPrincipal.select2();
                        selectComparar.select2();
                    }

                    function filtrarDatosComparacion() {
                        let clientePrincipal = $("#filtroClientePrincipal").val();
                        let clientesComparar = $("#filtroClientesComparar").val() || [];
                        let filtros = obtenerFiltrosSeleccionados();
                        // console.log(originalData.map(item => item.embalaje)); // 📌 Esto imprimirá todos los valores de "embalaje"


                        if (!clientePrincipal) return [];

                        return originalData.filter(d =>
                            d.cliente === clientePrincipal || clientesComparar.includes(d.cliente) &&
                            (filtros.nave.length === 0 || filtros.nave.includes(d.nave)) &&
                            (filtros.variedad.length === 0 || filtros.variedad.includes(d.variedad)) &&
                            (filtros.calibre.length === 0 || filtros.calibre.includes(d.calibre)) &&
                            (filtros.etiqueta.length === 0 || filtros.etiqueta.includes(d.etiqueta)) &&
                            (filtros.ETA_Week.length === 0 || filtros.ETA_Week.includes(d.ETA_Week.toString()))
                        );
                    }

                    function updateComparacionFOB() {
                        // Verificar si hay un cliente principal seleccionado
                        const clientePrincipal = $("#filtroClientePrincipal").val();
                        if (!clientePrincipal) return;
                        // Obtener datos del cliente principal
                        const fobClientePrincipal = getFOBForCliente(clientePrincipal) || 0;
                        const kilosClientePrincipal = getKilosForCliente(clientePrincipal) || 0;
                        // Obtener la lista de clientes a comparar
                        const clientesAComparar = $("#filtroClientesComparar").val() || [];
                        let comparacionDatos = [];
                        // Incluir el cliente principal en los datos con costo 0
                        comparacionDatos.push({
                            cliente: clientePrincipal,
                            costoPorOportunidad: 0,
                            esPrincipal: true
                        });
                        // Calcular el costo por oportunidad para cada cliente no elegido
                        clientesAComparar.forEach(cliente => {
                            const fobCliente = getFOBForCliente(cliente) || 0;
                            const kiilosCliente = getKilosForCliente(cliente) || 0;
                            const costoPorOportunidad = (fobCliente / kilosClientePrincipal -
                                fobClientePrincipal / kilosClientePrincipal);


                            comparacionDatos.push({
                                cliente: cliente,
                                costoPorOportunidad: costoPorOportunidad,
                                esPrincipal: false
                            });
                        });
                        console.log(comparacionDatos);
                        // Actualizar el gráfico con los nuevos datos
                        updateGraficoComparacion(comparacionDatos);
                    }



                    function getKilosForCliente(cliente) {
                        // Aquí debes implementar la lógica para obtener los kilos del cliente
                        // Ejemplo: si los datos están en un array de objetos 'originalData'
                        filteredData = filtrarDatosComparacion();
                        return filteredData.filter(item => item.cliente === cliente).reduce((sum, item) => sum + item
                            .Peso_neto, 0);
                    }

                    function getFOBForCliente(cliente) {
                        filteredData = filtrarDatosComparacion();
                        // Aquí debes implementar la lógica para obtener el FOB del cliente
                        return filteredData.filter(item => item.cliente === cliente).reduce((sum, item) => sum + item
                            .FOB_USD, 0);
                    }
                    let graficoComparacionFOB = null;

                    function updateGraficoComparacion(datos) {
                        const ctx = document.getElementById("graficoComparacionFOB").getContext("2d");

                        // Eliminar instancia previa del gráfico si existe
                        if (graficoComparacionFOB) {
                            graficoComparacionFOB.destroy();
                        }

                        const labels = datos.map(d => d.cliente);
                        const data = datos.map(d => d.costoPorOportunidad);

                        // Colores: Cliente Principal (azul), Comparados (rojo pastel)
                        const backgroundColors = datos.map(d => d.esPrincipal ? "rgba(54, 162, 235, 0.7)" :
                            "rgba(255, 99, 132, 0.7)");

                        graficoComparacionFOB = new Chart(ctx, {
                            type: "bar",
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: "Costo por Oportunidad",
                                    data: data,
                                    backgroundColor: backgroundColors,
                                    borderColor: backgroundColors.map(c => c.replace("0.7",
                                        "1")), // Más opaco en el borde
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: "USD"
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });
                    }


                    // Función para generar colores pastel para las barras
                    function generatePastelColors(count) {
                        const colors = [];
                        for (let i = 0; i < count; i++) {
                            const r = Math.floor(Math.random() * 256);
                            const g = Math.floor(Math.random() * 256);
                            const b = Math.floor(Math.random() * 256);
                            colors.push(`rgba(${r}, ${g}, ${b}, 0.4)`);
                        }
                        return colors;
                    }

                    function generarResumen() {
                        let table = $('#rendimientoTable').DataTable();
                        let data = table.rows().data().toArray();

                        let resumen = {};

                        // Calcular las sumas y sumaproducto por cliente
                        data.forEach(row => {
                            Object.keys(row.clientes || {}).forEach(cliente => {
                                if (!resumen[cliente]) {
                                    resumen[cliente] = {
                                        sumaKilos: 0,
                                        sumaDiferencia: 0,
                                        sumaProducto: 0
                                    };
                                }
                                let kilos = parseFloat(row.clientes[cliente].kilos_total) || 0;
                                let diferencia = parseFloat(row.clientes[cliente].diferencia) ||
                                    0;

                                resumen[cliente].sumaKilos += kilos;
                                resumen[cliente].sumaDiferencia += diferencia;
                                resumen[cliente].sumaProducto += kilos *
                                    diferencia; // Cálculo de sumaproducto
                            });
                        });

                        // Crear el cuadro resumen
                        let resumenHTML = `
        <table class="table table-bordered resumen-table">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Suma Total de Kilos</th>
                    <th>Suma Total Diferencia</th>
                    <th>SUMAPRODUCTO (Kilos Total × Diferencia)</th>
                    <th>Ratio (Total Diferencia / Suma de Kilos)</th>
                </tr>
            </thead>
            <tbody>
    `;

                        Object.entries(resumen).forEach(([cliente, valores]) => {
                            let ratio = valores.sumaKilos ? (valores.sumaDiferencia / valores.sumaKilos)
                                .toFixed(4) : "N/A";
                            resumenHTML += `
            <tr>
                <td>${cliente}</td>
                <td>${valores.sumaKilos.toLocaleString()}</td>
                <td>${valores.sumaDiferencia.toLocaleString()}</td>
                <td>${valores.sumaProducto.toLocaleString()}</td>
                <td>${ratio}</td>
            </tr>
        `;
                        });

                        resumenHTML += `</tbody></table>`;

                        // Insertar el cuadro resumen antes de la tabla
                        $("#resumenContainer").html(resumenHTML);
                    }

                    // Llamar a la función después de cargar DataTables
                    $('#rendimientoTable').on('init.dt', function() {
                        generarResumen();
                    });


                    /*
                    Pestaña Comparativa general
                    */
                    let tblGeneralComparativo = null;

                    function cargaRendimientoGeneral(data) {
                        // 1. Agrupar datos por Nave, Embalaje, Etiqueta, Variedad, Calibre
                        let groupedData = {};

                        data.forEach(dato => {
                            let key =
                                `${dato.nave}|${dato.embalaje}|${dato.etiqueta}|${dato.variedad}|${dato.calibre}`;

                            if (!groupedData[key]) {
                                groupedData[key] = {
                                    nave: dato.nave,
                                    embalaje: dato.embalaje,
                                    etiqueta: dato.etiqueta,
                                    variedad: dato.variedad,
                                    calibre: dato.calibre,
                                    kilos_total: 0,
                                    FOB_TO_USD: 0,
                                    FOB_kg: 0,
                                    clientes: {}
                                };
                            }

                            // row.clientes[cliente] = match ? {
                            //         FOB_kg: match.FOB_kg || 0,
                            //         kilos_total: match.kilos_total || 0,
                            //         diferencia: parseFloat(((dato.FOB_kg || 0) - (match.FOB_kg ||
                            //             0)).toFixed(2)),
                            //         total_diferencia: parseFloat(((dato.kilos_total || 0) - (match
                            //             .kilos_total || 0)).toFixed(2))
                            //     } : {
                            //         FOB_kg: 0,
                            //         kilos_total: 0,
                            //         diferencia: 0,
                            //         total_diferencia: 0
                            //     };
                            groupedData[key].kilos_total += dato.kilos_total || 0;
                            groupedData[key].FOB_TO_USD += dato.FOB_TO_USD || 0;
                            groupedData[key].FOB_kg += dato.FOB_kg || 0;

                            if (!groupedData[key].clientes[dato.cliente]) {
                                groupedData[key].clientes[dato.cliente] = {
                                    FOB_kg: 0,
                                    kilos_total: 0,
                                    diferencia: 0,
                                    total_diferencia: 0
                                };
                            }

                            groupedData[key].clientes[dato.cliente].FOB_kg += dato.FOB_kg || 0;
                            groupedData[key].clientes[dato.cliente].kilos_total += dato.kilos_total || 0;
                            groupedData[key].clientes[dato.cliente].diferencia += (dato.FOB_kg - groupedData[key].clientes[dato.cliente].FOB_kg) || 0;
                            groupedData[key].clientes[dato.cliente].total_diferencia += (dato.kilos_total-groupedData[key].clientes[dato.cliente].kilos_total) || 0;
                        });

                        let datosFinales = Object.values(groupedData);
                        let clientes = [...new Set(data.map(d => d
                        .cliente))]; // Asegurarse de obtener todos los clientes posibles

                        let headerRow = $("#RGheaderRow").empty();

                        // Encabezados estáticos
                        headerRow.append(`
        <th></th>
        <th>Nave</th>
        <th>Etiqueta</th>
        <th>Embalaje</th>
        <th>Variedad</th>
        <th>Calibre</th>
        <th>Kilos Total</th>
        <th>FOB TO USD</th>
        <th>FOB Kg</th>
    `);

                        // Encabezados dinámicos por cliente
                        clientes.forEach(cliente => {
                            headerRow.append(`
            <th class="cliente-header inicio-cliente">${cliente} - FOB Kg</th>
            <th class="cliente-header">${cliente} - Suma de Kilos</th>
            <th class="cliente-header">${cliente} - Diferencia</th>
            <th class="cliente-header fin-cliente">${cliente} - Total Diferencia</th>
        `);
                        });

                        // Función para formatear números a 4 decimales
                        function formatNumber(value) {
                            return parseFloat(value || 0).toFixed(4);
                        }

                        // Generamos los datos con todos los clientes y valores predeterminados para los que falten
                        let datosConClientes = datosFinales.map(d => {
                            // Asegurarse de que todos los clientes tengan su entrada
                            let clientesCompletos = {};

                            // Rellenamos con los datos de clientes existentes o valores predeterminados
                            clientes.forEach(cliente => {
                                if (d.clientes[cliente]) {
                                    clientesCompletos[cliente] = {
                                        FOB_kg: formatNumber(d.clientes[cliente].FOB_kg || 0),
                                        kilos_total: formatNumber(d.clientes[cliente].kilos_total ||
                                            0),
                                        diferencia: formatNumber(d.clientes[cliente].diferencia ||
                                            0),
                                        total_diferencia: formatNumber(d.clientes[cliente]
                                            .total_diferencia || 0)
                                    };
                                } else {
                                    clientesCompletos[cliente] = {
                                        FOB_kg: formatNumber(
                                        0), // Valores por defecto cuando el cliente no tiene datos
                                        kilos_total: formatNumber(0),
                                        diferencia: formatNumber(0),
                                        total_diferencia: formatNumber(0)
                                    };
                                }
                            });

                            // Ahora agregamos los datos de la fila con los valores completos para los clientes
                            return {
                                ...d,
                                kilos_total: formatNumber(d.kilos_total || 0),
                                FOB_TO_USD: formatNumber(d.FOB_TO_USD || 0),
                                FOB_kg: formatNumber(d.FOB_kg || 0),
                                clientes: clientesCompletos
                            };
                        });

                        // Configuración de DataTable
                        $('#rendimientoGeneralTable').DataTable({
                            destroy: true,
                            data: datosConClientes,
                            columns: [{
                                    className: 'dt-control',
                                    orderable: false,
                                    data: null,
                                    defaultContent: ''
                                },
                                {
                                    data: 'nave'
                                },
                                {
                                    data: 'etiqueta'
                                },
                                {
                                    data: 'embalaje'
                                },
                                {
                                    data: 'variedad'
                                },
                                {
                                    data: 'calibre'
                                },
                                {
                                    data: 'kilos_total'
                                },
                                {
                                    data: 'FOB_TO_USD'
                                },
                                {
                                    data: 'FOB_kg'
                                },
                                ...clientes.flatMap(cliente => [{
                                        data: `clientes.${cliente}.FOB_kg`,
                                        className: `cliente-${cliente} inicio-cliente`
                                    },
                                    {
                                        data: `clientes.${cliente}.kilos_total`,
                                        className: `cliente-${cliente}`
                                    },
                                    {
                                        data: `clientes.${cliente}.diferencia`,
                                        className: `cliente-${cliente}`
                                    },
                                    {
                                        data: `clientes.${cliente}.total_diferencia`,
                                        className: `cliente-${cliente} fin-cliente`
                                    }
                                ])
                            ],
                            scrollX: true,
                            scrollCollapse: true,
                            fixedColumns: {
                                leftColumns: 9
                            },
                            pageLength: 25
                        });
                    }



                    //pruebas
                    // let tableX = new DataTable('#example', {
                    //     searchPanes: {
                    //         layout: 'columns-1'
                    //     },
                    //     pageLength: 25
                    // });

                    // document
                    //     .querySelector('div.dtsp-verticalPanes')
                    //     .appendChild(tableX.searchPanes.container().get(0));
                });
            </script>
        @endsection
