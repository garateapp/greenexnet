@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.grupo.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.grupos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.grupo.fields.id') }}
                        </th>
                        <td>
                            {{ $grupo->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grupo.fields.nombre') }}
                        </th>
                        <td>
                            {{ $grupo->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grupo.fields.rut') }}
                        </th>
                        <td>
                            {{ $grupo->rut }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grupo.fields.conjunto') }}
                        </th>
                        <td>
                            {{ $grupo->conjunto->nombre ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.grupos.index') }}">
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
            <a class="nav-link" href="#grupo_productors" role="tab" data-toggle="tab">
                {{ trans('cruds.productor.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="grupo_productors">
            @includeIf('admin.grupos.relationships.grupoProductors', ['productors' => $grupo->grupoProductors])
        </div>
    </div>
</div>

@endsection