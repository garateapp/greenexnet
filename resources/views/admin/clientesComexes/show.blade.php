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

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#clientecomex_metas_cliente_comexes" role="tab" data-toggle="tab">
                {{ trans('cruds.metasClienteComex.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#cliente_base_recibidors" role="tab" data-toggle="tab">
                {{ trans('cruds.baseRecibidor.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="clientecomex_metas_cliente_comexes">
            @includeIf('admin.clientesComexes.relationships.clientecomexMetasClienteComexes', ['metasClienteComexes' => $clientesComex->clientecomexMetasClienteComexes])
        </div>
        <div class="tab-pane" role="tabpanel" id="cliente_base_recibidors">
            @includeIf('admin.clientesComexes.relationships.clienteBaseRecibidors', ['baseRecibidors' => $clientesComex->clienteBaseRecibidors])
        </div>
    </div>
</div>

@endsection