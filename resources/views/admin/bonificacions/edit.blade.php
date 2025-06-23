@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.bonificacion.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.bonificacions.update", [$bonificacion->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="productor_id">{{ trans('cruds.bonificacion.fields.productor') }}</label>
                <select class="form-control select2 {{ $errors->has('productor') ? 'is-invalid' : '' }}" name="productor_id" id="productor_id">
                    @foreach($productors as $id => $entry)
                        <option value="{{ $id }}" {{ (old('productor_id') ? old('productor_id') : $bonificacion->productor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('productor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('productor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.bonificacion.fields.productor_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="valor">{{ trans('cruds.bonificacion.fields.valor') }}</label>
                <input class="form-control {{ $errors->has('valor') ? 'is-invalid' : '' }}" type="number" name="valor" id="valor" value="{{ old('valor', $bonificacion->valor) }}" step="0.0000000001">
                @if($errors->has('valor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('valor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.bonificacion.fields.valor_helper') }}</span>
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