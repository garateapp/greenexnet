@extends('layouts.admin')


@section('content')
    <link href="{{ asset('css/bootstrap-table.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('js/webdatarocks/webdatarocks.css') }}" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <style>
        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #a9dd94;
            border-color: #dee2e6 #dee2e6 #f8fafc;
        }

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

        .highlight {
            background-color: green;
            color: white;
        }

        #loading-animation {
            display: flex;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
        }

        video {
            border-radius: 10px;
        }
    </style>

    <!-- Bootstrap JS -->


    <style>
        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #a9dd94;
            border-color: #dee2e6 #dee2e6 #f8fafc;
        }

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

        .highlight {
            background-color: green;
            color: white;
        }

        #loading-animation {
            display: flex;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
        }

        video {
            border-radius: 10px;
        }

        #progress-bar {
            transition: width 0.2s ease;
        }

        .card-body {
            overflow-x: auto important !;
            /* Habilita el scroll horizontal si es necesario */
            padding: 15px;
            /* Ajusta el padding interno */
        }

        .table {
            width: 100%;
            font-size: 80%;
            /* Asegura que la tabla use todo el ancho disponible */
            margin-bottom: 0;

            /* Elimina el margen inferior extra */
        }

        .table-bordered {
            border: 1px solid #dee2e6;
            /* Estilo de borde de la tabla */
        }

        .calugar {
            max-height: 300px;
            overflow-y: auto;
            overflow-x: auto;
        }

        @media (max-width: 576px) {
            .card {
                margin-bottom: 15px;
            }
        }

        @media (min-width: 768px) {
            .card {
                margin-bottom: 20px;
            }
        }

        .clickable-container {
            cursor: pointer;
            color: #ff7313;
        }

        /* Hacer los encabezados de las tablas sticky */
        table thead th {
            position: sticky;
            top: 0;
            /* Fijar los encabezados al tope */
            background-color: #fff;
            /* Fondo blanco para los encabezados */
            z-index: 1;
            /* Asegurar que los encabezados queden encima del contenido */
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            /* Sombra sutil para los encabezados */
        }

        /* Asegurarnos de que las filas no se peguen a los encabezados */
        table tbody td {
            background-color: #f9f9f9;
        }

        /* Si es necesario, puedes ajustar el tamaño de los encabezados */
        table thead th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        .nave {
            font-weight: bold;
            background-color: #e0f7fa;
        }

        .contenedor {
            font-weight: bold;
            background-color: #f1f8e9;
        }

        .etiqueta {
            background-color: #fff8e1;
        }

        /* Estilos para el modal */
        .modal {
            display: none;
            /* Ocultar el modal por defecto */
            position: fixed;
            z-index: 1;
            left: 0px;
            top: 30px;
            width: 100%;
            height: 80%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            /* Fondo oscuro */
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease-in-out;
            /* Animación de entrada */
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            /* max-width: 600px; */
            margin: auto;
            overflow-y: auto;
            overflow-x: auto;
        }

        .close-btn {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            {{-- <div class="card">
                <div id="loading-animation"
                    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
                    <video autoplay loop muted style="width: auto; height: auto;">
                        <source src="{{ asset('img/embarque.webm') }}" type="video/webm">
                        Tu navegador no soporta el video.
                    </video>
                    <br />

                </div>
                <div class="card-header">
                    Sincronizar
                    <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">

                    </div>
                </div>
                <div class="card-body">
                    <button id="btnSync" class="btn btn-secondary mb-3" style="margin-top: 30px;" title="Sincronizar"><i
                            class="fas fa-sync"></i></button>

                </div>
            </div> --}}
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Filtros
                </div>
                <div class="card-body">
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                            <label for="filtroCliente">Cliente</label>
                            <select id="filtroCliente" class="form-control select2" multiple="multiple"></select>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                            <label for="filtroNave">Nave</label>
                            <select id="filtroNave" class="form-control select2" multiple="multiple"></select>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                            <label for="filtroNumEmbarque">Embarque</label>
                            <select id="filtroNumEmbarque" class="form-control select2" multiple="multiple"></select>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                            <label for="filtroContenedor">Contenedor</label>
                            <select id="filtroContenedor" class="form-control select2" multiple="multiple"></select>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                            <label for="filtroVariedad">Variedad</label>
                            <select id="filtroVariedad" class="form-control select2" multiple="multiple"></select>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                            <label for="filtroCalibre">Calibre</label>
                            <select id="filtroCalibre" class="form-control select2" multiple="multiple"></select>
                        </div>

                        <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                            <label for="filtroEmbalaje">Embalaje</label>
                            <select id="filtroEmbalaje" class="form-control select2" multiple="multiple"></select>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                            <label for="filtroEtiqueta">Etiqueta</label>
                            <select id="filtroEtiqueta" class="form-control select2" multiple="multiple"></select>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                            <label for="filtroTransporte">Transporte</label>
                            <select id="filtroTransporte" class="form-control select2" multiple="multiple"></select>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                            <label for="filtroDestino">País destino</label>
                            <select id="filtroDestino" class="form-control select2" multiple="multiple"></select>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                            <label for="filtroProductor">Productor</label>
                            <select id="filtroProductor" class="form-control select2" multiple="multiple"></select>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                            <label for="filtroSemana">Semana</label>
                            <select id="filtroSemana" class="form-control select2" multiple="multiple"></select>
                        </div>
                    </div>
                    <button id="btnAplicarFiltros" class="btn btn-secondary mb-3 align-middle" style="margin-top: 30px;">
                        Aplicar filtros
                    </button>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    Calibres x Contenedor
                </div>
                <div class="card-body calugar">
                    <table id="tablaCalibres" class="display table table-bordered table-striped table-hover">
                        <thead id="tablaCalibreHead">
                            <!-- Encabezados dinámicos -->
                        </thead>
                        <tbody id="tablaCalibreBody">
                            <!-- Filas dinámicas -->
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <!-- Cliente x Embarque -->
        <div class="col-12 col-sm-6 ">
            <div class="card">
                <div class="card-header">
                    Etiqueta x Contenedor
                </div>
                <div class="card-body" style="height: 300px; overflow-y: auto;">
                    <table id="tablaEmbarqueDestinatario" class="display table table-bordered table-striped table-hover">
                        <thead id="tablaEtiquetaHead"></thead>
                        <tbody id="tablaEtiquetaBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Variedad x Contenedor -->
        <div class="col-12 col-sm-6 ">
            <div class="card">
                <div class="card-header">
                    Variedad x Contenedor
                </div>
                <div class="card-body calugar">
                    <table class="display table table-bordered table-striped table-hover">
                        <thead id="tablaVariedadHead"></thead>
                        <tbody id="tablaVariedadBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Contenedor x Semana -->
        <div class="col-12 col-sm-6">
            <div class="card">
                <div class="card-header">
                    Contenedor x Semana
                </div>
                <div class="card-body calugar">
                    <table id="tablaSemanas" class="display table table-bordered table-striped table-hover">
                        <thead id="tablaSemanaHead">
                            <!-- Header dinámico -->
                        </thead>
                        <tbody id="tablaSemanaBody">
                            <!-- Filas dinámicas -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6">
            <div class="card">
                <div class="card-header">
                    Contenedor x Cliente
                </div>
                <div class="card-body calugar">
                    <table id="tablaSemanas" class="display table table-bordered table-striped table-hover">
                        <thead id="tablaClienteHead">
                            <!-- Header dinámico -->
                        </thead>
                        <tbody id="tablaClienteBody">
                            <!-- Filas dinámicas -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="col-12 col-sm-6">
            <div class="card">
                <div class="card-header">
                    Contenedor x Nave
                </div>
                <div class="card-body calugar">
                    <table id="tablaNave" class="display table table-bordered table-striped table-hover">
                        <thead id="tablaNaveHead">
                            <!-- Header dinámico -->
                        </thead>
                        <tbody id="tablaNaveBody">
                            <!-- Filas dinámicas -->
                        </tbody>
                    </table>
                </div>
            </div>
        <!-- Modal para detalles de contenedor -->
        <!-- Modal -->
        <div id="contenedorModal" class="modal">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="contenedorModalLabel">Detalles del Contenedor</h5> <span
                        id="numContenedor"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="closeModal"></button>
                </div>
                <div class="modal-body">
                    <!-- Wrapper para permitir el scroll horizontal -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>n_embarque</th>
                                    <th>id_destinatario</th>
                                    <th>n_destinatario</th>
                                    <th>c_destinatario</th>
                                    <th>fecha_embarque</th>
                                    <th>n_packing_origen</th>
                                    <th>n_naviera</th>
                                    <th>n_nave</th>
                                    <th>contenedor</th>
                                    <th>N_Especie</th>
                                    <th>N_Variedad</th>
                                    <th>n_embalaje</th>
                                    <th>t_embalaje</th>
                                    <th>n_etiqueta</th>
                                    <th>cantidad</th>
                                    <th>peso_neto</th>
                                    <th>n_puerto_origen</th>
                                    <th>n_pais_destino</th>
                                    <th>n_puerto_destino</th>
                                    <th>transporte</th>
                                    <th>etd</th>
                                    <th>eta</th>
                                    <th>numero_reserva_agente_naviero</th>
                                    <th>total_pallets</th>
                                    <th>numero_referencia</th>
                                    <th>nave</th>
                                    <th>folio</th>
                                    <th>peso_std_embalaje</th>
                                    <th>n_variedad_rotulacion</th>
                                    <th>n_categoria</th>
                                    <th>fecha_produccion</th>
                                    <th>n_productor_rotulacion</th>
                                    <th>codigo_sag_productor</th>
                                    <th>n_calibre</th>
                                </tr>
                            </thead>
                            <tbody id="contenedorModalBody">
                                <!-- El contenido de las filas será agregado dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla general de detalles -->
    <script src="https://cdn.jsdelivr.net/npm/eruda"></script>
    <script>
        let totalRegistros = 0;

        function showLoading() {

            $("#loading-animation").fadeIn();
        }

        function hideLoading() {
            $("#loading-animation").fadeOut();
        }







        document.addEventListener("DOMContentLoaded", async () => {
            let originalData = []; // Almacena todos los datos originales
            let filteredData = []; // Almacena los datos después de aplicar filtros

            // Mapear IDs de filtros con las claves de los datos
            const filters = {
                n_embarque: "filtroNumEmbarque",
                nave: "filtroNave",
                contenedor: "filtroContenedor",
                N_Variedad: "filtroVariedad",
                n_calibre: "filtroCalibre",
                n_embalaje: "filtroEmbalaje",
                n_etiqueta: "filtroEtiqueta",
                transporte: "filtroTransporte",
                n_pais_destino: "filtroDestino",
                n_productor_rotulacion: "filtroProductor",
                n_destinatario: "filtroCliente",
                semana: "filtroSemana",
            }; // Almacenar todos los datos originales

            // Cargar los datos desde el archivo datos.json
            async function loadData() {
                try {
                    const response = await fetch("https://net.greenexweb.cl/storage/datos.json");
                    const data = await response.json();

                    originalData = data; // Guardar todos los datos originales
                    try {
                        await fillSelects(); // Llenar selects de 'n_embarque' y 'nave'
                        await fillWeekSelect(); // Llenar select de semanas
                    } catch (error) {
                        console.error("Error al recargar los selects:", error);
                    }

                    return data;
                } catch (error) {
                    console.error("Error al cargar datos desde el archivo:", error);
                }
            }

            // Función para obtener valores únicos de una columna
            async function getUniqueValues(column) {
                const uniqueValues = new Set();

                originalData.forEach(item => {
                    const value = item[column];
                    if (value) uniqueValues.add(value);
                });

                return Array.from(uniqueValues).filter(value => value !== null && value !== "");
            }

            // Función para obtener semanas únicas
            function getWeekNumber(date) {
                const d = new Date(date);
                const oneJan = new Date(d.getFullYear(), 0, 1);
                //console.log(oneJan+"--"+d);
                const numberOfDays = Math.floor((d - oneJan) / (24 * 60 * 60 * 1000));
                return Math.ceil((numberOfDays + oneJan.getDay() + 1) / 7);
            }

            async function getUniqueWeeks() {
                const uniqueWeeks = new Set();

                originalData.forEach(item => {
                    const eta = item.eta;
                    console.log(eta);
                    if (eta) {
                        const weekNumber = getWeekNumber(eta);
                        uniqueWeeks.add(weekNumber);
                    }
                });

                return Array.from(uniqueWeeks).sort((a, b) => a - b); // Ordenamos por número de semana
            }

            // Llenar los selects
            async function fillSelects() {
                try {
                    console.log(originalData);
                    // Definir los campos y sus respectivos elementos select
                    const fields = {
                        n_embarque: "filtroNumEmbarque",
                        nave: "filtroNave",
                        contenedor: "filtroContenedor",
                        N_Variedad: "filtroVariedad",
                        n_calibre: "filtroCalibre",
                        n_embalaje: "filtroEmbalaje",
                        n_etiqueta: "filtroEtiqueta",
                        transporte: "filtroTransporte",
                        n_pais_destino: "filtroDestino",
                        n_productor_rotulacion: "filtroProductor",
                        n_destinatario: "filtroCliente"
                    };

                    // Llenar cada select dinámicamente
                    for (const [field, selectId] of Object.entries(fields)) {
                        const values = await getUniqueValues(field); // Obtener valores únicos
                        const selectElement = document.getElementById(selectId); // Seleccionar el elemento

                        values.forEach(value => {
                            const option = document.createElement("option");
                            option.value = value;
                            option.textContent = value;
                            selectElement.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error("Error llenando los selectores:", error);
                }
            }

            // Llenar el select de semanas
            async function fillWeekSelect() {
                try {
                    const weeks = await getUniqueWeeks();
                    const weekSelect = document.getElementById("filtroSemana");

                    weeks.forEach(week => {
                        const option = document.createElement("option");
                        option.value = week;
                        option.textContent = `${week}`;
                        weekSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error("Error llenando el selector de semanas:", error);
                }
            }

            // Aplicar los filtros
            function applyFilters() {
                // Obtener valores de los filtros
                const filterValues = {};
                for (const [key, filterId] of Object.entries(filters)) {
                    const value = document.getElementById(filterId).value.trim();
                    if (value) {
                        filterValues[key] = value;
                    }
                }

                // Aplicar filtros a los datos originales
                filteredData = originalData.filter((item) => {
                    return Object.entries(filterValues).every(([key, value]) => {
                        return item[key]?.toString().toLowerCase().includes(value
                            .toLowerCase());
                    });
                });

                // Actualizar todas las tablas
                updateAllTables();
            }

            // Función para generar las tablas
            function generateTable(groupByRow, groupByColumn, tableHeadId, tableBodyId) {
                const groupedData = {};
                const columnSet = new Set();

                filteredData.forEach((record) => {
                    const rowKey = record[groupByRow];
                    const colKey = record[groupByColumn];
                    const quantity = parseFloat(record.cantidad) || 0;

                    if (rowKey && colKey) {
                        groupedData[rowKey] = groupedData[rowKey] || {};
                        groupedData[rowKey][colKey] = (groupedData[rowKey][colKey] || 0) + quantity;
                        columnSet.add(colKey);
                    }
                });

                const columns = Array.from(columnSet).sort();

                const tableHead = document.getElementById(tableHeadId);
                const tableBody = document.getElementById(tableBodyId);

                tableHead.innerHTML = "";
                tableBody.innerHTML = "";

                // Crear encabezado
                const headerRow = document.createElement("tr");
                const headerCell = document.createElement("th");
                headerCell.textContent = `${groupByRow} / ${groupByColumn}`;
                headerRow.appendChild(headerCell);

                columns.forEach((col) => {
                    const th = document.createElement("th");
                    th.textContent = col;
                    headerRow.appendChild(th);
                });

                const totalColHeader = document.createElement("th");
                totalColHeader.textContent = "Total Fila";
                headerRow.appendChild(totalColHeader);
                tableHead.appendChild(headerRow);

                let grandTotal = 0;
                const columnTotals = {};

                // Crear filas
                Object.entries(groupedData).forEach(([row, rowData]) => {
                    const rowElement = document.createElement("tr");

                    const rowHeader = document.createElement("td");
                    rowHeader.textContent = row;
                    rowHeader.classList.add("clickable-container");
                    rowHeader.addEventListener("click", (event) => {
                        event.stopPropagation(); // Prevenir propagación innecesaria
                        console.log("Clic en fila:", row); // Verificar en consola
                        showContainerDetails(row); // Llamada a la función con el valor correcto
                    });

                    rowElement.appendChild(rowHeader);

                    let rowTotal = 0;
                    columns.forEach((col) => {
                        const cell = document.createElement("td");
                        const value = rowData[col] || 0;
                        cell.textContent = value;
                        rowElement.appendChild(cell);

                        rowTotal += value;
                        columnTotals[col] = (columnTotals[col] || 0) + value;
                    });

                    const rowTotalCell = document.createElement("td");
                    rowTotalCell.textContent = rowTotal;
                    rowElement.appendChild(rowTotalCell);

                    grandTotal += rowTotal;
                    tableBody.appendChild(rowElement);
                });

                // Crear fila de Totales
                const totalRow = document.createElement("tr");
                const totalHeader = document.createElement("td");
                totalHeader.textContent = "Total Columna";
                totalRow.appendChild(totalHeader);

                columns.forEach((col) => {
                    const totalCell = document.createElement("td");
                    totalCell.textContent = columnTotals[col] || 0;
                    totalRow.appendChild(totalCell);
                });

                const grandTotalCell = document.createElement("td");
                grandTotalCell.textContent = grandTotal;
                totalRow.appendChild(grandTotalCell);

                tableBody.appendChild(totalRow);
            }

            // 4. Actualizar Tablas Específicas
            function updateAllTables() {
                generateTable("contenedor", "n_etiqueta", "tablaEtiquetaHead", "tablaEtiquetaBody");
                generateTable("contenedor", "N_Variedad", "tablaVariedadHead", "tablaVariedadBody");
                generateTable("contenedor", "n_calibre", "tablaCalibreHead", "tablaCalibreBody");
                generateTable("contenedor", "semana", "tablaSemanaHead", "tablaSemanaBody");
                generateTable("contenedor", "n_destinatario", "tablaClienteHead", "tablaClienteBody");
                generateTable("contenedor", "nave", "tablaNaveHead", "tablaNaveBody");
            }

            // 5. Inicializar
            async function initializeTables() {
                await loadData();
                applyFilters();
            }

            document.getElementById("btnAplicarFiltros").addEventListener("click", applyFilters);

            initializeTables();

            // 🟢 1. Mostrar detalles en un modal al hacer clic en una celda de contenedor
            // function showContainerDetails(containerValue) {
            //     // Filtrar los datos por contenedor respetando los filtros activos
            //     const filteredData = originalData.filter((item) => item.contenedor === containerValue);
            //     // Mostrar los datos del contenedor en el modal o en otro lugar
            //     console.log(filteredData);
            // }
            function showContainerDetails(containerValue) {
                // Filtrar los datos por contenedor respetando los filtros activos
                const filteredData = originalData.filter(item => item.contenedor === containerValue);
                document.getElementById("numContenedor").innerHTML = containerValue;
                // Obtener el modal y el cuerpo de la tabla
                const modalBody = document.getElementById("contenedorModalBody");
                modalBody.innerHTML = ""; // Limpiar contenido previo

                // Crear las filas de la tabla dentro del modal
                filteredData.forEach(item => {
                    const row = document.createElement("tr");

                    // Lista de las cabeceras de la tabla
                    const headers = [
                        "id", "n_embarque", "id_destinatario", "n_destinatario", "c_destinatario",
                        "fecha_embarque", "n_packing_origen",
                        "n_naviera", "n_nave", "contenedor", "N_Especie", "N_Variedad",
                        "n_embalaje", "t_embalaje", "n_etiqueta",
                        "cantidad", "peso_neto", "n_puerto_origen", "n_pais_destino",
                        "n_puerto_destino", "transporte", "etd", "eta",
                        "numero_reserva_agente_naviero", "total_pallets", "numero_referencia",
                        "nave", "folio", "peso_std_embalaje",
                        "n_variedad_rotulacion", "n_categoria", "fecha_produccion",
                        "n_productor_rotulacion", "codigo_sag_productor",
                        "n_calibre"
                    ];

                    // Crear celdas para cada columna
                    headers.forEach(header => {
                        const td = document.createElement("td");
                        td.textContent = item[header] ||
                            "N/A"; // Si no existe el valor, mostrar "N/A"
                        row.appendChild(td);
                    });

                    modalBody.appendChild(row);
                });

                // Mostrar el modal
                // Obtener el modal
                document.getElementById('contenedorModal').style.display = 'flex';


            }

            // 2. Cerrar el modal
            function closeModal() {
                document.getElementById('contenedorModal').style.display = 'none';
                //modal.addAttribute('inert');
                //document.getElementById("containerModal").addAttr('inert');

            }

            // 3. Generar la tabla con el evento de clic en celdas de contenedor

            const closeModalBtn = document.getElementById('closeModal');
            const modalTableBody = document.querySelector('#modalTable tbody');

            closeModalBtn.addEventListener('click', closeModal);

        });
    </script>
@endsection
