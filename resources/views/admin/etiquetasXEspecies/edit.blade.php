@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.etiquetasXEspecy.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.etiquetas-x-especies.update", [$etiquetasXEspecy->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="especie_id">{{ trans('cruds.etiquetasXEspecy.fields.especie') }}</label>
                <select class="form-control select2 {{ $errors->has('especie') ? 'is-invalid' : '' }}" name="especie_id" id="especie_id">
                    @foreach($especies as $id => $entry)
                        <option value="{{ $id }}" {{ (old('especie_id') ? old('especie_id') : $etiquetasXEspecy->especie->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('especie'))
                    <div class="invalid-feedback">
                        {{ $errors->first('especie') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.etiquetasXEspecy.fields.especie_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="etiqueta_id">{{ trans('cruds.etiquetasXEspecy.fields.etiqueta') }}</label>
                <select class="form-control select2 {{ $errors->has('etiqueta') ? 'is-invalid' : '' }}" name="etiqueta_id" id="etiqueta_id" required>
                    @foreach($etiquetas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('etiqueta_id') ? old('etiqueta_id') : $etiquetasXEspecy->etiqueta->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('etiqueta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('etiqueta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.etiquetasXEspecy.fields.etiqueta_helper') }}</span>
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