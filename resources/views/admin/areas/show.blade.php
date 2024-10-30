@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.area.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.areas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.area.fields.id') }}
                        </th>
                        <td>
                            {{ $area->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.area.fields.nombre') }}
                        </th>
                        <td>
                            {{ $area->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.area.fields.entidad') }}
                        </th>
                        <td>
                            {{ $area->entidad->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.area.fields.descripcion') }}
                        </th>
                        <td>
                            {{ $area->descripcion }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.area.fields.estado') }}
                        </th>
                        <td>
                            {{ $area->estado->nombre ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.areas.index') }}">
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
            <a class="nav-link" href="#area_locacions" role="tab" data-toggle="tab">
                {{ trans('cruds.locacion.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="area_locacions">
            @includeIf('admin.areas.relationships.areaLocacions', ['locacions' => $area->areaLocacions])
        </div>
    </div>
</div>

@endsection