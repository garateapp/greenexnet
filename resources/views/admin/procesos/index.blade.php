@extends('layouts.admin')
@section('content')
@can('proceso_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.procesos.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.proceso.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Proceso', 'route' => 'admin.procesos.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.proceso.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Proceso">
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
                        Norma
                        </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($productors as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('proceso_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.procesos.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
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

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.procesos.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'productor_nombre', name: 'productor.nombre' },
{ data: 'productor.rut', name: 'productor.rut' },
{ data: 'fecha_proceso', name: 'fecha_proceso' },
{ data: 'variedad', name: 'variedad' },
{ data: 'categoria', name: 'categoria' },
{ data: 'etiqueta', name: 'etiqueta' },
{ data: 'calibre', name: 'calibre' },
{ data: 'color', name: 'color' },
{ data: 'total_kilos', name: 'total_kilos' },
{ data: 'etd_week', name: 'etd_week' },
{ data: 'eta_week', name: 'eta_week' },
{ data: 'resultado_kilo', name: 'resultado_kilo' },
{ data: 'resultado_total', name: 'resultado_total' },
{ data: 'precio_comercial', name: 'precio_comercial' },
{ data: 'total_comercial', name: 'total_comercial' },
{ data: 'costo_comercial', name: 'costo_comercial' },
{ data: 'norma', name: 'norma' },
{ data: 'actions', orderable: false, searchable: false },
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Proceso').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
});

</script>
@endsection