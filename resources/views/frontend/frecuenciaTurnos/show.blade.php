@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.frecuenciaTurno.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.frecuencia-turnos.index') }}">
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
                            <a class="btn btn-default" href="{{ route('frontend.frecuencia-turnos.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection