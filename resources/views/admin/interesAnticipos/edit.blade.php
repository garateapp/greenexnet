@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.interesAnticipo.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.interes-anticipos.update", [$interesAnticipo->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="anticipo_id">{{ trans('cruds.interesAnticipo.fields.anticipo') }}</label>
                <select class="form-control select2 {{ $errors->has('anticipo') ? 'is-invalid' : '' }}" name="anticipo_id" id="anticipo_id" required>
                    @foreach($anticipos as $id => $entry)
                        <option value="{{ $id }}" {{ (old('anticipo_id') ? old('anticipo_id') : $interesAnticipo->anticipo->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('anticipo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('anticipo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.interesAnticipo.fields.anticipo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="valor">{{ trans('cruds.interesAnticipo.fields.valor') }}</label>
                <input class="form-control {{ $errors->has('valor') ? 'is-invalid' : '' }}" type="number" name="valor" id="valor" value="{{ old('valor', $interesAnticipo->valor) }}" step="0.01" required>
                @if($errors->has('valor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('valor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.interesAnticipo.fields.valor_helper') }}</span>
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