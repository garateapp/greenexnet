@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.capturadorEstructura.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.capturador-estructuras.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.capturadorEstructura.fields.id') }}
                        </th>
                        <td>
                            {{ $capturadorEstructura->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.capturadorEstructura.fields.capturador') }}
                        </th>
                        <td>
                            {{ $capturadorEstructura->capturador->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.capturadorEstructura.fields.propiedad') }}
                        </th>
                        <td>
                            {{ $capturadorEstructura->propiedad }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.capturadorEstructura.fields.coordenada') }}
                        </th>
                        <td>
                            {{ $capturadorEstructura->coordenada }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.capturadorEstructura.fields.orden') }}
                        </th>
                        <td>
                            {{ $capturadorEstructura->orden }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.capturadorEstructura.fields.visible') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $capturadorEstructura->visible ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.capturadorEstructura.fields.formula') }}
                        </th>
                        <td>
                            {{ $capturadorEstructura->formula }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.capturador-estructuras.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection