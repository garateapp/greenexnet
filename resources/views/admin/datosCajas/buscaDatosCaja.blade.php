@extends('layouts.admin')
@section('content')
    @can('datos_caja_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.datos-cajas.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.datosCaja.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', [
                    'model' => 'DatosCaja',
                    'route' => 'admin.datos-cajas.parseCsvImport',
                ])
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.datosCaja.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-DatosCaja">
                <thead>
                    <tr>
                        <th width="10">

                        </th>

                        <th>
                            {{ trans('cruds.datosCaja.fields.proceso') }}
                        </th>
                        <th>
                            {{ trans('cruds.datosCaja.fields.fecha_produccion') }}
                        </th>
                        <th>
                            {{ trans('cruds.datosCaja.fields.turno') }}
                        </th>
                        <th>
                            {{ trans('cruds.datosCaja.fields.cod_linea') }}
                        </th>
                        <th>
                            {{ trans('cruds.datosCaja.fields.cat') }}
                        </th>
                        <th>
                            {{ trans('cruds.datosCaja.fields.variedad_real') }}
                        </th>
                        <th>
                            {{ trans('cruds.datosCaja.fields.variedad_timbrada') }}
                        </th>
                        <th>
                            {{ trans('cruds.datosCaja.fields.salida') }}
                        </th>
                        <th>
                            {{ trans('cruds.datosCaja.fields.marca') }}
                        </th>
                        <th>
                            {{ trans('cruds.datosCaja.fields.productor_real') }}
                        </th>
                        <th>
                            {{ trans('cruds.datosCaja.fields.especie') }}
                        </th>
                        <th>
                            {{ trans('cruds.datosCaja.fields.cod_caja') }}
                        </th>
                        <th>
                            {{ trans('cruds.datosCaja.fields.cod_confeccion') }}
                        </th>
                        <th>
                            {{ trans('cruds.datosCaja.fields.calibre_timbrado') }}
                        </th>
                        <th>
                            {{ trans('cruds.datosCaja.fields.peso_timbrado') }}
                        </th>


                        <th>
                            {{ trans('cruds.datosCaja.fields.codigo_qr') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
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
            @can('datos_caja_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.datos-cajas.massDestroy') }}",
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
                ajax: "{{ route('admin.datos-cajas.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },

                    {
                        data: 'Proceso',
                        name: 'Proceso'
                    },
                    {
                        data: 'FechaProduccion',
                        name: 'FechaProduccion'
                    },
                    {
                        data: 'Turno',
                        name: 'Turno'
                    },
                    {
                        data: 'CodLinea',
                        name: 'CodLinea'
                    },
                    {
                        data: 'CAT',
                        name: 'CAT'
                    },
                    {
                        data: 'VariedadReal',
                        name: 'VariedadReal'
                    },
                    {
                        data: 'VariedadTimbrada',
                        name: 'VariedadTimbrada'
                    },
                    {
                        data: 'Salida',
                        name: 'Salida'
                    },
                    {
                        data: 'Marca',
                        name: 'Marca'
                    },
                    {
                        data: 'ProductorReal',
                        name: 'ProductorReal'
                    },
                    {
                        data: 'Especie',
                        name: 'Especie'
                    },
                    {
                        data: 'CodCaja',
                        name: 'CodCaja'
                    },
                    {
                        data: 'CodConfeccion',
                        name: 'CodConfeccion'
                    },
                    {
                        data: 'CalibreTimbrado',
                        name: 'CalibreTimbrado'
                    },
                    {
                        data: 'PesoTimbrado',
                        name: 'PesoTimbrado'
                    },
                    {
                        data: 'NuevoLote',
                        name: 'NuevoLote'
                    },
                    {
                        data: 'codigo_qr',
                        name: 'codigo_qr'
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
            let table = $('.datatable-DatosCaja').DataTable(dtOverrideGlobals);
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
    </script>
@endsection
