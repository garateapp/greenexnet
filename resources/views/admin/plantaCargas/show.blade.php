@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.plantaCarga.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.planta-cargas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.plantaCarga.fields.id') }}
                        </th>
                        <td>
                            {{ $plantaCarga->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plantaCarga.fields.nombre') }}
                        </th>
                        <td>
                            {{ $plantaCarga->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plantaCarga.fields.direccion') }}
                        </th>
                        <td>
                            {{ $plantaCarga->direccion }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plantaCarga.fields.id_fx') }}
                        </th>
                        <td>
                            {{ $plantaCarga->id_fx }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.planta-cargas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection