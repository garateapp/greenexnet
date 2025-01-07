@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.conversorXl.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.conversor-xls.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.conversorXl.fields.id') }}
                        </th>
                        <td>
                            {{ $conversorXl->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.conversorXl.fields.cliente') }}
                        </th>
                        <td>
                            {{ $conversorXl->cliente->codigo_cliente ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.conversorXl.fields.modulo') }}
                        </th>
                        <td>
                            {{ $conversorXl->modulo->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.conversorXl.fields.tipo') }}
                        </th>
                        <td>
                            {{ $conversorXl->tipo->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.conversorXl.fields.propiedad') }}
                        </th>
                        <td>
                            {{ $conversorXl->propiedad }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.conversorXl.fields.coordenada') }}
                        </th>
                        <td>
                            {{ $conversorXl->coordenada }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.conversorXl.fields.orden') }}
                        </th>
                        <td>
                            {{ $conversorXl->orden }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.conversorXl.fields.visible') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $conversorXl->visible ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.conversorXl.fields.formula') }}
                        </th>
                        <td>
                            {{ $conversorXl->formula }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.conversor-xls.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection