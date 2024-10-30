@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.frecuenciaTurno.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.frecuencia-turnos.update", [$frecuenciaTurno->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.frecuenciaTurno.fields.dia') }}</label>
                            <select class="form-control" name="dia" id="dia" required>
                                <option value disabled {{ old('dia', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\FrecuenciaTurno::DIA_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('dia', $frecuenciaTurno->dia) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
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
                            <select class="form-control select2" name="turno_id" id="turno_id">
                                @foreach($turnos as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('turno_id') ? old('turno_id') : $frecuenciaTurno->turno->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                            <input class="form-control" type="text" name="nombre" id="nombre" value="{{ old('nombre', $frecuenciaTurno->nombre) }}">
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

        </div>
    </div>
</div>
@endsection