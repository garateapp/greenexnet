@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.frecuenciaTurno.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.frecuencia-turnos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.frecuenciaTurno.fields.id') }}
                        </th>
                        <td>
                            {{ $frecuenciaTurno->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.frecuenciaTurno.fields.dia') }}
                        </th>
                        <td>
                            {{ App\Models\FrecuenciaTurno::DIA_SELECT[$frecuenciaTurno->dia] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.frecuenciaTurno.fields.turno') }}
                        </th>
                        <td>
                            {{ $frecuenciaTurno->turno->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.frecuenciaTurno.fields.nombre') }}
                        </th>
                        <td>
                            {{ $frecuenciaTurno->nombre }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.frecuencia-turnos.index') }}">
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
            <a class="nav-link" href="#frecuencia_turnos_frecuencia" role="tab" data-toggle="tab">
                {{ trans('cruds.turnosFrecuencium.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="frecuencia_turnos_frecuencia">
            @includeIf('admin.frecuenciaTurnos.relationships.frecuenciaTurnosFrecuencia', ['turnosFrecuencia' => $frecuenciaTurno->frecuenciaTurnosFrecuencia])
        </div>
    </div>
</div>

@endsection