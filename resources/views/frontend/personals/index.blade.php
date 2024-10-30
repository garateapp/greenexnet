@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('personal_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.personals.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.personal.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'Personal', 'route' => 'admin.personals.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.personal.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Personal">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.personal.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.personal.fields.nombre') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.personal.fields.codigo') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.personal.fields.rut') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.personal.fields.email') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.personal.fields.telefono') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.personal.fields.cargo') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.personal.fields.estado') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.personal.fields.entidad') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($personals as $key => $personal)
                                    <tr data-entry-id="{{ $personal->id }}">
                                        <td>
                                            {{ $personal->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $personal->nombre ?? '' }}
                                        </td>
                                        <td>
                                            {{ $personal->codigo ?? '' }}
                                        </td>
                                        <td>
                                            {{ $personal->rut ?? '' }}
                                        </td>
                                        <td>
                                            {{ $personal->email ?? '' }}
                                        </td>
                                        <td>
                                            {{ $personal->telefono ?? '' }}
                                        </td>
                                        <td>
                                            {{ $personal->cargo->nombre ?? '' }}
                                        </td>
                                        <td>
                                            {{ $personal->estado->nombre ?? '' }}
                                        </td>
                                        <td>
                                            {{ $personal->entidad->nombre ?? '' }}
                                        </td>
                                        <td>
                                            @can('personal_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.personals.show', $personal->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('personal_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.personals.edit', $personal->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('personal_delete')
                                                <form action="{{ route('frontend.personals.destroy', $personal->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('personal_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.personals.massDestroy') }}",
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
  let table = $('.datatable-Personal:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection