@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.handPack.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.hand-packs.update", [$handPack->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="rut">{{ trans('cruds.handPack.fields.rut') }}</label>
                <input class="form-control {{ $errors->has('rut') ? 'is-invalid' : '' }}" type="text" name="rut" id="rut" value="{{ old('rut', $handPack->rut) }}" required>
                @if($errors->has('rut'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rut') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.handPack.fields.rut_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="fecha">{{ trans('cruds.handPack.fields.fecha') }}</label>
                <input class="form-control date {{ $errors->has('fecha') ? 'is-invalid' : '' }}" type="text" name="fecha" id="fecha" value="{{ old('fecha', $handPack->fecha) }}" required>
                @if($errors->has('fecha'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.handPack.fields.fecha_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.handPack.fields.embalaje') }}</label>
                <select class="form-control {{ $errors->has('embalaje') ? 'is-invalid' : '' }}" name="embalaje" id="embalaje" required>
                    <option value disabled {{ old('embalaje', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\HandPack::EMBALAJE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('embalaje', $handPack->embalaje) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('embalaje'))
                    <div class="invalid-feedback">
                        {{ $errors->first('embalaje') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.handPack.fields.embalaje_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="guuid">{{ trans('cruds.handPack.fields.guuid') }}</label>
                <input class="form-control {{ $errors->has('guuid') ? 'is-invalid' : '' }}" type="text" name="guuid" id="guuid" value="{{ old('guuid', $handPack->guuid) }}" required>
                @if($errors->has('guuid'))
                    <div class="invalid-feedback">
                        {{ $errors->first('guuid') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.handPack.fields.guuid_helper') }}</span>
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