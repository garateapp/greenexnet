@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.valorDolar.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.valor-dolars.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="fecha_cambio">{{ trans('cruds.valorDolar.fields.fecha_cambio') }}</label>
                <input class="form-control date {{ $errors->has('fecha_cambio') ? 'is-invalid' : '' }}" type="text" name="fecha_cambio" id="fecha_cambio" value="{{ old('fecha_cambio') }}" required>
                @if($errors->has('fecha_cambio'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha_cambio') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.valorDolar.fields.fecha_cambio_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="valor">{{ trans('cruds.valorDolar.fields.valor') }}</label>
                <input class="form-control {{ $errors->has('valor') ? 'is-invalid' : '' }}" type="number" name="valor" id="valor" value="{{ old('valor', '') }}" step="0.01" required>
                @if($errors->has('valor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('valor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.valorDolar.fields.valor_helper') }}</span>
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