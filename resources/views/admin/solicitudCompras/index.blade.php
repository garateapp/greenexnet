@extends('layouts.admin')
@section('content')
@can('solicitud_compra_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.solicitud-compras.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.solicitudCompra.title_singular') }}
            </a>
            <a class="btn btn-info" href="{{ route('admin.solicitud-compras.kanban') }}">
                {{ trans('cruds.solicitudCompra.kanban') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.solicitudCompra.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-SolicitudCompra">
            <thead>
                <tr>
                    <th width="10">
                    </th>
                    <th>
                        {{ trans('cruds.solicitudCompra.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.solicitudCompra.fields.titulo') }}
                    </th>
                    <th>
                        {{ trans('cruds.solicitudCompra.fields.solicitante') }}
                    </th>
                    <th>
                        {{ trans('cruds.solicitudCompra.fields.centro_costo') }}
                    </th>
                    <th>
                        {{ trans('cruds.solicitudCompra.fields.monto_estimado') }}
                    </th>
                    <th>
                        {{ trans('cruds.solicitudCompra.fields.moneda') }}
                    </th>
                    <th>
                        {{ trans('cruds.solicitudCompra.fields.cotizaciones_requeridas') }}
                    </th>
                    <th>
                        {{ trans('cruds.solicitudCompra.fields.estado') }}
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
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('solicitud_compra_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.solicitud-compras.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      })

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
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
    ajax: "{{ route('admin.solicitud-compras.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
      { data: 'id', name: 'id' },
      { data: 'titulo', name: 'titulo' },
      { data: 'solicitante_name', name: 'solicitante.name' },
      { data: 'centro_costo_nombre', name: 'centroCosto.n_centrocosto' },
      { data: 'monto_estimado', name: 'monto_estimado' },
      { data: 'moneda_nombre', name: 'moneda.nombre' },
      { data: 'cotizaciones_requeridas', name: 'cotizaciones_requeridas' },
      { data: 'estado_nombre', name: 'estado.nombre' },
      { data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-SolicitudCompra').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

  let visibleColumnsIndexes = null;
  $('.datatable thead').on('input', '.search', function () {
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
