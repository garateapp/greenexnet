@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.turno.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.turnos.index') }}">
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
                            <a class="btn btn-default" href="{{ route('frontend.turnos.index') }}">
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