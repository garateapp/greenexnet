@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.agenteAduana.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.agente-aduanas.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.agenteAduana.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.agenteAduana.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="rut">{{ trans('cruds.agenteAduana.fields.rut') }}</label>
                <input class="form-control {{ $errors->has('rut') ? 'is-invalid' : '' }}" type="text" name="rut" id="rut" value="{{ old('rut', '') }}" required>
                @if($errors->has('rut'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rut') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.agenteAduana.fields.rut_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="codigo">{{ trans('cruds.agenteAduana.fields.codigo') }}</label>
                <input class="form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }}" type="text" name="codigo" id="codigo" value="{{ old('codigo', '') }}">
                @if($errors->has('codigo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('codigo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.agenteAduana.fields.codigo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="direccion">{{ trans('cruds.agenteAduana.fields.direccion') }}</label>
                <input class="form-control {{ $errors->has('direccion') ? 'is-invalid' : '' }}" type="text" name="direccion" id="direccion" value="{{ old('direccion', '') }}" required>
                @if($errors->has('direccion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('direccion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.agenteAduana.fields.direccion_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.agenteAduana.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.agenteAduana.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="telefono">{{ trans('cruds.agenteAduana.fields.telefono') }}</label>
                <input class="form-control {{ $errors->has('telefono') ? 'is-invalid' : '' }}" type="text" name="telefono" id="telefono" value="{{ old('telefono', '') }}">
                @if($errors->has('telefono'))
                    <div class="invalid-feedback">
                        {{ $errors->first('telefono') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.agenteAduana.fields.telefono_helper') }}</span>
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