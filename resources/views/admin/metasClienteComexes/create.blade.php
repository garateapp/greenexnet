@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.metasClienteComex.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.metas-cliente-comexes.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="clientecomex_id">{{ trans('cruds.metasClienteComex.fields.clientecomex') }}</label>
                <select class="form-control select2 {{ $errors->has('clientecomex') ? 'is-invalid' : '' }}" name="clientecomex_id" id="clientecomex_id" required>
                    @foreach($clientecomexes as $id => $entry)
                        <option value="{{ $id }}" {{ old('clientecomex_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('clientecomex'))
                    <div class="invalid-feedback">
                        {{ $errors->first('clientecomex') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.metasClienteComex.fields.clientecomex_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="cantidad">{{ trans('cruds.metasClienteComex.fields.cantidad') }}</label>
                <input class="form-control {{ $errors->has('cantidad') ? 'is-invalid' : '' }}" type="number" name="cantidad" id="cantidad" value="{{ old('cantidad', '0') }}" step="1" required>
                @if($errors->has('cantidad'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cantidad') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.metasClienteComex.fields.cantidad_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="anno">Año</label>
                <input class="form-control {{ $errors->has('cantidad') ? 'is-invalid' : '' }}" type="number" name="anno" id="anno" value="{{ old('anno', 'date("Y")') }}" step="1" required>
                @if($errors->has('anno'))
                    <div class="invalid-feedback">
                        {{ $errors->first('anno') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.metasClienteComex.fields.cantidad_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="observaciones">{{ trans('cruds.metasClienteComex.fields.observaciones') }}</label>
                <textarea class="form-control {{ $errors->has('observaciones') ? 'is-invalid' : '' }}" name="observaciones" id="observaciones">{{ old('observaciones') }}</textarea>
                @if($errors->has('observaciones'))
                    <div class="invalid-feedback">
                        {{ $errors->first('observaciones') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.metasClienteComex.fields.observaciones_helper') }}</span>
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
