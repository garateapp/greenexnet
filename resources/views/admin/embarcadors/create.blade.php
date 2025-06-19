@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.embarcador.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.embarcadors.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="codigo">{{ trans('cruds.embarcador.fields.codigo') }}</label>
                <input class="form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }}" type="text" name="codigo" id="codigo" value="{{ old('codigo', '') }}" required>
                @if($errors->has('codigo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('codigo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarcador.fields.codigo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.embarcador.fields.via') }}</label>
                <select class="form-control {{ $errors->has('via') ? 'is-invalid' : '' }}" name="via" id="via" required>
                    <option value disabled {{ old('via', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Embarcador::VIA_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('via', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('via'))
                    <div class="invalid-feedback">
                        {{ $errors->first('via') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarcador.fields.via_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.embarcador.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarcador.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="rut">{{ trans('cruds.embarcador.fields.rut') }}</label>
                <input class="form-control {{ $errors->has('rut') ? 'is-invalid' : '' }}" type="text" name="rut" id="rut" value="{{ old('rut', '') }}">
                @if($errors->has('rut'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rut') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarcador.fields.rut_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="attn">{{ trans('cruds.embarcador.fields.attn') }}</label>
                <input class="form-control {{ $errors->has('attn') ? 'is-invalid' : '' }}" type="text" name="attn" id="attn" value="{{ old('attn', '') }}" required>
                @if($errors->has('attn'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attn') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarcador.fields.attn_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="email">{{ trans('cruds.embarcador.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" name="email" id="email" value="{{ old('email', '') }}">
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarcador.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="telefono">{{ trans('cruds.embarcador.fields.telefono') }}</label>
                <input class="form-control {{ $errors->has('telefono') ? 'is-invalid' : '' }}" type="text" name="telefono" id="telefono" value="{{ old('telefono', '') }}">
                @if($errors->has('telefono'))
                    <div class="invalid-feedback">
                        {{ $errors->first('telefono') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarcador.fields.telefono_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cc">{{ trans('cruds.embarcador.fields.cc') }}</label>
                <textarea class="form-control {{ $errors->has('cc') ? 'is-invalid' : '' }}" name="cc" id="cc">{{ old('cc') }}</textarea>
                @if($errors->has('cc'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cc') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarcador.fields.cc_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="p_sag_dir">{{ trans('cruds.embarcador.fields.p_sag_dir') }}</label>
                <input class="form-control {{ $errors->has('p_sag_dir') ? 'is-invalid' : '' }}" type="text" name="p_sag_dir" id="p_sag_dir" value="{{ old('p_sag_dir', '') }}" required>
                @if($errors->has('p_sag_dir'))
                    <div class="invalid-feedback">
                        {{ $errors->first('p_sag_dir') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarcador.fields.p_sag_dir_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="g_dir_a">{{ trans('cruds.embarcador.fields.g_dir_a') }}</label>
                <input class="form-control {{ $errors->has('g_dir_a') ? 'is-invalid' : '' }}" type="text" name="g_dir_a" id="g_dir_a" value="{{ old('g_dir_a', '') }}" required>
                @if($errors->has('g_dir_a'))
                    <div class="invalid-feedback">
                        {{ $errors->first('g_dir_a') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarcador.fields.g_dir_a_helper') }}</span>
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