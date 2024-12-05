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

    <div class="content">
        <div class="container-fluid">


            <div id="loading-animation"
                style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
                <video autoplay loop muted style="width: 200px; height: auto;">
                    <source src="{{ asset('img/transito.webm') }}" type="video/webm">
                    Your browser does not support the video tag.
                </video>
                <br />
                <div class="text-white text-opacity-75 text-end" id="loading-animation-text">Separando y Contando Cajas,
                    Espera por favor..... :)</div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                    <label for="filtroVariedad">Variedad</label>
                    <select id="filtroVariedad" class="form-control select2" multiple="multiple"></select>
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
                    <button id="btnRecargar" class="btn btn-secondary mb-3" style="margin-top: 30px;" title="Recargar"><i
                            class="fas fa-sync"></i></button>
                </div>

            </div>
            <div class="modal fade" id="calibreModal" tabindex="-1" aria-labelledby="calibreModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="calibreModalLabel">Detalles del Calibre</h5>
                            <button type="button" id="btnImprimir" class="btn-secondary" data-bs-dismiss="modal"
                                aria-label="Close"><i class="fas fa-print"></i></button>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="fas fa-close"></i></button>
                        </div>

                        <div class="modal-body" id="modalCalibreContent">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Folio</th>
                                        <th>Variedad</th>
                                        <th>Embalaje</th>
                                        <th>Etiqueta</th>
                                        <th>Calibre</th>
                                        <th>Cantidad</th>
                                        <th>Peso</th>
                                    </tr>
                                </thead>
                                <tbody id="calibreModalBody">
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <button id="btnvistaBodega" class="btn btn-primary">Vista Bodega</button> &nbsp; <button id="btnvistaVariedad"
                class="btn btn-primary">Vista Variedad</button> &nbsp; <button id="btnvistaCalibre"
                class="btn btn-primary">Vista Calibre</button> --}}

            <div class="row" id="vistaCalibres" style="display: none;">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Transito
                        </div>
                        <div class="card-body">
                            <div class="container-lg px-4" id="contenedorCalibres"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Transito
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="TransitoTable"
                            class="display table table-bordered table-striped table-hover ajaxTable datatable datatable-transito"
                            style="width:100%">

                        </table>
                    </div>
                </div>
            </div>

        </div>
        <script>
            document.getElementById("btnImprimir").onclick = function() {
                printElement(document.getElementById("modalCalibreContent"));
            };

            function printElement(elem) {
                var domClone = elem.cloneNode(true);

                var $printSection = document.getElementById("modalCalibreContent");

                if (!$printSection) {
                    var $printSection = document.createElement("div");
                    $printSection.id = "modalCalibreContent";
                    document.body.appendChild($printSection);
                }

                $printSection.innerHTML = "";
                $printSection.appendChild(domClone);
                window.print();
            }

            function showLoading() {

                $("#loading-animation").fadeIn();
            }

            function hideLoading() {
                $("#loading-animation").fadeOut();
            }

            function showLoadingDetalle() {
                $("#loading-animation-detalle").fadeIn();
            }

            function hideLoadingDetalle() {
                $("#loading-animation-detalle").fadeOut();
            }

            function cambiarVideo(rutaVideo, texto) {
                // Seleccionar la etiqueta <source> dentro del <video>
                const videoSource = document.querySelector('#loading-animation video source');
                const videoElement = document.querySelector('#loading-animation video');
                $("#loading-animation-text").text(texto);
                // Cambiar el atributo src del <source> con la nueva ruta
                videoSource.setAttribute('src', rutaVideo);

                // Recargar el video para aplicar el cambio
                videoElement.load();
            }
            $(document).ready(function() {
                let table = null;
                let dtButtons = $.extend(false, [], $.fn.dataTable.defaults.buttons);
                cambiarVideo('{{ asset('img/transito.webm') }}',
                    'Separando y Contando Cajas, Espera por favor..... :)');
                showLoading(); // Mostrar la animación de carga
                var variedades = [];
                var calibres = [];
                var embalaje = [];
                var etiqueta = [];
                $.ajax({
                    url: "{{ route('admin.reporteria.obtieneTransito') }}",
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        hideLoading(); // Ocultar la animación de carga
                        variedades = data.n_variedades;
                        calibres = data.n_calibres;
                        embalaje = data.c_embalajes;
                        bodega = data.n_bodegas;
                        etiqueta = data.n_etiquetas;


                        // bodega.forEach(
                        //     function(value) {
                        //         $('#filtroBodega').append(new Option(value, value));
                        //     });

                        let calibreData = "";
                        const calibresOrdenados = calibres.sort((a, b) => {
                            const [priA, secA] = asignarPrioridad(a);
                            const [priB, secB] = asignarPrioridad(b);

                            return priA - priB || secA -
                                secB; // Ordenar por prioridad principal y luego secundaria
                        });

                        console.log(calibresOrdenados);
                        // let calibreHeader = "";
                        // calibresOrdenados.forEach(

                        //     function(value) {
                        //         calibreHeader += '<th colspan="2">' + value + '</th>';
                        //     }
                        // );
                        calibresOrdenados.forEach(

                            function(value) {
                                calibreData += '<th>Cajas</th><th>Pallets</th>';
                            }
                        );

                        let calibreHeader = calibresOrdenados.map(calibre =>
                            `<th colspan="2">${calibre}</th>`).join(
                            "");

                        // Segunda fila del encabezado (subcolumnas)
                        let calibreSubHeader = calibresOrdenados
                            .map(() => `<th>Cajas</th><th>Pallets</th>`)
                            .join("");

                        const agrupado = data.data.reduce((acc, item) => {
                            const key =
                                `${item.c_embalaje}-${item.n_variedad}-${item.n_etiqueta}`;

                            if (!acc[key]) {
                                acc[key] = {
                                    variedad: item.n_variedad,
                                    embalaje: item.c_embalaje,
                                    etiqueta: item.n_etiqueta,
                                    totalCajas: 0, // Total de cajas por agrupación
                                    cajasxpallet: {}, // Inicializamos el objeto para los pallets por calibre
                                    cajaxpallet: 0,
                                    ...calibres.reduce((cal, calibre) => ({
                                        ...cal,
                                        [calibre]: 0
                                    }), {}) // Inicializa cada calibre con 0
                                };
                            }

                            // Asignar cajaxpallet desde embalajes_detalle
                            if (item.c_embalaje) {
                                const embalaje = data.embalajes_detalle.find(e => e.c_embalaje ===
                                    item
                                    .c_embalaje);
                                if (embalaje) {
                                    acc[key].cajaxpallet = embalaje.cajaxpallet;
                                }
                            }

                            // Sumar la cantidad al calibre correspondiente
                            if (item.n_calibre && calibres.includes(item.n_calibre)) {
                                acc[key][item.n_calibre] += parseFloat(item.cantidad);
                            }

                            // Sumar la cantidad total de cajas
                            acc[key].totalCajas += parseFloat(item.cantidad);

                            // Calcular los pallets por calibre
                            if (item.n_calibre && calibres.includes(item.n_calibre) && acc[key]
                                .cajaxpallet) {
                                // Realizar la división y redondear a un solo decimal
                                acc[key].cajasxpallet[item.n_calibre] = (acc[key][item.n_calibre] /
                                    acc[key].cajaxpallet).toFixed(1);
                            }
                            return acc;
                        }, {});

                        const resultadoTabla = Object.values(agrupado);

                        $('#TransitoTable').html(
                            `<thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Variedad</th>
                                    <th>Embalaje</th>
                                    <th>Etiqueta</th>

                                    ${calibreHeader}
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th> <select class="search" strict="true" id="filtroVariedad"> <option value>{{ trans('global.all') }}</option></select></th>
                                    <th> <select class="search" strict="true" id="filtroEmbalaje"> <option value>{{ trans('global.all') }}</option></select></th>
                                    <th> <select class="search" strict="true" id="filtroEtiqueta"> <option value>{{ trans('global.all') }}</option></select></th>

                                    ${calibreSubHeader}
                                </tr>
                            </thead>
                            <tbody></tbody>`
                        );
                        variedades.forEach(
                            function(value) {

                                $('#filtroVariedad').append(new Option(value, value));
                            });

                        etiqueta.forEach(
                            function(value) {
                                $('#filtroEtiqueta').append(new Option(value, value));
                            });
                        embalaje.forEach(
                            function(value) {
                                $('#filtroEmbalaje').append(new Option(value, value));
                            });
                        table = $('#TransitoTable').DataTable({

                            fixedColumns: true,
                            fixedHeader: true,
                            responsive: true,
                            language: {
                                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-CL.json'
                            },
                            data: resultadoTabla, // Tus datos
                            displayLength: 50,
                            columns: [{
                                    className: 'dt-control',
                                    orderable: false,
                                    data: null,
                                    defaultContent: ''
                                },
                                {

                                    data: null,

                                    orderable: false,
                                    render: function(data, type, row, meta) {
                                        return '<button class="btn btn-primary btn-sm interact-btn"><i class="fas fa-plus interact-btn"></i></button>';
                                    }
                                },
                                {
                                    data: 'variedad',
                                    title: 'Variedad'
                                },
                                {
                                    data: 'embalaje',
                                    title: 'Embalaje'
                                },
                                {
                                    data: 'etiqueta',
                                    title: 'Etiqueta'
                                },

                                ...calibres.flatMap(calibre => [{
                                        data: calibre,
                                        title: `${calibre} Cajas`,
                                        render: (data, type, row) => row[calibre] || 0
                                    },
                                    {
                                        data: `cajasxpallet.${calibre}`,
                                        title: `${calibre} Pallets`,
                                        render: (data, type, row) => row.cajasxpallet?.[
                                            calibre
                                        ] || "0.0"
                                    }
                                ])
                            ],
                            createdRow: function(row, data) {
                                calibres.forEach(calibre => {
                                    const palletsValue = parseFloat(data.cajasxpallet?.[
                                        calibre
                                    ] || 0);
                                    console.log(row, data.cajasxpallet?.[calibre]);
                                    if (palletsValue > 1) {
                                        // Encuentra la celda correspondiente al pallet
                                        let palletCell = $(
                                            `td:eq(${calibres.indexOf(calibre) * 2 + 6})`,
                                            row);
                                        palletCell.css({
                                            backgroundColor: "green",
                                            color: "white",
                                            cursor: "pointer"
                                        });
                                    }
                                });
                            },
                        });
                        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                            const filtroVariedad = $('#filtroVariedad').val() || [];
                            const filtroEmbalaje = $('#filtroEmbalaje').val() || [];
                            const filtroEtiqueta = $('#filtroEtiqueta').val() || [];

                            const variedad = data[
                                2]; // Ajusta el índice según la posición de la columna en la tabla
                            const embalaje = data[3]; // Ajusta el índice
                            const etiqueta = data[4]; // Ajusta el índice

                            const coincideVariedad = filtroVariedad.length === 0 || filtroVariedad
                                .includes(variedad);
                            const coincideEmbalaje = filtroEmbalaje.length === 0 || filtroEmbalaje
                                .includes(embalaje);
                            const coincideEtiqueta = filtroEtiqueta.length === 0 || filtroEtiqueta
                                .includes(etiqueta);

                            return coincideVariedad && coincideEmbalaje && coincideEtiqueta;
                        });
                        $("#btnRecargar").on('click', function() {
                            location.reload();
                        });
                        // Asigna el evento `change` a los filtros
                        $('#filtroVariedad, #filtroEmbalaje, #filtroEtiqueta').on('change', function() {
                            table.draw(); // Redibuja la tabla aplicando los filtros
                        });


                        $("#contenedorCalibres").html('<div class="tab-content rounded-bottom">' +
                            '<div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1011"><div class="row g-4">' +
                            '<div class="col-6 col-lg-4 col-xl-3 col-xxl-2">' +
                            '<div class="card text-white bg-info">' +
                            '<div class="card-body">' +
                            '<div class="text-white text-opacity-75 text-end">' +
                            '<i class="fas fa-box-open fa-2x"></i>' +
                            '</div>' +
                            '<div class="fs-4 fw-semibold">87.500</div>' +
                            '<div class="small text-white text-opacity-75 text-uppercase fw-semibold text-truncate" id="calibre"></div>' +
                            '<div class="progress progress-white progress-thin mt-3">' +
                            '<div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>');

                    },

                    error: function(xhr, status, error) {
                        hideLoading(); // Ocultar incluso si ocurre un error
                        console.error("Error en la solicitud AJAX:", error);
                    }
                });

                $('#toggleFiltros').on('click', function() {
                    $('#filtrosSlide').toggleClass('active');
                });
                $('#cerrar').on('click', function() {
                    $('#filtrosSlide').toggleClass('active');
                });
                $('.select2').on('change', function() {
                    // Redibujar la tabla
                    applyFilters();
                    //applyChildRowFilters();
                });

                function applyFilters() {
                    let filters = {
                        variedad: $('#filtroVariedad').val(),
                        calibre: $('#filtroCalibre').val(),
                        embalaje: $('#filtroEmbalaje').val(),
                        bodega: $('#filtroBodega').val(),
                    };
                }
                // Función para asignar prioridad
                function asignarPrioridad(calibre) {
                    if (calibre.startsWith("L")) {
                        return [0, (calibre.match(/D/g) || [])
                            .length
                        ]; // L tiene prioridad 0, más Ds incrementan el orden
                    }
                    if (calibre.startsWith("X")) {
                        return [1, (calibre.match(/D/g) || []).length]; // X tiene prioridad 1
                    }
                    const match = calibre.match(/^(\d*)J/); // Buscar números seguidos de J
                    if (match) {
                        return [2, parseInt(match[1] || "1")]; // J tiene prioridad 2, con el número antes
                    }
                    return [3, calibre]; // Otros calibres tienen menor prioridad
                }

                $('#TransitoTable').on('click', 'button', function() {

                    const tr = $(this).closest('tr'); // Fila actual
                    const row = table.row(tr); // Objeto de la fila en DataTables

                    if (row.child.isShown()) {
                        // Si las filas de detalles están abiertas, las cerramos
                        row.child.hide();
                        tr.removeClass('details-open');
                    } else {
                        // Llamada AJAX para obtener los detalles
                        const data = row.data();
                        cambiarVideo('{{ asset('img/yegua.webm') }}',
                            'Voy Corriendo, Espere por favor..... :)');
                        showLoading();
                        $.ajax({
                            url: "{{ route('admin.reporteria.obtieneDetallesTransito') }}", // Actualiza con tu URL
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                n_embalaje: data.embalaje,
                                n_variedad: data.variedad,
                                n_etiqueta: data.etiqueta
                            },
                            beforeSend: function() {
                                // Agregar una animación o indicador de carga
                                row.child('<div class="loading">Cargando...</div>').show();
                                tr.addClass('details-open');
                            },
                            success: function(response) {
                                // Construir las filas de detalles con la respuesta
                                const detalleHTML = generarHTMLDetalles(response);
                                hideLoading();
                                row.child(detalleHTML).show();
                            },
                            error: function() {
                                hideLoading();
                                row.child('<div class="error">Error al cargar detalles</div>')
                                    .show();
                            }
                        });
                    }
                });
                // Delegación de eventos para el botón interactivo dentro de las celdas
                $('#TransitoTable').on('click', 'td', function() {
                    var table = $('#TransitoTable').DataTable();
                    var cell = table.cell(this);
                    const row = $(this).closest('tr');
                    const rowData = table.row(row).data();
                    console.log(rowData);
                    var columnIdx = cell.index().column;
                    var n_variedad = rowData.variedad;
                    var n_embalaje = rowData.embalaje;
                    var n_etiqueta = rowData.etiqueta;

                    // Solo actuar si es una columna de calibres (ajustar el rango según tus columnas)
                    if (columnIdx >= 5) {
                        // Obtener el nombre del calibre desde la cabecera
                        var columnHeader = $('#TransitoTable thead th').eq(columnIdx - 4).text();
                        console.log(columnHeader);
                        console.log('n_variedad:' + n_variedad, 'n_embalaje:' + n_embalaje, 'n_etiqueta:' +
                            n_etiqueta);
                        // Mostrar un loader mientras se realiza la solicitud
                        //$('#modalCalibreContent').html('<p class="text-center">Cargando datos...</p>');

                        // Realizar la llamada Ajax para obtener datos específicos
                        cambiarVideo('{{ asset('img/yegua.webm') }}',
                            'Buscando los detalles, Espere por favor..... :)');
                        showLoading();
                        $.ajax({
                            url: "{{ route('admin.reporteria.obtieneDetallesTransitoCalibre') }}", // Cambiar a tu ruta
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                n_calibre: columnHeader, // El calibre (columna seleccionada)
                                n_variedad: n_variedad,
                                c_embalaje: n_embalaje,
                                n_etiqueta: n_etiqueta
                            },
                            success: function(response) {
                                hideLoading();
                                console.log(response.data);
                                generarHTMLDetallesModal(response.data);
                                // Suponiendo que `response` contiene HTML o texto

                            },
                            error: function() {
                                hideLoading();
                                $('#modalCalibreContent').html(
                                    '<p class="text-danger">Error al cargar datos.</p>');
                            }
                        });

                        // Abrir el modal
                        $('#calibreModal').modal('show');
                    }
                });

                function generarHTMLDetallesModal(data) {

                    let bodyModal = "";
                    data.forEach(item => {
                        console.log(item);
                        if (item.e_inspeccion == "Aprobada") {
                            bodyModal +=
                                `<tr style="color: green"><td>${item.fecha_produccion}</td><td>${item.folio}</td>
                                <td>${item.n_variedad_original}</td><td>${item.c_embalaje}</td><td>${item.n_etiqueta}</td><td>${item.n_calibre}</td><td>${item.cantidad}</td><td>${item.peso_neto}</td></tr>`;
                        } else {
                            bodyModal +=
                                `<tr style="color: red"><td>${item.fecha_produccion}</td><td>${item.folio}</td>
                            <td>${item.n_variedad_original}</td><td>${item.c_embalaje}</td><td>${item.n_etiqueta}</td><td>${item.n_calibre}</td><td>${item.cantidad}</td><td>${item.peso_neto}</td></tr>`;
                        }
                    });
                    $('#calibreModalBody').html(bodyModal);

                }

                function generarHTMLDetalles(data) {
                    console.log(data);
                    let html =
                        '<div class="col-md-4" style="overflow-x: auto;"><table class="display table table-bordered table-striped table-hover ajaxTable datatable" style="white-space: nowrap;"><thead><tr>';
                    html +=
                        '<th>Fecha Producción</th><th>Folio</th><th>Bodega</th><th>Inpección</th><th>Texto Libre</th>'; // Modifica con tus columnas
                    html += '</tr></thead><tbody>';

                    data.forEach(item => {
                        html += `<tr>
                    <td style="text-align: center;width: 6%;">${item.fecha_produccion}</td>
                    <td style="text-align: center;width: 6%;">${item.folio}</td>
                    <td style="text-align: center;width: 6%;">${item.n_bodega}</td>
                    <td style="text-align: center;width: 6%;">${item.e_inspeccion}</td>
                    <td style="text-align: center;width: 6%;">${item.texto_libre_hs}</td>
                 </tr>`;
                    });

                    html += '</tbody></table></div>';
                    return html;
                }

            });
        </script>
    @endsection
