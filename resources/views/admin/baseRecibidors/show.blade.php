@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.baseRecibidor.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.base-recibidors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.baseRecibidor.fields.id') }}
                        </th>
                        <td>
                            {{ $baseRecibidor->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.baseRecibidor.fields.cliente') }}
                        </th>
                        <td>
                            {{ $baseRecibidor->cliente->nombre_fantasia ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.baseRecibidor.fields.codigo') }}
                        </th>
                        <td>
                            {{ $baseRecibidor->codigo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.baseRecibidor.fields.rut_sistema') }}
                        </th>
                        <td>
                            {{ $baseRecibidor->rut_sistema }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.baseRecibidor.fields.estado') }}
                        </th>
                        <td>
                            {{ App\Models\BaseRecibidor::ESTADO_RADIO[$baseRecibidor->estado] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.base-recibidors.index') }}">
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
            <a class="nav-link" href="#cliente_correoalso_airs" role="tab" data-toggle="tab">
                {{ trans('cruds.correoalsoAir.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="cliente_correoalso_airs">
            @includeIf('admin.baseRecibidors.relationships.clienteCorreoalsoAirs', ['correoalsoAirs' => $baseRecibidor->clienteCorreoalsoAirs])
        </div>
    </div>
</div>

@endsection