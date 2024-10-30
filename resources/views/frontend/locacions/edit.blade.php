@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.locacion.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.locacions.update", [$locacion->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="nombre">{{ trans('cruds.locacion.fields.nombre') }}</label>
                            <input class="form-control" type="text" name="nombre" id="nombre" value="{{ old('nombre', $locacion->nombre) }}">
                            @if($errors->has('nombre'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nombre') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.locacion.fields.nombre_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="area_id">{{ trans('cruds.locacion.fields.area') }}</label>
                            <select class="form-control select2" name="area_id" id="area_id">
                                @foreach($areas as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('area_id') ? old('area_id') : $locacion->area->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('area'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('area') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.locacion.fields.area_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="cantidad_personal">{{ trans('cruds.locacion.fields.cantidad_personal') }}</label>
                            <input class="form-control" type="number" name="cantidad_personal" id="cantidad_personal" value="{{ old('cantidad_personal', $locacion->cantidad_personal) }}" step="1" required>
                            @if($errors->has('cantidad_personal'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('cantidad_personal') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.locacion.fields.cantidad_personal_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="estado_id">{{ trans('cruds.locacion.fields.estado') }}</label>
                            <select class="form-control select2" name="estado_id" id="estado_id">
                                @foreach($estados as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('estado_id') ? old('estado_id') : $locacion->estado->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('estado'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('estado') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.locacion.fields.estado_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="locacion_padre_id">{{ trans('cruds.locacion.fields.locacion_padre') }}</label>
                            <select class="form-control select2" name="locacion_padre_id" id="locacion_padre_id" required>
                                @foreach($locacion_padres as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('locacion_padre_id') ? old('locacion_padre_id') : $locacion->locacion_padre->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('locacion_padre'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('locacion_padre') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.locacion.fields.locacion_padre_helper') }}</span>
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