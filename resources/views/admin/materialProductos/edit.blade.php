@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.materialProducto.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.material-productos.update", [$materialProducto->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="embalaje_id">{{ trans('cruds.materialProducto.fields.embalaje') }}</label>
                <select class="form-control select2 {{ $errors->has('embalaje') ? 'is-invalid' : '' }}" name="embalaje_id" id="embalaje_id" required>
                    @foreach($embalajes as $id => $entry)
                        <option value="{{ $id }}" {{ (old('embalaje_id') ? old('embalaje_id') : $materialProducto->embalaje->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('embalaje'))
                    <div class="invalid-feedback">
                        {{ $errors->first('embalaje') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.materialProducto.fields.embalaje_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="material_id">{{ trans('cruds.materialProducto.fields.material') }}</label>
                <select class="form-control select2 {{ $errors->has('material') ? 'is-invalid' : '' }}" name="material_id" id="material_id" required>
                    @foreach($materials as $id => $entry)
                        <option value="{{ $id }}" {{ (old('material_id') ? old('material_id') : $materialProducto->material->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('material'))
                    <div class="invalid-feedback">
                        {{ $errors->first('material') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.materialProducto.fields.material_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="unidadxcaja">{{ trans('cruds.materialProducto.fields.unidadxcaja') }}</label>
                <input class="form-control {{ $errors->has('unidadxcaja') ? 'is-invalid' : '' }}" type="number" name="unidadxcaja" id="unidadxcaja" value="{{ old('unidadxcaja', $materialProducto->unidadxcaja) }}" step="0.01" required>
                @if($errors->has('unidadxcaja'))
                    <div class="invalid-feedback">
                        {{ $errors->first('unidadxcaja') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.materialProducto.fields.unidadxcaja_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="unidadxpallet">{{ trans('cruds.materialProducto.fields.unidadxpallet') }}</label>
                <input class="form-control {{ $errors->has('unidadxpallet') ? 'is-invalid' : '' }}" type="number" name="unidadxpallet" id="unidadxpallet" value="{{ old('unidadxpallet', $materialProducto->unidadxpallet) }}" step="0.01" required>
                @if($errors->has('unidadxpallet'))
                    <div class="invalid-feedback">
                        {{ $errors->first('unidadxpallet') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.materialProducto.fields.unidadxpallet_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="costoxcajaclp">{{ trans('cruds.materialProducto.fields.costoxcajaclp') }}</label>
                <input class="form-control {{ $errors->has('costoxcajaclp') ? 'is-invalid' : '' }}" type="number" name="costoxcajaclp" id="costoxcajaclp" value="{{ old('costoxcajaclp', $materialProducto->costoxcajaclp) }}" step="0.01" required>
                @if($errors->has('costoxcajaclp'))
                    <div class="invalid-feedback">
                        {{ $errors->first('costoxcajaclp') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.materialProducto.fields.costoxcajaclp_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="costoxpallet_clp">{{ trans('cruds.materialProducto.fields.costoxpallet_clp') }}</label>
                <input class="form-control {{ $errors->has('costoxpallet_clp') ? 'is-invalid' : '' }}" type="number" name="costoxpallet_clp" id="costoxpallet_clp" value="{{ old('costoxpallet_clp', $materialProducto->costoxpallet_clp) }}" step="0.01" required>
                @if($errors->has('costoxpallet_clp'))
                    <div class="invalid-feedback">
                        {{ $errors->first('costoxpallet_clp') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.materialProducto.fields.costoxpallet_clp_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="costoxcaja_usd">{{ trans('cruds.materialProducto.fields.costoxcaja_usd') }}</label>
                <input class="form-control {{ $errors->has('costoxcaja_usd') ? 'is-invalid' : '' }}" type="number" name="costoxcaja_usd" id="costoxcaja_usd" value="{{ old('costoxcaja_usd', $materialProducto->costoxcaja_usd) }}" step="0.01" required>
                @if($errors->has('costoxcaja_usd'))
                    <div class="invalid-feedback">
                        {{ $errors->first('costoxcaja_usd') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.materialProducto.fields.costoxcaja_usd_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="costoxpallet_usd">{{ trans('cruds.materialProducto.fields.costoxpallet_usd') }}</label>
                <input class="form-control {{ $errors->has('costoxpallet_usd') ? 'is-invalid' : '' }}" type="text" name="costoxpallet_usd" id="costoxpallet_usd" value="{{ old('costoxpallet_usd', $materialProducto->costoxpallet_usd) }}" required>
                @if($errors->has('costoxpallet_usd'))
                    <div class="invalid-feedback">
                        {{ $errors->first('costoxpallet_usd') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.materialProducto.fields.costoxpallet_usd_helper') }}</span>
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