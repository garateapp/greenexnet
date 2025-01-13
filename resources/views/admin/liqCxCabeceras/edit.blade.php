@extends('layouts.admin')
@section('content')
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
            {{ trans('global.edit') }} {{ trans('cruds.liqCxCabecera.title_singular') }}
        </div>
        <div class="col-md-6">
        <a href={{ route('admin.liq-cx-cabeceras.index') }} class="btn btn-success mt-3 ">
            Volver
        </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.liq-cx-cabeceras.update', [$liqCxCabecera->id]) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">
                    <!-- Columna izquierda -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required"
                                for="instructivo">{{ trans('cruds.liqCxCabecera.fields.instructivo') }}</label>
                            <input class="form-control {{ $errors->has('instructivo') ? 'is-invalid' : '' }}" type="text"
                                name="instructivo" id="instructivo"
                                value="{{ old('instructivo', $liqCxCabecera->instructivo) }}" required>
                            @if ($errors->has('instructivo'))
                                <div class="invalid-feedback">{{ $errors->first('instructivo') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.instructivo_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="required"
                                for="cliente_id">{{ trans('cruds.liqCxCabecera.fields.cliente') }}</label>
                            <select class="form-control select2 {{ $errors->has('cliente') ? 'is-invalid' : '' }}"
                                name="cliente_id" id="cliente_id" required>
                                @foreach ($clientes as $id => $entry)
                                    <option value="{{ $id }}"
                                        {{ (old('cliente_id') ? old('cliente_id') : $liqCxCabecera->cliente->id ?? '') == $id ? 'selected' : '' }}>
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('cliente'))
                                <div class="invalid-feedback">{{ $errors->first('cliente') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.cliente_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="eta">{{ trans('cruds.liqCxCabecera.fields.eta') }}</label>
                            <input class="form-control date {{ $errors->has('eta') ? 'is-invalid' : '' }}" type="text"
                                name="eta" id="eta" value="{{ old('eta', $liqCxCabecera->eta) }}">
                            @if ($errors->has('eta'))
                                <div class="invalid-feedback">{{ $errors->first('eta') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.eta_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="required"
                                for="tasa_intercambio">{{ trans('cruds.liqCxCabecera.fields.tasa_intercambio') }}</label>
                            <input class="form-control {{ $errors->has('tasa_intercambio') ? 'is-invalid' : '' }}"
                                type="number" name="tasa_intercambio" id="tasa_intercambio"
                                value="{{ old('tasa_intercambio', $liqCxCabecera->tasa_intercambio) }}" step="0.01"
                                required>
                            @if ($errors->has('tasa_intercambio'))
                                <div class="invalid-feedback">{{ $errors->first('tasa_intercambio') }}</div>
                            @endif
                            <span
                                class="help-block">{{ trans('cruds.liqCxCabecera.fields.tasa_intercambio_helper') }}</span>
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required" for="nave_id">{{ trans('cruds.liqCxCabecera.fields.nave') }}</label>
                            <select class="form-control select2 {{ $errors->has('nave') ? 'is-invalid' : '' }}"
                                name="nave_id" id="nave_id" required>
                                @foreach ($naves as $id => $entry)
                                    <option value="{{ $id }}"
                                        {{ (old('nave_id') ? old('nave_id') : $liqCxCabecera->nave->id ?? '') == $id ? 'selected' : '' }}>
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('nave'))
                                <div class="invalid-feedback">{{ $errors->first('nave') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.nave_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="required"
                                for="total_costo">{{ trans('cruds.liqCxCabecera.fields.total_costo') }}</label>
                            <input class="form-control {{ $errors->has('total_costo') ? 'is-invalid' : '' }}"
                                type="number" name="total_costo" id="total_costo"
                                value="{{ old('total_costo', $liqCxCabecera->total_costo) }}" step="0.01" required>
                            @if ($errors->has('total_costo'))
                                <div class="invalid-feedback">{{ $errors->first('total_costo') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.total_costo_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="total_bruto">{{ trans('cruds.liqCxCabecera.fields.total_bruto') }}</label>
                            <input class="form-control {{ $errors->has('total_bruto') ? 'is-invalid' : '' }}"
                                type="number" name="total_bruto" id="total_bruto"
                                value="{{ old('total_bruto', $liqCxCabecera->total_bruto) }}" step="0.01">
                            @if ($errors->has('total_bruto'))
                                <div class="invalid-feedback">{{ $errors->first('total_bruto') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.total_bruto_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="required"
                                for="total_neto">{{ trans('cruds.liqCxCabecera.fields.total_neto') }}</label>
                            <input class="form-control {{ $errors->has('total_neto') ? 'is-invalid' : '' }}" type="number"
                                name="total_neto" id="total_neto"
                                value="{{ old('total_neto', $liqCxCabecera->total_neto) }}" step="0.01" required>
                            @if ($errors->has('total_neto'))
                                <div class="invalid-feedback">{{ $errors->first('total_neto') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.total_neto_helper') }}</span>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>

        </div>
    </div>
    <div>
        <ul class="nav nav-tabs" id="liquidacionesTabs" role="tablist">
            <!-- Pestaña de Liquidaciones -->
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="liquidaciones-tab" data-bs-toggle="tab"
                    data-bs-target="#liquidaciones" type="button" role="tab" aria-controls="liquidaciones"
                    aria-selected="true">
                    Liquidaciones
                </button>
            </li>
            <!-- Pestaña de Costos -->
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="costos-tab" data-bs-toggle="tab" data-bs-target="#costos" type="button"
                    role="tab" aria-controls="costos" aria-selected="false">
                    Costos
                </button>
            </li>
        </ul>
        <div class="tab-content" id="liquidacionesTabsContent">
            <!-- Contenido de la Pestaña de Liquidaciones -->
            <div class="tab-pane fade show active" id="liquidaciones" role="tabpanel"
                aria-labelledby="liquidaciones-tab">
                <div class="table-responsive mt-3">
                    <div class="card">
                        <div class="card-header">
                            Items
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
                                data-bs-target="#addLiquidacionModal">
                                Agregar Liquidación
                            </button>
                            <table id="liquidacionesTable"
                                class="table table-bordered table-striped table-hover ajaxTable datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>

                                        <th>Contenedor</th>
                                        <th>ETA</th>
                                        <th>Pallet</th>
                                        <th>Variedad</th>
                                        <th>Etiqueta</th>
                                        <th>Embalaje</th>
                                        <th>Calibre</th>
                                        <th>Cantidad</th>
                                        <th>Fecha Venta</th>
                                        <th>Ventas</th>
                                        <th>Precio Unitario</th>
                                        <th>Monto RMB</th>
                                        <th>Observaciones</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Contenido de la Pestaña de Costos -->
            <div class="tab-pane fade" id="costos" role="tabpanel" aria-labelledby="costos-tab">
                <div class="card">
                    <div class="card-header">
                        Costos
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCostoModal">
                            Agregar Costo
                        </button>
                        <table id="costosTable"
                            class="table table-bordered table-striped table-hover ajaxTable datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo de Costo</th>
                                    <th>Nombre</th>
                                    <th>Monto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    
    <div class="modal fade" id="addCostoModal" tabindex="-1" role="dialog" aria-labelledby="addCostoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCostoModalLabel">Agregar Costo</h5>
                    <button type="button" id="btnCloseModal" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addCostoForm">
                        <div class="form-group">
                            <label for="categoria">Categoría</label>
                            <select id="categoria" name="categoria" class="form-control">
                                <option value="Comisión">Comisión</option>
                                <option value="VAT(iva)">VAT(iva)</option>
                                <option value="Impuestos destino">Impuestos destino</option>
                                <option value="Entrada Mercado">Entrada Mercado</option>
                                <option value="Costos Mercado">Costos Mercado</option>
                                <option value="Otros Costos Destino">Otros Costos Destino</option>
                                <option value="Flete">Flete</option>
                                <option value="Costo Logistico">Costo Logistico</option>
                                <option value="Flete Internacional">Flete Internacional</option>
                                <option value="Flete Domestico">Flete Domestico</option>
                                <option value="Impuestos">Impuestos</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nombre_costo">Nombre del Costo</label>
                            <input type="text" id="nombre_costo" name="nombre_costo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <input type="number" step="0.01" id="valor" name="valor" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="saveCostoBtn">Guardar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para agregar o editar un item -->
    <div class="modal fade" id="addLiquidacionModal" tabindex="-1" aria-labelledby="addLiquidacionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLiquidacionModalLabel">Agregar Liquidación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="liquidacionForm">
                        @csrf
                        <div class="mb-3">
                            <label for="contenedor" class="form-label">Contenedor</label>
                            <input type="text" class="form-control" id="contenedor" name="contenedor">
                        </div>
                        <div class="mb-3">
                            <label for="eta" class="form-label">ETA</label>
                            <input type="date" class="form-control" id="eta" name="eta">
                        </div>
                        <div class="mb-3">
                            <label for="pallet" class="form-label">Pallet</label>
                            <input type="text" class="form-control" id="pallet" name="pallet">
                        </div>
                        <div class="mb-3">
                            <label for="variedad" class="form-label">Variedad</label>
                            <input type="text" class="form-control" id="variedad_id" name="variedad_id">
                        </div>
                        <div class="mb-3">
                            <label for="etiqueta" class="form-label">Etiqueta</label>
                            <input type="text" class="form-control" id="etiqueta_id" name="etiqueta_id">
                        </div>
                        <div class="mb-3">
                            <label for="embalaje" class="form-label">Embalaje</label>
                            <input type="text" class="form-control" id="embalaje_id" name="embalaje_id">
                        </div>
                        <div class="mb-3">
                            <label for="calibre" class="form-label">Calibre</label>
                            <input type="text" class="form-control" id="calibre" name="calibre">
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad">
                        </div>
                        <div class="mb-3">
                            <label for="fecha_venta" class="form-label">Fecha de Venta</label>
                            <input type="date" class="form-control" id="fecha_venta" name="fecha_venta">
                        </div>
                        <div class="mb-3">
                            <label for="ventas" class="form-label">Ventas</label>
                            <input type="number" class="form-control" id="ventas" name="ventas">
                        </div>
                        <div class="mb-3">
                            <label for="precio_unitario" class="form-label">Precio Unitario</label>
                            <input type="number" step="0.01" class="form-control" id="precio_unitario"
                                name="precio_unitario">
                        </div>
                        <div class="mb-3">
                            <label for="monto_rmb" class="form-label">Monto RMB</label>
                            <input type="number" step="0.01" class="form-control" id="monto_rmb" name="monto_rmb">
                        </div>
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveLiquidacion">Guardar</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            let liqCxCabecera = '{{ $liqCxCabecera->id }}';
            let table = $('#liquidacionesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.liq-cx-cabeceras.getDatosLiqItems') }}',
                    data: function(d) {
                        d.id = liqCxCabecera; // Pasar instructivo como parámetro
                    }
                },
                fixedColumns: true,
                fixedHeader: true,
                responsive: true,
                language: {

                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-CL.json'
                },
                columns: [{
                        data: 'id'
                    },

                    {
                        data: 'contenedor',
                        render: makeEditable('contenedor')
                    },
                    {
                        data: 'eta',
                        render: makeEditable('eta')
                    },
                    {
                        data: 'pallet',
                        render: makeEditable('pallet')
                    },
                    {
                        data: 'variedad_id',
                        render: makeEditable('variedad_id')
                    },
                    {
                        data: 'etiqueta_id',
                        render: makeEditable('etiqueta_id')
                    },
                    {
                        data: 'embalaje_id',
                        render: makeEditable('embalaje_id')
                    },
                    {
                        data: 'calibre',
                        render: makeEditable('calibre')
                    },
                    {
                        data: 'cantidad',
                        render: makeEditable('cantidad')
                    },
                    {
                        data: 'fecha_venta',
                        render: makeEditable('fecha_venta')
                    },
                    {
                        data: 'ventas',
                        render: makeEditable('ventas')
                    },
                    {
                        data: 'precio_unitario',
                        render: makeEditable('precio_unitario')
                    },
                    {
                        data: 'monto_rmb',
                        render: makeEditable('monto_rmb')
                    },
                    {
                        data: 'observaciones',
                        render: makeEditable('observaciones')
                    },
                    {
                        data: 'id',
                        render: function(data) {
                            return `<button class="btn btn-danger btn-sm delete-btn" data-id="${data}">Eliminar</button>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            let tableCosto = $('#costosTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.liq-cx-cabeceras.getDatosLiqCostos') }}',
                    data: function(d) {
                        d.id = liqCxCabecera; // Pasar instructivo como parámetro
                    }
                },
                fixedColumns: true,
                fixedHeader: true,
                responsive: true,
                language: {

                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-CL.json'
                },
                columns: [{
                        data: 'id'
                    },

                    {
                        data: 'categoria',
                        render: makeEditable('categoria')
                    },
                    {
                        data: 'nombre_costo',
                        render: makeEditable('nombre_costo')
                    },
                    {
                        data: 'valor',
                        render: makeEditable('valor')
                    },

                    {
                        data: 'id',
                        render: function(data) {
                            return `<button class="btn btn-danger btn-sm delete-btn" data-id="${data}">Eliminar</button>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            // Función para permitir la edición inline
            function makeEditable(field) {
                return function(data, type, row, meta) {
                    if (type === 'display') {
                        if (field === 'categoria') {
                            // Para el campo "categoria", mostrar un <select>
                            return `<span class="no-editable" data-id="${row.id}" data-field="${field}">${data}</span>`;
                        }
                        // Para el resto de los campos, se mantiene como input normal
                        return `<span class="editable" data-id="${row.id}" data-field="${field}">${data}</span>`;
                    }

                    return data;
                }
            }
            // Evento para detectar clic en campos editables
            $('#liquidacionesTable').on('click', '.editable', function() {
                let span = $(this);
                let currentValue = span.text();
                let field = span.data('field');
                let id = span.data('id');

                // Reemplazar contenido con un input
                let input = $(`<input type="text" class="form-control" value="${currentValue}">`);
                span.replaceWith(input);

                // Enfocar y manejar cambios
                input.focus().blur(function() {
                    let newValue = $(this).val();
                    if (newValue !== currentValue) {
                        $.ajax({
                            url: '{{ route('admin.liq-cx-cabeceras.updateInline') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id,
                                field: field,
                                value: newValue
                            },
                            success: function(response) {
                                if (response.success) {
                                    input.replaceWith(
                                        `<span class="editable" data-id="${id}" data-field="${field}">${newValue}</span>`
                                    );
                                } else {
                                    alert('Error al actualizar el campo.');
                                    input.replaceWith(
                                        `<span class="editable" data-id="${id}" data-field="${field}">${currentValue}</span>`
                                    );
                                }
                            },
                            error: function() {
                                alert('Error al conectar con el servidor.');
                                input.replaceWith(
                                    `<span class="editable" data-id="${id}" data-field="${field}">${currentValue}</span>`
                                );
                            }
                        });
                    } else {
                        input.replaceWith(
                            `<span class="editable" data-id="${id}" data-field="${field}">${currentValue}</span>`
                        );
                    }
                });
            });
            $('#liquidacionesTable').on('click', '.delete-btn', function() {
                let id = $(this).data('id');

                if (confirm('¿Estás seguro de que deseas eliminar esta línea?')) {
                    $.ajax({
                        url: `/admin/liq-cx-cabeceras/destroyItem/${id}`,
                        method: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                table.ajax.reload(); // Recargar la tabla
                            } else {
                                alert('Error al eliminar la línea.');
                            }
                        },
                        error: function() {
                            alert('Ocurrió un error al intentar eliminar la línea.');
                        }
                    });
                }
            });
            $('#costosTable').on('click', '.editable', function() {
                let span = $(this);
                let currentValue = span.text();
                let field = span.data('field');
                let id = span.data('id');

                // Reemplazar contenido con un input
                let input = $(`<input type="text" class="form-control" value="${currentValue}">`);
                span.replaceWith(input);

                // Enfocar y manejar cambios
                input.focus().blur(function() {
                    let newValue = $(this).val();
                    if (newValue !== currentValue) {
                        $.ajax({
                            url: '{{ route('admin.liq-costos.updatecosto') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id,
                                field: field,
                                value: newValue
                            },
                            success: function(response) {
                                if (response.success) {
                                    input.replaceWith(
                                        `<span class="editable" data-id="${id}" data-field="${field}">${newValue}</span>`
                                    );
                                } else {
                                    alert('Error al actualizar el campo.');
                                    input.replaceWith(
                                        `<span class="editable" data-id="${id}" data-field="${field}">${currentValue}</span>`
                                    );
                                }
                            },
                            error: function() {
                                alert('Error al conectar con el servidor.');
                                input.replaceWith(
                                    `<span class="editable" data-id="${id}" data-field="${field}">${currentValue}</span>`
                                );
                            }
                        });
                    } else {
                        input.replaceWith(
                            `<span class="editable" data-id="${id}" data-field="${field}">${currentValue}</span>`
                        );
                    }
                });
            });


            $('#costosTable').on('click', '.delete-btn', function() {
                let id = $(this).data('id');

                if (confirm('¿Estás seguro de que deseas eliminar esta línea?')) {
                    $.ajax({
                        url: `/admin/liq-cx-cabeceras/destroyCosto/${id}`,
                        method: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                tableCosto.ajax.reload(); // Recargar la tabla
                            } else {
                                alert('Error al eliminar la línea.');
                            }
                        },
                        error: function() {
                            alert('Ocurrió un error al intentar eliminar la línea.');
                        }
                    });
                }
            });
            // Función para agregar una fila a Liquidaciones


            $("#saveLiquidacion").click(function() {
                var liqcabecera_id = liqCxCabecera;
                var contenedor = $('#contenedor').val();
                var eta = $('#eta').val();
                var pallet = $('#pallet').val();
                var variedad_id = $('#variedad_id').val();
                var etiqueta_id = $('#etiqueta_id').val();
                var embalaje_id = $('#embalaje_id').val();
                var calibre = $('#calibre').val();
                var cantidad = $('#cantidad').val();
                var fecha_venta = $('#fecha_venta').val();
                var ventas = $('#ventas').val();
                var precio_unitario = $('#precio_unitario').val();
                var monto_rmb = $('#monto_rmb').val();
                var observaciones = $('#observaciones').val();
                $.ajax({
                    url: '{{ route('admin.liquidaciones-cxes.store') }}',
                    method: 'POST',
                    data: {
                        liqcabecera_id: liqcabecera_id,
                        contenedor: contenedor,
                        eta: eta,
                        pallet: pallet,
                        variedad_id: variedad_id,
                        etiqueta_id: etiqueta_id,
                        embalaje_id: embalaje_id,
                        calibre: calibre,
                        cantidad: cantidad,
                        fecha_venta: fecha_venta,
                        ventas: ventas,
                        precio_unitario: precio_unitario,
                        monto_rmb: monto_rmb,
                        observaciones: observaciones,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            // Cierra el modal
                            $("#addLiquidacionModal").modal('hide');
                            // Limpia el formulario
                            document.getElementById('liquidacionForm').reset();
                            // Actualiza la tabla
                            $('#liquidacionesTable').DataTable().ajax.reload();
                        } else {
                            alert('Error al guardar el item: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Ocurrio un error al intentar guardar el item.');
                    }
                });
            });
            $('#saveCostoBtn').click(function() {
                var categoria = $('#categoria').val();
                var nombre_costo = $('#nombre_costo').val();
                var valor = $('#valor').val();

                // Validar los campos antes de enviar
                if (categoria && nombre_costo && valor) {
                    // Agregar la nueva fila al DataTable
                    $.ajax({
                        url: '{{ route('admin.liq-costos.store') }}', // Ruta para guardar los datos
                        method: 'POST',
                        data: {
                            liq_cabecera_id: liqCxCabecera,
                            categoria: categoria,
                            nombre_costo: nombre_costo,
                            valor: valor,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Recargar la tabla después de guardar
                            tableCosto.ajax.reload();

                            //closeModal('addCostoModal');
                            var modal = new bootstrap.Modal(document.getElementById(
                                'addCostoModal'));
                            modal.hide();

                            $('#addCostoForm')[0].reset();
                        },
                        error: function(error) {
                            alert('Hubo un error al guardar los datos.');
                        }
                    });

                } else {
                    alert("Por favor complete todos los campos.");
                }
            });

            // Guardar una fila editada
            $('#liquidacionesTable, #costosTable').on('click', '.save-btn', function() {
                let row = $(this).closest('tr');
                let data = row.find('input').map(function() {
                    return $(this).val();
                }).get();

            });
            // Guardar una fila editada
            $('#costosTable').on('click', '.save-btn', function() {
                let row = $(this).closest('tr');
                let data = row.find('input').map(function() {
                    return $(this).val();
                }).get();

                // Aquí puedes hacer una llamada AJAX para guardar los datos

                // Ejemplo de solicitud AJAX:
                // $.ajax({
                //     url: '/ruta/a/guardar',
                //     method: 'POST',
                //     data: {
                //         _token: '{{ csrf_token() }}',
                //         data: data
                //     },
                //     success: function(response) {
                //         alert('Fila guardada');
                //         liquidacionesTable.ajax.reload();
                //         costosTable.ajax.reload();
                //     },
                //     error: function() {
                //         alert('Hubo un error al guardar la fila');
                //     }
                // });
            });

            function openModal(id) {
                document.getElementById(id).setAttribute('aria-hidden', 'false');
                document.getElementById(id).focus();
            }

            function closeModal(id) {
                document.getElementById(id).setAttribute('aria-hidden', 'true');
            }

        });
    </script>
@endsection
