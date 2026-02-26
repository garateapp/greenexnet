@can('material_producto_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.material-productos.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.materialProducto.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.materialProducto.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-embalajeMaterialProductos">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.materialProducto.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.materialProducto.fields.embalaje') }}
                        </th>
                        <th>
                            {{ trans('cruds.materialProducto.fields.material') }}
                        </th>
                        <th>
                            {{ trans('cruds.material.fields.codigo') }}
                        </th>
                        <th>
                            {{ trans('cruds.materialProducto.fields.unidadxcaja') }}
                        </th>
                        <th>
                            {{ trans('cruds.materialProducto.fields.unidadxpallet') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materialProductos as $key => $materialProducto)
                        <tr data-entry-id="{{ $materialProducto->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $materialProducto->id ?? '' }}
                            </td>
                            <td>
                                {{ $materialProducto->embalaje->c_embalaje ?? '' }}
                            </td>
                            <td>
                                {{ $materialProducto->material->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $materialProducto->material->codigo ?? '' }}
                            </td>
                            <td>
                                {{ $materialProducto->unidadxcaja ?? '' }}
                            </td>
                            <td>
                                {{ $materialProducto->unidadxpallet ?? '' }}
                            </td>
                            <td>
                                @can('material_producto_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.material-productos.show', $materialProducto->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('material_producto_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.material-productos.edit', $materialProducto->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('material_producto_delete')
                                    <form action="{{ route('admin.material-productos.destroy', $materialProducto->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('material_producto_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.material-productos.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

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

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-embalajeMaterialProductos:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection
