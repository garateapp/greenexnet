@extends('layouts.admin')
@section('content')
    @can('personal_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.personals.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.personal.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                <form method="POST" action="{{ route('admin.personals.importFromAccessLogs') }}" class="d-inline-block" onsubmit="return confirm('Importar personas desde Control Access Logs?');">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        Importar Control de Acceso
                    </button>
                </form>
                @include('csvImport.modal', [
                    'model' => 'Personal',
                    'route' => 'admin.personals.parseCsvImport',
                ])
            </div>
        </div>
    @endcan
    @if(session('import_personals_message'))
        <div class="alert alert-info">
            {{ session('import_personals_message') }}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.personal.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Personal">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.personal.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.personal.fields.nombre') }}
                        </th>
                        <th>
                            {{ trans('cruds.personal.fields.codigo') }}
                        </th>
                        <th>
                            {{ trans('cruds.personal.fields.rut') }}
                        </th>
                        <th>
                            {{ trans('cruds.personal.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.personal.fields.telefono') }}
                        </th>
                        <th>
                            {{ trans('cruds.personal.fields.cargo') }}
                        </th>
                        <th>
                            {{ trans('cruds.personal.fields.estado') }}
                        </th>
                        <th>
                            {{ trans('cruds.personal.fields.entidad') }}
                        </th>
                        <th>
                            Foto
                        </th>
                        <th>
                            &nbsp;
                        </th>
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
            @can('personal_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.personals.massDestroy') }}",
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
                ajax: "{{ route('admin.personals.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },
                    {
                        data: 'codigo',
                        name: 'codigo'
                    },
                    {
                        data: 'rut',
                        name: 'rut'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'telefono',
                        name: 'telefono'
                    },
                    {
                        data: 'cargo_nombre',
                        name: 'cargo.nombre'
                    },
                    {
                        data: 'estado_nombre',
                        name: 'estado.nombre'
                    },
                    {
                        data: 'entidad_nombre',
                        name: 'entidad.nombre'
                    },
                    {
                        data: 'foto',
                        name: 'foto',
                        render: function(data, type, full, meta) {
                            // Verifica que haya una foto antes de intentar mostrarla
                            if (data) {
                                return `<img src="/storage/${data}" alt="Foto" width="50" height="50" />`;
                            } else {
                                return 'No disponible';
                            }
                        },
                        orderable: false,
                        searchable: false
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
                pageLength: 10000,
            };
            let table = $('.datatable-Personal').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
