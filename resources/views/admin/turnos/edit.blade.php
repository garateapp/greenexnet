@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.turno.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.turnos.update", [$turno->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.turno.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', $turno->nombre) }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.turno.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="hora_inicio">{{ trans('cruds.turno.fields.hora_inicio') }}</label>
                <input class="form-control timepicker {{ $errors->has('hora_inicio') ? 'is-invalid' : '' }}" type="text" name="hora_inicio" id="hora_inicio" value="{{ old('hora_inicio', $turno->hora_inicio) }}" required>
                @if($errors->has('hora_inicio'))
                    <div class="invalid-feedback">
                        {{ $errors->first('hora_inicio') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.turno.fields.hora_inicio_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="hora_fin">{{ trans('cruds.turno.fields.hora_fin') }}</label>
                <input class="form-control timepicker {{ $errors->has('hora_fin') ? 'is-invalid' : '' }}" type="text" name="hora_fin" id="hora_fin" value="{{ old('hora_fin', $turno->hora_fin) }}" required>
                @if($errors->has('hora_fin'))
                    <div class="invalid-feedback">
                        {{ $errors->first('hora_fin') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.turno.fields.hora_fin_helper') }}</span>
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