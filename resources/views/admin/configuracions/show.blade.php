@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.configuracion.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.configuracions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracion.fields.id') }}
                        </th>
                        <td>
                            {{ $configuracion->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracion.fields.variable') }}
                        </th>
                        <td>
                            {{ $configuracion->variable }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracion.fields.valor') }}
                        </th>
                        <td>
                            {{ $configuracion->valor }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.configuracions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection