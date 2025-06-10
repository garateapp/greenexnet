@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.materialProducto.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.material-productos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.materialProducto.fields.id') }}
                        </th>
                        <td>
                            {{ $materialProducto->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.materialProducto.fields.embalaje') }}
                        </th>
                        <td>
                            {{ $materialProducto->embalaje->c_embalaje ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.materialProducto.fields.material') }}
                        </th>
                        <td>
                            {{ $materialProducto->material->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.materialProducto.fields.unidadxcaja') }}
                        </th>
                        <td>
                            {{ $materialProducto->unidadxcaja }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.materialProducto.fields.unidadxpallet') }}
                        </th>
                        <td>
                            {{ $materialProducto->unidadxpallet }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.materialProducto.fields.costoxcajaclp') }}
                        </th>
                        <td>
                            {{ $materialProducto->costoxcajaclp }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.materialProducto.fields.costoxpallet_clp') }}
                        </th>
                        <td>
                            {{ $materialProducto->costoxpallet_clp }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.materialProducto.fields.costoxcaja_usd') }}
                        </th>
                        <td>
                            {{ $materialProducto->costoxcaja_usd }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.materialProducto.fields.costoxpallet_usd') }}
                        </th>
                        <td>
                            {{ $materialProducto->costoxpallet_usd }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.material-productos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection