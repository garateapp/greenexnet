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
    <div class="card">
        <div class="card-header">
            Costos Origen
        </div>

        <div class="card-body">
            <form name="frmUploadTrato" method="POST" action="{{ route('admin.costosorigen.guardacostosorigen') }}"
                enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="file">Selecciona archivo</label>
                    <input type="file" name="file" id="file" required>
                    <button type="submit" class="btn btn-primary" id="btnUploadTrato">Subir Archivo</button>

                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Costos de Origen
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="liquidacionesTabs" role="tablist">
                <!-- Pestaña de Liquidaciones -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="totales-tab" data-bs-toggle="tab" data-bs-target="#totales"
                        type="button" role="tab" aria-controls="totales" aria-selected="true">
                        Totales
                    </button>
                </li>
                <!-- Pestaña de Costos -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contenedor-tab" data-bs-toggle="tab" data-bs-target="#contenedor"
                        type="button" role="tab" aria-controls="contenedor" aria-selected="false">
                        Contenedor
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="reporteTabsContent">
                <!-- Contenido de la Pestaña de Liquidaciones -->
                <div class="tab-pane fade show active" id="totales" role="tabpanel" aria-labelledby="totales-tab">
                    <div class="row">
                        <div class="table-responsive col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    COSTOS DE CARGA
                                </div>
                                <div class="card-body">

                                    <table class="table table-bordered table-striped">


                                        @foreach ($costos as $costo)
                                        <thead>
                                            <tr>
                                                <th>Costos de Carga</th>
                                                <th>Total CLP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Consolidación Safe Cargo</td>
                                                <td>{{ number_format($costo->consolidacion_safe_cargo, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Citacion Falso</td>
                                                <td>{{ number_format($costo->citacion_falso, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Materiales Consolidación</td>
                                                <td>{{ number_format($costo->materiales_consolidacion, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Flete Terrestre + Underlung</td>
                                                <td>{{ number_format($costo->flete_terrestre_underlung, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Falso Flete</td>
                                                <td>{{ number_format($costo->falso_flete, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Interplanta</td>
                                                <td>{{ number_format($costo->interplanta, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Sobrestadia</td>
                                                <td>{{ number_format($costo->sobreestadia, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Porteo</td>
                                                <td>{{ number_format($costo->porteo, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Almacenaje</td>
                                                <td>{{ number_format($costo->almacenaje, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Retiro Cruzado</td>
                                                <td>{{ number_format($costo->retiro_cruzado, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Otros Costos Carga</td>
                                                <td>{{ number_format($costo->otros_costos_carga, 0,  ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            @php
                                                $totalCostosCarga = $costo->consolidacion_safe_cargo + $costo->citacion_falso + $costo->materiales_consolidacion + $costo->flete_terrestre_underlung + $costo->falso_flete + $costo->interplanta + $costo->sobreestadia + $costo->porteo + $costo->almacenaje + $costo->retiro_cruzado + $costo->otros_costos_carga;
                                            @endphp
                                            <tr>
                                                <td>Total</td>
                                                <td>{{ number_format($totalCostosCarga, 0,  ',', '.') }}</td>
                                            </tr>
                                        </tfoot>
                                    @endforeach
                                    </table>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    COSTOS DE REEMISIÓN DOCUMENTAL
                                </div>
                                <div class="card-body">

                                    <table class="table table-bordered table-striped">


                                        @foreach ($costos as $costo)
                                        <thead>
                                            <tr>
                                                <th>Costos de Carga</th>
                                                <th>Total CLP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Matriz Fuera de Plazo</td>
                                                <td>{{ number_format($costo->matriz_fuera_plazo, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Corrección Matriz</td>
                                                <td>{{ number_format($costo->correccion_matriz, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Corrección Matriz 2</td>
                                                <td>{{ number_format($costo->correccion_matriz_2, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Corrección BL 1</td>
                                                <td>{{ number_format($costo->correccion_bl_1, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Corrección BL 2</td>
                                                <td>{{ number_format($costo->correccion_bl_2, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Reemisión C O</td>
                                                <td>{{ number_format($costo->reemision_c_o, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Reemisión Fitosanitario</td>
                                                <td>{{ number_format($costo->reemision_fitosanitario, 0,  ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Otros Documental</td>
                                                <td>{{ number_format($costo->otros_documental, 0,  ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            @php
                                                $totalCostosDocs = $costo->matriz_fuera_plazo + $costo->correccion_matriz + $costo->correccion_matriz_2 + $costo->correccion_bl_1 + $costo->correccion_bl_2 + $costo->reemision_c_o + $costo->reemision_fitosanitario + $costo->otros_documental;
                                            @endphp
                                            <tr>
                                                <td>Total</td>
                                                <td>{{ number_format($totalCostosDocs, 0,  ',', '.') }}</td>
                                            </tr>
                                        </tfoot>
                                    @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    COSTOS DE EMBARQUES EN ORIGEN
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">


                                        @foreach ($costos as $costo)
                                            <thead>
                                                <tr>
                                                    <th>Costos de Embarque de Origen</th>
                                                    <th>Total CLP</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Agenciamiento</td>
                                                    <td>{{ number_format($costo->agenciamiento, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Honorarios AGA</td>
                                                    <td>{{ number_format($costo->honorarios_aga, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Certificado de Origen</td>
                                                    <td>{{ number_format($costo->certificado_origen, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Diferencias CO</td>
                                                    <td>{{ number_format($costo->diferencias_co, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Seguridad Portuaria</td>
                                                    <td>{{ number_format($costo->seguridad_portuaria, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Gate Out</td>
                                                    <td>{{ number_format($costo->gate_out, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Servicio Retiro Express</td>
                                                    <td>{{ number_format($costo->servicio_retiro_express, 0,  ',', '.') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Gate In</td>
                                                    <td>{{ number_format($costo->gate_in, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Gate Set</td>
                                                    <td>{{ number_format($costo->gate_set, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Late Arrival</td>
                                                    <td>{{ number_format($costo->late_arrival, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Early Arrival</td>
                                                    <td>{{ number_format($costo->early_arrival, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Emision Destino</td>
                                                    <td>{{ number_format($costo->emision_destino, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Servicio Detention</td>
                                                    <td>{{ number_format($costo->servicio_detention, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Doc Fee</td>
                                                    <td>{{ number_format($costo->doc_fee, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Control Sello</td>
                                                    <td>{{ number_format($costo->control_sello, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Almacenamiento</td>
                                                    <td>{{ number_format($costo->almacenamiento, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Pago Tardio</td>
                                                    <td>{{ number_format($costo->pago_tardio, 0,  ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Otros Costos Embarque</td>
                                                    <td>{{ number_format($costo->otros_costos_embarque, 0,  ',', '.') }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td>Total</td>
                                                    @php
                                                        $totalCostosEmbarque =
                                                            $costo->diferencias_co +
                                                            $costo->seguridad_portuaria +
                                                            $costo->gate_out +
                                                            $costo->servicio_retiro_express +
                                                            $costo->gate_in +
                                                            $costo->gate_set +
                                                            $costo->late_arrival +
                                                            $costo->early_arrival +
                                                            $costo->emision_destino +
                                                            $costo->servicio_detention +
                                                            $costo->doc_fee +
                                                            $costo->control_sello +
                                                            $costo->almacenamiento +
                                                            $costo->pago_tardio +
                                                            $costo->otros_costos_embarque;
                                                    @endphp
                                                    <td>{{ number_format($totalCostosEmbarque, 0,  ',', '.') }}</td>
                                                </tr>
                                            </tfoot>
                                        @endforeach

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="contenedor" role="tabpanel" aria-labelledby="contenedor-tab">
                    <div class="table-responsive mt-3">
                        Contenedor
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#btnUploadTrato').on('submit', function(e) {


                var formData = new FormData($("#formUploadTrato")[0]);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {},
                    error: function(response) {
                        alert('Error al subir el archivo.');
                        // Aquí puedes agregar código para manejar el error
                    }
                });
            });

            function formatNumber2(number) {
                return new Intl.NumberFormat('es-CL', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            }
        });
    </script>
@endsection
