@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.politicaCotizacion.title_singular') }} {{ trans('global.show') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.politicaCotizacion.fields.id') }}
                        </th>
                        <td>
                            {{ $politicaCotizacion->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.politicaCotizacion.fields.monto_min') }}
                        </th>
                        <td>
                            {{ $politicaCotizacion->monto_min }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.politicaCotizacion.fields.monto_max') }}
                        </th>
                        <td>
                            {{ $politicaCotizacion->monto_max }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.politicaCotizacion.fields.cotizaciones_requeridas') }}
                        </th>
                        <td>
                            {{ $politicaCotizacion->cotizaciones_requeridas }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.politicaCotizacion.fields.activo') }}
                        </th>
                        <td>
                            {{ $politicaCotizacion->activo ? 'Si' : 'No' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a class="btn btn-default" href="{{ route('admin.politica-cotizaciones.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
</div>

@endsection
