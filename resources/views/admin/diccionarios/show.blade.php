@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.diccionario.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.diccionarios.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.diccionario.fields.id') }}
                        </th>
                        <td>
                            {{ $diccionario->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.diccionario.fields.variable') }}
                        </th>
                        <td>
                            {{ $diccionario->variable }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.diccionario.fields.valor') }}
                        </th>
                        <td>
                            {{ $diccionario->valor }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.diccionario.fields.tipo') }}
                        </th>
                        <td>
                            {{ App\Models\Diccionario::TIPO_SELECT[$diccionario->tipo] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.diccionarios.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection