@can('proceso_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.procesos.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.proceso.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.proceso.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-productorProcesos">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.productor') }}
                        </th>
                        <th>
                            {{ trans('cruds.productor.fields.rut') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.fecha_proceso') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.variedad') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.categoria') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.etiqueta') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.calibre') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.color') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.total_kilos') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.etd_week') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.eta_week') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.resultado_kilo') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.resultado_total') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.precio_comercial') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.total_comercial') }}
                        </th>
                        <th>
                            {{ trans('cruds.proceso.fields.costo_comercial') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($procesos as $key => $proceso)
                        <tr data-entry-id="{{ $proceso->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $proceso->id ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->productor->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->productor->rut ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->fecha_proceso ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->variedad ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->categoria ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->etiqueta ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->calibre ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->color ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->total_kilos ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->etd_week ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->eta_week ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->resultado_kilo ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->resultado_total ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->precio_comercial ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->total_comercial ?? '' }}
                            </td>
                            <td>
                                {{ $proceso->costo_comercial ?? '' }}
                            </td>
                            <td>
                                @can('proceso_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.procesos.show', $proceso->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('proceso_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.procesos.edit', $proceso->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('proceso_delete')
                                    <form action="{{ route('admin.procesos.destroy', $proceso->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('proceso_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.procesos.massDestroy') }}",
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
  let table = $('.datatable-productorProcesos:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection