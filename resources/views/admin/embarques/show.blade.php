@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.embarque.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.embarques.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.id') }}
                        </th>
                        <td>
                            {{ $embarque->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.temporada') }}
                        </th>
                        <td>
                            {{ App\Models\Embarque::TEMPORADA_SELECT[$embarque->temporada] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.num_embarque') }}
                        </th>
                        <td>
                            {{ $embarque->num_embarque }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.id_cliente') }}
                        </th>
                        <td>
                            {{ $embarque->id_cliente }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.n_cliente') }}
                        </th>
                        <td>
                            {{ $embarque->n_cliente }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.planta_carga') }}
                        </th>
                        <td>
                            {{ $embarque->planta_carga }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.n_naviera') }}
                        </th>
                        <td>
                            {{ $embarque->n_naviera }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.nave') }}
                        </th>
                        <td>
                            {{ $embarque->nave }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.num_contenedor') }}
                        </th>
                        <td>
                            {{ $embarque->num_contenedor }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.especie') }}
                        </th>
                        <td>
                            {{ $embarque->especie }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.variedad') }}
                        </th>
                        <td>
                            {{ $embarque->variedad }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.embalajes') }}
                        </th>
                        <td>
                            {{ $embarque->embalajes }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.etiqueta') }}
                        </th>
                        <td>
                            {{ $embarque->etiqueta }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.cajas') }}
                        </th>
                        <td>
                            {{ $embarque->cajas }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.peso_neto') }}
                        </th>
                        <td>
                            {{ $embarque->peso_neto }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.puerto_embarque') }}
                        </th>
                        <td>
                            {{ $embarque->puerto_embarque }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.pais_destino') }}
                        </th>
                        <td>
                            {{ $embarque->pais_destino }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.puerto_destino') }}
                        </th>
                        <td>
                            {{ $embarque->puerto_destino }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.mercado') }}
                        </th>
                        <td>
                            {{ $embarque->mercado }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.etd_estimado') }}
                        </th>
                        <td>
                            {{ $embarque->etd_estimado }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.eta_estimado') }}
                        </th>
                        <td>
                            {{ $embarque->eta_estimado }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.fecha_zarpe_real') }}
                        </th>
                        <td>
                            {{ $embarque->fecha_zarpe_real }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.fecha_arribo_real') }}
                        </th>
                        <td>
                            {{ $embarque->fecha_arribo_real }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.dias_transito_real') }}
                        </th>
                        <td>
                            {{ $embarque->dias_transito_real }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.estado') }}
                        </th>
                        <td>
                            {{ App\Models\Embarque::ESTADO_SELECT[$embarque->estado] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.descargado') }}
                        </th>
                        <td>
                            {{ $embarque->descargado }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.retirado_full') }}
                        </th>
                        <td>
                            {{ $embarque->retirado_full }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.devuelto_vacio') }}
                        </th>
                        <td>
                            {{ $embarque->devuelto_vacio }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.notas') }}
                        </th>
                        <td>
                            {{ $embarque->notas }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.calificacion') }}
                        </th>
                        <td>
                            {{ $embarque->calificacion }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.conexiones') }}
                        </th>
                        <td>
                            {{ $embarque->conexiones }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.con_fecha_hora') }}
                        </th>
                        <td>
                            {{ $embarque->con_fecha_hora }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.status_aereo') }}
                        </th>
                        <td>
                            {{ App\Models\Embarque::STATUS_AEREO_SELECT[$embarque->status_aereo] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.num_pallets') }}
                        </th>
                        <td>
                            {{ $embarque->num_pallets }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.embalaje_std') }}
                        </th>
                        <td>
                            {{ $embarque->embalaje_std }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.num_orden') }}
                        </th>
                        <td>
                            {{ $embarque->num_orden }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarque.fields.tipo_especie') }}
                        </th>
                        <td>
                            {{ $embarque->tipo_especie }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.embarques.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection