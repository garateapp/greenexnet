@can('etiquetas_x_especy_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.etiquetas-x-especies.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.etiquetasXEspecy.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.etiquetasXEspecy.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-etiquetaEtiquetasXEspecies">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.etiquetasXEspecy.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.etiquetasXEspecy.fields.especie') }}
                        </th>
                        <th>
                            {{ trans('cruds.especy.fields.nombre') }}
                        </th>
                        <th>
                            {{ trans('cruds.etiquetasXEspecy.fields.etiqueta') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($etiquetasXEspecies as $key => $etiquetasXEspecy)
                        <tr data-entry-id="{{ $etiquetasXEspecy->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $etiquetasXEspecy->id ?? '' }}
                            </td>
                            <td>
                                {{ $etiquetasXEspecy->especie->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $etiquetasXEspecy->especie->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $etiquetasXEspecy->etiqueta->nombre ?? '' }}
                            </td>
                            <td>
                                @can('etiquetas_x_especy_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.etiquetas-x-especies.show', $etiquetasXEspecy->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('etiquetas_x_especy_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.etiquetas-x-especies.edit', $etiquetasXEspecy->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('etiquetas_x_especy_delete')
                                    <form action="{{ route('admin.etiquetas-x-especies.destroy', $etiquetasXEspecy->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('etiquetas_x_especy_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.etiquetas-x-especies.massDestroy') }}",
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
  let table = $('.datatable-etiquetaEtiquetasXEspecies:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection