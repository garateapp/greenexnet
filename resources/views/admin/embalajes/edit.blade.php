@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.embalaje.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.embalajes.update", [$embalaje->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="c_embalaje">{{ trans('cruds.embalaje.fields.c_embalaje') }}</label>
                <input class="form-control {{ $errors->has('c_embalaje') ? 'is-invalid' : '' }}" type="text" name="c_embalaje" id="c_embalaje" value="{{ old('c_embalaje', $embalaje->c_embalaje) }}" required>
                @if($errors->has('c_embalaje'))
                    <div class="invalid-feedback">
                        {{ $errors->first('c_embalaje') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embalaje.fields.c_embalaje_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="kgxcaja">{{ trans('cruds.embalaje.fields.kgxcaja') }}</label>
                <input class="form-control {{ $errors->has('kgxcaja') ? 'is-invalid' : '' }}" type="number" name="kgxcaja" id="kgxcaja" value="{{ old('kgxcaja', $embalaje->kgxcaja) }}" step="1" required>
                @if($errors->has('kgxcaja'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kgxcaja') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embalaje.fields.kgxcaja_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="cajaxpallet">{{ trans('cruds.embalaje.fields.cajaxpallet') }}</label>
                <input class="form-control {{ $errors->has('cajaxpallet') ? 'is-invalid' : '' }}" type="text" name="cajaxpallet" id="cajaxpallet" value="{{ old('cajaxpallet', $embalaje->cajaxpallet) }}" required>
                @if($errors->has('cajaxpallet'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cajaxpallet') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embalaje.fields.cajaxpallet_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="altura_pallet">{{ trans('cruds.embalaje.fields.altura_pallet') }}</label>
                <input class="form-control {{ $errors->has('altura_pallet') ? 'is-invalid' : '' }}" type="number" name="altura_pallet" id="altura_pallet" value="{{ old('altura_pallet', $embalaje->altura_pallet) }}" step="0.01">
                @if($errors->has('altura_pallet'))
                    <div class="invalid-feedback">
                        {{ $errors->first('altura_pallet') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embalaje.fields.altura_pallet_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.embalaje.fields.tipo_embarque') }}</label>
                <select class="form-control {{ $errors->has('tipo_embarque') ? 'is-invalid' : '' }}" name="tipo_embarque" id="tipo_embarque">
                    <option value disabled {{ old('tipo_embarque', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Embalaje::TIPO_EMBARQUE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('tipo_embarque', $embalaje->tipo_embarque) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('tipo_embarque'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo_embarque') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embalaje.fields.tipo_embarque_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="caja">{{ trans('cruds.embalaje.fields.caja') }}</label>
                <input class="form-control {{ $errors->has('caja') ? 'is-invalid' : '' }}" type="text" name="caja" id="caja" value="{{ old('caja', $embalaje->caja) }}" required>
                @if($errors->has('caja'))
                    <div class="invalid-feedback">
                        {{ $errors->first('caja') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embalaje.fields.caja_helper') }}</span>
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