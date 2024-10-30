@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.turnosFrecuencium.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.turnos-frecuencia.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.turnosFrecuencium.fields.id') }}
                        </th>
                        <td>
                            {{ $turnosFrecuencium->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.turnosFrecuencium.fields.frecuencia') }}
                        </th>
                        <td>
                            {{ $turnosFrecuencium->frecuencia->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.turnosFrecuencium.fields.locacion') }}
                        </th>
                        <td>
                            {{ $turnosFrecuencium->locacion->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.turnosFrecuencium.fields.nombre') }}
                        </th>
                        <td>
                            {{ $turnosFrecuencium->nombre }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.turnos-frecuencia.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection