@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.plantaCarga.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.planta-cargas.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.plantaCarga.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plantaCarga.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="direccion">{{ trans('cruds.plantaCarga.fields.direccion') }}</label>
                <input class="form-control {{ $errors->has('direccion') ? 'is-invalid' : '' }}" type="text" name="direccion" id="direccion" value="{{ old('direccion', '') }}">
                @if($errors->has('direccion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('direccion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plantaCarga.fields.direccion_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="id_fx">{{ trans('cruds.plantaCarga.fields.id_fx') }}</label>
                <input class="form-control {{ $errors->has('id_fx') ? 'is-invalid' : '' }}" type="number" name="id_fx" id="id_fx" value="{{ old('id_fx', '') }}" step="1" required>
                @if($errors->has('id_fx'))
                    <div class="invalid-feedback">
                        {{ $errors->first('id_fx') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plantaCarga.fields.id_fx_helper') }}</span>
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