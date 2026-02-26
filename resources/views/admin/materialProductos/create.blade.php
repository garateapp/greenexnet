@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.materialProducto.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.material-productos.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="embalaje_id">{{ trans('cruds.materialProducto.fields.embalaje') }}</label>
                <select class="form-control select2 {{ $errors->has('embalaje') ? 'is-invalid' : '' }}" name="embalaje_id" id="embalaje_id" required>
                    @foreach($embalajes as $id => $entry)
                        <option value="{{ $id }}" {{ old('embalaje_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                        <option value="{{ $id }}" {{ old('material_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                <input class="form-control {{ $errors->has('unidadxcaja') ? 'is-invalid' : '' }}" type="number" name="unidadxcaja" id="unidadxcaja" value="{{ old('unidadxcaja', '') }}" step="0.01" required>
                @if($errors->has('unidadxcaja'))
                    <div class="invalid-feedback">
                        {{ $errors->first('unidadxcaja') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.materialProducto.fields.unidadxcaja_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="unidadxpallet">{{ trans('cruds.materialProducto.fields.unidadxpallet') }}</label>
                <input class="form-control {{ $errors->has('unidadxpallet') ? 'is-invalid' : '' }}" type="number" name="unidadxpallet" id="unidadxpallet" value="{{ old('unidadxpallet', '') }}" step="0.01" required>
                @if($errors->has('unidadxpallet'))
                    <div class="invalid-feedback">
                        {{ $errors->first('unidadxpallet') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.materialProducto.fields.unidadxpallet_helper') }}</span>
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
