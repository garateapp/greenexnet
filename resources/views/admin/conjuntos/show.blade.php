@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.conjunto.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.conjuntos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.conjunto.fields.id') }}
                        </th>
                        <td>
                            {{ $conjunto->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.conjunto.fields.nombre') }}
                        </th>
                        <td>
                            {{ $conjunto->nombre }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.conjuntos.index') }}">
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
            <a class="nav-link" href="#conjunto_grupos" role="tab" data-toggle="tab">
                {{ trans('cruds.grupo.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="conjunto_grupos">
            @includeIf('admin.conjuntos.relationships.conjuntoGrupos', ['grupos' => $conjunto->conjuntoGrupos])
        </div>
    </div>
</div>

@endsection