@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.liquidacionesCx.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.liquidaciones-cxes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.id') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.contenedor') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->contenedor }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.eta') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->eta }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.variedad') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->variedad->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.pallet') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->pallet }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.etiqueta') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->etiqueta->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.calibre') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->calibre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.embalaje') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->embalaje->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.cantidad') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->cantidad }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.fecha_venta') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->fecha_venta }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.ventas') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->ventas }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.precio_unitario') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->precio_unitario }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.monto_rmb') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->monto_rmb }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.observaciones') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->observaciones }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liquidacionesCx.fields.liqcabecera') }}
                        </th>
                        <td>
                            {{ $liquidacionesCx->liqcabecera->instructivo ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.liquidaciones-cxes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection