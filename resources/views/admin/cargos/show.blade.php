@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.cargo.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.cargos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.cargo.fields.id') }}
                        </th>
                        <td>
                            {{ $cargo->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.cargo.fields.nombre') }}
                        </th>
                        <td>
                            {{ $cargo->nombre }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.cargos.index') }}">
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
            <a class="nav-link" href="#cargo_personals" role="tab" data-toggle="tab">
                {{ trans('cruds.personal.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="cargo_personals">
            @includeIf('admin.cargos.relationships.cargoPersonals', ['personals' => $cargo->cargoPersonals])
        </div>
    </div>
</div>

@endsection