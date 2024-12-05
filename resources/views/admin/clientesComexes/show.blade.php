@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.clientesComex.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.clientes-comexes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.clientesComex.fields.id') }}
                        </th>
                        <td>
                            {{ $clientesComex->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clientesComex.fields.codigo_cliente') }}
                        </th>
                        <td>
                            {{ $clientesComex->codigo_cliente }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clientesComex.fields.nombre_empresa') }}
                        </th>
                        <td>
                            {{ $clientesComex->nombre_empresa }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clientesComex.fields.nombre_fantasia') }}
                        </th>
                        <td>
                            {{ $clientesComex->nombre_fantasia }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.clientes-comexes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection