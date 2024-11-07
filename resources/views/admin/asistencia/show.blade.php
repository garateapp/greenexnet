@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.asistencium.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.asistencia.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.asistencium.fields.id') }}
                        </th>
                        <td>
                            {{ $asistencium->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.asistencium.fields.locacion') }}
                        </th>
                        <td>
                            {{ $asistencium->locacion->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.asistencium.fields.turno') }}
                        </th>
                        <td>
                            {{ $asistencium->turno->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.asistencium.fields.personal') }}
                        </th>
                        <td>
                            {{ $asistencium->personal->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.asistencium.fields.fecha_hora') }}
                        </th>
                        <td>
                            {{ $asistencium->fecha_hora }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.asistencia.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection