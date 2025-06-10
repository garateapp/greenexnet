@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.proceso.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.procesos.update", [$proceso->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="productor_id">{{ trans('cruds.proceso.fields.productor') }}</label>
                <select class="form-control select2 {{ $errors->has('productor') ? 'is-invalid' : '' }}" name="productor_id" id="productor_id" required>
                    @foreach($productors as $id => $entry)
                        <option value="{{ $id }}" {{ (old('productor_id') ? old('productor_id') : $proceso->productor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('productor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('productor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.productor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="fecha_proceso">{{ trans('cruds.proceso.fields.fecha_proceso') }}</label>
                <input class="form-control date {{ $errors->has('fecha_proceso') ? 'is-invalid' : '' }}" type="text" name="fecha_proceso" id="fecha_proceso" value="{{ old('fecha_proceso', $proceso->fecha_proceso) }}" required>
                @if($errors->has('fecha_proceso'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha_proceso') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.fecha_proceso_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="variedad">{{ trans('cruds.proceso.fields.variedad') }}</label>
                <input class="form-control {{ $errors->has('variedad') ? 'is-invalid' : '' }}" type="text" name="variedad" id="variedad" value="{{ old('variedad', $proceso->variedad) }}" required>
                @if($errors->has('variedad'))
                    <div class="invalid-feedback">
                        {{ $errors->first('variedad') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.variedad_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="categoria">{{ trans('cruds.proceso.fields.categoria') }}</label>
                <input class="form-control {{ $errors->has('categoria') ? 'is-invalid' : '' }}" type="text" name="categoria" id="categoria" value="{{ old('categoria', $proceso->categoria) }}" required>
                @if($errors->has('categoria'))
                    <div class="invalid-feedback">
                        {{ $errors->first('categoria') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.categoria_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="etiqueta">{{ trans('cruds.proceso.fields.etiqueta') }}</label>
                <input class="form-control {{ $errors->has('etiqueta') ? 'is-invalid' : '' }}" type="text" name="etiqueta" id="etiqueta" value="{{ old('etiqueta', $proceso->etiqueta) }}">
                @if($errors->has('etiqueta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('etiqueta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.etiqueta_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="calibre">{{ trans('cruds.proceso.fields.calibre') }}</label>
                <input class="form-control {{ $errors->has('calibre') ? 'is-invalid' : '' }}" type="text" name="calibre" id="calibre" value="{{ old('calibre', $proceso->calibre) }}" required>
                @if($errors->has('calibre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('calibre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.calibre_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="color">{{ trans('cruds.proceso.fields.color') }}</label>
                <input class="form-control {{ $errors->has('color') ? 'is-invalid' : '' }}" type="text" name="color" id="color" value="{{ old('color', $proceso->color) }}">
                @if($errors->has('color'))
                    <div class="invalid-feedback">
                        {{ $errors->first('color') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.color_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="total_kilos">{{ trans('cruds.proceso.fields.total_kilos') }}</label>
                <input class="form-control {{ $errors->has('total_kilos') ? 'is-invalid' : '' }}" type="number" name="total_kilos" id="total_kilos" value="{{ old('total_kilos', $proceso->total_kilos) }}" step="0.01" required>
                @if($errors->has('total_kilos'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_kilos') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.total_kilos_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="etd_week">{{ trans('cruds.proceso.fields.etd_week') }}</label>
                <input class="form-control {{ $errors->has('etd_week') ? 'is-invalid' : '' }}" type="number" name="etd_week" id="etd_week" value="{{ old('etd_week', $proceso->etd_week) }}" step="1">
                @if($errors->has('etd_week'))
                    <div class="invalid-feedback">
                        {{ $errors->first('etd_week') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.etd_week_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="eta_week">{{ trans('cruds.proceso.fields.eta_week') }}</label>
                <input class="form-control {{ $errors->has('eta_week') ? 'is-invalid' : '' }}" type="number" name="eta_week" id="eta_week" value="{{ old('eta_week', $proceso->eta_week) }}" step="1" required>
                @if($errors->has('eta_week'))
                    <div class="invalid-feedback">
                        {{ $errors->first('eta_week') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.eta_week_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="resultado_kilo">{{ trans('cruds.proceso.fields.resultado_kilo') }}</label>
                <input class="form-control {{ $errors->has('resultado_kilo') ? 'is-invalid' : '' }}" type="text" name="resultado_kilo" id="resultado_kilo" value="{{ old('resultado_kilo', $proceso->resultado_kilo) }}">
                @if($errors->has('resultado_kilo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('resultado_kilo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.resultado_kilo_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="resultado_total">{{ trans('cruds.proceso.fields.resultado_total') }}</label>
                <input class="form-control {{ $errors->has('resultado_total') ? 'is-invalid' : '' }}" type="number" name="resultado_total" id="resultado_total" value="{{ old('resultado_total', $proceso->resultado_total) }}" step="0.01">
                @if($errors->has('resultado_total'))
                    <div class="invalid-feedback">
                        {{ $errors->first('resultado_total') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.resultado_total_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="precio_comercial">{{ trans('cruds.proceso.fields.precio_comercial') }}</label>
                <input class="form-control {{ $errors->has('precio_comercial') ? 'is-invalid' : '' }}" type="number" name="precio_comercial" id="precio_comercial" value="{{ old('precio_comercial', $proceso->precio_comercial) }}" step="0.01">
                @if($errors->has('precio_comercial'))
                    <div class="invalid-feedback">
                        {{ $errors->first('precio_comercial') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.precio_comercial_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="total_comercial">{{ trans('cruds.proceso.fields.total_comercial') }}</label>
                <input class="form-control {{ $errors->has('total_comercial') ? 'is-invalid' : '' }}" type="number" name="total_comercial" id="total_comercial" value="{{ old('total_comercial', $proceso->total_comercial) }}" step="0.01">
                @if($errors->has('total_comercial'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_comercial') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.total_comercial_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="costo_comercial">{{ trans('cruds.proceso.fields.costo_comercial') }}</label>
                <input class="form-control {{ $errors->has('costo_comercial') ? 'is-invalid' : '' }}" type="number" name="costo_comercial" id="costo_comercial" value="{{ old('costo_comercial', $proceso->costo_comercial) }}" step="0.01">
                @if($errors->has('costo_comercial'))
                    <div class="invalid-feedback">
                        {{ $errors->first('costo_comercial') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proceso.fields.costo_comercial_helper') }}</span>
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