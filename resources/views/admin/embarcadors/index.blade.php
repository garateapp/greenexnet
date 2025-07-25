@extends('layouts.admin')
@section('content')
@can('embarcador_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.embarcadors.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.embarcador.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Embarcador', 'route' => 'admin.embarcadors.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.embarcador.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Embarcador">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.embarcador.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.embarcador.fields.codigo') }}
                    </th>
                    <th>
                        {{ trans('cruds.embarcador.fields.via') }}
                    </th>
                    <th>
                        {{ trans('cruds.embarcador.fields.nombre') }}
                    </th>
                    <th>
                        {{ trans('cruds.embarcador.fields.rut') }}
                    </th>
                    <th>
                        {{ trans('cruds.embarcador.fields.attn') }}
                    </th>
                    <th>
                        {{ trans('cruds.embarcador.fields.email') }}
                    </th>
                    <th>
                        {{ trans('cruds.embarcador.fields.telefono') }}
                    </th>
                    <th>
                        {{ trans('cruds.embarcador.fields.cc') }}
                    </th>
                    <th>
                        {{ trans('cruds.embarcador.fields.p_sag_dir') }}
                    </th>
                    <th>
                        {{ trans('cruds.embarcador.fields.g_dir_a') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
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
@can('embarcador_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.embarcadors.massDestroy') }}",
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
    ajax: "{{ route('admin.embarcadors.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'codigo', name: 'codigo' },
{ data: 'via', name: 'via' },
{ data: 'nombre', name: 'nombre' },
{ data: 'rut', name: 'rut' },
{ data: 'attn', name: 'attn' },
{ data: 'email', name: 'email' },
{ data: 'telefono', name: 'telefono' },
{ data: 'cc', name: 'cc' },
{ data: 'p_sag_dir', name: 'p_sag_dir' },
{ data: 'g_dir_a', name: 'g_dir_a' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Embarcador').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection