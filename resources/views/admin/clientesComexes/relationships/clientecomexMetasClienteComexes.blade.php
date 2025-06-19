@can('metas_cliente_comex_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.metas-cliente-comexes.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.metasClienteComex.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.metasClienteComex.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-clientecomexMetasClienteComexes">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.metasClienteComex.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.metasClienteComex.fields.clientecomex') }}
                        </th>
                        <th>
                            {{ trans('cruds.clientesComex.fields.nombre_fantasia') }}
                        </th>
                        <th>
                            {{ trans('cruds.metasClienteComex.fields.cantidad') }}
                        </th>
                        <th>
                            {{ trans('cruds.metasClienteComex.fields.observaciones') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($metasClienteComexes as $key => $metasClienteComex)
                        <tr data-entry-id="{{ $metasClienteComex->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $metasClienteComex->id ?? '' }}
                            </td>
                            <td>
                                {{ $metasClienteComex->clientecomex->nombre_fantasia ?? '' }}
                            </td>
                            <td>
                                {{ $metasClienteComex->clientecomex->nombre_fantasia ?? '' }}
                            </td>
                            <td>
                                {{ $metasClienteComex->cantidad ?? '' }}
                            </td>
                            <td>
                                {{ $metasClienteComex->observaciones ?? '' }}
                            </td>
                            <td>
                                @can('metas_cliente_comex_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.metas-cliente-comexes.show', $metasClienteComex->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('metas_cliente_comex_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.metas-cliente-comexes.edit', $metasClienteComex->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('metas_cliente_comex_delete')
                                    <form action="{{ route('admin.metas-cliente-comexes.destroy', $metasClienteComex->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('metas_cliente_comex_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.metas-cliente-comexes.massDestroy') }}",
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
  let table = $('.datatable-clientecomexMetasClienteComexes:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection