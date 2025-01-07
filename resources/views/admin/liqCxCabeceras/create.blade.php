@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.liqCxCabecera.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.liq-cx-cabeceras.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="instructivo">{{ trans('cruds.liqCxCabecera.fields.instructivo') }}</label>
                <input class="form-control {{ $errors->has('instructivo') ? 'is-invalid' : '' }}" type="text" name="instructivo" id="instructivo" value="{{ old('instructivo', '') }}" required>
                @if($errors->has('instructivo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('instructivo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.instructivo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="cliente_id">{{ trans('cruds.liqCxCabecera.fields.cliente') }}</label>
                <select class="form-control select2 {{ $errors->has('cliente') ? 'is-invalid' : '' }}" name="cliente_id" id="cliente_id" required>
                    @foreach($clientes as $id => $entry)
                        <option value="{{ $id }}" {{ old('cliente_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('cliente'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cliente') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.cliente_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nave_id">{{ trans('cruds.liqCxCabecera.fields.nave') }}</label>
                <select class="form-control select2 {{ $errors->has('nave') ? 'is-invalid' : '' }}" name="nave_id" id="nave_id" required>
                    @foreach($naves as $id => $entry)
                        <option value="{{ $id }}" {{ old('nave_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('nave'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nave') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.nave_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="eta">{{ trans('cruds.liqCxCabecera.fields.eta') }}</label>
                <input class="form-control date {{ $errors->has('eta') ? 'is-invalid' : '' }}" type="text" name="eta" id="eta" value="{{ old('eta') }}">
                @if($errors->has('eta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('eta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.eta_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="tasa_intercambio">{{ trans('cruds.liqCxCabecera.fields.tasa_intercambio') }}</label>
                <input class="form-control {{ $errors->has('tasa_intercambio') ? 'is-invalid' : '' }}" type="number" name="tasa_intercambio" id="tasa_intercambio" value="{{ old('tasa_intercambio', '') }}" step="0.01" required>
                @if($errors->has('tasa_intercambio'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tasa_intercambio') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.tasa_intercambio_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="total_costo">{{ trans('cruds.liqCxCabecera.fields.total_costo') }}</label>
                <input class="form-control {{ $errors->has('total_costo') ? 'is-invalid' : '' }}" type="number" name="total_costo" id="total_costo" value="{{ old('total_costo', '0') }}" step="0.01" required>
                @if($errors->has('total_costo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_costo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.total_costo_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="total_bruto">{{ trans('cruds.liqCxCabecera.fields.total_bruto') }}</label>
                <input class="form-control {{ $errors->has('total_bruto') ? 'is-invalid' : '' }}" type="number" name="total_bruto" id="total_bruto" value="{{ old('total_bruto', '0') }}" step="0.01">
                @if($errors->has('total_bruto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_bruto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.total_bruto_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="total_neto">{{ trans('cruds.liqCxCabecera.fields.total_neto') }}</label>
                <input class="form-control {{ $errors->has('total_neto') ? 'is-invalid' : '' }}" type="number" name="total_neto" id="total_neto" value="{{ old('total_neto', '0') }}" step="0.01" required>
                @if($errors->has('total_neto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_neto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.liqCxCabecera.fields.total_neto_helper') }}</span>
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