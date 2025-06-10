@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.anticipo.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.anticipos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.anticipo.fields.id') }}
                        </th>
                        <td>
                            {{ $anticipo->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.anticipo.fields.productor') }}
                        </th>
                        <td>
                            {{ $anticipo->productor->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.anticipo.fields.valor') }}
                        </th>
                        <td>
                            {{ $anticipo->valor }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.anticipo.fields.num_docto') }}
                        </th>
                        <td>
                            {{ $anticipo->num_docto }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.anticipo.fields.fecha_documento') }}
                        </th>
                        <td>
                            {{ $anticipo->fecha_documento }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.anticipo.fields.tipo_cambio') }}
                        </th>
                        <td>
                            {{ $anticipo->tipo_cambio->valor ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.anticipo.fields.especie') }}
                        </th>
                        <td>
                            {{ $anticipo->especie->nombre ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.anticipos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection