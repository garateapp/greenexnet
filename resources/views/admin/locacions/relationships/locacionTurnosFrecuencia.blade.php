@can('turnos_frecuencium_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.turnos-frecuencia.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.turnosFrecuencium.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.turnosFrecuencium.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-locacionTurnosFrecuencia">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.turnosFrecuencium.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.turnosFrecuencium.fields.frecuencia') }}
                        </th>
                        <th>
                            {{ trans('cruds.turnosFrecuencium.fields.locacion') }}
                        </th>
                        <th>
                            {{ trans('cruds.turnosFrecuencium.fields.nombre') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($turnosFrecuencia as $key => $turnosFrecuencium)
                        <tr data-entry-id="{{ $turnosFrecuencium->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $turnosFrecuencium->id ?? '' }}
                            </td>
                            <td>
                                {{ $turnosFrecuencium->frecuencia->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $turnosFrecuencium->locacion->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $turnosFrecuencium->nombre ?? '' }}
                            </td>
                            <td>
                                @can('turnos_frecuencium_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.turnos-frecuencia.show', $turnosFrecuencium->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('turnos_frecuencium_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.turnos-frecuencia.edit', $turnosFrecuencium->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('turnos_frecuencium_delete')
                                    <form action="{{ route('admin.turnos-frecuencia.destroy', $turnosFrecuencium->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('turnos_frecuencium_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.turnos-frecuencia.massDestroy') }}",
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
  let table = $('.datatable-locacionTurnosFrecuencia:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection