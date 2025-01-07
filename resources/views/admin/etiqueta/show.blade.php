@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.etiquetum.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.etiqueta.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.etiquetum.fields.id') }}
                        </th>
                        <td>
                            {{ $etiquetum->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.etiquetum.fields.codigo') }}
                        </th>
                        <td>
                            {{ $etiquetum->codigo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.etiquetum.fields.nombre') }}
                        </th>
                        <td>
                            {{ $etiquetum->nombre }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.etiqueta.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#etiqueta_etiquetas_x_especies" role="tab" data-toggle="tab">
                {{ trans('cruds.etiquetasXEspecy.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="etiqueta_etiquetas_x_especies">
            @includeIf('admin.etiqueta.relationships.etiquetaEtiquetasXEspecies', ['etiquetasXEspecies' => $etiquetum->etiquetaEtiquetasXEspecies])
        </div>
    </div>
</div>

@endsection