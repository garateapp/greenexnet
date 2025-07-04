@can('anticipo_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.anticipos.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.anticipo.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.anticipo.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-productorAnticipos">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.anticipo.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.anticipo.fields.productor') }}
                        </th>
                        <th>
                            {{ trans('cruds.productor.fields.rut') }}
                        </th>
                        <th>
                            {{ trans('cruds.anticipo.fields.valor') }}
                        </th>
                        <th>
                            {{ trans('cruds.anticipo.fields.num_docto') }}
                        </th>
                        <th>
                            {{ trans('cruds.anticipo.fields.fecha_documento') }}
                        </th>
                        <th>
                            {{ trans('cruds.anticipo.fields.tipo_cambio') }}
                        </th>
                        <th>
                            {{ trans('cruds.valorDolar.fields.fecha_cambio') }}
                        </th>
                        <th>
                            {{ trans('cruds.anticipo.fields.especie') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($anticipos as $key => $anticipo)
                        <tr data-entry-id="{{ $anticipo->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $anticipo->id ?? '' }}
                            </td>
                            <td>
                                {{ $anticipo->productor->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $anticipo->productor->rut ?? '' }}
                            </td>
                            <td>
                                {{ $anticipo->valor ?? '' }}
                            </td>
                            <td>
                                {{ $anticipo->num_docto ?? '' }}
                            </td>
                            <td>
                                {{ $anticipo->fecha_documento ?? '' }}
                            </td>
                            <td>
                                {{ $anticipo->tipo_cambio->valor ?? '' }}
                            </td>
                            <td>
                                {{ $anticipo->tipo_cambio->fecha_cambio ?? '' }}
                            </td>
                            <td>
                                {{ $anticipo->especie->nombre ?? '' }}
                            </td>
                            <td>
                                @can('anticipo_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.anticipos.show', $anticipo->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('anticipo_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.anticipos.edit', $anticipo->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('anticipo_delete')
                                    <form action="{{ route('admin.anticipos.destroy', $anticipo->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('anticipo_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.anticipos.massDestroy') }}",
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
  let table = $('.datatable-productorAnticipos:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection