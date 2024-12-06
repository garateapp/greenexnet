@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.metasClienteComex.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.metas-cliente-comexes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.metasClienteComex.fields.id') }}
                        </th>
                        <td>
                            {{ $metasClienteComex->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.metasClienteComex.fields.clientecomex') }}
                        </th>
                        <td>
                            {{ $metasClienteComex->clientecomex->nombre_fantasia ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.metasClienteComex.fields.cantidad') }}
                        </th>
                        <td>
                            {{ $metasClienteComex->cantidad }}
                        </td>
                    </tr>
                    <tr>
                        <th>

                        </th>
                        <td>
                            {{ $metasClienteComex->anno ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.metasClienteComex.fields.observaciones') }}
                        </th>
                        <td>
                            {{ $metasClienteComex->observaciones }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.metas-cliente-comexes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
