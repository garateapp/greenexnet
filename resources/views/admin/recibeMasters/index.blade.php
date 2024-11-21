@extends('layouts.admin')
@section('content')
    @can('recibe_master_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.recibe-masters.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.recibeMaster.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                <a class="btn btn-success" id="obtenerLotes" href="#">
                    Obtener Lotes
                </a>
                <a class="btn btn-success" id="CapturarLote" href="#">
                    Capturar Lote
                </a>
                <div class="alert alert-success" role="alert" id="alert-success" style="display:none">

                </div>
                <div class="alert alert-danger" role="alert" id="alert-error" style="display:none">

                </div>
                @include('csvImport.modal', [
                    'model' => 'RecibeMaster',
                    'route' => 'admin.recibe-masters.parseCsvImport',
                ])
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.recibeMaster.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-RecibeMaster">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.especie') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.exportador') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.partida') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.estado') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.cod_central') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.cod_productor') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.nro_guia_despacho') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.fecha_recepcion') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.fecha_cosecha') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.cod_variedad') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.estiba_camion') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.esponjas_cloradas') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.nro_bandeja') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.hora_llegada') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.kilo_muestra') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.kilo_neto') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.temp_ingreso') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.temp_salida') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.lote') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.huerto') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.hidro') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.fecha_envio') }}
                        </th>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.respuesta_envio') }}
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
                                @foreach (App\Models\RecibeMaster::ESTADO_SELECT as $key => $item)
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
                        </td>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach (App\Models\RecibeMaster::ESTIBA_CAMION_SELECT as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach (App\Models\RecibeMaster::ESPONJAS_CLORADAS_SELECT as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
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
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('recibe_master_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.recibe-masters.massDestroy') }}",
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

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.recibe-masters.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'especie',
                        name: 'especie'
                    },
                    {
                        data: 'exportador',
                        name: 'exportador'
                    },
                    {
                        data: 'partida',
                        name: 'partida'
                    },
                    {
                        data: 'estado',
                        name: 'estado'
                    },
                    {
                        data: 'cod_central',
                        name: 'cod_central'
                    },
                    {
                        data: 'cod_productor',
                        name: 'cod_productor'
                    },
                    {
                        data: 'nro_guia_despacho',
                        name: 'nro_guia_despacho'
                    },
                    {
                        data: 'fecha_recepcion',
                        name: 'fecha_recepcion'
                    },
                    {
                        data: 'fecha_cosecha',
                        name: 'fecha_cosecha'
                    },
                    {
                        data: 'cod_variedad',
                        name: 'cod_variedad'
                    },
                    {
                        data: 'estiba_camion',
                        name: 'estiba_camion'
                    },
                    {
                        data: 'esponjas_cloradas',
                        name: 'esponjas_cloradas'
                    },
                    {
                        data: 'nro_bandeja',
                        name: 'nro_bandeja'
                    },
                    {
                        data: 'hora_llegada',
                        name: 'hora_llegada'
                    },
                    {
                        data: 'kilo_muestra',
                        name: 'kilo_muestra'
                    },
                    {
                        data: 'kilo_neto',
                        name: 'kilo_neto'
                    },
                    {
                        data: 'temp_ingreso',
                        name: 'temp_ingreso'
                    },
                    {
                        data: 'temp_salida',
                        name: 'temp_salida'
                    },
                    {
                        data: 'lote',
                        name: 'lote'
                    },
                    {
                        data: 'huerto',
                        name: 'huerto'
                    },
                    {
                        data: 'hidro',
                        name: 'hidro'
                    },
                    {
                        data: 'fecha_envio',
                        name: 'fecha_envio'
                    },
                    {
                        data: 'respuesta_envio',
                        name: 'respuesta_envio'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            };
            let table = $('.datatable-RecibeMaster').DataTable(dtOverrideGlobals);
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
            table.on('column-visibility.dt', function(e, settings, column, state) {
                visibleColumnsIndexes = []
                table.columns(":visible").every(function(colIdx) {
                    visibleColumnsIndexes.push(colIdx);
                });
            })
        });

        //Obtener Lotes
        $("#obtenerLotes").click(function() {
            $.ajax({
                url: "{{ route('admin.recibe-masters.ObtieneLotes') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.status == "OK") {
                        $("#alert-success").show();
                        $("#alert-success").html(data.message);
                        $("#alert-danger").hide();

                    } else {
                        $("#alert-error").show();
                        $("#alert-error").html(data.message);
                        $("#alert-success").hide();
                    }
                }
            });
        })
        $("#CapturarLote").click(function() {
            $.ajax({
                url: "{{ route('admin.recibe-masters.CapturarLote') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.status == "OK") {
                        $("#alert-success").show();
                        $("#alert-success").html(data.message);
                        $("#alert-danger").hide();

                    } else {
                        $("#alert-error").show();
                        $("#alert-error").html(data.message);
                        $("#alert-success").hide();
                    }
                }
            });
        })
    </script>
@endsection
