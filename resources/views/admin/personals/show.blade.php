@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.personal.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.personals.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.personal.fields.id') }}
                        </th>
                        <td>
                            {{ $personal->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personal.fields.nombre') }}
                        </th>
                        <td>
                            {{ $personal->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personal.fields.codigo') }}
                        </th>
                        <td>
                            {{ $personal->codigo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personal.fields.rut') }}
                        </th>
                        <td>
                            {{ $personal->rut }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personal.fields.email') }}
                        </th>
                        <td>
                            {{ $personal->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personal.fields.telefono') }}
                        </th>
                        <td>
                            {{ $personal->telefono }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personal.fields.cargo') }}
                        </th>
                        <td>
                            {{ $personal->cargo->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personal.fields.estado') }}
                        </th>
                        <td>
                            {{ $personal->estado->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personal.fields.entidad') }}
                        </th>
                        <td>
                            {{ $personal->entidad->nombre ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.personals.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection