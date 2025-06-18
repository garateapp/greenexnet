@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.correoalsoAir.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.correoalso-airs.update", [$correoalsoAir->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="cliente_id">{{ trans('cruds.correoalsoAir.fields.cliente') }}</label>
                <select class="form-control select2 {{ $errors->has('cliente') ? 'is-invalid' : '' }}" name="cliente_id" id="cliente_id" required>
                    @foreach($clientes as $id => $entry)
                        <option value="{{ $id }}" {{ (old('cliente_id') ? old('cliente_id') : $correoalsoAir->cliente->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('cliente'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cliente') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.correoalsoAir.fields.cliente_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="puerto_requerido">{{ trans('cruds.correoalsoAir.fields.puerto_requerido') }}</label>
                <textarea class="form-control {{ $errors->has('puerto_requerido') ? 'is-invalid' : '' }}" name="puerto_requerido" id="puerto_requerido">{{ old('puerto_requerido', $correoalsoAir->puerto_requerido) }}</textarea>
                @if($errors->has('puerto_requerido'))
                    <div class="invalid-feedback">
                        {{ $errors->first('puerto_requerido') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.correoalsoAir.fields.puerto_requerido_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="correos">{{ trans('cruds.correoalsoAir.fields.correos') }}</label>
                <textarea class="form-control {{ $errors->has('correos') ? 'is-invalid' : '' }}" name="correos" id="correos">{{ old('correos', $correoalsoAir->correos) }}</textarea>
                @if($errors->has('correos'))
                    <div class="invalid-feedback">
                        {{ $errors->first('correos') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.correoalsoAir.fields.correos_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="also_notify">{{ trans('cruds.correoalsoAir.fields.also_notify') }}</label>
                <textarea class="form-control {{ $errors->has('also_notify') ? 'is-invalid' : '' }}" name="also_notify" id="also_notify">{{ old('also_notify', $correoalsoAir->also_notify) }}</textarea>
                @if($errors->has('also_notify'))
                    <div class="invalid-feedback">
                        {{ $errors->first('also_notify') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.correoalsoAir.fields.also_notify_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.correoalsoAir.fields.transporte') }}</label>
                <select class="form-control {{ $errors->has('transporte') ? 'is-invalid' : '' }}" name="transporte" id="transporte" required>
                    <option value disabled {{ old('transporte', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\CorreoalsoAir::TRANSPORTE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('transporte', $correoalsoAir->transporte) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('transporte'))
                    <div class="invalid-feedback">
                        {{ $errors->first('transporte') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.correoalsoAir.fields.transporte_helper') }}</span>
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