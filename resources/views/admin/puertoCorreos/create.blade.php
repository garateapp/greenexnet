@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.puertoCorreo.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.puerto-correos.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="puerto_embarque_id">{{ trans('cruds.puertoCorreo.fields.puerto_embarque') }}</label>
                <select class="form-control select2 {{ $errors->has('puerto_embarque') ? 'is-invalid' : '' }}" name="puerto_embarque_id" id="puerto_embarque_id" required>
                    @foreach($puerto_embarques as $id => $entry)
                        <option value="{{ $id }}" {{ old('puerto_embarque_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('puerto_embarque'))
                    <div class="invalid-feedback">
                        {{ $errors->first('puerto_embarque') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.puertoCorreo.fields.puerto_embarque_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="emails">{{ trans('cruds.puertoCorreo.fields.emails') }}</label>
                <textarea class="form-control {{ $errors->has('emails') ? 'is-invalid' : '' }}" name="emails" id="emails">{{ old('emails') }}</textarea>
                @if($errors->has('emails'))
                    <div class="invalid-feedback">
                        {{ $errors->first('emails') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.puertoCorreo.fields.emails_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="pais_id">{{ trans('cruds.puertoCorreo.fields.pais') }}</label>
                <select class="form-control select2 {{ $errors->has('pais') ? 'is-invalid' : '' }}" name="pais_id" id="pais_id">
                    @foreach($pais as $id => $entry)
                        <option value="{{ $id }}" {{ old('pais_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('pais'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pais') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.puertoCorreo.fields.pais_helper') }}</span>
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