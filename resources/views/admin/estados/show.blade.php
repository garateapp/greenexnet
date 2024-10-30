@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.estado.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.estados.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.estado.fields.id') }}
                        </th>
                        <td>
                            {{ $estado->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.estado.fields.nombre') }}
                        </th>
                        <td>
                            {{ $estado->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.estado.fields.icono') }}
                        </th>
                        <td>
                            @if($estado->icono)
                                <a href="{{ $estado->icono->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $estado->icono->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.estados.index') }}">
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
            <a class="nav-link" href="#estado_areas" role="tab" data-toggle="tab">
                {{ trans('cruds.area.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#estado_personals" role="tab" data-toggle="tab">
                {{ trans('cruds.personal.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="estado_areas">
            @includeIf('admin.estados.relationships.estadoAreas', ['areas' => $estado->estadoAreas])
        </div>
        <div class="tab-pane" role="tabpanel" id="estado_personals">
            @includeIf('admin.estados.relationships.estadoPersonals', ['personals' => $estado->estadoPersonals])
        </div>
    </div>
</div>

@endsection