@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.liquidacionesCx.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.liquidaciones-cxes.update", [$liquidacionesCx->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="contenedor">{{ trans('cruds.liquidacionesCx.fields.contenedor') }}</label>
                <input class="form-control {{ $errors->has('contenedor') ? 'is-invalid' : '' }}" type="text" name="contenedor" id="contenedor" value="{{ old('contenedor', $liquidacionesCx->contenedor) }}">
                @if($errors->has('contenedor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('contenedor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liquidacionesCx.fields.contenedor_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="eta">{{ trans('cruds.liquidacionesCx.fields.eta') }}</label>
                <input class="form-control date {{ $errors->has('eta') ? 'is-invalid' : '' }}" type="text" name="eta" id="eta" value="{{ old('eta', $liquidacionesCx->eta) }}">
                @if($errors->has('eta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('eta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liquidacionesCx.fields.eta_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="variedad_id">{{ trans('cruds.liquidacionesCx.fields.variedad') }}</label>
                <select class="form-control select2 {{ $errors->has('variedad') ? 'is-invalid' : '' }}" name="variedad_id" id="variedad_id" required>
                    @foreach($variedads as $id => $entry)
                        <option value="{{ $id }}" {{ (old('variedad_id') ? old('variedad_id') : $liquidacionesCx->variedad->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('variedad'))
                    <div class="invalid-feedback">
                        {{ $errors->first('variedad') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liquidacionesCx.fields.variedad_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="pallet">{{ trans('cruds.liquidacionesCx.fields.pallet') }}</label>
                <input class="form-control {{ $errors->has('pallet') ? 'is-invalid' : '' }}" type="text" name="pallet" id="pallet" value="{{ old('pallet', $liquidacionesCx->pallet) }}">
                @if($errors->has('pallet'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pallet') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liquidacionesCx.fields.pallet_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="etiqueta_id">{{ trans('cruds.liquidacionesCx.fields.etiqueta') }}</label>
                <select class="form-control select2 {{ $errors->has('etiqueta') ? 'is-invalid' : '' }}" name="etiqueta_id" id="etiqueta_id">
                    @foreach($etiquetas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('etiqueta_id') ? old('etiqueta_id') : $liquidacionesCx->etiqueta->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('etiqueta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('etiqueta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liquidacionesCx.fields.etiqueta_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="calibre">{{ trans('cruds.liquidacionesCx.fields.calibre') }}</label>
                <input class="form-control {{ $errors->has('calibre') ? 'is-invalid' : '' }}" type="text" name="calibre" id="calibre" value="{{ old('calibre', $liquidacionesCx->calibre) }}">
                @if($errors->has('calibre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('calibre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liquidacionesCx.fields.calibre_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="embalaje_id">{{ trans('cruds.liquidacionesCx.fields.embalaje') }}</label>
                <select class="form-control select2 {{ $errors->has('embalaje') ? 'is-invalid' : '' }}" name="embalaje_id" id="embalaje_id">
                    @foreach($embalajes as $id => $entry)
                        <option value="{{ $id }}" {{ (old('embalaje_id') ? old('embalaje_id') : $liquidacionesCx->embalaje->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('embalaje'))
                    <div class="invalid-feedback">
                        {{ $errors->first('embalaje') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liquidacionesCx.fields.embalaje_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="cantidad">{{ trans('cruds.liquidacionesCx.fields.cantidad') }}</label>
                <input class="form-control {{ $errors->has('cantidad') ? 'is-invalid' : '' }}" type="number" name="cantidad" id="cantidad" value="{{ old('cantidad', $liquidacionesCx->cantidad) }}" step="1" required>
                @if($errors->has('cantidad'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cantidad') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liquidacionesCx.fields.cantidad_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fecha_venta">{{ trans('cruds.liquidacionesCx.fields.fecha_venta') }}</label>
                <input class="form-control date {{ $errors->has('fecha_venta') ? 'is-invalid' : '' }}" type="text" name="fecha_venta" id="fecha_venta" value="{{ old('fecha_venta', $liquidacionesCx->fecha_venta) }}">
                @if($errors->has('fecha_venta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha_venta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liquidacionesCx.fields.fecha_venta_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ventas">{{ trans('cruds.liquidacionesCx.fields.ventas') }}</label>
                <input class="form-control {{ $errors->has('ventas') ? 'is-invalid' : '' }}" type="number" name="ventas" id="ventas" value="{{ old('ventas', $liquidacionesCx->ventas) }}" step="1">
                @if($errors->has('ventas'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ventas') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liquidacionesCx.fields.ventas_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="precio_unitario">{{ trans('cruds.liquidacionesCx.fields.precio_unitario') }}</label>
                <input class="form-control {{ $errors->has('precio_unitario') ? 'is-invalid' : '' }}" type="number" name="precio_unitario" id="precio_unitario" value="{{ old('precio_unitario', $liquidacionesCx->precio_unitario) }}" step="0.01">
                @if($errors->has('precio_unitario'))
                    <div class="invalid-feedback">
                        {{ $errors->first('precio_unitario') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liquidacionesCx.fields.precio_unitario_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="monto_rmb">{{ trans('cruds.liquidacionesCx.fields.monto_rmb') }}</label>
                <input class="form-control {{ $errors->has('monto_rmb') ? 'is-invalid' : '' }}" type="number" name="monto_rmb" id="monto_rmb" value="{{ old('monto_rmb', $liquidacionesCx->monto_rmb) }}" step="0.01">
                @if($errors->has('monto_rmb'))
                    <div class="invalid-feedback">
                        {{ $errors->first('monto_rmb') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liquidacionesCx.fields.monto_rmb_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="observaciones">{{ trans('cruds.liquidacionesCx.fields.observaciones') }}</label>
                <textarea class="form-control {{ $errors->has('observaciones') ? 'is-invalid' : '' }}" name="observaciones" id="observaciones">{{ old('observaciones', $liquidacionesCx->observaciones) }}</textarea>
                @if($errors->has('observaciones'))
                    <div class="invalid-feedback">
                        {{ $errors->first('observaciones') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liquidacionesCx.fields.observaciones_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="liqcabecera_id">{{ trans('cruds.liquidacionesCx.fields.liqcabecera') }}</label>
                <select class="form-control select2 {{ $errors->has('liqcabecera') ? 'is-invalid' : '' }}" name="liqcabecera_id" id="liqcabecera_id" required>
                    @foreach($liqcabeceras as $id => $entry)
                        <option value="{{ $id }}" {{ (old('liqcabecera_id') ? old('liqcabecera_id') : $liquidacionesCx->liqcabecera->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('liqcabecera'))
                    <div class="invalid-feedback">
                        {{ $errors->first('liqcabecera') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liquidacionesCx.fields.liqcabecera_helper') }}</span>
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