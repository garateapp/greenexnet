@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.tiposSeccionConversor.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.tipos-seccion-conversors.update", [$tiposSeccionConversor->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.tiposSeccionConversor.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', $tiposSeccionConversor->nombre) }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tiposSeccionConversor.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('eslistado') ? 'is-invalid' : '' }}">
                    <input class="form-check-input" type="checkbox" name="eslistado" id="eslistado" value="0"  {{ old('tiposSeccionConversor->eslistado', 0) == 1 || old('tiposSeccionConversor->eslistado') === null ? 'checked' : '' }}>
                    <label class="required form-check-label" for="eslistado">Es Listado?</label>
                </div>
                @if($errors->has('activo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('eslistado') }}
                    </div>
                @endif
               
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