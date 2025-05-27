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

        .form-switch .form-check-input:checked {
            background-position: right center;
            background-image: url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e);
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
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
        <p class="text-muted">
            Esta sección permite comparar las liquidaciones de diferentes especies y clientes,
            facilitando la identificación de diferencias en los precios y cantidades.
        </p>
        <div class="row">
            <div class="col-md-2">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Filtros</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">



                            <div class="form-group">
                                <label for="filtroEspecie">Especie</label>
                                <select class="form-control select2" id="cboEspecie" name="cboEspecie" multiple="multiple">
                                    <option value="">Seleccione un Especie</option>
                                </select>
                            </div>



                            <div class="form-group">
                                <label for="filtroClientePrincipal">Cliente</label>
                                <select class="form-control select2" id="cboCliente" name="cboCliente">
                                    <option value="">Seleccione un Cliente</option>
                                </select>
                            </div>



                            <div class="form-group">

                                <input type="radio" class="" style="font-size: 22px;" id="cboCxCompara"
                                    name="cboCxCompara" value="1" checked>&nbsp;&nbsp;Visualización
                                General<br />
                                <input type="radio" class="" id="cboCxCompara" name="cboCxCompara"
                                    value="2">&nbsp;&nbsp;Visualización por cliente

                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label for="showAllData" class="switch-label">
                                        <span>Comparativo</span>
                                        <span class="switch">
                                            <input type="checkbox" id="showAllData" name="showAllData">
                                            <span class="slider"></span>
                                        </span>
                                        <span>Todos los datos</span>
                                    </label>
                                </div>
                            </div>
                             <div class="row">
                               <p>
                               
                                    <button id="btnFiltrar" class="btn btn-primary mb-3" style="width: 140px;margin-left:25px;">Filtrar</button>
                           
                                </p>
                            </div>


                        </div>
                    </div>
                </div>
               
            </div>


            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Comparativo de Liquidaciones</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Ranking</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-section">
                                           
                                            <table id="rankingTable" class="display" style="width:100%">
                                                <thead>
                                                    <tr></tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Scores</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="card widget-flat">
                                            <div class="card-body">
                                                <div class="float-end">
                                                    <i class="mdi mdi-account-multiple widget-icon"></i>
                                                </div>
                                                <h5 class="text-muted fw-normal mt-0" title="">Costo de Oportunidad
                                                </h5>
                                                <h3 class="mt-3 mb-3" id="scoreCostoOportunidad"></h3>
                                                <p class="mb-0 text-muted">
                                                    <span class="text-success me-2" id="scoreTotalDiferencia"><i
                                                            class="mdi mdi-arrow-up-bold"></i>
                                                    </span>
                                                    <span class="text-nowrap">Total Diferencia</span>
                                                </p>
                                                <p class="mb-0 text-muted">
                                                    <span class="text-success me-2" id="scoreTotalKilos"><i
                                                            class="mdi mdi-arrow-up-bold"></i>
                                                    </span>
                                                    <span class="text-nowrap">Total Kilos</span>
                                                </p>
                                            </div> <!-- end card-body-->
                                        </div>
                                        {{-- <div class="card widget-flat">
                                            <div class="card-body">
                                                <div class="float-end">
                                                    <i class="mdi mdi-account-multiple widget-icon"></i>
                                                </div>
                                                <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Customers
                                                </h5>
                                                <h3 class="mt-3 mb-3">36,254</h3>
                                                <p class="mb-0 text-muted">
                                                    <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i>
                                                        5.27%</span>
                                                    <span class="text-nowrap">Since last month</span>
                                                </p>
                                            </div> <!-- end card-body-->
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Tabla Comparativa</h3>
                                    </div>
                                    <div class="card-body">


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
        let fullDataSet = []; // Store full dataset for toggling
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
            let scoreTotalKilos = 0;
            let scoreTotalDiferencia = 0;
            let scoreCostoOportunidad = 0;

            function initializeDataTable(data) {
                console.log('Initializing DataTable with data:', data);
                fullDataSet = data;
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
                        defaultContent: ''
                    },
                    {
                        title: 'Nave',
                        data: 'Nave'
                    },
                    {
                        title: 'Etiqueta',
                        data: 'Etiqueta'
                    },
                    {
                        title: 'Embalaje',
                        data: 'Embalaje'
                    },
                    {
                        title: 'Variedad',
                        data: 'Variedad'
                    },
                    {
                        title: 'Calibre',
                        data: 'Calibre'
                    },
                    {
                        title: 'Suma de Kilos Total',
                        data: 'Suma de Kilos Total',
                        className: 'numeric',
                        render: function(data) {
                            
                            return data ? Number(data).toLocaleString('es-ES', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : ''
                        }
                    },
                    {
                        title: 'Suma de FOB TO USD',
                        data: 'Suma de FOB TO USD',
                        className: 'numeric',
                        render: data => data ? Number(data).toLocaleString('es-ES', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }) : ''
                    },
                    {
                        title: 'Suma de FOB Kilo USD',
                        data: 'Suma de FOB Kilo USD',
                        className: 'numeric',
                        render: data => data ? Number(data).toLocaleString('es-ES', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }) : ''
                    },
                    {
                        title: 'Suma de Venta USD Kilo',
                        data: 'Suma de Venta USD Kilo',
                        className: 'numeric',
                        render: data => data ? Number(data).toLocaleString('es-ES', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }) : ''
                    },
                    {
                        title: 'Diferencia',
                        data: 'Diferencia',
                        className: 'numeric',
                        render: function(data) {
                            if (data === null) return '';
                            const num = Number(data);
                            const formatted = num.toLocaleString('es-ES', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
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
                           
                            const formatted = num.toLocaleString('es-ES', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
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
                        dynamicColumns.push({
                            title: 'FOB Kilo USD Resto de Clientes',
                            data: 'FOB Kilo USD Resto de Clientes',
                            className: 'numeric',
                            render: data => data !== null ? Number(data).toLocaleString('es-ES', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : ''
                        }, {
                            title: 'Venta Kilo USD Resto de Clientes',
                            data: 'Venta Kilo USD Resto de Clientes',
                            className: 'numeric',
                            render: data => data !== null ? Number(data).toLocaleString('es-ES', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : ''
                        });
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
                            dynamicColumns.push({
                                title: fobKey,
                                data: fobKey,
                                className: 'numeric',
                                render: data => data !== null ? Number(data).toLocaleString(
                                    'en-US', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }) : ''
                            }, {
                                title: ventaKey,
                                data: ventaKey,
                                className: 'numeric',
                                render: data => data !== null ? Number(data).toLocaleString(
                                    'en-US', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }) : ''
                            });
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
                    order: [
                        [0, 'asc'],
                        [1, 'asc']
                    ],
                    createdRow: function(row, data) {
                        if (data.Etiqueta.includes('Subtotal')) {
                            $(row).addClass('subtotal');
                        }
                    },
                    language: {
                        search: "Filtrar:",
                        lengthMenu: "Mostrar _MENU_ entradas",
                        zeroRecords: "No se encontraron resultados",
                        info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                        infoEmpty: "Mostrando 0 a 0 de 0 entradas",
                        infoFiltered: "(filtrado de _MAX_ entradas totales)",
                        paginate: {
                            first: "Primero",
                            last: "Último",
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    }
                });
                currentData = dataTable.data().toArray();
                const showAllData = $(this).is(':checked');
                const filteredData = showAllData ? currentData : currentData.filter(row => row.Diferencia !== 0 &&
                    row.Diferencia !== null);
                dataTable.clear();
                dataTable.rows.add(filteredData).draw();
               filteredData.forEach(row => {
                    scoreTotalKilos += row['Suma de Kilos Total'] || 0;
                    scoreTotalDiferencia += row['Total Diferencia'] || 0;
                });
                $("#scoreTotalKilos").html(scoreTotalKilos.toLocaleString('es-ES', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $("#scoreTotalDiferencia").html(scoreTotalDiferencia.toLocaleString('es-ES', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                 scoreCostoOportunidad = scoreTotalDiferencia / scoreTotalKilos;
                $("#scoreCostoOportunidad").html(scoreCostoOportunidad.toLocaleString('es-ES', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            }

            function fetchAndDisplayData() {
                scoreTotalKilos = 0;
                scoreTotalDiferencia = 0;
                scoreCostoOportunidad = 0;

                $("#loading-animation").show();
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
                        $("#loading-animation").hide();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al cargar los datos.',
                        });
                        $("#loading-animation").hide();
                    }
                });
                  $.ajax({
                    url: "{{ route('admin.reporteria.obtenerRankingOportunidad') }}",
                    type: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                       especie: $("#cboEspecie").val() || [],
                    },
                    success: function(data) {
                        initializeRankingTable(data);
                        $("#loading-animation").hide();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching ranking data:', error);
                        $("#loading-animation").hide();
                        alert('No se pudo cargar los datos de ranking. Intente de nuevo.');
                    }
                });
            }
            let currentData = [];
            // Switch toggle handler
            $('#showAllData').off('change').on('change', function() {
                if (dataTable && fullDataSet.length > 0) {
                    const showAllData = $(this).is(':checked');
                    const filteredData = showAllData ? fullDataSet : fullDataSet.filter(row => row
                        .Diferencia !== 0 && row.Diferencia !== null);
                    dataTable.clear();
                    dataTable.rows.add(filteredData).draw();
                    console.log('Switch toggled:', showAllData ? 'All data' : 'Non-zero Diferencia',
                        'Rows:', filteredData.length);
                }
            });



            $('#btnFiltrar').off('click').on('click', function(e) {
                e.preventDefault();
                fetchAndDisplayData();
             

            });


            function initializeRankingTable(data) {
                console.log('Initializing Ranking DataTable with data:', data);

                if (rankingTable && $.fn.DataTable.isDataTable('#rankingTable')) {
                    rankingTable.destroy();
                    rankingTable = null;
                }
                $('#rankingTable').empty().html('<thead><tr></tr></thead><tbody></tbody>');

                const columns = [{
                        title: 'Ranking',
                        data: 'Ranking',
                        className: 'numeric'
                    },
                    {
                        title: 'Cliente',
                        data: 'Cliente'
                    },
                    {
                        title: 'Suma de Kilos Total',
                        data: 'Suma de Kilos Total',
                        className: 'numeric',
                        render: function(data) {
                            if (!data) return '';
                            const num = Number(data);
                            const formatted = num.toLocaleString('es-ES', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            return num < 0 ? `<span class="negative">${formatted}</span>` : formatted;
                        }
                    },
                    {
                        title: 'Total Diferencia',
                        data: 'Total Diferencia',
                        className: 'numeric',
                        render: function(data) {
                            if (!data) return '';
                            const num = Number(data);
                            const formatted = num.toLocaleString('es-ES', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            return num < 0 ? `<span class="negative">${formatted}</span>` : formatted;
                        }
                    },
                    {
                        title: 'Costo Oportunidad',
                        data: 'Costo Oportunidad',
                        className: 'numeric',
                        render: function(data) {
                            if (!data) return '';
                            const num = Number(data);
                            const formatted = num.toLocaleString('es-ES', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            return num < 0 ? `<span class="negative">${formatted}</span>` : formatted;
                        }
                    }
                ];

                rankingTable = $('#rankingTable').DataTable({
                    data: data,
                    columns: columns,
                    pageLength: 100,
                    responsive: true,
                    order: [
                        [0, 'asc']
                    ], // Sort by Ranking
                    language: {
                        search: "Filtrar:",
                        lengthMenu: "Mostrar _MENU_ entradas",
                        zeroRecords: "No se encontraron resultados",
                        info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                        infoEmpty: "Mostrando 0 a 0 de 0 entradas",
                        infoFiltered: "(filtrado de _MAX_ entradas totales)",
                        paginate: {
                            first: "Primero",
                            last: "Último",
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    }
                });
            }






        });
    </script>
@endsection
