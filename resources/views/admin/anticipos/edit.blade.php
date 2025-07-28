@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.anticipo.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.anticipos.update", [$anticipo->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="productor_id">{{ trans('cruds.anticipo.fields.productor') }}</label>
                <select class="form-control select2 {{ $errors->has('productor') ? 'is-invalid' : '' }}" name="productor_id" id="productor_id" required>
                    @foreach($productors as $id => $entry)
                        <option value="{{ $id }}" {{ (old('productor_id') ? old('productor_id') : $anticipo->productor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('productor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('productor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.anticipo.fields.productor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="valor">{{ trans('cruds.anticipo.fields.valor') }}</label>
                <input class="form-control {{ $errors->has('valor') ? 'is-invalid' : '' }}" type="number" name="valor" id="valor" value="{{ old('valor', $anticipo->valor) }}" step="0.01" required>
                @if($errors->has('valor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('valor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.anticipo.fields.valor_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="num_docto">{{ trans('cruds.anticipo.fields.num_docto') }}</label>
                <input class="form-control {{ $errors->has('num_docto') ? 'is-invalid' : '' }}" type="number" name="num_docto" id="num_docto" value="{{ old('num_docto', $anticipo->num_docto) }}" step="1">
                @if($errors->has('num_docto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('num_docto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.anticipo.fields.num_docto_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fecha_documento">{{ trans('cruds.anticipo.fields.fecha_documento') }}</label>
                <input class="form-control date {{ $errors->has('fecha_documento') ? 'is-invalid' : '' }}" type="text" name="fecha_documento" id="fecha_documento" value="{{ old('fecha_documento', $anticipo->fecha_documento) }}">
                @if($errors->has('fecha_documento'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha_documento') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.anticipo.fields.fecha_documento_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tipo_cambio_id">{{ trans('cruds.anticipo.fields.tipo_cambio') }}</label>
                <input class="form-control {{ $errors->has('tipo_cambio_id') ? 'is-invalid' : '' }}" type="hidden" name="tipo_cambio_id" id="tipo_cambio_id" value="{{ old('tipo_cambio_id') }}">
                @if($errors->has('tipo_cambio'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo_cambio') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.anticipo.fields.tipo_cambio_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="especie_id">{{ trans('cruds.anticipo.fields.especie') }}</label>
                <select class="form-control select2 {{ $errors->has('especie') ? 'is-invalid' : '' }}" name="especie_id" id="especie_id">
                    @foreach($especies as $id => $entry)
                        <option value="{{ $id }}" {{ (old('especie_id') ? old('especie_id') : $anticipo->especie->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('especie'))
                    <div class="invalid-feedback">
                        {{ $errors->first('especie') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.anticipo.fields.especie_helper') }}</span>
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
