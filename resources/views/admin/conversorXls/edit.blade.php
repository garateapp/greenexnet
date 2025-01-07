@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.conversorXl.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.conversor-xls.update", [$conversorXl->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="cliente_id">{{ trans('cruds.conversorXl.fields.cliente') }}</label>
                <select class="form-control select2 {{ $errors->has('cliente') ? 'is-invalid' : '' }}" name="cliente_id" id="cliente_id">
                    @foreach($clientes as $id => $entry)
                        <option value="{{ $id }}" {{ (old('cliente_id') ? old('cliente_id') : $conversorXl->cliente->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('cliente'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cliente') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.conversorXl.fields.cliente_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="modulo_id">{{ trans('cruds.conversorXl.fields.modulo') }}</label>
                <select class="form-control select2 {{ $errors->has('modulo') ? 'is-invalid' : '' }}" name="modulo_id" id="modulo_id" required>
                    @foreach($modulos as $id => $entry)
                        <option value="{{ $id }}" {{ (old('modulo_id') ? old('modulo_id') : $conversorXl->modulo->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('modulo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('modulo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.conversorXl.fields.modulo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="tipo_id">{{ trans('cruds.conversorXl.fields.tipo') }}</label>
                <select class="form-control select2 {{ $errors->has('tipo') ? 'is-invalid' : '' }}" name="tipo_id" id="tipo_id" required>
                    @foreach($tipos as $id => $entry)
                        <option value="{{ $id }}" {{ (old('tipo_id') ? old('tipo_id') : $conversorXl->tipo->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('tipo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.conversorXl.fields.tipo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="propiedad">{{ trans('cruds.conversorXl.fields.propiedad') }}</label>
                <input class="form-control {{ $errors->has('propiedad') ? 'is-invalid' : '' }}" type="text" name="propiedad" id="propiedad" value="{{ old('propiedad', $conversorXl->propiedad) }}" required>
                @if($errors->has('propiedad'))
                    <div class="invalid-feedback">
                        {{ $errors->first('propiedad') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.conversorXl.fields.propiedad_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="coordenada">{{ trans('cruds.conversorXl.fields.coordenada') }}</label>
                <input class="form-control {{ $errors->has('coordenada') ? 'is-invalid' : '' }}" type="text" name="coordenada" id="coordenada" value="{{ old('coordenada', $conversorXl->coordenada) }}" required>
                @if($errors->has('coordenada'))
                    <div class="invalid-feedback">
                        {{ $errors->first('coordenada') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.conversorXl.fields.coordenada_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="orden">{{ trans('cruds.conversorXl.fields.orden') }}</label>
                <input class="form-control {{ $errors->has('orden') ? 'is-invalid' : '' }}" type="number" name="orden" id="orden" value="{{ old('orden', $conversorXl->orden) }}" step="1" required>
                @if($errors->has('orden'))
                    <div class="invalid-feedback">
                        {{ $errors->first('orden') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.conversorXl.fields.orden_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('visible') ? 'is-invalid' : '' }}">
                    <input class="form-check-input" type="checkbox" name="visible" id="visible" value="1" {{ $conversorXl->visible || old('visible', 0) === 1 ? 'checked' : '' }} required>
                    <label class="required form-check-label" for="visible">{{ trans('cruds.conversorXl.fields.visible') }}</label>
                </div>
                @if($errors->has('visible'))
                    <div class="invalid-feedback">
                        {{ $errors->first('visible') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.conversorXl.fields.visible_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="formula">{{ trans('cruds.conversorXl.fields.formula') }}</label>
                <input class="form-control {{ $errors->has('formula') ? 'is-invalid' : '' }}" type="text" name="formula" id="formula" value="{{ old('formula', $conversorXl->formula) }}">
                @if($errors->has('formula'))
                    <div class="invalid-feedback">
                        {{ $errors->first('formula') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.conversorXl.fields.formula_helper') }}</span>
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