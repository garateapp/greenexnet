@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.turno.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.turnos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.turno.fields.id') }}
                        </th>
                        <td>
                            {{ $turno->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.turno.fields.nombre') }}
                        </th>
                        <td>
                            {{ $turno->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.turno.fields.hora_inicio') }}
                        </th>
                        <td>
                            {{ $turno->hora_inicio }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.turno.fields.hora_fin') }}
                        </th>
                        <td>
                            {{ $turno->hora_fin }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.turnos.index') }}">
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
            <a class="nav-link" href="#turno_frecuencia_turnos" role="tab" data-toggle="tab">
                {{ trans('cruds.frecuenciaTurno.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="turno_frecuencia_turnos">
            @includeIf('admin.turnos.relationships.turnoFrecuenciaTurnos', ['frecuenciaTurnos' => $turno->turnoFrecuenciaTurnos])
        </div>
    </div>
</div>

@endsection