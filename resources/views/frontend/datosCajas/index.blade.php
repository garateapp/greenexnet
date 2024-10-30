@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('datos_caja_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.datos-cajas.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.datosCaja.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'DatosCaja', 'route' => 'admin.datos-cajas.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.datosCaja.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-DatosCaja">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.proceso') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.fecha_produccion') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.turno') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.cod_linea') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.cat') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.variedad_real') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.variedad_timbrada') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.salida') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.marca') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.productor_real') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.especie') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.cod_caja') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.cod_confeccion') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.calibre_timbrado') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.peso_timbrado') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.lote') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.nuevo_lote') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.codigo_qr') }}
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
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($datosCajas as $key => $datosCaja)
                                    <tr data-entry-id="{{ $datosCaja->id }}">
                                        <td>
                                            {{ $datosCaja->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->proceso ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->fecha_produccion ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->turno ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->cod_linea ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->cat ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->variedad_real ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->variedad_timbrada ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->salida ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->marca ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->productor_real ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->especie ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->cod_caja ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->cod_confeccion ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->calibre_timbrado ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->peso_timbrado ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->lote ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->nuevo_lote ?? '' }}
                                        </td>
                                        <td>
                                            {{ $datosCaja->codigo_qr ?? '' }}
                                        </td>
                                        <td>
                                            @can('datos_caja_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.datos-cajas.show', $datosCaja->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('datos_caja_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.datos-cajas.edit', $datosCaja->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('datos_caja_delete')
                                                <form action="{{ route('frontend.datos-cajas.destroy', $datosCaja->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('datos_caja_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.datos-cajas.massDestroy') }}",
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
  let table = $('.datatable-DatosCaja:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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
})

</script>
@endsection