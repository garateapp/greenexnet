@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.recibeMaster.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.recibe-masters.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.id') }}
                        </th>
                        <td>
                            {{ $recibeMaster->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.especie') }}
                        </th>
                        <td>
                            {{ $recibeMaster->especie }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.exportador') }}
                        </th>
                        <td>
                            {{ $recibeMaster->exportador }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.partida') }}
                        </th>
                        <td>
                            {{ $recibeMaster->partida }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.estado') }}
                        </th>
                        <td>
                            {{ App\Models\RecibeMaster::ESTADO_SELECT[$recibeMaster->estado] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.cod_central') }}
                        </th>
                        <td>
                            {{ $recibeMaster->cod_central }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.cod_productor') }}
                        </th>
                        <td>
                            {{ $recibeMaster->cod_productor }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.nro_guia_despacho') }}
                        </th>
                        <td>
                            {{ $recibeMaster->nro_guia_despacho }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.fecha_recepcion') }}
                        </th>
                        <td>
                            {{ $recibeMaster->fecha_recepcion }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.fecha_cosecha') }}
                        </th>
                        <td>
                            {{ $recibeMaster->fecha_cosecha }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.cod_variedad') }}
                        </th>
                        <td>
                            {{ $recibeMaster->cod_variedad }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.estiba_camion') }}
                        </th>
                        <td>
                            {{ App\Models\RecibeMaster::ESTIBA_CAMION_SELECT[$recibeMaster->estiba_camion] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.esponjas_cloradas') }}
                        </th>
                        <td>
                            {{ App\Models\RecibeMaster::ESPONJAS_CLORADAS_SELECT[$recibeMaster->esponjas_cloradas] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.nro_bandeja') }}
                        </th>
                        <td>
                            {{ $recibeMaster->nro_bandeja }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.hora_llegada') }}
                        </th>
                        <td>
                            {{ $recibeMaster->hora_llegada }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.kilo_muestra') }}
                        </th>
                        <td>
                            {{ $recibeMaster->kilo_muestra }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.kilo_neto') }}
                        </th>
                        <td>
                            {{ $recibeMaster->kilo_neto }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.temp_ingreso') }}
                        </th>
                        <td>
                            {{ $recibeMaster->temp_ingreso }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.temp_salida') }}
                        </th>
                        <td>
                            {{ $recibeMaster->temp_salida }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.lote') }}
                        </th>
                        <td>
                            {{ $recibeMaster->lote }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.huerto') }}
                        </th>
                        <td>
                            {{ $recibeMaster->huerto }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.hidro') }}
                        </th>
                        <td>
                            {{ $recibeMaster->hidro }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.fecha_envio') }}
                        </th>
                        <td>
                            {{ $recibeMaster->fecha_envio }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recibeMaster.fields.respuesta_envio') }}
                        </th>
                        <td>
                            {{ $recibeMaster->respuesta_envio }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.recibe-masters.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection