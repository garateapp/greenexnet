@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.costo.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.costos.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.costo.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.costo.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="valor_x_defecto">{{ trans('cruds.costo.fields.valor_x_defecto') }}</label>
                <input class="form-control {{ $errors->has('valor_x_defecto') ? 'is-invalid' : '' }}" type="text" name="valor_x_defecto" id="valor_x_defecto" value="{{ old('valor_x_defecto', '') }}">
                @if($errors->has('valor_x_defecto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('valor_x_defecto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.costo.fields.valor_x_defecto_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.costo.fields.categoria') }}</label>
                <select class="form-control {{ $errors->has('categoria') ? 'is-invalid' : '' }}" name="categoria" id="categoria">
                    <option value disabled {{ old('categoria', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Costo::CATEGORIA_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('categoria', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('categoria'))
                    <div class="invalid-feedback">
                        {{ $errors->first('categoria') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.costo.fields.categoria_helper') }}</span>
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