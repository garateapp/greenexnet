@can('productor_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.productors.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.productor.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.productor.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-grupoProductors">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.productor.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.productor.fields.rut') }}
                        </th>
                        <th>
                            {{ trans('cruds.productor.fields.nombre') }}
                        </th>
                        <th>
                            {{ trans('cruds.productor.fields.grupo') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productors as $key => $productor)
                        <tr data-entry-id="{{ $productor->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $productor->id ?? '' }}
                            </td>
                            <td>
                                {{ $productor->rut ?? '' }}
                            </td>
                            <td>
                                {{ $productor->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $productor->grupo->nombre ?? '' }}
                            </td>
                            <td>
                                @can('productor_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.productors.show', $productor->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('productor_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.productors.edit', $productor->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('productor_delete')
                                    <form action="{{ route('admin.productors.destroy', $productor->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('productor_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.productors.massDestroy') }}",
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
  let table = $('.datatable-grupoProductors:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection