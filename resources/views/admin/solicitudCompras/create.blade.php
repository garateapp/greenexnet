@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.solicitudCompra.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.solicitud-compras.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="titulo">{{ trans('cruds.solicitudCompra.fields.titulo') }}</label>
                <input class="form-control {{ $errors->has('titulo') ? 'is-invalid' : '' }}" type="text" name="titulo" id="titulo" value="{{ old('titulo', '') }}" required>
                @if($errors->has('titulo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('titulo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.solicitudCompra.fields.titulo_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="descripcion">{{ trans('cruds.solicitudCompra.fields.descripcion') }}</label>
                <textarea class="form-control {{ $errors->has('descripcion') ? 'is-invalid' : '' }}" name="descripcion" id="descripcion">{{ old('descripcion') }}</textarea>
                @if($errors->has('descripcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('descripcion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.solicitudCompra.fields.descripcion_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="monto_estimado">{{ trans('cruds.solicitudCompra.fields.monto_estimado') }}</label>
                <input class="form-control {{ $errors->has('monto_estimado') ? 'is-invalid' : '' }}" type="number" name="monto_estimado" id="monto_estimado" value="{{ old('monto_estimado', '') }}" step="0.01" required>
                @if($errors->has('monto_estimado'))
                    <div class="invalid-feedback">
                        {{ $errors->first('monto_estimado') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.solicitudCompra.fields.monto_estimado_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.solicitudCompra.fields.moneda') }}</label>
                <div class="form-control-plaintext">CLP</div>
            </div>
            <div class="form-group">
                <label for="fecha_requerida">{{ trans('cruds.solicitudCompra.fields.fecha_requerida') }}</label>
                <input class="form-control date {{ $errors->has('fecha_requerida') ? 'is-invalid' : '' }}" type="text" name="fecha_requerida" id="fecha_requerida" value="{{ old('fecha_requerida') }}">
                @if($errors->has('fecha_requerida'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha_requerida') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.solicitudCompra.fields.fecha_requerida_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="alert alert-info">
                    {{ trans('cruds.solicitudCompra.fields.cotizaciones_requeridas_helper') }}
                </div>
            </div>
            @if(config('panel.adquisiciones_puede_subir_cotizaciones'))
                <div class="form-group">
                    <div class="form-check {{ $errors->has('cotizaciones_por_adquisiciones') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="checkbox" name="cotizaciones_por_adquisiciones" id="cotizaciones_por_adquisiciones" value="1" {{ old('cotizaciones_por_adquisiciones', 0) ? 'checked' : '' }}>
                        <label class="form-check-label" for="cotizaciones_por_adquisiciones">
                            {{ trans('cruds.solicitudCompra.fields.cotizaciones_por_adquisiciones') }}
                        </label>
                    </div>
                    @if($errors->has('cotizaciones_por_adquisiciones'))
                        <div class="invalid-feedback">
                            {{ $errors->first('cotizaciones_por_adquisiciones') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.solicitudCompra.fields.cotizaciones_por_adquisiciones_helper') }}</span>
                </div>
            @else
                <input type="hidden" name="cotizaciones_por_adquisiciones" value="0">
            @endif
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
