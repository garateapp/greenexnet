@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.politicaCotizacion.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.politica-cotizaciones.update", [$politicaCotizacion->id]) }}">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="monto_min">{{ trans('cruds.politicaCotizacion.fields.monto_min') }}</label>
                <input class="form-control {{ $errors->has('monto_min') ? 'is-invalid' : '' }}" type="number" name="monto_min" id="monto_min" value="{{ old('monto_min', $politicaCotizacion->monto_min) }}" step="0.01" required>
                @if($errors->has('monto_min'))
                    <div class="invalid-feedback">
                        {{ $errors->first('monto_min') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.politicaCotizacion.fields.monto_min_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="monto_max">{{ trans('cruds.politicaCotizacion.fields.monto_max') }}</label>
                <input class="form-control {{ $errors->has('monto_max') ? 'is-invalid' : '' }}" type="number" name="monto_max" id="monto_max" value="{{ old('monto_max', $politicaCotizacion->monto_max) }}" step="0.01">
                @if($errors->has('monto_max'))
                    <div class="invalid-feedback">
                        {{ $errors->first('monto_max') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.politicaCotizacion.fields.monto_max_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="cotizaciones_requeridas">{{ trans('cruds.politicaCotizacion.fields.cotizaciones_requeridas') }}</label>
                <input class="form-control {{ $errors->has('cotizaciones_requeridas') ? 'is-invalid' : '' }}" type="number" name="cotizaciones_requeridas" id="cotizaciones_requeridas" value="{{ old('cotizaciones_requeridas', $politicaCotizacion->cotizaciones_requeridas) }}" min="1" max="3" required>
                @if($errors->has('cotizaciones_requeridas'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cotizaciones_requeridas') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.politicaCotizacion.fields.cotizaciones_requeridas_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('activo') ? 'is-invalid' : '' }}">
                    <input class="form-check-input" type="checkbox" name="activo" id="activo" value="1" {{ (old('activo', $politicaCotizacion->activo) == 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="activo">{{ trans('cruds.politicaCotizacion.fields.activo') }}</label>
                </div>
                @if($errors->has('activo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('activo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.politicaCotizacion.fields.activo_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
