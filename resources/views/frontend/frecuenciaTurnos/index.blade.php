@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('frecuencia_turno_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.frecuencia-turnos.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.frecuenciaTurno.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'FrecuenciaTurno', 'route' => 'admin.frecuencia-turnos.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.frecuenciaTurno.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-FrecuenciaTurno">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.frecuenciaTurno.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.frecuenciaTurno.fields.dia') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.frecuenciaTurno.fields.turno') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.frecuenciaTurno.fields.nombre') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($frecuenciaTurnos as $key => $frecuenciaTurno)
                                    <tr data-entry-id="{{ $frecuenciaTurno->id }}">
                                        <td>
                                            {{ $frecuenciaTurno->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\FrecuenciaTurno::DIA_SELECT[$frecuenciaTurno->dia] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $frecuenciaTurno->turno->nombre ?? '' }}
                                        </td>
                                        <td>
                                            {{ $frecuenciaTurno->nombre ?? '' }}
                                        </td>
                                        <td>
                                            @can('frecuencia_turno_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.frecuencia-turnos.show', $frecuenciaTurno->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('frecuencia_turno_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.frecuencia-turnos.edit', $frecuenciaTurno->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('frecuencia_turno_delete')
                                                <form action="{{ route('frontend.frecuencia-turnos.destroy', $frecuenciaTurno->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('frecuencia_turno_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.frecuencia-turnos.massDestroy') }}",
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
  let table = $('.datatable-FrecuenciaTurno:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection