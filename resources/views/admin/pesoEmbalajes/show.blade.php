@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.pesoEmbalaje.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.peso-embalajes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.pesoEmbalaje.fields.id') }}
                        </th>
                        <td>
                            {{ $pesoEmbalaje->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pesoEmbalaje.fields.especie') }}
                        </th>
                        <td>
                            {{ $pesoEmbalaje->especie->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pesoEmbalaje.fields.etiqueta') }}
                        </th>
                        <td>
                            {{ $pesoEmbalaje->etiqueta->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pesoEmbalaje.fields.embalajes') }}
                        </th>
                        <td>
                            {{ $pesoEmbalaje->embalajes }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pesoEmbalaje.fields.peso_neto') }}
                        </th>
                        <td>
                            {{ $pesoEmbalaje->peso_neto }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pesoEmbalaje.fields.peso_bruto') }}
                        </th>
                        <td>
                            {{ $pesoEmbalaje->peso_bruto }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.peso-embalajes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection