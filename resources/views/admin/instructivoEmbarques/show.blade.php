@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.instructivoEmbarque.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.instructivo-embarques.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.id') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.instructivo') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->instructivo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.fecha') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->fecha }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.embarcador') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->embarcador->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.agente_aduana') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->agente_aduana->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.consignee') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->consignee->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.naviera') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->naviera->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.num_booking') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->num_booking }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.nave') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->nave }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.cut_off') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->cut_off }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.stacking_ini') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->stacking_ini }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.stacking_end') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->stacking_end }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.etd') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->etd }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.eta') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->eta }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.puerto_embarque') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->puerto_embarque->emails ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.puerto_destino') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->puerto_destino->emails ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.puerto_descarga') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->puerto_descarga->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.punto_de_entrada') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->punto_de_entrada }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.num_contenedor') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->num_contenedor }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.ventilacion') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->ventilacion }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.tara_contenedor') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->tara_contenedor }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.quest') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->quest }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.num_sello') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->num_sello }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.temperatura') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->temperatura }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.empresa_transportista') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->empresa_transportista }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.conductor') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->conductor->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.rut_conductor') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->rut_conductor }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.ppu') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->ppu }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.telefono') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->telefono }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.planta_carga') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->planta_carga->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.direccion') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->direccion }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.fecha_carga') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->fecha_carga }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.hora_carga') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->hora_carga }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.guia_despacho_dirigida') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->guia_despacho_dirigida }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.planilla_sag_dirigida') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->planilla_sag_dirigida }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.num_po') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->num_po }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.emision_de_bl') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->emision_de_bl->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.tipo_de_flete') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->tipo_de_flete->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.clausula_de_venta') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->clausula_de_venta->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.moneda') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->moneda->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.forma_de_pago') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->forma_de_pago->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.instructivoEmbarque.fields.modalidad_de_venta') }}
                        </th>
                        <td>
                            {{ $instructivoEmbarque->modalidad_de_venta->nombre ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.instructivo-embarques.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection