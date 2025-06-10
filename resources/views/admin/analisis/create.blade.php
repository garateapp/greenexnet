@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.analisi.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.analisis.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="productor_id">{{ trans('cruds.analisi.fields.productor') }}</label>
                <select class="form-control select2 {{ $errors->has('productor') ? 'is-invalid' : '' }}" name="productor_id" id="productor_id">
                    @foreach($productors as $id => $entry)
                        <option value="{{ $id }}" {{ old('productor_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('productor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('productor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.analisi.fields.productor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="temporada">{{ trans('cruds.analisi.fields.temporada') }}</label>
                <input class="form-control {{ $errors->has('temporada') ? 'is-invalid' : '' }}" type="text" name="temporada" id="temporada" value="{{ old('temporada', '') }}" required>
                @if($errors->has('temporada'))
                    <div class="invalid-feedback">
                        {{ $errors->first('temporada') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.analisi.fields.temporada_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="especie">{{ trans('cruds.analisi.fields.especie') }}</label>
                <input class="form-control {{ $errors->has('especie') ? 'is-invalid' : '' }}" type="text" name="especie" id="especie" value="{{ old('especie', '') }}" required>
                @if($errors->has('especie'))
                    <div class="invalid-feedback">
                        {{ $errors->first('especie') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.analisi.fields.especie_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="csg">{{ trans('cruds.analisi.fields.csg') }}</label>
                <input class="form-control {{ $errors->has('csg') ? 'is-invalid' : '' }}" type="text" name="csg" id="csg" value="{{ old('csg', '') }}" required>
                @if($errors->has('csg'))
                    <div class="invalid-feedback">
                        {{ $errors->first('csg') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.analisi.fields.csg_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="valor">{{ trans('cruds.analisi.fields.valor') }}</label>
                <input class="form-control {{ $errors->has('valor') ? 'is-invalid' : '' }}" type="text" name="valor" id="valor" value="{{ old('valor', '0') }}" required>
                @if($errors->has('valor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('valor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.analisi.fields.valor_helper') }}</span>
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