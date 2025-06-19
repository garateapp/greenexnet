@can('instructivo_embarque_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.instructivo-embarques.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.instructivoEmbarque.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.instructivoEmbarque.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-navieraInstructivoEmbarques">
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
                </thead>
                <tbody>
                    @foreach($instructivoEmbarques as $key => $instructivoEmbarque)
                        <tr data-entry-id="{{ $instructivoEmbarque->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $instructivoEmbarque->id ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->instructivo ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->fecha ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->embarcador->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->embarcador->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->agente_aduana->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->consignee->codigo ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->naviera->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->num_booking ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->nave ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->cut_off ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->stacking_ini ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->stacking_end ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->etd ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->eta ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->puerto_embarque->emails ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->puerto_destino->emails ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->puerto_descarga->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->punto_de_entrada ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->num_contenedor ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->ventilacion ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->tara_contenedor ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->quest ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->num_sello ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->temperatura ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->empresa_transportista ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->conductor->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->rut_conductor ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->ppu ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->telefono ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->planta_carga->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->direccion ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->fecha_carga ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->hora_carga ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->guia_despacho_dirigida ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->planilla_sag_dirigida ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->num_po ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->emision_de_bl->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->tipo_de_flete->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->clausula_de_venta->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->moneda->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->forma_de_pago->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $instructivoEmbarque->modalidad_de_venta->nombre ?? '' }}
                            </td>
                            <td>
                                @can('instructivo_embarque_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.instructivo-embarques.show', $instructivoEmbarque->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('instructivo_embarque_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.instructivo-embarques.edit', $instructivoEmbarque->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('instructivo_embarque_delete')
                                    <form action="{{ route('admin.instructivo-embarques.destroy', $instructivoEmbarque->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('instructivo_embarque_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.instructivo-embarques.massDestroy') }}",
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
  let table = $('.datatable-navieraInstructivoEmbarques:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection