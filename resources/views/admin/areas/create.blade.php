@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.area.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.areas.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.area.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.area.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="entidad_id">{{ trans('cruds.area.fields.entidad') }}</label>
                <select class="form-control select2 {{ $errors->has('entidad') ? 'is-invalid' : '' }}" name="entidad_id" id="entidad_id">
                    @foreach($entidads as $id => $entry)
                        <option value="{{ $id }}" {{ old('entidad_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                <input class="form-control {{ $errors->has('descripcion') ? 'is-invalid' : '' }}" type="text" name="descripcion" id="descripcion" value="{{ old('descripcion', '') }}">
                @if($errors->has('descripcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('descripcion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.area.fields.descripcion_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="estado_id">{{ trans('cruds.area.fields.estado') }}</label>
                <select class="form-control select2 {{ $errors->has('estado') ? 'is-invalid' : '' }}" name="estado_id" id="estado_id">
                    @foreach($estados as $id => $entry)
                        <option value="{{ $id }}" {{ old('estado_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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



@endsection