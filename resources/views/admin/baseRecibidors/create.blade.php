@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.baseRecibidor.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.base-recibidors.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="cliente_id">{{ trans('cruds.baseRecibidor.fields.cliente') }}</label>
                <select class="form-control select2 {{ $errors->has('cliente') ? 'is-invalid' : '' }}" name="cliente_id" id="cliente_id" required>
                    @foreach($clientes as $id => $entry)
                        <option value="{{ $id }}" {{ old('cliente_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('cliente'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cliente') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.baseRecibidor.fields.cliente_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="codigo">{{ trans('cruds.baseRecibidor.fields.codigo') }}</label>
                <input class="form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }}" type="text" name="codigo" id="codigo" value="{{ old('codigo', '') }}" required>
                @if($errors->has('codigo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('codigo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.baseRecibidor.fields.codigo_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="rut_sistema">{{ trans('cruds.baseRecibidor.fields.rut_sistema') }}</label>
                <input class="form-control {{ $errors->has('rut_sistema') ? 'is-invalid' : '' }}" type="text" name="rut_sistema" id="rut_sistema" value="{{ old('rut_sistema', '') }}">
                @if($errors->has('rut_sistema'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rut_sistema') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.baseRecibidor.fields.rut_sistema_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.baseRecibidor.fields.estado') }}</label>
                @foreach(App\Models\BaseRecibidor::ESTADO_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('estado') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="estado_{{ $key }}" name="estado" value="{{ $key }}" {{ old('estado', '') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="estado_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('estado'))
                    <div class="invalid-feedback">
                        {{ $errors->first('estado') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.baseRecibidor.fields.estado_helper') }}</span>
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