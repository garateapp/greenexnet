@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.etiquetasXEspecy.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.etiquetas-x-especies.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.etiquetasXEspecy.fields.id') }}
                        </th>
                        <td>
                            {{ $etiquetasXEspecy->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.etiquetasXEspecy.fields.especie') }}
                        </th>
                        <td>
                            {{ $etiquetasXEspecy->especie->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.etiquetasXEspecy.fields.etiqueta') }}
                        </th>
                        <td>
                            {{ $etiquetasXEspecy->etiqueta->nombre ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.etiquetas-x-especies.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection