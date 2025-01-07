@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.diccionario.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.diccionarios.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="variable">{{ trans('cruds.diccionario.fields.variable') }}</label>
                <input class="form-control {{ $errors->has('variable') ? 'is-invalid' : '' }}" type="text" name="variable" id="variable" value="{{ old('variable', '') }}" required>
                @if($errors->has('variable'))
                    <div class="invalid-feedback">
                        {{ $errors->first('variable') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.diccionario.fields.variable_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="valor">{{ trans('cruds.diccionario.fields.valor') }}</label>
                <input class="form-control {{ $errors->has('valor') ? 'is-invalid' : '' }}" type="text" name="valor" id="valor" value="{{ old('valor', '') }}" required>
                @if($errors->has('valor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('valor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.diccionario.fields.valor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.diccionario.fields.tipo') }}</label>
                <select class="form-control {{ $errors->has('tipo') ? 'is-invalid' : '' }}" name="tipo" id="tipo" required>
                    <option value disabled {{ old('tipo', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Diccionario::TIPO_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('tipo', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('tipo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.diccionario.fields.tipo_helper') }}</span>
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