@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.recepcion.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.recepcions.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="productor_id">{{ trans('cruds.recepcion.fields.productor') }}</label>
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
                <span class="help-block">{{ trans('cruds.recepcion.fields.productor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="variedad">{{ trans('cruds.recepcion.fields.variedad') }}</label>
                <input class="form-control {{ $errors->has('variedad') ? 'is-invalid' : '' }}" type="text" name="variedad" id="variedad" value="{{ old('variedad', '') }}" required>
                @if($errors->has('variedad'))
                    <div class="invalid-feedback">
                        {{ $errors->first('variedad') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.recepcion.fields.variedad_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="total_kilos">{{ trans('cruds.recepcion.fields.total_kilos') }}</label>
                <input class="form-control {{ $errors->has('total_kilos') ? 'is-invalid' : '' }}" type="number" name="total_kilos" id="total_kilos" value="{{ old('total_kilos', '0') }}" step="0.01" required>
                @if($errors->has('total_kilos'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_kilos') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.recepcion.fields.total_kilos_helper') }}</span>
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