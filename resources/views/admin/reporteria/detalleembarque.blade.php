@extends('layouts.admin')


@section('content')
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
            overflow-x: auto;
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
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Sincronizar
                    <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">

                    </div>
                </div>
                <div class="card-body">
                    <button id="btnSync" class="btn btn-secondary mb-3" style="margin-top: 30px;" title="Sincronizar"><i
                            class="fas fa-sync"></i></button>
                    <div style="width: 100%; background-color: #f3f3f3; border: 1px solid #ccc; border-radius: 5px;">
                        <div id="progress-bar"
                            style="width: 0%; height: 25px; background-color: #4caf50; text-align: center; color: white; line-height: 25px; border-radius: 5px;">
                            0%
                        </div>
                    </div>
                </div>
            </div>
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
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-lg-6 col-xl-6 col-xxl-2">
            <div class="card">
                <div class="card-header">
                    Calibres x Contenedor
                </div>
                <div class="card-body calugar">
                    <table id="tablaCalibres" class="display table table-bordered table-striped table-hover">
                        <thead>
                            <!-- Encabezados dinámicos -->
                        </thead>
                        <tbody>
                            <!-- Filas dinámicas -->
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="col-6 col-lg-6 col-xl-6 col-xxl-2">
            <div class="card">
                <div class="card-header">
                    Cliente x Embarque

                </div>
                <div class="card-body calugar">
                    <table id="tablaEmbarqueDestinatario" class="display table table-bordered table-striped table-hover">
                        <thead id="tablaEmbarqueDestinatarioHead">
                            <!-- Header dinámico -->
                        </thead>
                        <tbody id="tablaEmbarqueDestinatarioBody">
                            <!-- Filas dinámicas -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-6 col-xl-6 col-xxl-2">
            <div class="card">
                <div class="card-header">
                    Variedad x Contenedor
                </div>
                <div class="card-body">
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-6 col-xl-3 col-xxl-2">
            <div class="card">
                <div class="card-header">
                    Etiqueta x Contenedor
                </div>
                <div class="card-body">
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-6 col-xl-3 col-xxl-2">
            <div class="card">
                <div class="card-header">
                    Contenedor x Semana
                </div>
                <div class="card-body  calugar">
                    <table id="tablaSemanas" class="display table table-bordered table-striped table-hover">
                        <thead id="tablaNavesSemanasHead">
                            <!-- Header dinámico -->
                        </thead>
                        <tbody id="tablaNavesSemanasBody">
                            <!-- Filas dinámicas -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            let totalRegistros = 0;

            function sincronizar() {
                obtieneCantidad();
                const urls = [
                    "{{ route('admin.reporteria.ObtieneEmbarquesyPackingList') }}",
                    // "{{ route('admin.reporteria.getPackingList') }}",
                    "{{ route('admin.reporteria.getClientesComex') }}"
                ];

                const progressBar = $("#progress-bar");
                const totalRequests = urls.length + (totalRegistros % 5000);
                let completedRequests = 0;

                function updateProgress() {
                    completedRequests++;
                    const percentage = Math.round((completedRequests / totalRequests) * 100);
                    progressBar.css("width", percentage + "%");
                    progressBar.text(percentage + "%");

                    if (completedRequests === totalRequests) {
                        alert("Sincronización completada");
                    }
                }


                function obtieneCantidad() {
                    $.ajax({
                        url: "{{ route('admin.reporteria.getCantRegistros') }}",
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log("Datos recibidos de: " + data);
                            totalRegistros = data.totalRegistros;
                        },
                        error: function(xhr, status, error) {
                            console.error("Error en la solicitud AJAX a " + url, error);
                        }
                    });
                }

                // if (url ===
                //                 "{{ route('admin.reporteria.ObtieneEmbarquesyPackingList') }}"
                //             ) {
                //
                //             }
                urls.forEach((url) => {
                    if (url === "{{ route('admin.reporteria.getClientesComex') }}") {
                        $.ajax({
                            url: url,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                console.log("Datos recibidos de: " + url, data);
                                updateProgress(); // Incrementar el progreso al completar
                                if (url ===
                                    "{{ route('admin.reporteria.getClientesComex') }}") {
                                    console.log("data", data.CxComex);
                                    SetDBCxComex(data.CxComex);
                                }

                            },
                            error: function(xhr, status, error) {
                                console.error("Error en la solicitud AJAX a " + url, error);
                                updateProgress(); // Incrementar el progreso incluso si falla
                            }
                        });
                    } else {

                        $.ajax({
                            url: url,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                console.log("Datos recibidos de: " + url, data);
                                updateProgress(); // Incrementar el progreso al completar
                                console.log("data", data.objEmbarque);
                                SetDBEmbarque(data.objEmbarque);

                            },
                            error: function(xhr, status, error) {
                                console.error("Error en la solicitud AJAX a " + url, error);
                                updateProgress(); // Incrementar el progreso incluso si falla
                            }
                        });
                    }
                });

            }
            $("#btnSync").on("click", function() {
                $("#progress-bar").css("width", "0%").text("0%");
                sincronizar();
            });

            function SetDBCxComex(data) {
                const openRequest = indexedDB.open("CxComexDB", 1);

                openRequest.onupgradeneeded = function(event) {
                    const db = event.target.result;

                    // Crear objeto de almacenamiento
                    if (!db.objectStoreNames.contains("CxComex")) {
                        const store = db.createObjectStore("CxComex", {
                            keyPath: "id"
                        });
                        store.createIndex("codigo_cliente", "codigo_cliente");
                        store.createIndex("nombre_empresa", "nombre_empresa");
                    }
                };

                openRequest.onsuccess = function(event) {
                    const db = event.target.result;

                    // Almacenar datos
                    const transaction = db.transaction("CxComex", "readwrite");
                    const store = transaction.objectStore("CxComex");

                    data.forEach((item) => {
                        store.put(item);
                    });

                    transaction.oncomplete = function() {
                        console.log("Todos los datos se han almacenado correctamente.");
                    };

                    transaction.onerror = function(error) {
                        console.error("Error al almacenar datos:", error);
                    };
                };

                openRequest.onerror = function(error) {
                    console.error("Error al abrir la base de datos:", error);
                };
            }

            function SetDBEmbarque(data) {
                const openRequest = indexedDB.open("EmbarqueDB", 1);

                openRequest.onupgradeneeded = function(event) {
                    const db = event.target.result;

                    // Crear objeto de almacenamiento
                    if (!db.objectStoreNames.contains("EmbarqueDB")) {
                        const store = db.createObjectStore("EmbarqueDB", {
                            keyPath: "id"
                        });
                        store.createIndex("n_embarque", "n_embarque");
                    }
                };

                openRequest.onsuccess = function(event) {
                    const db = event.target.result;

                    // Almacenar datos
                    const transaction = db.transaction("EmbarqueDB", "readwrite");
                    const store = transaction.objectStore("EmbarqueDB");

                    data.forEach((item) => {
                        store.put(item);
                    });

                    transaction.oncomplete = function() {
                        console.log("Todos los datos se han almacenado correctamente.");
                        fillSelects(); // Llenar n_embarque y nave
                        fillWeekSelect(); // Llenar semanas
                    };

                    transaction.onerror = function(error) {
                        console.error("Error al almacenar datos:", error);
                    };
                };

                openRequest.onerror = function(error) {
                    console.error("Error al abrir la base de datos:", error);
                };
            }




            // Llenar semanas

            document.addEventListener("DOMContentLoaded", async () => {
                try {
                    await fillSelects(); // Llenar selects de 'n_embarque' y 'nave'
                    await fillWeekSelect(); // Llenar select de semanas
                } catch (error) {
                    console.error("Error al recargar los selects:", error);
                }

                function openDatabase() {
                    return new Promise((resolve, reject) => {
                        const request = indexedDB.open("EmbarqueDB", 1);

                        request.onsuccess = (event) => {
                            resolve(event.target.result);
                        };

                        request.onerror = (event) => {
                            reject("Error al abrir la base de datos:", event.target.error);
                        };
                    });
                }
                async function getUniqueValues(column) {
                    const db = await openDatabase();
                    const transaction = db.transaction("EmbarqueDB", "readonly");
                    const store = transaction.objectStore("EmbarqueDB");
                    const uniqueValues = new Set();

                    return new Promise((resolve, reject) => {
                        const request = store.openCursor();

                        request.onsuccess = (event) => {
                            const cursor = event.target.result;
                            if (cursor) {
                                uniqueValues.add(cursor.value[column]);
                                cursor.continue();
                            } else {
                                resolve(Array.from(uniqueValues).filter(value => value !== null &&
                                    value !== ""));
                            }
                        };

                        request.onerror = (event) => {
                            reject("Error al leer los datos:", event.target.error);
                        };
                    });
                }
                async function fillSelects() {
                    try {
                        const nEmbarques = await getUniqueValues("n_embarque");
                        const naves = await getUniqueValues("nave");
                        const contenedor = await getUniqueValues("contenedor");
                        const variedad = await getUniqueValues("N_Variedad");
                        const calibre = await getUniqueValues("n_calibre");
                        const embalaje = await getUniqueValues("n_embalaje");
                        const etiqueta = await getUniqueValues("n_etiqueta");
                        const transporte = await getUniqueValues("transporte");
                        const destino = await getUniqueValues("n_pais_destino");
                        const productor = await getUniqueValues("n_productor_rotulacion");


                        const nEmbarqueSelect = document.getElementById("filtroNumEmbarque");
                        const naveSelect = document.getElementById("filtroNave");
                        const contenedorSelect = document.getElementById("filtroContenedor");
                        const variedadSelect = document.getElementById("filtroVariedad");
                        const calibreSelect = document.getElementById("filtroCalibre");
                        const embalajeSelect = document.getElementById("filtroEmbalaje");
                        const etiquetaSelect = document.getElementById("filtroEtiqueta");
                        const transporteSelect = document.getElementById("filtroTransporte");
                        const destinoSelect = document.getElementById("filtroDestino");
                        const productorSelect = document.getElementById("filtroProductor");

                        // Llenar n_embarque
                        nEmbarques.forEach(value => {
                            const option = document.createElement("option");
                            option.value = value;
                            option.textContent = value;
                            nEmbarqueSelect.appendChild(option);
                        });

                        // Llenar nave
                        naves.forEach(value => {
                            const option = document.createElement("option");
                            option.value = value;
                            option.textContent = value;
                            naveSelect.appendChild(option);
                        });
                        // Llenar contenedor
                        contenedor.forEach(value => {
                            const option = document.createElement("option");
                            option.value = value;
                            option.textContent = value;
                            contenedorSelect.appendChild(option);
                        });
                        variedad.forEach(value => {
                            const option = document.createElement("option");
                            option.value = value;
                            option.textContent = value;
                            variedadSelect.appendChild(option);
                        });
                        calibre.forEach(value => {
                            const option = document.createElement("option");
                            option.value = value;
                            option.textContent = value;
                            calibreSelect.appendChild(option);
                        });
                        embalaje.forEach(value => {
                            const option = document.createElement("option");
                            option.value = value;
                            option.textContent = value;
                            embalajeSelect.appendChild(option);
                        });
                        etiqueta.forEach(value => {
                            const option = document.createElement("option");
                            option.value = value;
                            option.textContent = value;
                            etiquetaSelect.appendChild(option);
                        });
                        transporte.forEach(value => {
                            const option = document.createElement("option");
                            option.value = value;
                            option.textContent = value;
                            transporteSelect.appendChild(option);
                        });
                        destino.forEach(value => {
                            const option = document.createElement("option");
                            option.value = value;
                            option.textContent = value;
                            destinoSelect.appendChild(option);
                        });
                        productor.forEach(value => {
                            const option = document.createElement("option");
                            option.value = value;
                            option.textContent = value;
                            productorSelect.appendChild(option);
                        });



                    } catch (error) {
                        console.error("Error llenando los selectores:", error);
                    }
                }

                // Ejecutar al cargar la página
                function getWeekNumber(date) {
                    const d = new Date(date);
                    const oneJan = new Date(d.getFullYear(), 0, 1);
                    const numberOfDays = Math.floor((d - oneJan) / (24 * 60 * 60 * 1000));
                    return Math.ceil((numberOfDays + oneJan.getDay() + 1) / 7);
                }
                async function getUniqueWeeks() {
                    const db = await openDatabase();
                    const transaction = db.transaction("EmbarqueDB", "readonly");
                    const store = transaction.objectStore("EmbarqueDB");
                    const uniqueWeeks = new Set();

                    return new Promise((resolve, reject) => {
                        const request = store.openCursor();

                        request.onsuccess = (event) => {
                            const cursor = event.target.result;
                            if (cursor) {
                                const eta = cursor.value.eta;
                                if (eta) {
                                    const weekNumber = getWeekNumber(eta);
                                    uniqueWeeks.add(weekNumber);
                                }
                                cursor.continue();
                            } else {
                                resolve(Array.from(uniqueWeeks).sort((a, b) => a -
                                    b)); // Ordenamos por número de semana
                            }
                        };

                        request.onerror = (event) => {
                            reject("Error al leer los datos:", event.target.error);
                        };
                    });
                }
                async function fillWeekSelect() {
                    try {
                        const weeks = await getUniqueWeeks();
                        const weekSelect = document.getElementById("filtroSemana");

                        weeks.forEach(week => {
                            const option = document.createElement("option");
                            option.value = week;
                            option.textContent = `Semana ${week}`;
                            weekSelect.appendChild(option);
                        });
                    } catch (error) {
                        console.error("Error llenando el selector de semanas:", error);
                    }
                }
                // Función para abrir la base de datos IndexedDB
                async function openDatabaseEmbarqueDB() {
                    return new Promise((resolve, reject) => {
                        const request = indexedDB.open("EmbarqueDB");

                        request.onsuccess = (event) => {
                            resolve(event.target.result);
                        };

                        request.onerror = (event) => {
                            reject("Error al abrir la base de datos: " + event.target.error);
                        };
                    });
                }

                // Obtener valores únicos de una columna
                async function getUniqueValues(column) {
                    const db = await openDatabaseEmbarqueDB();
                    const transaction = db.transaction("EmbarqueDB", "readonly");
                    const store = transaction.objectStore("EmbarqueDB");
                    const uniqueValues = new Set();

                    return new Promise((resolve, reject) => {
                        const request = store.openCursor();

                        request.onsuccess = (event) => {
                            const cursor = event.target.result;
                            if (cursor) {
                                const value = cursor.value[column];
                                if (value) uniqueValues.add(value);
                                cursor.continue();
                            } else {
                                resolve(Array.from(uniqueValues));
                            }
                        };

                        request.onerror = (event) => {
                            reject("Error al obtener valores únicos:", event.target.error);
                        };
                    });
                }
                async function countByCombination(column1, value1, column2, value2) {
                    const db = await openDatabase();
                    const transaction = db.transaction("EmbarqueDB", "readonly");
                    const store = transaction.objectStore("EmbarqueDB");
                    let count = 0;

                    return new Promise((resolve, reject) => {
                        const request = store.openCursor();

                        request.onsuccess = (event) => {
                            const cursor = event.target.result;
                            if (cursor) {
                                const record = cursor.value;
                                if (record[column1] === value1 && record[column2] === value2) {
                                    count++;
                                }
                                cursor.continue();
                            } else {
                                resolve(count);
                            }
                        };

                        request.onerror = (event) => {
                            reject("Error al contar registros:", event.target.error);
                        };
                    });
                }

                // Generar la tabla dinámicamente
                async function generateTable() {
                    try {
                        const calibres = await getUniqueValues("n_calibre");
                        const contenedores = await getUniqueValues("contenedor");

                        const table = document.getElementById("tablaCalibres");
                        const thead = table.querySelector("thead");
                        const tbody = table.querySelector("tbody");

                        // Crear encabezados
                        const headerRow = document.createElement("tr");
                        const emptyHeader = document.createElement("th");
                        emptyHeader.textContent = "Contenedor / Calibre";
                        headerRow.appendChild(emptyHeader);

                        calibres.forEach(calibre => {
                            const th = document.createElement("th");
                            th.textContent = calibre;
                            headerRow.appendChild(th);
                        });
                        thead.appendChild(headerRow);

                        // Crear filas
                        for (const contenedor of contenedores) {
                            const row = document.createElement("tr");
                            const contenedorCell = document.createElement("td");
                            contenedorCell.textContent = contenedor;
                            row.appendChild(contenedorCell);

                            for (const calibre of calibres) {
                                const cell = document.createElement("td");
                                const count = await countByCombination("contenedor", contenedor, "n_calibre",
                                    calibre);
                                cell.textContent = count;
                                row.appendChild(cell);
                            }

                            tbody.appendChild(row);
                        }
                    } catch (error) {
                        console.error("Error generando la tabla:", error);
                    }
                }
                generateTable();




                // Generar tabla dinámica con Naves y Semanas, asegurando contenedores únicos
                async function getDataByNaveAndWeek() {
                    const db = await openDatabaseEmbarqueDB();
                    const transaction = db.transaction("EmbarqueDB", "readonly");
                    const store = transaction.objectStore("EmbarqueDB");

                    const groupedData = {};

                    return new Promise((resolve, reject) => {
                        const request = store.openCursor();

                        request.onsuccess = (event) => {
                            const cursor = event.target.result;
                            if (cursor) {
                                const record = cursor.value;
                                const week = getWeekNumber(record.etd);
                                const nave = record.nave || "Sin nave";
                                const contenedor = record.contenedor || "Sin contenedor";

                                if (!groupedData[nave]) {
                                    groupedData[nave] = {};
                                }
                                if (!groupedData[nave][week]) {
                                    groupedData[nave][week] = [];
                                }

                                groupedData[nave][week].push(contenedor);
                                cursor.continue();
                            } else {
                                resolve(groupedData);
                            }
                        };

                        request.onerror = (event) => {
                            reject("Error al agrupar los datos:", event.target.error);
                        };
                    });
                }


                async function generateNaveWeekTable() {
                    try {
                        const groupedData = await getDataByNaveAndWeek();
                        const tableHead = document.getElementById("tablaNavesSemanasHead");
                        const tableBody = document.getElementById("tablaNavesSemanasBody");

                        tableHead.innerHTML = ""; // Limpiar encabezado previo
                        tableBody.innerHTML = ""; // Limpiar cuerpo previo

                        // Obtener semanas únicas ordenadas
                        const allWeeks = new Set();
                        Object.values(groupedData).forEach((weeks) => {
                            Object.keys(weeks).forEach((week) => allWeeks.add(Number(week)));
                        });
                        const sortedWeeks = Array.from(allWeeks).sort((a, b) => a - b);

                        // Crear encabezado
                        const headerRow = document.createElement("tr");
                        const naveCell = document.createElement("th");
                        naveCell.textContent = "Nave / Semana";
                        naveCell.rowSpan = 2; // Celda abarca dos filas del encabezado
                        headerRow.appendChild(naveCell);

                        // Crear celdas de semanas
                        sortedWeeks.forEach((week) => {
                            const th = document.createElement("th");
                            th.textContent = `Semana ${week}`;
                            headerRow.appendChild(th);
                        });

                        tableHead.appendChild(headerRow);

                        // Crear filas por nave, excluyendo naves sin datos
                        Object.entries(groupedData).forEach(([nave, weeks]) => {
                            // Crear un conjunto para evitar contenedores duplicados
                            const uniqueContenedores = new Set();

                            // Recopilar contenedores de todas las semanas para esta nave
                            sortedWeeks.forEach((week) => {
                                const contenedores = weeks[week] || [];
                                contenedores.forEach((contenedor) => uniqueContenedores.add(
                                    contenedor));
                            });

                            // Verificar si la nave tiene al menos un contenedor único
                            if (uniqueContenedores.size === 0) {
                                return; // Excluir naves sin contenedores
                            }

                            const row = document.createElement("tr");
                            const naveCell = document.createElement("td");
                            naveCell.textContent = nave;
                            row.appendChild(naveCell);

                            // Añadir celdas de contenedores únicos para cada semana
                            sortedWeeks.forEach((week) => {
                                const cell = document.createElement("td");
                                const contenedores = weeks[week] ||
                            []; // Contenedores para la semana

                                // Filtrar contenedores únicos para esta semana
                                const uniqueWeekContenedores = contenedores.filter((
                                    contenedor) => {
                                    if (uniqueContenedores.has(contenedor)) {
                                        uniqueContenedores.delete(
                                            contenedor); // Evitar duplicados
                                        return true;
                                    }
                                    return false;
                                });

                                cell.textContent = uniqueWeekContenedores.join(", ") ||
                                    "Sin datos"; // Mostrar lista de contenedores únicos
                                row.appendChild(cell);
                            });

                            tableBody.appendChild(row);
                        });
                    } catch (error) {
                        console.error("Error generando la tabla:", error);
                    }
                }

                // Inicializar tabla
                async function initializeTable() {
                    await generateNaveWeekTable();
                }

                initializeTable();
                // Generar tabla dinámica con n_embarque y n_destinatarios únicos por semana
                async function generateEmbarqueDestinatarioTable() {
                    try {
                        const groupedData =
                    await getDataByEmbarqueAndWeek(); // Reutilizamos la función base con nueva agrupación
                        const tableHead = document.getElementById("tablaEmbarqueDestinatarioHead");
                        const tableBody = document.getElementById("tablaEmbarqueDestinatarioBody");

                        tableHead.innerHTML = ""; // Limpiar encabezado previo
                        tableBody.innerHTML = ""; // Limpiar cuerpo previo

                        // Obtener semanas únicas ordenadas
                        const allWeeks = new Set();
                        Object.values(groupedData).forEach((weeks) => {
                            Object.keys(weeks).forEach((week) => allWeeks.add(Number(week)));
                        });
                        const sortedWeeks = Array.from(allWeeks).sort((a, b) => a - b);

                        // Crear encabezado
                        const headerRow = document.createElement("tr");
                        const embarqueCell = document.createElement("th");
                        embarqueCell.textContent = "N° Embarque / Semana";
                        embarqueCell.rowSpan = 2; // Celda abarca dos filas del encabezado
                        headerRow.appendChild(embarqueCell);

                        // Crear celdas de semanas
                        sortedWeeks.forEach((week) => {
                            const th = document.createElement("th");
                            th.textContent = `Semana ${week}`;
                            headerRow.appendChild(th);
                        });

                        tableHead.appendChild(headerRow);

                        // Crear filas por n_embarque, excluyendo filas sin destinatarios
                        Object.entries(groupedData).forEach(([embarque, weeks]) => {
                            // Crear un conjunto para evitar destinatarios duplicados
                            const uniqueDestinatarios = new Set();

                            // Recopilar destinatarios de todas las semanas para este embarque
                            sortedWeeks.forEach((week) => {
                                const destinatarios = weeks[week] || [];
                                destinatarios.forEach((destinatario) => uniqueDestinatarios.add(
                                    destinatario));
                            });

                            // Verificar si el embarque tiene al menos un destinatario único
                            if (uniqueDestinatarios.size === 0) {
                                return; // Excluir embarques sin destinatarios
                            }

                            const row = document.createElement("tr");
                            const embarqueCell = document.createElement("td");
                            embarqueCell.textContent = embarque;
                            row.appendChild(embarqueCell);

                            // Añadir celdas de destinatarios únicos para cada semana
                            sortedWeeks.forEach((week) => {
                                const cell = document.createElement("td");
                                const destinatarios = weeks[week] ||
                            []; // Destinatarios para la semana

                                // Filtrar destinatarios únicos para esta semana
                                const uniqueWeekDestinatarios = destinatarios.filter((
                                    destinatario) => {
                                    if (uniqueDestinatarios.has(destinatario)) {
                                        uniqueDestinatarios.delete(
                                        destinatario); // Evitar duplicados
                                        return true;
                                    }
                                    return false;
                                });

                                cell.textContent = uniqueWeekDestinatarios.join(", ") ||
                                    "Sin datos"; // Mostrar lista de destinatarios únicos
                                row.appendChild(cell);
                            });

                            tableBody.appendChild(row);
                        });
                    } catch (error) {
                        console.error("Error generando la tabla:", error);
                    }
                }

                // Obtener datos agrupados por n_embarque y semana
                async function getDataByEmbarqueAndWeek() {
                    const db = await openDatabaseEmbarqueDB();
                    const transaction = db.transaction("EmbarqueDB", "readonly");
                    const store = transaction.objectStore("EmbarqueDB");
                    const groupedData = {};

                    return new Promise((resolve, reject) => {
                        const request = store.openCursor();

                        request.onsuccess = (event) => {
                            const cursor = event.target.result;
                            if (cursor) {
                                const record = cursor.value;
                                const n_embarque = record.n_embarque;
                                const n_destinatario = record.n_destinatario;
                                const eta = record.eta;

                                if (n_embarque && n_destinatario && eta) {
                                    const week = getWeekNumber(eta); // Calcular semana

                                    if (!groupedData[n_embarque]) {
                                        groupedData[n_embarque] = {};
                                    }

                                    if (!groupedData[n_embarque][week]) {
                                        groupedData[n_embarque][week] = [];
                                    }

                                    groupedData[n_embarque][week].push(n_destinatario);
                                }

                                cursor.continue();
                            } else {
                                resolve(groupedData); // Retornar datos agrupados
                            }
                        };

                        request.onerror = (event) => {
                            reject("Error al agrupar datos:", event.target.error);
                        };
                    });
                }

                // Inicializar tabla
                async function initializeEmbarqueDestinatarioTable() {
                    await generateEmbarqueDestinatarioTable();
                }

                initializeEmbarqueDestinatarioTable();



            });
        </script>
    @endsection
