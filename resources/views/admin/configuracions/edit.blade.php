@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.configuracion.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.configuracions.update", [$configuracion->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="variable">{{ trans('cruds.configuracion.fields.variable') }}</label>
                <input class="form-control {{ $errors->has('variable') ? 'is-invalid' : '' }}" type="text" name="variable" id="variable" value="{{ old('variable', $configuracion->variable) }}" required>
                @if($errors->has('variable'))
                    <div class="invalid-feedback">
                        {{ $errors->first('variable') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracion.fields.variable_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="valor">{{ trans('cruds.configuracion.fields.valor') }}</label>
                <input class="form-control {{ $errors->has('valor') ? 'is-invalid' : '' }}" type="text" name="valor" id="valor" value="{{ old('valor', $configuracion->valor) }}" required>
                @if($errors->has('valor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('valor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracion.fields.valor_helper') }}</span>
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