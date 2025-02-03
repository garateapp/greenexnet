@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.proveedor.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.proveedors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.proveedor.fields.id') }}
                        </th>
                        <td>
                            {{ $proveedor->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proveedor.fields.rut') }}
                        </th>
                        <td>
                            {{ $proveedor->rut }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proveedor.fields.cobro') }}
                        </th>
                        <td>
                            {{ $proveedor->cobro }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proveedor.fields.nombre_simple') }}
                        </th>
                        <td>
                            {{ $proveedor->nombre_simple }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proveedor.fields.razon_social') }}
                        </th>
                        <td>
                            {{ $proveedor->razon_social }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.proveedors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection