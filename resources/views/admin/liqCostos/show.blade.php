@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.liqCosto.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.liq-costos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.liqCosto.fields.id') }}
                        </th>
                        <td>
                            {{ $liqCosto->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liqCosto.fields.liq_cabecera') }}
                        </th>
                        <td>
                            {{ $liqCosto->liq_cabecera->instructivo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liqCosto.fields.nombre_costo') }}
                        </th>
                        <td>
                            {{ $liqCosto->nombre_costo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.liqCosto.fields.valor') }}
                        </th>
                        <td>
                            {{ $liqCosto->valor }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.liq-costos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection