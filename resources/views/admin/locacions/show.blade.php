@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.locacion.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.locacions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.locacion.fields.id') }}
                        </th>
                        <td>
                            {{ $locacion->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.locacion.fields.nombre') }}
                        </th>
                        <td>
                            {{ $locacion->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.locacion.fields.area') }}
                        </th>
                        <td>
                            {{ $locacion->area->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.locacion.fields.cantidad_personal') }}
                        </th>
                        <td>
                            {{ $locacion->cantidad_personal }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.locacion.fields.estado') }}
                        </th>
                        <td>
                            {{ $locacion->estado->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.locacion.fields.locacion_padre') }}
                        </th>
                        <td>
                            {{ $locacion->locacion_padre->nombre ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.locacions.index') }}">
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
            <a class="nav-link" href="#locacion_turnos_frecuencia" role="tab" data-toggle="tab">
                {{ trans('cruds.turnosFrecuencium.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="locacion_turnos_frecuencia">
            @includeIf('admin.locacions.relationships.locacionTurnosFrecuencia', ['turnosFrecuencia' => $locacion->locacionTurnosFrecuencia])
        </div>
    </div>
</div>

@endsection