@can('correoalso_air_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.correoalso-airs.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.correoalsoAir.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.correoalsoAir.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-clienteCorreoalsoAirs">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.correoalsoAir.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.correoalsoAir.fields.cliente') }}
                        </th>
                        <th>
                            {{ trans('cruds.correoalsoAir.fields.puerto_requerido') }}
                        </th>
                        <th>
                            {{ trans('cruds.correoalsoAir.fields.correos') }}
                        </th>
                        <th>
                            {{ trans('cruds.correoalsoAir.fields.also_notify') }}
                        </th>
                        <th>
                            {{ trans('cruds.correoalsoAir.fields.transporte') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($correoalsoAirs as $key => $correoalsoAir)
                        <tr data-entry-id="{{ $correoalsoAir->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $correoalsoAir->id ?? '' }}
                            </td>
                            <td>
                                {{ $correoalsoAir->cliente->codigo ?? '' }}
                            </td>
                            <td>
                                {{ $correoalsoAir->puerto_requerido ?? '' }}
                            </td>
                            <td>
                                {{ $correoalsoAir->correos ?? '' }}
                            </td>
                            <td>
                                {{ $correoalsoAir->also_notify ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\CorreoalsoAir::TRANSPORTE_SELECT[$correoalsoAir->transporte] ?? '' }}
                            </td>
                            <td>
                                @can('correoalso_air_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.correoalso-airs.show', $correoalsoAir->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('correoalso_air_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.correoalso-airs.edit', $correoalsoAir->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('correoalso_air_delete')
                                    <form action="{{ route('admin.correoalso-airs.destroy', $correoalsoAir->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('correoalso_air_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.correoalso-airs.massDestroy') }}",
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
  let table = $('.datatable-clienteCorreoalsoAirs:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection