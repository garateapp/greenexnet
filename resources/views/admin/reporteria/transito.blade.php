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
                <h1>Contando Cajas Espera por favor..... :)</h1>
            </div>

            <div class="row">
                <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                    <label for="filtroVariedad">Variedad</label>
                    <select id="filtroVariedad" class="form-control select2" multiple="multiple"></select>
                </div>
                <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                    <label for="filtroEmbalaje">Embalaje</label>
                    <select id="filtroEmbalaje" class="form-control select2" multiple="multiple"></select>
                </div>
                <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                    <label for="filtroCalibre">Calibre</label>
                    <select id="filtroCalibre" class="form-control select2" multiple="multiple"></select>
                </div>
                <div class="col-6 col-lg-4 col-xl-3 col-xxl-2">
                    <label for="filtroBodega">Bodega</label>
                    <select id="filtroBodega" class="form-control select2" multiple="multiple"></select>
                </div>
            </div>
            <hr />

            <button id="btnvistaBodega" class="btn btn-primary">Vista Bodega</button> &nbsp; <button id="btnvistaVariedad"
                class="btn btn-primary">Vista Variedad</button> &nbsp; <button id="btnvistaCalibre"
                class="btn btn-primary">Vista Calibre</button>
            <hr />
            <div class="row" id="vistaCalibres">
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
            function showLoading() {
                $("#loading-animation").fadeIn();
            }

            function hideLoading() {
                $("#loading-animation").fadeOut();
            }
            $(document).ready(function() {
                let table = null;
                let dtButtons = $.extend(false, [], $.fn.dataTable.defaults.buttons);
                showLoading(); // Mostrar la animación de carga
                $.ajax({
                    url: "{{ route('admin.reporteria.obtieneTransito') }}",
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        hideLoading(); // Ocultar la animación de carga
                        var variedades = data.n_variedades;
                        var calibres = data.n_calibres;
                        var embalaje = data.c_embalajes;
                        var bodega = data.n_bodegas;

                        variedades.forEach(
                            function(value) {

                                $('#filtroVariedad').append(new Option(value, value));
                            });

                        calibres.forEach(
                            function(value) {
                                $('#filtroCalibre').append(new Option(value, value));
                            });
                        embalaje.forEach(
                            function(value) {
                                $('#filtroEmbalaje').append(new Option(value, value));
                            });
                        bodega.forEach(
                            function(value) {
                                $('#filtroBodega').append(new Option(value, value));
                            });

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
                                `${item.n_variedad}-${item.n_embalaje}-${item.e_inspeccion}-${item.n_exportadora}`;

                            if (!acc[key]) {
                                acc[key] = {
                                    variedad: item.n_variedad,
                                    embalaje: item.c_embalaje,
                                    etiqueta: item.n_etiqueta,
                                    exportadora: item.n_exportadora,
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
                                    <th></th>
                                    <th></th>
                                    <th></th>

                                    ${calibreSubHeader}
                                </tr>
                            </thead>
                            <tbody></tbody>`
                        );
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
                                    if (palletsValue > 1) {
                                        // Encuentra la celda correspondiente al pallet
                                        let palletCell = $(
                                            `td:eq(${calibres.indexOf(calibre) * 2 + 5})`,
                                            row);
                                        palletCell.css({
                                            backgroundColor: "green",
                                            color: "white"
                                        });
                                    }
                                });
                            },
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
                                row.child(detalleHTML).show();
                            },
                            error: function() {
                                row.child('<div class="error">Error al cargar detalles</div>')
                                    .show();
                            }
                        });
                    }
                });

                function generarHTMLDetalles(data) {
                    let html = '<table class="table table-bordered table-striped"><thead><tr>';
                    html += '<th>Fecha Producción</th><th>Folio</th><th>Inpección</th><th>Texto Libre</th>'; // Modifica con tus columnas
                    html += '</tr></thead><tbody>';

                    data.forEach(item => {
                        html += `<tr>
                    <td>${item.fecha_produccion}</td>
                    <td>${item.folio}</td>
                    <td>${item.e_inspeccion}</td>
                    <td>${item.texto_libre_hs}</td>
                 </tr>`;
                    });

                    html += '</tbody></table>';
                    return html;
                }

            });
        </script>
    @endsection
