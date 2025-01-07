@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.liqCxCabecera.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.liq-cx-cabeceras.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.id') }}
                        </th>
                        <td>
                            {{ $liqCxCabecera->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.instructivo') }}
                        </th>
                        <td>
                            {{ $liqCxCabecera->instructivo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.cliente') }}
                        </th>
                        <td>
                            {{ $liqCxCabecera->cliente->nombre_fantasia ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.nave') }}
                        </th>
                        <td>
                            {{ $liqCxCabecera->nave->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.eta') }}
                        </th>
                        <td>
                            {{ $liqCxCabecera->eta }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.tasa_intercambio') }}
                        </th>
                        <td>
                            {{ $liqCxCabecera->tasa_intercambio }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.total_costo') }}
                        </th>
                        <td>
                            {{ $liqCxCabecera->total_costo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.total_bruto') }}
                        </th>
                        <td>
                            {{ $liqCxCabecera->total_bruto }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liqCxCabecera.fields.total_neto') }}
                        </th>
                        <td>
                            {{ $liqCxCabecera->total_neto }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.liq-cx-cabeceras.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection