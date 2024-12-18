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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Embarques
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
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
            });
        </script>
    @endsection
