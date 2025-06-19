@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.chofer.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.chofers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.chofer.fields.id') }}
                        </th>
                        <td>
                            {{ $chofer->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.chofer.fields.nombre') }}
                        </th>
                        <td>
                            {{ $chofer->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.chofer.fields.rut') }}
                        </th>
                        <td>
                            {{ $chofer->rut }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.chofer.fields.telefono') }}
                        </th>
                        <td>
                            {{ $chofer->telefono }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.chofer.fields.patente') }}
                        </th>
                        <td>
                            {{ $chofer->patente }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.chofers.index') }}">
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
            <a class="nav-link" href="#conductor_instructivo_embarques" role="tab" data-toggle="tab">
                {{ trans('cruds.instructivoEmbarque.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="conductor_instructivo_embarques">
            @includeIf('admin.chofers.relationships.conductorInstructivoEmbarques', ['instructivoEmbarques' => $chofer->conductorInstructivoEmbarques])
        </div>
    </div>
</div>

@endsection