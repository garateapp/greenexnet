@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.clientesComex.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.clientes-comexes.update", [$clientesComex->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="codigo_cliente">{{ trans('cruds.clientesComex.fields.codigo_cliente') }}</label>
                <input class="form-control {{ $errors->has('codigo_cliente') ? 'is-invalid' : '' }}" type="text" name="codigo_cliente" id="codigo_cliente" value="{{ old('codigo_cliente', $clientesComex->codigo_cliente) }}" required>
                @if($errors->has('codigo_cliente'))
                    <div class="invalid-feedback">
                        {{ $errors->first('codigo_cliente') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clientesComex.fields.codigo_cliente_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nombre_empresa">{{ trans('cruds.clientesComex.fields.nombre_empresa') }}</label>
                <input class="form-control {{ $errors->has('nombre_empresa') ? 'is-invalid' : '' }}" type="text" name="nombre_empresa" id="nombre_empresa" value="{{ old('nombre_empresa', $clientesComex->nombre_empresa) }}" required>
                @if($errors->has('nombre_empresa'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre_empresa') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clientesComex.fields.nombre_empresa_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nombre_fantasia">{{ trans('cruds.clientesComex.fields.nombre_fantasia') }}</label>
                <input class="form-control {{ $errors->has('nombre_fantasia') ? 'is-invalid' : '' }}" type="text" name="nombre_fantasia" id="nombre_fantasia" value="{{ old('nombre_fantasia', $clientesComex->nombre_fantasia) }}" required>
                @if($errors->has('nombre_fantasia'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre_fantasia') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clientesComex.fields.nombre_fantasia_helper') }}</span>
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