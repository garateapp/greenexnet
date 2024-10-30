@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.frecuenciaTurno.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.frecuencia-turnos.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required">{{ trans('cruds.frecuenciaTurno.fields.dia') }}</label>
                <select class="form-control {{ $errors->has('dia') ? 'is-invalid' : '' }}" name="dia" id="dia" required>
                    <option value disabled {{ old('dia', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\FrecuenciaTurno::DIA_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('dia', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('dia'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dia') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.frecuenciaTurno.fields.dia_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="turno_id">{{ trans('cruds.frecuenciaTurno.fields.turno') }}</label>
                <select class="form-control select2 {{ $errors->has('turno') ? 'is-invalid' : '' }}" name="turno_id" id="turno_id">
                    @foreach($turnos as $id => $entry)
                        <option value="{{ $id }}" {{ old('turno_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('turno'))
                    <div class="invalid-feedback">
                        {{ $errors->first('turno') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.frecuenciaTurno.fields.turno_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nombre">{{ trans('cruds.frecuenciaTurno.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}">
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.frecuenciaTurno.fields.nombre_helper') }}</span>
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