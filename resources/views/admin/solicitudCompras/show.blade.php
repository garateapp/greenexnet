@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.solicitudCompra.title_singular') }} {{ trans('global.show') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.solicitudCompra.fields.id') }}
                        </th>
                        <td>
                            {{ $solicitudCompra->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.solicitudCompra.fields.titulo') }}
                        </th>
                        <td>
                            {{ $solicitudCompra->titulo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.solicitudCompra.fields.descripcion') }}
                        </th>
                        <td>
                            {{ $solicitudCompra->descripcion }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.solicitudCompra.fields.solicitante') }}
                        </th>
                        <td>
                            {{ $solicitudCompra->solicitante ? $solicitudCompra->solicitante->name : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.solicitudCompra.fields.monto_estimado') }}
                        </th>
                        <td>
                            {{ number_format($solicitudCompra->monto_estimado, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.solicitudCompra.fields.centro_costo') }}
                        </th>
                        <td>
                            @php
                                $centro = $solicitudCompra->centroCosto;
                                $label = $centro ? trim(($centro->c_centrocosto ? $centro->c_centrocosto . ' - ' : '') . $centro->n_centrocosto) : '';
                                $entidad = $centro && $centro->entidad ? $centro->entidad->nombre : '';
                            @endphp
                            {{ $label }}{{ $entidad ? ' (' . $entidad . ')' : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.solicitudCompra.fields.moneda') }}
                        </th>
                        <td>
                            CLP
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.solicitudCompra.fields.cotizaciones_requeridas') }}
                        </th>
                        <td>
                            {{ $solicitudCompra->cotizaciones_requeridas }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.solicitudCompra.fields.estado') }}
                        </th>
                        <td>
                            {{ $solicitudCompra->estado ? $solicitudCompra->estado->nombre : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.solicitudCompra.fields.cotizaciones_por_adquisiciones') }}
                        </th>
                        <td>
                            {{ $solicitudCompra->cotizaciones_por_adquisiciones ? 'Si' : 'No' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.solicitudCompra.fields.fecha_requerida') }}
                        </th>
                        <td>
                            {{ $solicitudCompra->fecha_requerida }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a class="btn btn-default" href="{{ route('admin.solicitud-compras.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        {{ trans('cruds.cotizacionCompra.title') }}
        ({{ $solicitudCompra->cotizaciones->count() }}/{{ $solicitudCompra->cotizaciones_requeridas }})
    </div>
    <div class="card-body">
        @if($solicitudCompra->cotizaciones->count() > 0)
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ trans('cruds.cotizacionCompra.fields.proveedor') }}</th>
                        <th>{{ trans('cruds.cotizacionCompra.fields.monto') }}</th>
                        <th>{{ trans('cruds.cotizacionCompra.fields.moneda') }}</th>
                        <th>{{ trans('cruds.cotizacionCompra.fields.fecha_recepcion') }}</th>
                        <th>{{ trans('cruds.cotizacionCompra.fields.archivo') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($solicitudCompra->cotizaciones as $cotizacion)
                        <tr>
                            <td>{{ $cotizacion->proveedor }}</td>
                            <td>{{ number_format($cotizacion->monto, 0, ',', '.') }}</td>
                            <td>{{ $cotizacion->moneda ? $cotizacion->moneda->nombre : '' }}</td>
                            <td>{{ $cotizacion->fecha_recepcion }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $cotizacion->archivo_path) }}" target="_blank" rel="noopener">
                                    {{ trans('global.view_file') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                {{ trans('cruds.cotizacionCompra.empty') }}
            </div>
        @endif
    </div>
</div>

@if(auth()->id() === $solicitudCompra->solicitante_id || config('panel.adquisiciones_puede_subir_cotizaciones'))
<div class="card mt-3">
    <div class="card-header">
        {{ trans('cruds.cotizacionCompra.add') }}
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.solicitud-compras.storeCotizacion', $solicitudCompra) }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="proveedor">{{ trans('cruds.cotizacionCompra.fields.proveedor') }}</label>
                <input class="form-control {{ $errors->has('proveedor') ? 'is-invalid' : '' }}" type="text" name="proveedor" id="proveedor" value="{{ old('proveedor', '') }}" required>
                @if($errors->has('proveedor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('proveedor') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label class="required" for="monto">{{ trans('cruds.cotizacionCompra.fields.monto') }}</label>
                <input class="form-control {{ $errors->has('monto') ? 'is-invalid' : '' }}" type="number" name="monto" id="monto" value="{{ old('monto', '') }}" step="1" required>
                @if($errors->has('monto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('monto') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.cotizacionCompra.fields.moneda') }}</label>
                <div class="form-control-plaintext">CLP</div>
            </div>
            <div class="form-group">
                <label for="fecha_recepcion">{{ trans('cruds.cotizacionCompra.fields.fecha_recepcion') }}</label>
                <input class="form-control date {{ $errors->has('fecha_recepcion') ? 'is-invalid' : '' }}" type="text" name="fecha_recepcion" id="fecha_recepcion" value="{{ old('fecha_recepcion') }}">
                @if($errors->has('fecha_recepcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha_recepcion') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label class="required" for="archivo">{{ trans('cruds.cotizacionCompra.fields.archivo') }}</label>
                <input class="form-control {{ $errors->has('archivo') ? 'is-invalid' : '' }}" type="file" name="archivo" id="archivo" required>
                @if($errors->has('archivo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('archivo') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection
