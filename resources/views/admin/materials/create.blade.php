@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.material.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.materials.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="codigo">{{ trans('cruds.material.fields.codigo') }}</label>
                <input class="form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }}" type="text" name="codigo" id="codigo" value="{{ old('codigo', '') }}" required>
                @if($errors->has('codigo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('codigo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.material.fields.codigo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.material.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.material.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.material.fields.unidad') }}</label>
                <select class="form-control {{ $errors->has('unidad') ? 'is-invalid' : '' }}" name="unidad" id="unidad">
                    <option value disabled {{ old('unidad', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Material::UNIDAD_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('unidad', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('unidad'))
                    <div class="invalid-feedback">
                        {{ $errors->first('unidad') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.material.fields.unidad_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="costo_ult_oc">{{ trans('cruds.material.fields.costo_ult_oc') }}</label>
                <input class="form-control {{ $errors->has('costo_ult_oc') ? 'is-invalid' : '' }}" type="number" name="costo_ult_oc" id="costo_ult_oc" value="{{ old('costo_ult_oc', '') }}" step="0.01">
                @if($errors->has('costo_ult_oc'))
                    <div class="invalid-feedback">
                        {{ $errors->first('costo_ult_oc') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.material.fields.costo_ult_oc_helper') }}</span>
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