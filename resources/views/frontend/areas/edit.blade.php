@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.area.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.areas.update", [$area->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="nombre">{{ trans('cruds.area.fields.nombre') }}</label>
                            <input class="form-control" type="text" name="nombre" id="nombre" value="{{ old('nombre', $area->nombre) }}" required>
                            @if($errors->has('nombre'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nombre') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.area.fields.nombre_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="entidad_id">{{ trans('cruds.area.fields.entidad') }}</label>
                            <select class="form-control select2" name="entidad_id" id="entidad_id">
                                @foreach($entidads as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('entidad_id') ? old('entidad_id') : $area->entidad->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('entidad'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('entidad') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.area.fields.entidad_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">{{ trans('cruds.area.fields.descripcion') }}</label>
                            <input class="form-control" type="text" name="descripcion" id="descripcion" value="{{ old('descripcion', $area->descripcion) }}">
                            @if($errors->has('descripcion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('descripcion') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.area.fields.descripcion_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="estado_id">{{ trans('cruds.area.fields.estado') }}</label>
                            <select class="form-control select2" name="estado_id" id="estado_id">
                                @foreach($estados as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('estado_id') ? old('estado_id') : $area->estado->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('estado'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('estado') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.area.fields.estado_helper') }}</span>
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