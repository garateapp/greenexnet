@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.baseContacto.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.base-contactos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.baseContacto.fields.id') }}
                        </th>
                        <td>
                            {{ $baseContacto->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.baseContacto.fields.cliente') }}
                        </th>
                        <td>
                            {{ $baseContacto->cliente->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.baseContacto.fields.tipo_transporte') }}
                        </th>
                        <td>
                            {{ App\Models\BaseContacto::TIPO_TRANSPORTE_SELECT[$baseContacto->tipo_transporte] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.baseContacto.fields.rut_recibidor') }}
                        </th>
                        <td>
                            {{ $baseContacto->rut_recibidor }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.baseContacto.fields.direccion') }}
                        </th>
                        <td>
                            {{ $baseContacto->direccion }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.baseContacto.fields.contacto') }}
                        </th>
                        <td>
                            {{ $baseContacto->contacto }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.baseContacto.fields.telefono') }}
                        </th>
                        <td>
                            {{ $baseContacto->telefono }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.baseContacto.fields.fax') }}
                        </th>
                        <td>
                            {{ $baseContacto->fax }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.baseContacto.fields.email') }}
                        </th>
                        <td>
                            {{ $baseContacto->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.baseContacto.fields.notify') }}
                        </th>
                        <td>
                            {{ $baseContacto->notify }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.base-contactos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection