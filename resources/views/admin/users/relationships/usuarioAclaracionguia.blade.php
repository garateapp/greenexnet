@can('aclaracionguium_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.aclaracionguia.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.aclaracionguium.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.aclaracionguium.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-usuarioAclaracionguia">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.aclaracionguium.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.aclaracionguium.fields.numero_guia') }}
                        </th>
                        <th>
                            {{ trans('cruds.aclaracionguium.fields.fecha_aceptacion') }}
                        </th>
                        <th>
                            {{ trans('cruds.aclaracionguium.fields.fecha_aclaracion') }}
                        </th>
                        <th>
                            {{ trans('cruds.aclaracionguium.fields.usuario') }}
                        </th>
                        <th>
                            {{ trans('cruds.aclaracionguium.fields.motivo_aclaracion') }}
                        </th>
                        <th>
                            {{ trans('cruds.aclaracionguium.fields.mawb') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aclaracionguia as $key => $aclaracionguium)
                        <tr data-entry-id="{{ $aclaracionguium->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $aclaracionguium->id ?? '' }}
                            </td>
                            <td>
                                {{ $aclaracionguium->numero_guia->guia_courier ?? '' }}
                            </td>
                            <td>
                                {{ $aclaracionguium->fecha_aceptacion ?? '' }}
                            </td>
                            <td>
                                {{ $aclaracionguium->fecha_aclaracion ?? '' }}
                            </td>
                            <td>
                                {{ $aclaracionguium->usuario->name ?? '' }}
                            </td>
                            <td>
                                {{ $aclaracionguium->motivo_aclaracion ?? '' }}
                            </td>
                            <td>
                                {{ $aclaracionguium->mawb->mawb ?? '' }}
                            </td>
                            <td>
                                @can('aclaracionguium_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.aclaracionguia.show', $aclaracionguium->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('aclaracionguium_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.aclaracionguia.edit', $aclaracionguium->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('aclaracionguium_delete')
                                    <form action="{{ route('admin.aclaracionguia.destroy', $aclaracionguium->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('aclaracionguium_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.aclaracionguia.massDestroy') }}",
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
  let table = $('.datatable-usuarioAclaracionguia:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection