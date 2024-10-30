@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.entidad.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.entidads.update", [$entidad->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.entidad.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', $entidad->nombre) }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entidad.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="rut">{{ trans('cruds.entidad.fields.rut') }}</label>
                <input class="form-control {{ $errors->has('rut') ? 'is-invalid' : '' }}" type="text" name="rut" id="rut" value="{{ old('rut', $entidad->rut) }}" required>
                @if($errors->has('rut'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rut') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entidad.fields.rut_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="tipo_id">{{ trans('cruds.entidad.fields.tipo') }}</label>
                <select class="form-control select2 {{ $errors->has('tipo') ? 'is-invalid' : '' }}" name="tipo_id" id="tipo_id" required>
                    @foreach($tipos as $id => $entry)
                        <option value="{{ $id }}" {{ (old('tipo_id') ? old('tipo_id') : $entidad->tipo->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('tipo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entidad.fields.tipo_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="direccion">{{ trans('cruds.entidad.fields.direccion') }}</label>
                <input class="form-control {{ $errors->has('direccion') ? 'is-invalid' : '' }}" type="text" name="direccion" id="direccion" value="{{ old('direccion', $entidad->direccion) }}">
                @if($errors->has('direccion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('direccion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entidad.fields.direccion_helper') }}</span>
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