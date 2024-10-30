@can('locacion_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.locacions.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.locacion.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.locacion.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-areaLocacions">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.locacion.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.locacion.fields.nombre') }}
                        </th>
                        <th>
                            {{ trans('cruds.locacion.fields.area') }}
                        </th>
                        <th>
                            {{ trans('cruds.locacion.fields.cantidad_personal') }}
                        </th>
                        <th>
                            {{ trans('cruds.locacion.fields.estado') }}
                        </th>
                        <th>
                            {{ trans('cruds.locacion.fields.locacion_padre') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locacions as $key => $locacion)
                        <tr data-entry-id="{{ $locacion->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $locacion->id ?? '' }}
                            </td>
                            <td>
                                {{ $locacion->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $locacion->area->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $locacion->cantidad_personal ?? '' }}
                            </td>
                            <td>
                                {{ $locacion->estado->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $locacion->locacion_padre->nombre ?? '' }}
                            </td>
                            <td>
                                @can('locacion_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.locacions.show', $locacion->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('locacion_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.locacions.edit', $locacion->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('locacion_delete')
                                    <form action="{{ route('admin.locacions.destroy', $locacion->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('locacion_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.locacions.massDestroy') }}",
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
  let table = $('.datatable-areaLocacions:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection