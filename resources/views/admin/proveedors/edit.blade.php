@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.proveedor.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.proveedors.update", [$proveedor->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="rut">{{ trans('cruds.proveedor.fields.rut') }}</label>
                <input class="form-control {{ $errors->has('rut') ? 'is-invalid' : '' }}" type="text" name="rut" id="rut" value="{{ old('rut', $proveedor->rut) }}" required>
                @if($errors->has('rut'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rut') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proveedor.fields.rut_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="cobro">{{ trans('cruds.proveedor.fields.cobro') }}</label>
                <input class="form-control {{ $errors->has('cobro') ? 'is-invalid' : '' }}" type="text" name="cobro" id="cobro" value="{{ old('cobro', $proveedor->cobro) }}" required>
                @if($errors->has('cobro'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cobro') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proveedor.fields.cobro_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nombre_simple">{{ trans('cruds.proveedor.fields.nombre_simple') }}</label>
                <input class="form-control {{ $errors->has('nombre_simple') ? 'is-invalid' : '' }}" type="text" name="nombre_simple" id="nombre_simple" value="{{ old('nombre_simple', $proveedor->nombre_simple) }}">
                @if($errors->has('nombre_simple'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre_simple') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proveedor.fields.nombre_simple_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="razon_social">{{ trans('cruds.proveedor.fields.razon_social') }}</label>
                <input class="form-control {{ $errors->has('razon_social') ? 'is-invalid' : '' }}" type="text" name="razon_social" id="razon_social" value="{{ old('razon_social', $proveedor->razon_social) }}" required>
                @if($errors->has('razon_social'))
                    <div class="invalid-feedback">
                        {{ $errors->first('razon_social') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.proveedor.fields.razon_social_helper') }}</span>
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