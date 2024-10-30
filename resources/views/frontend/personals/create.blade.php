@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.personal.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.personals.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="nombre">{{ trans('cruds.personal.fields.nombre') }}</label>
                            <input class="form-control" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                            @if($errors->has('nombre'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nombre') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.personal.fields.nombre_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="codigo">{{ trans('cruds.personal.fields.codigo') }}</label>
                            <input class="form-control" type="text" name="codigo" id="codigo" value="{{ old('codigo', '') }}">
                            @if($errors->has('codigo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('codigo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.personal.fields.codigo_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="rut">{{ trans('cruds.personal.fields.rut') }}</label>
                            <input class="form-control" type="text" name="rut" id="rut" value="{{ old('rut', '') }}" required>
                            @if($errors->has('rut'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('rut') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.personal.fields.rut_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="email">{{ trans('cruds.personal.fields.email') }}</label>
                            <input class="form-control" type="text" name="email" id="email" value="{{ old('email', '') }}">
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.personal.fields.email_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="telefono">{{ trans('cruds.personal.fields.telefono') }}</label>
                            <input class="form-control" type="text" name="telefono" id="telefono" value="{{ old('telefono', '') }}">
                            @if($errors->has('telefono'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('telefono') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.personal.fields.telefono_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="cargo_id">{{ trans('cruds.personal.fields.cargo') }}</label>
                            <select class="form-control select2" name="cargo_id" id="cargo_id">
                                @foreach($cargos as $id => $entry)
                                    <option value="{{ $id }}" {{ old('cargo_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('cargo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('cargo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.personal.fields.cargo_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="estado_id">{{ trans('cruds.personal.fields.estado') }}</label>
                            <select class="form-control select2" name="estado_id" id="estado_id" required>
                                @foreach($estados as $id => $entry)
                                    <option value="{{ $id }}" {{ old('estado_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('estado'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('estado') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.personal.fields.estado_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="entidad_id">{{ trans('cruds.personal.fields.entidad') }}</label>
                            <select class="form-control select2" name="entidad_id" id="entidad_id" required>
                                @foreach($entidads as $id => $entry)
                                    <option value="{{ $id }}" {{ old('entidad_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('entidad'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('entidad') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.personal.fields.entidad_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection