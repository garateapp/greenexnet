@can('grupo_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.grupos.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.grupo.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.grupo.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-conjuntoGrupos">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.grupo.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.grupo.fields.nombre') }}
                        </th>
                        <th>
                            {{ trans('cruds.grupo.fields.rut') }}
                        </th>
                        <th>
                            {{ trans('cruds.grupo.fields.conjunto') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grupos as $key => $grupo)
                        <tr data-entry-id="{{ $grupo->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $grupo->id ?? '' }}
                            </td>
                            <td>
                                {{ $grupo->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $grupo->rut ?? '' }}
                            </td>
                            <td>
                                {{ $grupo->conjunto->nombre ?? '' }}
                            </td>
                            <td>
                                @can('grupo_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.grupos.show', $grupo->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('grupo_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.grupos.edit', $grupo->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('grupo_delete')
                                    <form action="{{ route('admin.grupos.destroy', $grupo->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('grupo_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.grupos.massDestroy') }}",
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
  let table = $('.datatable-conjuntoGrupos:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection