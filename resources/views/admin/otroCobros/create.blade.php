@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.otroCobro.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.otro-cobros.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="productor_id">{{ trans('cruds.otroCobro.fields.productor') }}</label>
                <select class="form-control select2 {{ $errors->has('productor') ? 'is-invalid' : '' }}" name="productor_id" id="productor_id" required>
                    @foreach($productors as $id => $entry)
                        <option value="{{ $id }}" {{ old('productor_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('productor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('productor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.otroCobro.fields.productor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="valor">{{ trans('cruds.otroCobro.fields.valor') }}</label>
                <input class="form-control {{ $errors->has('valor') ? 'is-invalid' : '' }}" type="number" name="valor" id="valor" value="{{ old('valor', '0') }}" step="0.0000000001" required>
                @if($errors->has('valor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('valor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.otroCobro.fields.valor_helper') }}</span>
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