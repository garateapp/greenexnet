@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.condpago.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.condpagos.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="cond_pago">{{ trans('cruds.condpago.fields.cond_pago') }}</label>
                <input class="form-control {{ $errors->has('cond_pago') ? 'is-invalid' : '' }}" type="text" name="cond_pago" id="cond_pago" value="{{ old('cond_pago', '') }}">
                @if($errors->has('cond_pago'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cond_pago') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.condpago.fields.cond_pago_helper') }}</span>
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