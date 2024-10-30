@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.tipo.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tipos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.tipo.fields.id') }}
                        </th>
                        <td>
                            {{ $tipo->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tipo.fields.sigla') }}
                        </th>
                        <td>
                            {{ $tipo->sigla }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tipo.fields.nombre') }}
                        </th>
                        <td>
                            {{ $tipo->nombre }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tipos.index') }}">
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
            <a class="nav-link" href="#tipo_entidads" role="tab" data-toggle="tab">
                {{ trans('cruds.entidad.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="tipo_entidads">
            @includeIf('admin.tipos.relationships.tipoEntidads', ['entidads' => $tipo->tipoEntidads])
        </div>
    </div>
</div>

@endsection