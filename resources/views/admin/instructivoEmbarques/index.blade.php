@extends('layouts.admin')
@section('content')
@can('instructivo_embarque_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.instructivo-embarques.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.instructivoEmbarque.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'InstructivoEmbarque', 'route' => 'admin.instructivo-embarques.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.instructivoEmbarque.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-InstructivoEmbarque">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.instructivo') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.fecha') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.embarcador') }}
                    </th>
                    <th>
                        {{ trans('cruds.embarcador.fields.nombre') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.agente_aduana') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.consignee') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.naviera') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.num_booking') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.nave') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.cut_off') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.stacking_ini') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.stacking_end') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.etd') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.eta') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.puerto_embarque') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.puerto_destino') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.puerto_descarga') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.punto_de_entrada') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.num_contenedor') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.ventilacion') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.tara_contenedor') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.quest') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.num_sello') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.temperatura') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.empresa_transportista') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.conductor') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.rut_conductor') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.ppu') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.telefono') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.planta_carga') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.direccion') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.fecha_carga') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.hora_carga') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.guia_despacho_dirigida') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.planilla_sag_dirigida') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.num_po') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.emision_de_bl') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.tipo_de_flete') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.clausula_de_venta') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.moneda') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.forma_de_pago') }}
                    </th>
                    <th>
                        {{ trans('cruds.instructivoEmbarque.fields.modalidad_de_venta') }}
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
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($embarcadors as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($agente_aduanas as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($base_recibidors as $key => $item)
                                <option value="{{ $item->codigo }}">{{ $item->codigo }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($navieras as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
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
                    <td>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($puerto_correos as $key => $item)
                                <option value="{{ $item->emails }}">{{ $item->emails }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($puerto_correos as $key => $item)
                                <option value="{{ $item->emails }}">{{ $item->emails }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($puertos as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
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
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($chofers as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
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
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($planta_cargas as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($emision_bls as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($tipofletes as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($clausula_venta as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($monedas as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($forma_pagos as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($mod_venta as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
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
@can('instructivo_embarque_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.instructivo-embarques.massDestroy') }}",
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
    ajax: "{{ route('admin.instructivo-embarques.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'instructivo', name: 'instructivo' },
{ data: 'fecha', name: 'fecha' },
{ data: 'embarcador_nombre', name: 'embarcador.nombre' },
{ data: 'embarcador.nombre', name: 'embarcador.nombre' },
{ data: 'agente_aduana_nombre', name: 'agente_aduana.nombre' },
{ data: 'consignee_codigo', name: 'consignee.codigo' },
{ data: 'naviera_nombre', name: 'naviera.nombre' },
{ data: 'num_booking', name: 'num_booking' },
{ data: 'nave', name: 'nave' },
{ data: 'cut_off', name: 'cut_off' },
{ data: 'stacking_ini', name: 'stacking_ini' },
{ data: 'stacking_end', name: 'stacking_end' },
{ data: 'etd', name: 'etd' },
{ data: 'eta', name: 'eta' },
{ data: 'puerto_embarque_emails', name: 'puerto_embarque.emails' },
{ data: 'puerto_destino_emails', name: 'puerto_destino.emails' },
{ data: 'puerto_descarga_nombre', name: 'puerto_descarga.nombre' },
{ data: 'punto_de_entrada', name: 'punto_de_entrada' },
{ data: 'num_contenedor', name: 'num_contenedor' },
{ data: 'ventilacion', name: 'ventilacion' },
{ data: 'tara_contenedor', name: 'tara_contenedor' },
{ data: 'quest', name: 'quest' },
{ data: 'num_sello', name: 'num_sello' },
{ data: 'temperatura', name: 'temperatura' },
{ data: 'empresa_transportista', name: 'empresa_transportista' },
{ data: 'conductor_nombre', name: 'conductor.nombre' },
{ data: 'rut_conductor', name: 'rut_conductor' },
{ data: 'ppu', name: 'ppu' },
{ data: 'telefono', name: 'telefono' },
{ data: 'planta_carga_nombre', name: 'planta_carga.nombre' },
{ data: 'direccion', name: 'direccion' },
{ data: 'fecha_carga', name: 'fecha_carga' },
{ data: 'hora_carga', name: 'hora_carga' },
{ data: 'guia_despacho_dirigida', name: 'guia_despacho_dirigida' },
{ data: 'planilla_sag_dirigida', name: 'planilla_sag_dirigida' },
{ data: 'num_po', name: 'num_po' },
{ data: 'emision_de_bl_nombre', name: 'emision_de_bl.nombre' },
{ data: 'tipo_de_flete_nombre', name: 'tipo_de_flete.nombre' },
{ data: 'clausula_de_venta_nombre', name: 'clausula_de_venta.nombre' },
{ data: 'moneda_nombre', name: 'moneda.nombre' },
{ data: 'forma_de_pago_nombre', name: 'forma_de_pago.nombre' },
{ data: 'modalidad_de_venta_nombre', name: 'modalidad_de_venta.nombre' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-InstructivoEmbarque').DataTable(dtOverrideGlobals);
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