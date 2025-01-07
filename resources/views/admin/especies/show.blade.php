@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.especy.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.especies.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.especy.fields.id') }}
                        </th>
                        <td>
                            {{ $especy->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.especy.fields.codigo') }}
                        </th>
                        <td>
                            {{ $especy->codigo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.especy.fields.nombre') }}
                        </th>
                        <td>
                            {{ $especy->nombre }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.especies.index') }}">
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
            <a class="nav-link" href="#especie_variedads" role="tab" data-toggle="tab">
                {{ trans('cruds.variedad.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#especie_etiquetas_x_especies" role="tab" data-toggle="tab">
                {{ trans('cruds.etiquetasXEspecy.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="especie_variedads">
            @includeIf('admin.especies.relationships.especieVariedads', ['variedads' => $especy->especieVariedads])
        </div>
        <div class="tab-pane" role="tabpanel" id="especie_etiquetas_x_especies">
            @includeIf('admin.especies.relationships.especieEtiquetasXEspecies', ['etiquetasXEspecies' => $especy->especieEtiquetasXEspecies])
        </div>
    </div>
</div>

@endsection