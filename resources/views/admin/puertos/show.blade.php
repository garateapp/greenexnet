@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.puerto.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.puertos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.puerto.fields.id') }}
                        </th>
                        <td>
                            {{ $puerto->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.puerto.fields.codigo') }}
                        </th>
                        <td>
                            {{ $puerto->codigo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.puerto.fields.nombre') }}
                        </th>
                        <td>
                            {{ $puerto->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.puerto.fields.cap') }}
                        </th>
                        <td>
                            {{ $puerto->cap }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.puertos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection