@can('valor_flete_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.valor-fletes.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.valorFlete.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.valorFlete.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-productorValorFletes">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.valorFlete.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.valorFlete.fields.condicion') }}
                        </th>
                        <th>
                            {{ trans('cruds.valorFlete.fields.productor') }}
                        </th>
                        <th>
                            {{ trans('cruds.valorFlete.fields.valor') }}
                        </th>
                        <th>
                            {{ trans('cruds.valorFlete.fields.valor_dolar') }}
                        </th>
                        <th>
                            {{ trans('cruds.valorDolar.fields.fecha_cambio') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($valorFletes as $key => $valorFlete)
                        <tr data-entry-id="{{ $valorFlete->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $valorFlete->id ?? '' }}
                            </td>
                            <td>
                                {{ $valorFlete->condicion ?? '' }}
                            </td>
                            <td>
                                {{ $valorFlete->productor->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $valorFlete->valor ?? '' }}
                            </td>
                            <td>
                                {{ $valorFlete->valor_dolar->valor ?? '' }}
                            </td>
                            <td>
                                {{ $valorFlete->valor_dolar->fecha_cambio ?? '' }}
                            </td>
                            <td>
                                @can('valor_flete_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.valor-fletes.show', $valorFlete->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('valor_flete_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.valor-fletes.edit', $valorFlete->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('valor_flete_delete')
                                    <form action="{{ route('admin.valor-fletes.destroy', $valorFlete->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('valor_flete_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.valor-fletes.massDestroy') }}",
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
  let table = $('.datatable-productorValorFletes:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection