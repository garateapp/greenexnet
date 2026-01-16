@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.centroCosto.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.centro-costos.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="entidad_id">{{ trans('cruds.centroCosto.fields.entidad') }}</label>
                <select class="form-control select2 {{ $errors->has('entidad_id') ? 'is-invalid' : '' }}" name="entidad_id" id="entidad_id">
                    @foreach($entidads as $id => $entry)
                        <option value="{{ $id }}" {{ old('entidad_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('entidad_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('entidad_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.centroCosto.fields.entidad_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="id_centrocosto">{{ trans('cruds.centroCosto.fields.id_centrocosto') }}</label>
                <input class="form-control {{ $errors->has('id_centrocosto') ? 'is-invalid' : '' }}" type="text" name="id_centrocosto" id="id_centrocosto" value="{{ old('id_centrocosto', '') }}">
                @if($errors->has('id_centrocosto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('id_centrocosto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.centroCosto.fields.id_centrocosto_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="c_centrocosto">{{ trans('cruds.centroCosto.fields.c_centrocosto') }}</label>
                <input class="form-control {{ $errors->has('c_centrocosto') ? 'is-invalid' : '' }}" type="text" name="c_centrocosto" id="c_centrocosto" value="{{ old('c_centrocosto', '') }}">
                @if($errors->has('c_centrocosto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('c_centrocosto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.centroCosto.fields.c_centrocosto_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="n_centrocosto">{{ trans('cruds.centroCosto.fields.n_centrocosto') }}</label>
                <input class="form-control {{ $errors->has('n_centrocosto') ? 'is-invalid' : '' }}" type="text" name="n_centrocosto" id="n_centrocosto" value="{{ old('n_centrocosto', '') }}" required>
                @if($errors->has('n_centrocosto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('n_centrocosto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.centroCosto.fields.n_centrocosto_helper') }}</span>
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
