@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.capturador.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.capturadors.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.capturador.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.capturador.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cliente_id">{{ trans('cruds.capturador.fields.cliente') }}</label>
                <select class="form-control select2 {{ $errors->has('cliente') ? 'is-invalid' : '' }}" name="cliente_id" id="cliente_id">
                    @foreach($clientes as $id => $entry)
                        <option value="{{ $id }}" {{ old('cliente_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('cliente'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cliente') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.capturador.fields.cliente_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="modulo_id">{{ trans('cruds.capturador.fields.modulo') }}</label>
                <select class="form-control select2 {{ $errors->has('modulo') ? 'is-invalid' : '' }}" name="modulo_id" id="modulo_id" required>
                    @foreach($modulos as $id => $entry)
                        <option value="{{ $id }}" {{ old('modulo_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('modulo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('modulo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.capturador.fields.modulo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="funcion_id">{{ trans('cruds.capturador.fields.funcion') }}</label>
                <select class="form-control select2 {{ $errors->has('funcion') ? 'is-invalid' : '' }}" name="funcion_id" id="funcion_id" required>
                    @foreach($funcions as $id => $entry)
                        <option value="{{ $id }}" {{ old('funcion_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('funcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('funcion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.capturador.fields.funcion_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('activo') ? 'is-invalid' : '' }}">
                    <input class="form-check-input" type="checkbox" name="activo" id="activo" value=""  {{ old('activo', 0) == 1 || old('activo') === null ? 'checked' : '' }}>
                    <label class="required form-check-label" for="activo">{{ trans('cruds.capturador.fields.activo') }}</label>
                </div>
                @if($errors->has('activo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('activo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.capturador.fields.activo_helper') }}</span>
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