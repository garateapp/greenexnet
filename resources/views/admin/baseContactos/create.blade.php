@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.baseContacto.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.base-contactos.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="cliente_id">{{ trans('cruds.baseContacto.fields.cliente') }}</label>
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
                <span class="help-block">{{ trans('cruds.baseContacto.fields.cliente_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.baseContacto.fields.tipo_transporte') }}</label>
                <select class="form-control {{ $errors->has('tipo_transporte') ? 'is-invalid' : '' }}" name="tipo_transporte" id="tipo_transporte">
                    <option value disabled {{ old('tipo_transporte', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\BaseContacto::TIPO_TRANSPORTE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('tipo_transporte', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('tipo_transporte'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo_transporte') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.baseContacto.fields.tipo_transporte_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="rut_recibidor">{{ trans('cruds.baseContacto.fields.rut_recibidor') }}</label>
                <input class="form-control {{ $errors->has('rut_recibidor') ? 'is-invalid' : '' }}" type="text" name="rut_recibidor" id="rut_recibidor" value="{{ old('rut_recibidor', '') }}">
                @if($errors->has('rut_recibidor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rut_recibidor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.baseContacto.fields.rut_recibidor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="direccion">{{ trans('cruds.baseContacto.fields.direccion') }}</label>
                <input class="form-control {{ $errors->has('direccion') ? 'is-invalid' : '' }}" type="text" name="direccion" id="direccion" value="{{ old('direccion', '') }}" required>
                @if($errors->has('direccion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('direccion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.baseContacto.fields.direccion_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="contacto">{{ trans('cruds.baseContacto.fields.contacto') }}</label>
                <input class="form-control {{ $errors->has('contacto') ? 'is-invalid' : '' }}" type="text" name="contacto" id="contacto" value="{{ old('contacto', '') }}" required>
                @if($errors->has('contacto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('contacto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.baseContacto.fields.contacto_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="telefono">{{ trans('cruds.baseContacto.fields.telefono') }}</label>
                <input class="form-control {{ $errors->has('telefono') ? 'is-invalid' : '' }}" type="text" name="telefono" id="telefono" value="{{ old('telefono', '') }}">
                @if($errors->has('telefono'))
                    <div class="invalid-feedback">
                        {{ $errors->first('telefono') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.baseContacto.fields.telefono_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fax">{{ trans('cruds.baseContacto.fields.fax') }}</label>
                <input class="form-control {{ $errors->has('fax') ? 'is-invalid' : '' }}" type="text" name="fax" id="fax" value="{{ old('fax', '') }}">
                @if($errors->has('fax'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fax') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.baseContacto.fields.fax_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="email">{{ trans('cruds.baseContacto.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}">
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.baseContacto.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="notify">{{ trans('cruds.baseContacto.fields.notify') }}</label>
                <input class="form-control {{ $errors->has('notify') ? 'is-invalid' : '' }}" type="text" name="notify" id="notify" value="{{ old('notify', '') }}">
                @if($errors->has('notify'))
                    <div class="invalid-feedback">
                        {{ $errors->first('notify') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.baseContacto.fields.notify_helper') }}</span>
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