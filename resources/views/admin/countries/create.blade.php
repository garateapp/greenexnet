@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.country.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.countries.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.country.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="short_code">{{ trans('cruds.country.fields.short_code') }}</label>
                <input class="form-control {{ $errors->has('short_code') ? 'is-invalid' : '' }}" type="text" name="short_code" id="short_code" value="{{ old('short_code', '') }}" required>
                @if($errors->has('short_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('short_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.short_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="codigo_sag">{{ trans('cruds.country.fields.codigo_sag') }}</label>
                <input class="form-control {{ $errors->has('codigo_sag') ? 'is-invalid' : '' }}" type="text" name="codigo_sag" id="codigo_sag" value="{{ old('codigo_sag', '') }}">
                @if($errors->has('codigo_sag'))
                    <div class="invalid-feedback">
                        {{ $errors->first('codigo_sag') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.codigo_sag_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cap">{{ trans('cruds.country.fields.cap') }}</label>
                <input class="form-control {{ $errors->has('cap') ? 'is-invalid' : '' }}" type="text" name="cap" id="cap" value="{{ old('cap', '') }}">
                @if($errors->has('cap'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cap') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.cap_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="codigo_facturacion">{{ trans('cruds.country.fields.codigo_facturacion') }}</label>
                <input class="form-control {{ $errors->has('codigo_facturacion') ? 'is-invalid' : '' }}" type="text" name="codigo_facturacion" id="codigo_facturacion" value="{{ old('codigo_facturacion', '') }}">
                @if($errors->has('codigo_facturacion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('codigo_facturacion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.codigo_facturacion_helper') }}</span>
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