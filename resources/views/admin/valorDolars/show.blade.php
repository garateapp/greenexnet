@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.valorDolar.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.valor-dolars.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.valorDolar.fields.id') }}
                        </th>
                        <td>
                            {{ $valorDolar->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.valorDolar.fields.fecha_cambio') }}
                        </th>
                        <td>
                            {{ $valorDolar->fecha_cambio }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.valorDolar.fields.valor') }}
                        </th>
                        <td>
                            {{ $valorDolar->valor }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.valor-dolars.index') }}">
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
            <a class="nav-link" href="#valor_dolar_valor_fletes" role="tab" data-toggle="tab">
                {{ trans('cruds.valorFlete.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#tipo_cambio_anticipos" role="tab" data-toggle="tab">
                {{ trans('cruds.anticipo.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="valor_dolar_valor_fletes">
            @includeIf('admin.valorDolars.relationships.valorDolarValorFletes', ['valorFletes' => $valorDolar->valorDolarValorFletes])
        </div>
        <div class="tab-pane" role="tabpanel" id="tipo_cambio_anticipos">
            @includeIf('admin.valorDolars.relationships.tipoCambioAnticipos', ['anticipos' => $valorDolar->tipoCambioAnticipos])
        </div>
    </div>
</div>

@endsection