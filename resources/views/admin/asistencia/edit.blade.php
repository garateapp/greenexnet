@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.asistencium.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.asistencia.update", [$asistencium->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="locacion_id">{{ trans('cruds.asistencium.fields.locacion') }}</label>
                <select class="form-control select2 {{ $errors->has('locacion') ? 'is-invalid' : '' }}" name="locacion_id" id="locacion_id" required>
                    @foreach($locacions as $id => $entry)
                        <option value="{{ $id }}" {{ (old('locacion_id') ? old('locacion_id') : $asistencium->locacion->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('locacion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('locacion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.asistencium.fields.locacion_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="turno_id">{{ trans('cruds.asistencium.fields.turno') }}</label>
                <select class="form-control select2 {{ $errors->has('turno') ? 'is-invalid' : '' }}" name="turno_id" id="turno_id" required>
                    @foreach($turnos as $id => $entry)
                        <option value="{{ $id }}" {{ (old('turno_id') ? old('turno_id') : $asistencium->turno->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('turno'))
                    <div class="invalid-feedback">
                        {{ $errors->first('turno') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.asistencium.fields.turno_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="personal_id">{{ trans('cruds.asistencium.fields.personal') }}</label>
                <select class="form-control select2 {{ $errors->has('personal') ? 'is-invalid' : '' }}" name="personal_id" id="personal_id" required>
                    @foreach($personals as $id => $entry)
                        <option value="{{ $id }}" {{ (old('personal_id') ? old('personal_id') : $asistencium->personal->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('personal'))
                    <div class="invalid-feedback">
                        {{ $errors->first('personal') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.asistencium.fields.personal_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fecha_hora">{{ trans('cruds.asistencium.fields.fecha_hora') }}</label>
                <input class="form-control datetime {{ $errors->has('fecha_hora') ? 'is-invalid' : '' }}" type="text" name="fecha_hora" id="fecha_hora" value="{{ old('fecha_hora', $asistencium->fecha_hora) }}">
                @if($errors->has('fecha_hora'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha_hora') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.asistencium.fields.fecha_hora_helper') }}</span>
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