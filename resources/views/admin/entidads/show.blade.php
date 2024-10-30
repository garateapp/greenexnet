@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.entidad.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.entidads.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.entidad.fields.id') }}
                        </th>
                        <td>
                            {{ $entidad->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entidad.fields.nombre') }}
                        </th>
                        <td>
                            {{ $entidad->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entidad.fields.rut') }}
                        </th>
                        <td>
                            {{ $entidad->rut }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entidad.fields.tipo') }}
                        </th>
                        <td>
                            {{ $entidad->tipo->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entidad.fields.direccion') }}
                        </th>
                        <td>
                            {{ $entidad->direccion }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.entidads.index') }}">
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
            <a class="nav-link" href="#entidad_areas" role="tab" data-toggle="tab">
                {{ trans('cruds.area.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#entidad_personals" role="tab" data-toggle="tab">
                {{ trans('cruds.personal.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="entidad_areas">
            @includeIf('admin.entidads.relationships.entidadAreas', ['areas' => $entidad->entidadAreas])
        </div>
        <div class="tab-pane" role="tabpanel" id="entidad_personals">
            @includeIf('admin.entidads.relationships.entidadPersonals', ['personals' => $entidad->entidadPersonals])
        </div>
    </div>
</div>

@endsection