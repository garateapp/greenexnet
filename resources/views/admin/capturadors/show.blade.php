@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.capturador.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.capturadors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.capturador.fields.id') }}
                        </th>
                        <td>
                            {{ $capturador->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.capturador.fields.nombre') }}
                        </th>
                        <td>
                            {{ $capturador->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.capturador.fields.cliente') }}
                        </th>
                        <td>
                            {{ $capturador->cliente->nombre_fantasia ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.capturador.fields.modulo') }}
                        </th>
                        <td>
                            {{ $capturador->modulo->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.capturador.fields.funcion') }}
                        </th>
                        <td>
                            {{ $capturador->funcion->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.capturador.fields.activo') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $capturador->activo ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.capturadors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection