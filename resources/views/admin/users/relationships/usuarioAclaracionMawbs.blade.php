@can('aclaracion_mawb_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.aclaracion-mawbs.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.aclaracionMawb.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.aclaracionMawb.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-usuarioAclaracionMawbs">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.aclaracionMawb.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.aclaracionMawb.fields.mawb') }}
                        </th>
                        <th>
                            {{ trans('cruds.aclaracionMawb.fields.fecha_aceptacion') }}
                        </th>
                        <th>
                            {{ trans('cruds.aclaracionMawb.fields.fecha_aclaracion') }}
                        </th>
                        <th>
                            {{ trans('cruds.aclaracionMawb.fields.usuario') }}
                        </th>
                        <th>
                            {{ trans('cruds.aclaracionMawb.fields.motivo_aclaracion') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aclaracionMawbs as $key => $aclaracionMawb)
                        <tr data-entry-id="{{ $aclaracionMawb->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $aclaracionMawb->id ?? '' }}
                            </td>
                            <td>
                                {{ $aclaracionMawb->mawb->mawb ?? '' }}
                            </td>
                            <td>
                                {{ $aclaracionMawb->fecha_aceptacion ?? '' }}
                            </td>
                            <td>
                                {{ $aclaracionMawb->fecha_aclaracion ?? '' }}
                            </td>
                            <td>
                                {{ $aclaracionMawb->usuario->name ?? '' }}
                            </td>
                            <td>
                                {{ $aclaracionMawb->motivo_aclaracion ?? '' }}
                            </td>
                            <td>
                                @can('aclaracion_mawb_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.aclaracion-mawbs.show', $aclaracionMawb->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('aclaracion_mawb_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.aclaracion-mawbs.edit', $aclaracionMawb->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('aclaracion_mawb_delete')
                                    <form action="{{ route('admin.aclaracion-mawbs.destroy', $aclaracionMawb->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('aclaracion_mawb_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.aclaracion-mawbs.massDestroy') }}",
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
  let table = $('.datatable-usuarioAclaracionMawbs:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection