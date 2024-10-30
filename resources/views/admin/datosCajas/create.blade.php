@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.datosCaja.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.datos-cajas.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="proceso">{{ trans('cruds.datosCaja.fields.proceso') }}</label>
                <input class="form-control {{ $errors->has('proceso') ? 'is-invalid' : '' }}" type="text" name="proceso" id="proceso" value="{{ old('proceso', '') }}" required>
                @if($errors->has('proceso'))
                    <div class="invalid-feedback">
                        {{ $errors->first('proceso') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.proceso_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="fecha_produccion">{{ trans('cruds.datosCaja.fields.fecha_produccion') }}</label>
                <input class="form-control date {{ $errors->has('fecha_produccion') ? 'is-invalid' : '' }}" type="text" name="fecha_produccion" id="fecha_produccion" value="{{ old('fecha_produccion') }}" required>
                @if($errors->has('fecha_produccion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha_produccion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.fecha_produccion_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="turno">{{ trans('cruds.datosCaja.fields.turno') }}</label>
                <input class="form-control {{ $errors->has('turno') ? 'is-invalid' : '' }}" type="text" name="turno" id="turno" value="{{ old('turno', '') }}" required>
                @if($errors->has('turno'))
                    <div class="invalid-feedback">
                        {{ $errors->first('turno') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.turno_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="cod_linea">{{ trans('cruds.datosCaja.fields.cod_linea') }}</label>
                <input class="form-control {{ $errors->has('cod_linea') ? 'is-invalid' : '' }}" type="text" name="cod_linea" id="cod_linea" value="{{ old('cod_linea', '') }}" required>
                @if($errors->has('cod_linea'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cod_linea') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.cod_linea_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cat">{{ trans('cruds.datosCaja.fields.cat') }}</label>
                <input class="form-control {{ $errors->has('cat') ? 'is-invalid' : '' }}" type="text" name="cat" id="cat" value="{{ old('cat', '') }}">
                @if($errors->has('cat'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cat') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.cat_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="variedad_real">{{ trans('cruds.datosCaja.fields.variedad_real') }}</label>
                <input class="form-control {{ $errors->has('variedad_real') ? 'is-invalid' : '' }}" type="text" name="variedad_real" id="variedad_real" value="{{ old('variedad_real', '') }}">
                @if($errors->has('variedad_real'))
                    <div class="invalid-feedback">
                        {{ $errors->first('variedad_real') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.variedad_real_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="variedad_timbrada">{{ trans('cruds.datosCaja.fields.variedad_timbrada') }}</label>
                <input class="form-control {{ $errors->has('variedad_timbrada') ? 'is-invalid' : '' }}" type="text" name="variedad_timbrada" id="variedad_timbrada" value="{{ old('variedad_timbrada', '') }}">
                @if($errors->has('variedad_timbrada'))
                    <div class="invalid-feedback">
                        {{ $errors->first('variedad_timbrada') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.variedad_timbrada_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="salida">{{ trans('cruds.datosCaja.fields.salida') }}</label>
                <input class="form-control {{ $errors->has('salida') ? 'is-invalid' : '' }}" type="text" name="salida" id="salida" value="{{ old('salida', '') }}">
                @if($errors->has('salida'))
                    <div class="invalid-feedback">
                        {{ $errors->first('salida') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.salida_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="marca">{{ trans('cruds.datosCaja.fields.marca') }}</label>
                <input class="form-control {{ $errors->has('marca') ? 'is-invalid' : '' }}" type="text" name="marca" id="marca" value="{{ old('marca', '') }}">
                @if($errors->has('marca'))
                    <div class="invalid-feedback">
                        {{ $errors->first('marca') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.marca_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="productor_real">{{ trans('cruds.datosCaja.fields.productor_real') }}</label>
                <input class="form-control {{ $errors->has('productor_real') ? 'is-invalid' : '' }}" type="text" name="productor_real" id="productor_real" value="{{ old('productor_real', '') }}">
                @if($errors->has('productor_real'))
                    <div class="invalid-feedback">
                        {{ $errors->first('productor_real') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.productor_real_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="especie">{{ trans('cruds.datosCaja.fields.especie') }}</label>
                <input class="form-control {{ $errors->has('especie') ? 'is-invalid' : '' }}" type="text" name="especie" id="especie" value="{{ old('especie', '') }}">
                @if($errors->has('especie'))
                    <div class="invalid-feedback">
                        {{ $errors->first('especie') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.especie_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cod_caja">{{ trans('cruds.datosCaja.fields.cod_caja') }}</label>
                <input class="form-control {{ $errors->has('cod_caja') ? 'is-invalid' : '' }}" type="text" name="cod_caja" id="cod_caja" value="{{ old('cod_caja', '') }}">
                @if($errors->has('cod_caja'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cod_caja') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.cod_caja_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cod_confeccion">{{ trans('cruds.datosCaja.fields.cod_confeccion') }}</label>
                <input class="form-control {{ $errors->has('cod_confeccion') ? 'is-invalid' : '' }}" type="text" name="cod_confeccion" id="cod_confeccion" value="{{ old('cod_confeccion', '') }}">
                @if($errors->has('cod_confeccion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cod_confeccion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.cod_confeccion_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="calibre_timbrado">{{ trans('cruds.datosCaja.fields.calibre_timbrado') }}</label>
                <input class="form-control {{ $errors->has('calibre_timbrado') ? 'is-invalid' : '' }}" type="text" name="calibre_timbrado" id="calibre_timbrado" value="{{ old('calibre_timbrado', '') }}">
                @if($errors->has('calibre_timbrado'))
                    <div class="invalid-feedback">
                        {{ $errors->first('calibre_timbrado') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.calibre_timbrado_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="peso_timbrado">{{ trans('cruds.datosCaja.fields.peso_timbrado') }}</label>
                <input class="form-control {{ $errors->has('peso_timbrado') ? 'is-invalid' : '' }}" type="text" name="peso_timbrado" id="peso_timbrado" value="{{ old('peso_timbrado', '') }}">
                @if($errors->has('peso_timbrado'))
                    <div class="invalid-feedback">
                        {{ $errors->first('peso_timbrado') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.peso_timbrado_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="lote">{{ trans('cruds.datosCaja.fields.lote') }}</label>
                <input class="form-control {{ $errors->has('lote') ? 'is-invalid' : '' }}" type="text" name="lote" id="lote" value="{{ old('lote', '') }}">
                @if($errors->has('lote'))
                    <div class="invalid-feedback">
                        {{ $errors->first('lote') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.lote_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nuevo_lote">{{ trans('cruds.datosCaja.fields.nuevo_lote') }}</label>
                <input class="form-control {{ $errors->has('nuevo_lote') ? 'is-invalid' : '' }}" type="text" name="nuevo_lote" id="nuevo_lote" value="{{ old('nuevo_lote', '') }}">
                @if($errors->has('nuevo_lote'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nuevo_lote') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.nuevo_lote_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="codigo_qr">{{ trans('cruds.datosCaja.fields.codigo_qr') }}</label>
                <input class="form-control {{ $errors->has('codigo_qr') ? 'is-invalid' : '' }}" type="text" name="codigo_qr" id="codigo_qr" value="{{ old('codigo_qr', '') }}">
                @if($errors->has('codigo_qr'))
                    <div class="invalid-feedback">
                        {{ $errors->first('codigo_qr') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.datosCaja.fields.codigo_qr_helper') }}</span>
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