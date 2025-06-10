@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.valorFlete.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.valor-fletes.update", [$valorFlete->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="condicion">{{ trans('cruds.valorFlete.fields.condicion') }}</label>
                <input class="form-control {{ $errors->has('condicion') ? 'is-invalid' : '' }}" type="number" name="condicion" id="condicion" value="{{ old('condicion', $valorFlete->condicion) }}" step="0.1" max="1">
                @if($errors->has('condicion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('condicion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.valorFlete.fields.condicion_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="productor_id">{{ trans('cruds.valorFlete.fields.productor') }}</label>
                <select class="form-control select2 {{ $errors->has('productor') ? 'is-invalid' : '' }}" name="productor_id" id="productor_id" required>
                    @foreach($productors as $id => $entry)
                        <option value="{{ $id }}" {{ (old('productor_id') ? old('productor_id') : $valorFlete->productor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('productor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('productor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.valorFlete.fields.productor_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="valor">{{ trans('cruds.valorFlete.fields.valor') }}</label>
                <input class="form-control {{ $errors->has('valor') ? 'is-invalid' : '' }}" type="number" name="valor" id="valor" value="{{ old('valor', $valorFlete->valor) }}" step="0.01">
                @if($errors->has('valor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('valor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.valorFlete.fields.valor_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="valor_dolar_id">{{ trans('cruds.valorFlete.fields.valor_dolar') }}</label>
                <select class="form-control select2 {{ $errors->has('valor_dolar') ? 'is-invalid' : '' }}" name="valor_dolar_id" id="valor_dolar_id">
                    @foreach($valor_dolars as $id => $entry)
                        <option value="{{ $id }}" {{ (old('valor_dolar_id') ? old('valor_dolar_id') : $valorFlete->valor_dolar->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('valor_dolar'))
                    <div class="invalid-feedback">
                        {{ $errors->first('valor_dolar') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.valorFlete.fields.valor_dolar_helper') }}</span>
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