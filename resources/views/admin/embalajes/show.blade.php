@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.embalaje.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.embalajes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.embalaje.fields.id') }}
                        </th>
                        <td>
                            {{ $embalaje->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embalaje.fields.c_embalaje') }}
                        </th>
                        <td>
                            {{ $embalaje->c_embalaje }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embalaje.fields.kgxcaja') }}
                        </th>
                        <td>
                            {{ $embalaje->kgxcaja }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embalaje.fields.cajaxpallet') }}
                        </th>
                        <td>
                            {{ $embalaje->cajaxpallet }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embalaje.fields.altura_pallet') }}
                        </th>
                        <td>
                            {{ $embalaje->altura_pallet }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embalaje.fields.tipo_embarque') }}
                        </th>
                        <td>
                            {{ App\Models\Embalaje::TIPO_EMBARQUE_SELECT[$embalaje->tipo_embarque] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embalaje.fields.caja') }}
                        </th>
                        <td>
                            {{ $embalaje->caja }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.embalajes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection