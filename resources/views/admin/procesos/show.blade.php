@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.proceso.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.procesos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.id') }}
                        </th>
                        <td>
                            {{ $proceso->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.productor') }}
                        </th>
                        <td>
                            {{ $proceso->productor->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.fecha_proceso') }}
                        </th>
                        <td>
                            {{ $proceso->fecha_proceso }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.variedad') }}
                        </th>
                        <td>
                            {{ $proceso->variedad }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.categoria') }}
                        </th>
                        <td>
                            {{ $proceso->categoria }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.etiqueta') }}
                        </th>
                        <td>
                            {{ $proceso->etiqueta }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.calibre') }}
                        </th>
                        <td>
                            {{ $proceso->calibre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.color') }}
                        </th>
                        <td>
                            {{ $proceso->color }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.total_kilos') }}
                        </th>
                        <td>
                            {{ $proceso->total_kilos }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.etd_week') }}
                        </th>
                        <td>
                            {{ $proceso->etd_week }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.eta_week') }}
                        </th>
                        <td>
                            {{ $proceso->eta_week }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.resultado_kilo') }}
                        </th>
                        <td>
                            {{ $proceso->resultado_kilo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.resultado_total') }}
                        </th>
                        <td>
                            {{ $proceso->resultado_total }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.precio_comercial') }}
                        </th>
                        <td>
                            {{ $proceso->precio_comercial }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.total_comercial') }}
                        </th>
                        <td>
                            {{ $proceso->total_comercial }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.proceso.fields.costo_comercial') }}
                        </th>
                        <td>
                            {{ $proceso->costo_comercial }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.procesos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection