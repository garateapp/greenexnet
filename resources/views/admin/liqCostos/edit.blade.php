@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.liqCosto.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.liq-costos.update", [$liqCosto->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="liq_cabecera_id">{{ trans('cruds.liqCosto.fields.liq_cabecera') }}</label>
                <select class="form-control select2 {{ $errors->has('liq_cabecera') ? 'is-invalid' : '' }}" name="liq_cabecera_id" id="liq_cabecera_id" required>
                    @foreach($liq_cabeceras as $id => $entry)
                        <option value="{{ $id }}" {{ (old('liq_cabecera_id') ? old('liq_cabecera_id') : $liqCosto->liq_cabecera->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('liq_cabecera'))
                    <div class="invalid-feedback">
                        {{ $errors->first('liq_cabecera') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liqCosto.fields.liq_cabecera_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nombre_costo">{{ trans('cruds.liqCosto.fields.nombre_costo') }}</label>
                <input class="form-control {{ $errors->has('nombre_costo') ? 'is-invalid' : '' }}" type="text" name="nombre_costo" id="nombre_costo" value="{{ old('nombre_costo', $liqCosto->nombre_costo) }}" required>
                @if($errors->has('nombre_costo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre_costo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liqCosto.fields.nombre_costo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="valor">{{ trans('cruds.liqCosto.fields.valor') }}</label>
                <input class="form-control {{ $errors->has('valor') ? 'is-invalid' : '' }}" type="number" name="valor" id="valor" value="{{ old('valor', $liqCosto->valor) }}" step="0.01" required>
                @if($errors->has('valor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('valor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liqCosto.fields.valor_helper') }}</span>
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