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
    @can('liq_cx_cabecera_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <form id="frmComparativa" action="{{ route('admin.comex.generacomparativa') }}" method="POST">
                    <a class="btn btn-success" href="{{ route('admin.liq-cx-cabeceras.create') }}">
                        {{ trans('global.add') }} Liquidación
                    </a>
                    <a class="btn btn-success" href="{{ route('admin.comex.capturador') }}">
                        Capturar Liquidación
                    </a>

                    @csrf
                    <input type="hidden" name="ids" id="comparativaIds">
                </form>
                <form id="frmComparativaGlobal" action="{{ route('admin.comex.generacomparativaglobal') }}" method="POST">

                    @csrf

                </form>
                <button id="btnACtualizaFOB" class="btn btn-primary">Actualizar FOB G. Despacho</button>
            </div>
        </div>
    @endcan
    <div id="mensaje" style="display: none">
        @if (isset($message) && isset($status))
            <div class="alert alert-{{ $status }}" role="alert">
                {{ $message }}
            </div>
        @endif
    </div>
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.liqCxCabecera.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-LiqCxCabecera">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.id') }}
                        </th>
                        <th>
                            Especie
                        </th>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.instructivo') }}
                        </th>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.cliente') }}
                        </th>
                        <th>
                            {{ trans('cruds.clientesComex.fields.codigo_cliente') }}
                        </th>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.nave') }}
                        </th>
                        <th>
                            {{ trans('cruds.nafe.fields.codigo') }}
                        </th>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.eta') }}
                        </th>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.tasa_intercambio') }}
                        </th>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.total_costo') }}
                        </th>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.total_bruto') }}
                        </th>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.total_neto') }}
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
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach ($especies as $key => $item)
                                    <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach ($clientes_comexes as $key => $item)
                                    <option value="{{ $item->nombre_fantasia }}">{{ $item->nombre_fantasia }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach ($naves as $key => $item)
                                    <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                                @endforeach
                            </select>
                        </td>
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
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $("document").ready(function() {
            $("#btnACtualizaFOB").on('click', function() {
                event.preventDefault(); // Evita recarga si el botón está dentro de un formulario

                let btn = $(this); // Guardamos referencia al botón
                btn.prop("disabled", true); // Deshabilitamos el botón
                $.ajax({
                    url: "{{ route('admin.liq-cx-cabeceras.actualizarValorCliente') }}",
                    method: 'GET',
                    success: function(response) {
                        $("#msgOK").html("Datos actualizados correctamente!!").show();
                    },
                    error: function() {
                        $("#msgKO").html("Error al actualizar los datos!!").show();
                    },
                    complete: function() {
                        btn.prop("disabled",
                            false); // Volvemos a habilitar el botón al finalizar la petición
                    }
                });

            });
        });
    </script>
@endsection
@section('scripts')
    @parent
   
<script>
        
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('liq_cx_cabecera_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.liq-cx-cabeceras.massDestroy') }}",
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
            let comparativaButtonTrans = 'Genera Comparativa';
            let comparativaButton = {
                text: comparativaButtonTrans,
                url: "{{ route('admin.comex.generacomparativa') }}",
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
                        $('#comparativaIds').val(JSON.stringify(ids));
                        $("#frmComparativa").submit();
                    }

                }
            }
            let comparativaGlobalButtonTrans = 'Comparativa Global';
            let comparativaGlobalButton = {
                text: comparativaGlobalButtonTrans,
                url: "{{ route('admin.comex.generacomparativaglobal') }}",
                className: 'btn-primary',
                action: function(e, dt, node, config) {


                    if (confirm('{{ trans('global.areYouSure') }}')) {

                        $("#frmComparativaGlobal").submit();
                    }

                }
            }


            dtButtons.push(comparativaButton)
            dtButtons.push(comparativaGlobalButton)
            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.liq-cx-cabeceras.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'especie_nombre',
                        name: 'especie.nombre'
                    },
                    {
                        data: 'instructivo',
                        name: 'instructivo'
                    },
                    {
                        data: 'cliente_nombre_fantasia',
                        name: 'cliente.nombre_fantasia'
                    },
                    {
                        data: 'cliente.codigo_cliente',
                        name: 'cliente.codigo_cliente'
                    },
                    {
                        data: 'nave_nombre',
                        name: 'nave.nombre'
                    },
                    {
                        data: 'nave.codigo',
                        name: 'nave.codigo'
                    },
                    {
                        data: 'eta',
                        name: 'eta'
                    },
                    {
                        data: 'tasa_intercambio',
                        name: 'tasa_intercambio'
                    },
                    {
                        data: 'total_costo',
                        name: 'total_costo'
                    },
                    {
                        data: 'total_bruto',
                        name: 'total_bruto'
                    },
                    {
                        data: 'total_neto',
                        name: 'total_neto'
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
                rowCallback: function(row, data) {
                    // Obtener el valor del campo 'instructivo' de la fila actual
                    let instructivo = data.instructivo;

                    // Hacer la llamada AJAX a la función 'sinproceso'
                    $.ajax({
                        url: "{{ route('admin.liq-cx-cabeceras.sinproceso') }}", // Asegúrate de definir esta ruta en tu archivo de rutas
                        method: 'POST',
                        data: {
                            instructivo: instructivo,
                            _token: '{{ csrf_token() }}' // Token CSRF para Laravel
                        },
                        success: function(response) {
                            // Si hay despachos sin proceso (success: false), pintar la fila
                            if (!response.success) {
                                $(row).css('background-color', '#ffcccc'); // Ejemplo: fondo rojo claro
                            }
                        },
                        error: function(xhr) {
                            console.log('Error en la consulta AJAX:', xhr);
                        }
                    });
                }
            };
            let table = $('.datatable-LiqCxCabecera').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on(
                'shown.bs.tab click',
                function(e) {
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
