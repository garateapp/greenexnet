@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.datosCaja.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.datos-cajas.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.proceso') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->proceso }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.fecha_produccion') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->fecha_produccion }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.turno') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->turno }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.cod_linea') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->cod_linea }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.cat') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->cat }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.variedad_real') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->variedad_real }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.variedad_timbrada') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->variedad_timbrada }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.salida') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->salida }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.marca') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->marca }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.productor_real') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->productor_real }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.especie') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->especie }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.cod_caja') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->cod_caja }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.cod_confeccion') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->cod_confeccion }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.calibre_timbrado') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->calibre_timbrado }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.peso_timbrado') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->peso_timbrado }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.lote') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->lote }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.nuevo_lote') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->nuevo_lote }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.datosCaja.fields.codigo_qr') }}
                                    </th>
                                    <td>
                                        {{ $datosCaja->codigo_qr }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.datos-cajas.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection