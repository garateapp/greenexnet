@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.pesoEmbalaje.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.peso-embalajes.update", [$pesoEmbalaje->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="especie_id">{{ trans('cruds.pesoEmbalaje.fields.especie') }}</label>
                <select class="form-control select2 {{ $errors->has('especie') ? 'is-invalid' : '' }}" name="especie_id" id="especie_id" required>
                    @foreach($especies as $id => $entry)
                        <option value="{{ $id }}" {{ (old('especie_id') ? old('especie_id') : $pesoEmbalaje->especie->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('especie'))
                    <div class="invalid-feedback">
                        {{ $errors->first('especie') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pesoEmbalaje.fields.especie_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="etiqueta_id">{{ trans('cruds.pesoEmbalaje.fields.etiqueta') }}</label>
                <select class="form-control select2 {{ $errors->has('etiqueta') ? 'is-invalid' : '' }}" name="etiqueta_id" id="etiqueta_id" required>
                    @foreach($etiquetas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('etiqueta_id') ? old('etiqueta_id') : $pesoEmbalaje->etiqueta->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('etiqueta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('etiqueta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pesoEmbalaje.fields.etiqueta_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="embalajes">{{ trans('cruds.pesoEmbalaje.fields.embalajes') }}</label>
                <input class="form-control {{ $errors->has('embalajes') ? 'is-invalid' : '' }}" type="text" name="embalajes" id="embalajes" value="{{ old('embalajes', $pesoEmbalaje->embalajes) }}">
                @if($errors->has('embalajes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('embalajes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pesoEmbalaje.fields.embalajes_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="peso_neto">{{ trans('cruds.pesoEmbalaje.fields.peso_neto') }}</label>
                <input class="form-control {{ $errors->has('peso_neto') ? 'is-invalid' : '' }}" type="number" name="peso_neto" id="peso_neto" value="{{ old('peso_neto', $pesoEmbalaje->peso_neto) }}" step="0.01">
                @if($errors->has('peso_neto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('peso_neto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pesoEmbalaje.fields.peso_neto_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="peso_bruto">{{ trans('cruds.pesoEmbalaje.fields.peso_bruto') }}</label>
                <input class="form-control {{ $errors->has('peso_bruto') ? 'is-invalid' : '' }}" type="number" name="peso_bruto" id="peso_bruto" value="{{ old('peso_bruto', $pesoEmbalaje->peso_bruto) }}" step="0.01" required>
                @if($errors->has('peso_bruto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('peso_bruto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pesoEmbalaje.fields.peso_bruto_helper') }}</span>
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