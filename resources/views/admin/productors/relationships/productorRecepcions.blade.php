@can('recepcion_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.recepcions.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.recepcion.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.recepcion.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-productorRecepcions">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.recepcion.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.recepcion.fields.productor') }}
                        </th>
                        <th>
                            {{ trans('cruds.productor.fields.rut') }}
                        </th>
                        <th>
                            {{ trans('cruds.recepcion.fields.variedad') }}
                        </th>
                        <th>
                            {{ trans('cruds.recepcion.fields.total_kilos') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recepcions as $key => $recepcion)
                        <tr data-entry-id="{{ $recepcion->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $recepcion->id ?? '' }}
                            </td>
                            <td>
                                {{ $recepcion->productor->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $recepcion->productor->rut ?? '' }}
                            </td>
                            <td>
                                {{ $recepcion->variedad ?? '' }}
                            </td>
                            <td>
                                {{ $recepcion->total_kilos ?? '' }}
                            </td>
                            <td>
                                @can('recepcion_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.recepcions.show', $recepcion->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('recepcion_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.recepcions.edit', $recepcion->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('recepcion_delete')
                                    <form action="{{ route('admin.recepcions.destroy', $recepcion->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('recepcion_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.recepcions.massDestroy') }}",
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
  let table = $('.datatable-productorRecepcions:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection