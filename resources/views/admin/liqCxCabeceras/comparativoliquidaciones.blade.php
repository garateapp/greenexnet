@extends('layouts.admin')
@section('content')
    <style>
        .indicador {
            font-size: 1.5em;
            font-weight: bolder;
        }

        #loading-animation {
            display: flex;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
        }

        video {
            border-radius: 10px;
        }

        .select2 {
            flex: 1;
            /* Hace que los selects ocupen el mismo ancho */
            min-width: 150px;
            /* Evita que sean demasiado pequeños */
        }

        .subtotal-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }
         tr.subtotal {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        td.numeric {
            text-align: right;
        }
        .negative {
            color: red;
        }
    </style>
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
    <div id="mensaje" style="display: none">
        @if (isset($message) && isset($status))
            <div class="alert alert-{{ $status }}" role="alert">
                {{ $message }}
            </div>
        @endif
    </div>
    <div class="row">
        <div id="loading-animation"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
            <video autoplay loop muted style="width: 200px; height: auto;">
                <source src="{{ asset('img/transito.webm') }}" type="video/webm">
                Your browser does not support the video tag.
            </video>
            <br />
            <div class="text-white text-opacity-75 text-end" id="loading-animation-text">,
                Espera por favor, estamos generando los cálculos necesarios..... :)</div>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Comparativo Liquidaciones</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Filtros</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="filtroEspecie">Especie</label>
                                            <select class="form-control select2" id="cboEspecie" name="cboEspecie"
                                                multiple="multiple">
                                                <option value="">Seleccione un Especie</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="filtroClientePrincipal">Cliente</label>
                                            <select class="form-control select2" id="cboCliente" name="cboCliente">
                                                <option value="">Seleccione un Cliente</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">

                                            <input type="radio" class="" style="font-size: 22px;" id="cboCxCompara"
                                                name="cboCxCompara" value="1" checked>&nbsp;&nbsp;Visualización
                                            General<br />
                                            <input type="radio" class="" id="cboCxCompara" name="cboCxCompara"
                                                value="2">&nbsp;&nbsp;Visualización por cliente

                                        </div>

                                    </div>

                                    {{--  <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="filtroFamilia">Semana</label>
                                            <select class="form-control select2" id="cboSemana" name="cboSemana">
                                                <option value="">Seleccione una Semana</option>
                                            </select>
                                        </div>

                                    </div> --}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Resultados</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="btnFiltrar" class="btn btn-primary mt-3">Filtrar</button>
                                        <div class="table-responsive mt-4">
                                            <table id="comparativeTable" class="display" style="width:100%">
                                                <thead></thead>
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
        </div>

    </div>
    {{--  --}}
    <script>
        let liquidacionesData = [];
        let table;

        $(document).ready(function() {


            $("#loading-animation").show();
            $('.select2').select2({
                placeholder: "Seleccione una opción",
                allowClear: true
            });

            
            $("#loading-animation").show();
            $.ajax({
                url: "{{ route('admin.reporteria.obtenerDataComparativaInicial') }}",
                type: "get",
                // data: {
                //     _token: "{{ csrf_token() }}",
                //     especie: $("#cboEspecie").val() || [],
                //     cliente: $("#cboCliente").val() || "",

                // },
                success: function(response) {
                    liquidacionesData = response;
                    console.log("liquidacionesData loaded:", liquidacionesData.length, "records");
                    console.log("liquidacionesData loaded:", liquidacionesData);

                    // const uniqueIds = [...new Set(liquidacionesData.map(item => item.id))];
                    // console.log("Unique IDs in liquidacionesData:", uniqueIds.length);
                    // if (uniqueIds.length < liquidacionesData.length) {
                    //     console.warn("Duplicate IDs detected");
                    //     liquidacionesData = [...new Map(liquidacionesData.map(item => [item.id, item]))
                    //         .values()
                    //     ];
                    //     console.log("Deduplicated liquidacionesData:", liquidacionesData.length,
                    //         "records");
                    // }

                    const clientesUnicos = [...new Set(liquidacionesData.map(item => (item.cliente ||
                        "").toUpperCase()))];
                    const especiesUnicas = [...new Set(liquidacionesData.map(item => (item.especie ||
                        "")))];
                    const semanasUnicas = [...new Set(liquidacionesData.map(item => (item.ETA_Week ||
                        "")))];
                    console.log("Unique clientes:", clientesUnicos.length);
                    console.log("Unique especies:", especiesUnicas.length);
                    $('#cboCliente').select2({
                        data: clientesUnicos.map(cliente => ({
                            id: cliente,
                            text: cliente
                        })),
                        placeholder: "Selecciona clientes",
                        allowClear: true
                    });

                    // $('#cboCxCompara').select2({
                    //     data: clientesUnicos.map(cliente => ({
                    //         id: cliente,
                    //         text: cliente
                    //     })),
                    //     placeholder: "Selecciona cliente a comparar",
                    //     allowClear: true
                    // });

                    // $('#cboSemana').select2({
                    //     data: semanasUnicas.map(semana => ({
                    //         id: semana,
                    //         text: semana
                    //     })),
                    //     placeholder: "Selecciona semana",
                    //     allowClear: true
                    // });

                    $('#cboEspecie').select2({
                        data: especiesUnicas.map(especie => ({
                            id: especie,
                            text: especie
                        })),
                        placeholder: "Selecciona especie",
                        allowClear: true
                    });



                    $("#loading-animation").hide();
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                    $("#loading-animation").hide();
                    alert("Error al cargar los datos");
                }
            });
            let dataTable = null;
            function initializeDataTable(data) {
                console.log('Initializing DataTable with data:', data);

                // Destroy existing DataTable and clear DOM
                if (dataTable && $.fn.DataTable.isDataTable('#comparativeTable')) {
                    dataTable.destroy();
                    dataTable = null;
                }
                $('#comparativeTable').empty().html('<thead><tr></tr></thead><tbody></tbody>');

                // Fixed columns
                const fixedColumns = [{
                     className: 'dt-control',
                                    orderable: false,
                                    data: null,
                                    defaultContent: ''},
                    { title: 'Nave', data: 'Nave' },
                    { title: 'Etiqueta', data: 'Etiqueta' },
                    { title: 'Embalaje', data: 'Embalaje' },
                    { title: 'Variedad', data: 'Variedad' },
                    { title: 'Calibre', data: 'Calibre' },
                    { 
                        title: 'Suma de Kilos Total', 
                        data: 'Suma de Kilos Total',
                        className: 'numeric',
                        render: data => data ? Number(data).toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''
                    },
                    { 
                        title: 'Suma de FOB TO USD', 
                        data: 'Suma de FOB TO USD',
                        className: 'numeric',
                        render: data => data ? Number(data).toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''
                    },
                    { 
                        title: 'Suma de FOB Kilo USD', 
                        data: 'Suma de FOB Kilo USD',
                        className: 'numeric',
                        render: data => data ? Number(data).toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''
                    },
                    { 
                        title: 'Suma de Venta USD Kilo', 
                        data: 'Suma de Venta USD Kilo',
                        className: 'numeric',
                        render: data => data ? Number(data).toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''
                    },
                    { 
                        title: 'Diferencia', 
                        data: 'Diferencia',
                        className: 'numeric',
                         render: function(data) {
                            if (data === null) return '';
                            const num = Number(data);
                            const formatted = num.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            return num < 0 ? `<span class="negative">${formatted}</span>` : formatted;
                        }
                    },
                    { 
                        title: 'Total Diferencia', 
                        data: 'Total Diferencia',
                        className: 'numeric',
                         render: function(data) {
                            if (data === null) return '';
                            const num = Number(data);
                            const formatted = num.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            return num < 0 ? `<span class="negative">${formatted}</span>` : formatted;
                        }
                    }
                ];

                let columns = [...fixedColumns];

                if (data.length > 0) {
                    const sampleRow = data[0];
                    const dynamicColumns = [];

                    // For vista = 1, add Resto de Clientes columns
                    if ('FOB Kilo USD Resto de Clientes' in sampleRow) {
                        dynamicColumns.push(
                            {
                                title: 'FOB Kilo USD Resto de Clientes',
                                data: 'FOB Kilo USD Resto de Clientes',
                                className: 'numeric',
                                render: data => data !== null ? Number(data).toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''
                            },
                            {
                                title: 'Venta Kilo USD Resto de Clientes',
                                data: 'Venta Kilo USD Resto de Clientes',
                                className: 'numeric',
                                render: data => data !== null ? Number(data).toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''
                            }
                        );
                    } else {
                        // For vista = 2, group columns by client
                        const clients = [];
                        for (const key in sampleRow) {
                            if (key.startsWith('FOB Kilo USD (')) {
                                const client = key.replace('FOB Kilo USD (', '').replace(')', '');
                                clients.push(client);
                            }
                        }
                        // Sort clients alphabetically
                        clients.sort();

                        // Add FOB and Venta columns for each client
                        clients.forEach(client => {
                            const fobKey = `FOB Kilo USD (${client})`;
                            const ventaKey = `Venta Kilo USD (${client})`;
                            dynamicColumns.push(
                                {
                                    title: fobKey,
                                    data: fobKey,
                                    className: 'numeric',
                                    render: data => data !== null ? Number(data).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''
                                },
                                {
                                    title: ventaKey,
                                    data: ventaKey,
                                    className: 'numeric',
                                    render: data => data !== null ? Number(data).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''
                                }
                            );
                        });
                    }
                    columns = columns.concat(dynamicColumns);
                }

                console.log('Columns:', columns);

                dataTable = $('#comparativeTable').DataTable({
                    data: data,
                    columns: columns,
                    pageLength: 100,
                    responsive: true,
                    order: [[0, 'asc'], [1, 'asc']],
                    createdRow: function(row, data) {
                        if (data.Etiqueta.includes('Subtotal')) {
                            $(row).addClass('subtotal');
                        }
                    },
                    language: {
                        search: "Filter:",
                        lengthMenu: "Show _MENU_ entries"
                    }
                });
            }

            function fetchAndDisplayData() {
               
                $.ajax({
                    url: "{{ route('admin.reporteria.obtenerComparativo') }}",
                    type: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        especie: $("#cboEspecie").val() || [],
                        cliente: $("#cboCliente").val() || "",
                        vista: $("input[name='cboCxCompara']:checked").val() || "1",

                    },
                    success: function(data) {
                         if ($.fn.DataTable.isDataTable('#comparativeTable')) {
                            dataTable = $('#comparativeTable').DataTable();
                    dataTable.destroy();
                    dataTable = null;
                     console.log('Destroyed existing DataTable:', $('#comparativeTable')
                        .html());
                }
                        initializeDataTable(data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                        alert('Failed to load data. Please try again.');
                    }
                });
            }





            $('#btnFiltrar').off('click').on('click', function(e) {
                e.preventDefault();
                fetchAndDisplayData();
            });









        });
    </script>
@endsection
