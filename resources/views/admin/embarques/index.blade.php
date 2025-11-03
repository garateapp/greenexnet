@extends('layouts.admin')
@section('content')
    @can('embarque_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                {{-- <a class="btn btn-success" href="{{ route('admin.embarques.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.embarque.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button> --}}
                <a class="btn btn-success" href="{{ route('admin.embarques.ImportarEmbarques') }}">
                    Importar Embarques
                </a>
                <a class="btn btn-success" href="{{ route('admin.embarques.enviarMail') }}">
                    Enviar Mail
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.embarque.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">

            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Embarque"
                id="datatable-Embarque">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.id') }}
                        </th>
                        <th>Transporte</th>
                        <th>
                            {{ trans('cruds.embarque.fields.temporada') }}
                        </th>
                        </th>
                        <th>
                            Semana
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.num_embarque') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.id_cliente') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.n_cliente') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.planta_carga') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.n_naviera') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.nave') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.num_contenedor') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.especie') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.variedad') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.embalajes') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.etiqueta') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.cajas') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.peso_neto') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.puerto_embarque') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.pais_destino') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.puerto_destino') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.mercado') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.etd_estimado') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.eta_estimado') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.fecha_zarpe_real') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.fecha_arribo_real') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.dias_transito_real') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.estado') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.descargado') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.retirado_full') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.devuelto_vacio') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.notas') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.embarque.fields.calificacion') }}
                        </th>
                        <th>
                            País Conexión
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.conexiones') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.con_fecha_hora') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.status_aereo') }}
                        </th> --}}
                        <th>
                            {{ trans('cruds.embarque.fields.num_pallets') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.embarque.fields.embalaje_std') }}
                        </th> --}}
                        <th>
                            {{ trans('cruds.embarque.fields.num_orden') }}
                        </th>
                        <th>
                            {{ trans('cruds.embarque.fields.tipo_especie') }}
                        </th>
                        <th>
                            AWB
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>

                            <select class="search" strict="true" id="cboTransporte">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach (App\Models\Embarque::TRANSPORTE_SELECT as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach (App\Models\Embarque::TEMPORADA_SELECT as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach (App\Models\Embarque::ESTADO_SELECT as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        {{-- <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach (App\Models\Embarque::STATUS_AEREO_SELECT as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td> --}}
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        {{-- <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td> --}}
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                    </tr>
                </thead>
            </table>

        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(document).ready(function() {

            $(function() {
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                @can('embarque_delete')
                    let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                    let deleteButton = {
                        text: deleteButtonTrans,
                        url: "{{ route('admin.embarques.massDestroy') }}",
                        className: 'btn-danger',
                        action: function(e, dt, node, config) {
                            var ids = $.map(dt.rows({
                                selected: true
                            }).data(), function(entry) {
                                return entry.id
                            });

                            if (ids.length === 0) {
                                alert('{{ trans('global.datatables.zero_selected') }}')

                                return
                            }

                            if (confirm('{{ trans('global.areYouSure') }}')) {
                                $.ajax({
                                        headers: {
                                            'x-csrf-token': _token
                                        },
                                        method: 'POST',
                                        url: config.url,
                                        data: {
                                            ids: ids,
                                            _method: 'DELETE'
                                        }
                                    })
                                    .done(function() {
                                        location.reload()
                                    })
                            }
                        }
                    }
                    dtButtons.push(deleteButton)
                @endcan
                let dtButtonsGtime = $.extend(true, [], $.fn.dataTable.defaults.buttons)

                let ActualizarButtonTrans = 'Actualizar FX';
                let FXButton = {
                    text: ActualizarButtonTrans,
                    url: "{{ route('admin.embarques.ActualizaSistemaFX') }}",
                    className: 'btn-success',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {

                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(FXButton)
                let dtOverrideGlobals = {
                    buttons: dtButtons,
                    processing: true,
                    serverSide: true,
                    fixedHeader: true,
                    fixedColumns: true,
                    retrieve: true,
                    responsive: true,
                    scrollX: true, // Habilita scroll horizontal
                    scrollY: '600px', // Altura máxima del scroll vertical
                    scrollCollapse: true, // Colapsa la altura si no hay suficientes filas
                    aaSorting: [],
                    ajax: "{{ route('admin.embarques.index') }}",
                    columns: [{
                            data: 'placeholder',
                            name: 'placeholder'
                        },
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'transporte',
                            name: 'transporte'
                        },

                        {
                            data: 'temporada',
                            name: 'temporada'
                        },
                        {
                            data: 'semana',
                            name: 'semana'
                        },
                        {
                            data: 'num_embarque',
                            name: 'num_embarque'
                        },

                        {
                            data: 'id_cliente',
                            name: 'id_cliente',

                        },
                        {
                            data: 'n_cliente',
                            name: 'n_cliente'
                        },
                        {
                            data: 'planta_carga',
                            name: 'planta_carga',

                        },
                        {
                            data: 'n_naviera',
                            name: 'n_naviera'
                        },
                        {
                            data: 'nave',
                            name: 'nave'
                        },
                        {
                            data: 'num_contenedor',
                            name: 'num_contenedor'
                        },
                        {
                            data: 'especie',
                            name: 'especie'
                        },
                        {
                            data: 'variedad',
                            name: 'variedad'
                        },
                        {
                            data: 'embalajes',
                            name: 'embalajes'
                        },
                        {
                            data: 'etiqueta',
                            name: 'etiqueta'
                        },
                        {
                            data: 'cajas',
                            name: 'cajas'
                        },
                        {
                            data: 'peso_neto',
                            name: 'peso_neto'
                        },
                        {
                            data: 'puerto_embarque',
                            name: 'puerto_embarque'
                        },
                        {
                            data: 'pais_destino',
                            name: 'pais_destino'
                        },
                        {
                            data: 'puerto_destino',
                            name: 'puerto_destino'
                        },
                        {
                            data: 'mercado',
                            name: 'mercado',

                        },
                        {
                            data: 'etd_estimado',
                            name: 'etd_estimado'
                        },
                        {
                            data: 'eta_estimado',
                            name: 'eta_estimado'
                        },
                        {
                            data: 'fecha_zarpe_real',
                            name: 'fecha_zarpe_real',
                            type: 'datetime'
                        },
                        {
                            data: 'fecha_arribo_real',
                            name: 'fecha_arribo_real',
                            type: 'datetime'
                        },
                        {
                            data: 'dias_transito_real',
                            name: 'dias_transito_real'
                        },
                        {
                            data: 'estado',
                            name: 'estado'
                        },
                        {
                            data: 'descargado',
                            name: 'descargado'
                        },
                        {
                            data: 'retirado_full',
                            name: 'retirado_full'
                        },
                        {
                            data: 'devuelto_vacio',
                            name: 'devuelto_vacio'
                        },
                        {
                            data: 'notas',
                            name: 'notas'
                        },
                        // {
                        //     data: 'calificacion',
                        //     name: 'calificacion'
                        // },
                        // {
                        //     data: 'pais_conexion',
                        //     name: 'pais_conexion'
                        // },
                        // {
                        //     data: 'conexiones',
                        //     name: 'conexiones',
                        //     type: 'datetime'
                        // },
                        // {
                        //     data: 'con_fecha_hora',
                        //     name: 'con_fecha_hora'
                        // },
                        // {
                        //     data: 'status_aereo',
                        //     name: 'status_aereo'
                        // },
                        {
                            data: 'cant_pallets',
                            name: 'cant_pallets'
                        },
                        // {
                        //     data: 'embalaje_std',
                        //     name: 'embalaje_std'
                        // },
                        {
                            data: 'num_orden',
                            name: 'num_orden'
                        },
                        {
                            data: 'tipo_especie',
                            name: 'tipo_especie'
                        },
                        {
                            data: 'numero_reserva_agente_naviero',
                            name: 'numero_reserva_agente_naviero'
                        },
                        {
                            data: 'actions',
                            name: '{{ trans('global.actions') }}'
                        },

                    ],

                    orderCellsTop: true,
                    order: [
                        [1, 'desc']
                    ],
                    pageLength: 100,
                };
                let table = $('.datatable-Embarque').DataTable(dtOverrideGlobals);
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                });

                let visibleColumnsIndexes = null;
                $('.datatable thead').on('input', '.search', function() {
                    let strict = $(this).attr('strict') || false
                    let value = strict && this.value ? "^" + this.value + "$" : this.value

                    let index = $(this).parent().index()
                    if (visibleColumnsIndexes !== null) {
                        index = visibleColumnsIndexes[index]
                    }

                    table
                        .column(index)
                        .search(value, strict)
                        .draw()
                });
                $("#cboTransportefilter").on("change", function() {
                    //let value = $(this).val();

                    let strict = $(this).attr('strict') || false
                    let value = strict && this.value ? "^" + this.value + "$" : this.value

                    let index = 1
                    if (visibleColumnsIndexes !== null) {
                        index = visibleColumnsIndexes[index]
                    }

                    table
                        .column(index)
                        .search(value, strict)
                        .draw()
                });
                table.on('column-visibility.dt', function(e, settings, column, state) {
                    visibleColumnsIndexes = []
                    table.columns(":visible").every(function(colIdx) {
                        visibleColumnsIndexes.push(colIdx);
                    });
                })
                table.on('dblclick', 'tbody td:not(:first-child)', function(e) {
                    var cell = table.cell(this);
                    var originalValue = cell.data();
                    console.log("Original Value:", cell.index());
                    // Crear un input para edición

                    if (cell.index().column === 24 || cell.index().column === 25 || cell.index().column ===28
                        || cell.index().column === 29 || cell.index().column === 30 ) {
                        $(this).html(
                            '<div class="form-group"><input type="date" class="form-control date" value="' +
                            originalValue + '"></div>');

                    }
                    if (cell.index().column === 26 ) {
                        $(this).html(
                            '<div class="form-group"><input type="number" class="form-control" value="' +
                            originalValue + '"></div>');
                    }
                    if (cell.index().column === 27) {
                        $(this).html(`<select class="form-control {{ $errors->has('temporada') ? 'is-invalid' : '' }}" name="temporada" id="temporada">
                                                        <option value></option>
                                                        @foreach (App\Models\Embarque::ESTADO_SELECT as $key => $label)
                                                            <option value="{{ $key }}" {{ old('estado', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                                        @endforeach
                                                    </select>`);
                    }
                    //
                    if (cell.index().column === 31) {
                        $(this).html(
                            '<div class="form-group"><textarea class="form-control" value="' +
                            originalValue + '"></textarea></div>');
                    }
                    // if (cell.index().column === 36) {
                    //     $(this).html(`<select class="form-control {{ $errors->has('temporada') ? 'is-invalid' : '' }}" name="${cell.index().column}" id="${cell.index().column}">
                    //                             <option value></option>
                    //                             @foreach (App\Models\Embarque::STATUS_AEREO_SELECT as $key => $label)
                    //                                 <option value="{{ $key }}" {{ old('estado', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    //                             @endforeach
                    //                         </select>`);
                    // }
                    if (cell.index().column === 33 || cell.index().column ===
                        38 || cell.index().column === 34) {
                        $(this).html(
                            '<div class="form-group"><input type="text" class="form-control date" value="' +
                            originalValue + '"></div>');
                    }
                    var input = $(this).find('input');
                    var select = $(this).find('select');
                    var textarea = $(this).find('textarea');
                    console.log($(this));
                    // Guardar cambios al salir del input
                    input.on('blur', function() {
                        var newValue = input.val();

                        // Actualiza la celda con el nuevo valor
                        cell.data(newValue).draw();

                        guardarEdicion(cell, input); // Actualizar la celda

                    });

                    // Restaurar el valor original si se presiona Esc
                    input.on('keydown', function(e) {
                        if (e.key === "Escape") {
                            cell.data(originalValue).draw();
                            guardarEdicion(cell, input); // Actualizar la celda
                        }
                    });

                    input.focus();
                    select.on('change', function() {
                        var newValue = select.val(); // Obtén el nuevo valor seleccionado
                        if (newValue) {
                            cell.data(newValue)
                                .draw(); // Actualiza la celda con el nuevo valor
                            guardarEdicion(cell, select); // Actualizar la celda
                        } else {
                            cell.data(originalValue)
                                .draw(); // Si no hay selección, restaura el valor original
                            guardarEdicion(cell, select); // Actualizar la celda
                        }
                    });


                    // Restaurar el valor original si se presiona Esc
                    select.on('keydown', function(e) {
                        if (e.key === "Escape") {
                            cell.data(originalValue).draw();
                            guardarEdicion(cell, select); // Actualizar la celda
                        }
                    });
                    select.focus();
                    textarea.on('blur', function() {
                        var newValue = textarea.val();
                        cell.data(newValue).draw();
                        guardarEdicion(cell, textarea); // Actualizar la celda
                    });

                    // Restaurar el valor original si se presiona Esc
                    textarea.on('keydown', function(e) {
                        if (e.key === "Escape") {
                            cell.data(originalValue).draw();
                            guardarEdicion(cell, textarea); // Actualizar la celda
                        }
                    });

                    textarea.focus();
                });
                // Agregar un evento de clic a la celda para permitir la edición
                function guardarEdicion(cell, control) {
                    var newValue = control.val();
                    var columnName = table.column(cell.index().column).header().textContent.trim();
                    var rowId = table.row(cell.index().row).data().id;


                    // Mostrar el resultado con el nombre de la columna y su valor

                    $.ajax({
                        url: '{{ route('admin.embarques.GuardarEmbarques') }}', // Cambia esto por la ruta de tu API
                        type: 'POST',
                        data: {
                            id: rowId,
                            column: columnName,
                            value: newValue,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            cell.data(newValue).draw();
                            console.log('Actualización exitosa:', response);
                        },
                        error: function(xhr) {
                            cell.data(originalValue).draw();
                            alert('Error al actualizar: ' + xhr.responseText);
                        }
                    });
                }

            });

        });
    </script>
@endsection
