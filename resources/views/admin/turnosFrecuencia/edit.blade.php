@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.turnosFrecuencium.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.turnos-frecuencia.update", [$turnosFrecuencium->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="frecuencia_id">{{ trans('cruds.turnosFrecuencium.fields.frecuencia') }}</label>
                <select class="form-control select2 {{ $errors->has('frecuencia') ? 'is-invalid' : '' }}" name="frecuencia_id" id="frecuencia_id" required>
                    @foreach($frecuencias as $id => $entry)
                        <option value="{{ $id }}" {{ (old('frecuencia_id') ? old('frecuencia_id') : $turnosFrecuencium->frecuencia->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('frecuencia'))
                    <div class="invalid-feedback">
                        {{ $errors->first('frecuencia') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.turnosFrecuencium.fields.frecuencia_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="locacion_id">{{ trans('cruds.turnosFrecuencium.fields.locacion') }}</label>
                <select class="form-control select2 {{ $errors->has('locacion') ? 'is-invalid' : '' }}" name="locacion_id" id="locacion_id" required>
                    @foreach($locacions as $id => $entry)
                        <option value="{{ $id }}" {{ (old('locacion_id') ? old('locacion_id') : $turnosFrecuencium->locacion->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('locacion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('locacion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.turnosFrecuencium.fields.locacion_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.turnosFrecuencium.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', $turnosFrecuencium->nombre) }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.turnosFrecuencium.fields.nombre_helper') }}</span>
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