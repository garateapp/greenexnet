@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.productor.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.productors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.productor.fields.id') }}
                        </th>
                        <td>
                            {{ $productor->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productor.fields.rut') }}
                        </th>
                        <td>
                            {{ $productor->rut }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productor.fields.nombre') }}
                        </th>
                        <td>
                            {{ $productor->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productor.fields.grupo') }}
                        </th>
                        <td>
                            {{ $productor->grupo->nombre ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.productors.index') }}">
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
            <a class="nav-link" href="#productor_valor_fletes" role="tab" data-toggle="tab">
                {{ trans('cruds.valorFlete.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#productor_valor_envases" role="tab" data-toggle="tab">
                {{ trans('cruds.valorEnvase.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#productor_anticipos" role="tab" data-toggle="tab">
                {{ trans('cruds.anticipo.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#productor_recepcions" role="tab" data-toggle="tab">
                {{ trans('cruds.recepcion.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#productor_procesos" role="tab" data-toggle="tab">
                {{ trans('cruds.proceso.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="productor_valor_fletes">
            @includeIf('admin.productors.relationships.productorValorFletes', ['valorFletes' => $productor->productorValorFletes])
        </div>
        <div class="tab-pane" role="tabpanel" id="productor_valor_envases">
            @includeIf('admin.productors.relationships.productorValorEnvases', ['valorEnvases' => $productor->productorValorEnvases])
        </div>
        <div class="tab-pane" role="tabpanel" id="productor_anticipos">
            @includeIf('admin.productors.relationships.productorAnticipos', ['anticipos' => $productor->productorAnticipos])
        </div>
        <div class="tab-pane" role="tabpanel" id="productor_recepcions">
            @includeIf('admin.productors.relationships.productorRecepcions', ['recepcions' => $productor->productorRecepcions])
        </div>
        <div class="tab-pane" role="tabpanel" id="productor_procesos">
            @includeIf('admin.productors.relationships.productorProcesos', ['procesos' => $productor->productorProcesos])
        </div>
    </div>
</div>

@endsection