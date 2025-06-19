@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.chofer.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.chofers.update", [$chofer->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.chofer.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', $chofer->nombre) }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.chofer.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="rut">{{ trans('cruds.chofer.fields.rut') }}</label>
                <input class="form-control {{ $errors->has('rut') ? 'is-invalid' : '' }}" type="text" name="rut" id="rut" value="{{ old('rut', $chofer->rut) }}">
                @if($errors->has('rut'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rut') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.chofer.fields.rut_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="telefono">{{ trans('cruds.chofer.fields.telefono') }}</label>
                <input class="form-control {{ $errors->has('telefono') ? 'is-invalid' : '' }}" type="text" name="telefono" id="telefono" value="{{ old('telefono', $chofer->telefono) }}">
                @if($errors->has('telefono'))
                    <div class="invalid-feedback">
                        {{ $errors->first('telefono') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.chofer.fields.telefono_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="patente">{{ trans('cruds.chofer.fields.patente') }}</label>
                <input class="form-control {{ $errors->has('patente') ? 'is-invalid' : '' }}" type="text" name="patente" id="patente" value="{{ old('patente', $chofer->patente) }}">
                @if($errors->has('patente'))
                    <div class="invalid-feedback">
                        {{ $errors->first('patente') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.chofer.fields.patente_helper') }}</span>
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