@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.agenteAduana.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.agente-aduanas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.agenteAduana.fields.id') }}
                        </th>
                        <td>
                            {{ $agenteAduana->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.agenteAduana.fields.nombre') }}
                        </th>
                        <td>
                            {{ $agenteAduana->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.agenteAduana.fields.rut') }}
                        </th>
                        <td>
                            {{ $agenteAduana->rut }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.agenteAduana.fields.codigo') }}
                        </th>
                        <td>
                            {{ $agenteAduana->codigo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.agenteAduana.fields.direccion') }}
                        </th>
                        <td>
                            {{ $agenteAduana->direccion }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.agenteAduana.fields.email') }}
                        </th>
                        <td>
                            {{ $agenteAduana->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.agenteAduana.fields.telefono') }}
                        </th>
                        <td>
                            {{ $agenteAduana->telefono }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.agente-aduanas.index') }}">
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
            <a class="nav-link" href="#agente_aduana_instructivo_embarques" role="tab" data-toggle="tab">
                {{ trans('cruds.instructivoEmbarque.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="agente_aduana_instructivo_embarques">
            @includeIf('admin.agenteAduanas.relationships.agenteAduanaInstructivoEmbarques', ['instructivoEmbarques' => $agenteAduana->agenteAduanaInstructivoEmbarques])
        </div>
    </div>
</div>

@endsection