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

        #loading-animation {
            display: flex;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
        }

        video {
            border-radius: 10px;
        }
    </style>
    <div class="row">
        <div id="loading-animation"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
            <video autoplay loop muted style="width: 200px; height: auto;">
                <source src="{{ asset('img/transito.webm') }}" type="video/webm">
                Your browser does not support the video tag.
            </video>
            <br />
            <div class="text-white text-opacity-75 text-end" id="loading-animation-text">Obteniendo Instructivos,
                Espera por favor..... :)</div>
        </div>
    </div>
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

    </div>
    <div class="col-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                Liquidaciones Venta Cerezas 2025
            </div>
            <div class="card-body">
                <table id="tabla-datos" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>

                            <th>Instructivo</th>
                            <th>Tasa</th>
                            <th>Total Kilos</th>
                            <th>MONTO RMB</th>
                            <th>MONTO USD</th>
                            <th>Costos USD</th>
                            <th>FOB USD</th>
                        </tr>
                    </thead>
                    <tbody id="tablaDatosBody"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align: right;">Subtotal:</td>
                            <td id="subtotalCantidad"></td>
                            <td id="subtotalMontoRMB"></td>
                            <td id="subtotalMontoUSD"></td>
                        </tr>
                        <tr>
                            <td colspan="4"><strong>Total</strong></td>
                            <td id="totalCantidad">0</td>
                            <td id="totalMontoRMB">0</td>
                            <td id="totalMontoUSD">0</td>
                        </tr>
                    </tfoot>
                </table>
                {{-- <table id="tablaCalibres" class="display table table-bordered table-striped table-hover">
                        <thead id="tablaLiquidacionesHead">
                            <!-- Encabezados dinámicos -->
                        </thead>
                        <tbody id="tablaLiquidacionesBody">
                            <!-- Filas dinámicas -->
                        </tbody>
                    </table> --}}

            </div>
        </div>
    </div>
    </div>
    <script>
        function showLoading() {

            $("#loading-animation").fadeIn();
        }

        function hideLoading() {
            $("#loading-animation").fadeOut();
        }
        document.addEventListener("DOMContentLoaded", async () => {
            let originalData = []; // Datos originales sin filtrar

            // Función para obtener valores únicos de una columna específica


            // Función para aplicar los filtros en la tabla
            // function filterTable() {
            //     let filteredData = originalData;

            //     const filters = {
            //         variedad_id: $("#filtroVariedad").val() || [],
            //         calibre: $("#filtroCalibre").val() || [],
            //         etiqueta_id: $("#filtroEtiqueta").val() || [],
            //         Semana_Arribo: $("#filtroSemana").val() || [],
            //         nombre_fantasia: $("#filtroCliente").val() || []
            //     };

            //     // Convertir valores en arrays en caso de que sean únicos
            //     Object.keys(filters).forEach(key => {
            //         if (!Array.isArray(filters[key])) {
            //             filters[key] = [filters[key]];
            //         }
            //     });

            //     // Aplicar filtros solo si hay valores seleccionados
            //     filteredData = originalData.filter(item => {
            //         return Object.entries(filters).every(([key, values]) => {
            //             return values.length === 0 || values.includes(item[key].toString());
            //         });
            //     });

            //     cargarTabla(filteredData);
            //     calcularTotales(filteredData);
            // }

            // Función para calcular totales
            function calcularTotales(data) {
                let totalCantidad = 0;
                let totalMontoRMB = 0;
                let totalMontoUSD = 0;

                let subtotalCantidad = 0;
                let subtotalMontoRMB = 0;
                let subtotalMontoUSD = 0;

                // Calcular totales sobre originalData
                originalData.forEach(item => {
                    totalCantidad += parseFloat(item.cantidad) || 0;
                    totalMontoRMB += parseFloat(item["MONTO_USD"]) || 0;
                    totalMontoUSD += parseFloat(item["FOB_USD"]) || 0;
                });

                // Calcular subtotales sobre filteredData
                data.forEach(item => {
                    subtotalCantidad += parseFloat(item.cantidad) || 0;
                    subtotalMontoRMB += parseFloat(item["MONTO_USD"]) || 0;
                    subtotalMontoUSD += parseFloat(item["FOB_USD"]) || 0;
                });

                // Actualizar la fila de totales (calculados con originalData)
                document.getElementById("totalCantidad").textContent = totalCantidad.toLocaleString();
                document.getElementById("totalMontoRMB").textContent = totalMontoRMB.toLocaleString();
                document.getElementById("totalMontoUSD").textContent = totalMontoUSD.toLocaleString();

                // Actualizar los subtotales (calculados con filteredData)
                document.getElementById("subtotalCantidad").textContent = subtotalCantidad.toLocaleString();
                document.getElementById("subtotalMontoRMB").textContent = subtotalMontoRMB.toLocaleString();
                document.getElementById("subtotalMontoUSD").textContent = subtotalMontoUSD.toLocaleString();
            }


            // Función para cargar la tabla con DataTables
            function cargarTabla(datos) {
                $('#tabla-datos').DataTable({
                    destroy: true, // Permite recargar la tabla sin errores
                    data: datos,
                    columns: [{
                            data: 'placeholder',
                        },
                        {
                            data: "instructivo"
                        },


                        {
                            data: "tasa"
                        },
                        {
                            data: "total_kilos",
                            render: function(data) {
                                return parseFloat(data).toLocaleString();
                            }
                        },
                        {
                            data: "MONTO_RMB",
                            render: function(data) {
                                return parseFloat(data).toLocaleString();
                            }
                        },
                        {
                            data: "MONTO_USD",
                            render: function(data) {
                                return parseFloat(data).toLocaleString();
                            }
                        },
                        {
                            data: "costos",
                            render: function(data) {
                                return parseFloat(data).toLocaleString();
                            }
                        },
                        {
                            data: "FOB_USD",
                            render: function(data) {
                                return parseFloat(data).toLocaleString();
                            }
                        }

                    ],
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                    }
                });
            }
            // $('#tabla-datos tbody').on('click', 'td.dt-control', function() {
            //     var tr = $(this).closest('tr');
            //     var row = $('#tabla-datos').DataTable().row(tr);

            //     if (row.child.isShown()) {
            //         row.child.hide();
            //         tr.removeClass('shown');
            //     } else {
            //         var instructivo = row.data().instructivo; // Obtener el instructivo de la fila
            //         var calibre = row.data().calibre; // Obtener el calibre de la fila
            //         var etiqueta = row.data().etiqueta_id; // Obtener la etiqueta de la fila
            //         var variedad = row.data().variedad_id; // Obtener la variedad de la fila

            //         // Llamar a la API de Laravel para obtener la subtabla
            //         $.ajax({
            //             url: "{{ route('admin.reporteria.getDetallesInstructivo') }}",
            //             type: "GET",
            //             data: {
            //                 instructivo: instructivo,
            //                 calibre:calibre,
            //                 etiqueta:etiqueta,
            //                 variedad:variedad
            //             },
            //             success: function(response) {
            //                 row.child(formatearSubtabla(response))
            //                     .show(); // Mostrar la subtabla
            //                 tr.addClass('shown');
            //             },
            //             error: function(xhr) {
            //                 console.error("Error al cargar detalles:", xhr);
            //             }
            //         });
            //     }
            // });
            // Función para formatear la subtabla
            //     function formatearSubtabla(data) {
            //         var table = '<table class="table table-sm table-bordered" style="width:100%;">';
            //         table +=
            //             '<thead><tr><th>% Costos Asociado</th><th>Embalaje</th><th>Variedad</th><th>Calibre</th><th>Productor</th><th>CSG</th><th>Cantidad</th><th>Folio</th></tr></thead>';
            //         table += '<tbody>';

            //         data.forEach(item => {
            //             table += `<tr>
        //                 <td>${item.folio}</td>
        //     <td>${item.C_Embalaje}</td>
        //     <td>${item.n_variedad}</td>
        //     <td>${item.n_calibre}</td>
        //     <td>${item.n_productor}</td>
        //     <td>${item.CSG_Productor}</td>
        //     <td>${item.cantidad}</td>
        //     <td>${parseFloat(item.porcentaje).toFixed(2)}%</td>

        // </tr>`;
            //         });

            //         table += '</tbody></table>';
            //         return table;
            //     }
            // Función para cargar los datos desde la API
            async function loadData() {
                showLoading();
                try {
                    const response = await fetch("{{ route('admin.reporteria.getLiquidaciones') }}");
                    originalData = await response.json(); // Guardar los datos originales

                    cargarTabla(originalData); // Llenar la tabla con todos los datos
                    calcularTotales(originalData); // Calcular totales iniciales
                    //await fillSelects(); // Llenar los selectores
                    hideLoading();
                } catch (error) {
                    console.error("Error al cargar datos:", error);
                }
            }

            // Llamar a loadData() cuando la página cargue
            loadData();
        });
    </script>
@endsection
