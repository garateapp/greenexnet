@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.grupo.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.grupos.update", [$grupo->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.grupo.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', $grupo->nombre) }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grupo.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="rut">{{ trans('cruds.grupo.fields.rut') }}</label>
                <input class="form-control {{ $errors->has('rut') ? 'is-invalid' : '' }}" type="text" name="rut" id="rut" value="{{ old('rut', $grupo->rut) }}">
                @if($errors->has('rut'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rut') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grupo.fields.rut_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="conjunto_id">{{ trans('cruds.grupo.fields.conjunto') }}</label>
                <select class="form-control select2 {{ $errors->has('conjunto') ? 'is-invalid' : '' }}" name="conjunto_id" id="conjunto_id" required>
                    @foreach($conjuntos as $id => $entry)
                        <option value="{{ $id }}" {{ (old('conjunto_id') ? old('conjunto_id') : $grupo->conjunto->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('conjunto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('conjunto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grupo.fields.conjunto_helper') }}</span>
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