@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.recibeMaster.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.recibe-masters.update', [$recibeMaster->id]) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label class="required" for="especie">{{ trans('cruds.recibeMaster.fields.especie') }}</label>
                    <input class="form-control {{ $errors->has('especie') ? 'is-invalid' : '' }}" type="number"
                        name="especie" id="especie" value="{{ old('especie', $recibeMaster->especie) }}" step="1"
                        required>
                    @if ($errors->has('especie'))
                        <div class="invalid-feedback">
                            {{ $errors->first('especie') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.especie_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="exportador">{{ trans('cruds.recibeMaster.fields.exportador') }}</label>
                    <input class="form-control {{ $errors->has('exportador') ? 'is-invalid' : '' }}" type="number"
                        name="exportador" id="exportador" value="{{ old('exportador', $recibeMaster->exportador) }}"
                        step="1" required>
                    @if ($errors->has('exportador'))
                        <div class="invalid-feedback">
                            {{ $errors->first('exportador') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.exportador_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="partida">{{ trans('cruds.recibeMaster.fields.partida') }}</label>
                    <input class="form-control {{ $errors->has('partida') ? 'is-invalid' : '' }}" type="number"
                        name="partida" id="partida" value="{{ old('partida', $recibeMaster->partida) }}" step="1"
                        required>
                    @if ($errors->has('partida'))
                        <div class="invalid-feedback">
                            {{ $errors->first('partida') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.partida_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required">{{ trans('cruds.recibeMaster.fields.estado') }}</label>
                    <select class="form-control {{ $errors->has('estado') ? 'is-invalid' : '' }}" name="estado"
                        id="estado" required>
                        <option value disabled {{ old('estado', null) === null ? 'selected' : '' }}>
                            {{ trans('global.pleaseSelect') }}</option>
                        @foreach (App\Models\RecibeMaster::ESTADO_SELECT as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('estado', $recibeMaster->estado) === (string) $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('estado'))
                        <div class="invalid-feedback">
                            {{ $errors->first('estado') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.estado_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="cod_central">{{ trans('cruds.recibeMaster.fields.cod_central') }}</label>
                    <input class="form-control {{ $errors->has('cod_central') ? 'is-invalid' : '' }}" type="number"
                        name="cod_central" id="cod_central" value="{{ old('cod_central', $recibeMaster->cod_central) }}"
                        step="1" required>
                    @if ($errors->has('cod_central'))
                        <div class="invalid-feedback">
                            {{ $errors->first('cod_central') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.cod_central_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="cod_productor">{{ trans('cruds.recibeMaster.fields.cod_productor') }}</label>
                    <input class="form-control {{ $errors->has('cod_productor') ? 'is-invalid' : '' }}" type="text"
                        name="cod_productor" id="cod_productor"
                        value="{{ old('cod_productor', $recibeMaster->cod_productor) }}" required>
                    @if ($errors->has('cod_productor'))
                        <div class="invalid-feedback">
                            {{ $errors->first('cod_productor') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.cod_productor_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="nro_guia_despacho">{{ trans('cruds.recibeMaster.fields.nro_guia_despacho') }}</label>
                    <input class="form-control {{ $errors->has('nro_guia_despacho') ? 'is-invalid' : '' }}" type="text"
                        name="nro_guia_despacho" id="nro_guia_despacho"
                        value="{{ old('nro_guia_despacho', $recibeMaster->nro_guia_despacho) }}" required>
                    @if ($errors->has('nro_guia_despacho'))
                        <div class="invalid-feedback">
                            {{ $errors->first('nro_guia_despacho') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.nro_guia_despacho_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="fecha_recepcion">{{ trans('cruds.recibeMaster.fields.fecha_recepcion') }}</label>
                    <input class="form-control date {{ $errors->has('fecha_recepcion') ? 'is-invalid' : '' }}"
                        type="text" name="fecha_recepcion" id="fecha_recepcion"
                        value="{{ old('fecha_recepcion', $recibeMaster->fecha_recepcion) }}" required>
                    @if ($errors->has('fecha_recepcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('fecha_recepcion') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.fecha_recepcion_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="fecha_cosecha">{{ trans('cruds.recibeMaster.fields.fecha_cosecha') }}</label>
                    <input class="form-control date {{ $errors->has('fecha_cosecha') ? 'is-invalid' : '' }}" type="text"
                        name="fecha_cosecha" id="fecha_cosecha"
                        value="{{ old('fecha_cosecha', $recibeMaster->fecha_cosecha) }}" required>
                    @if ($errors->has('fecha_cosecha'))
                        <div class="invalid-feedback">
                            {{ $errors->first('fecha_cosecha') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.fecha_cosecha_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="cod_variedad">{{ trans('cruds.recibeMaster.fields.cod_variedad') }}</label>
                    <input class="form-control {{ $errors->has('cod_variedad') ? 'is-invalid' : '' }}" type="number"
                        name="cod_variedad" id="cod_variedad"
                        value="{{ old('cod_variedad', $recibeMaster->cod_variedad) }}" step="1" required>
                    @if ($errors->has('cod_variedad'))
                        <div class="invalid-feedback">
                            {{ $errors->first('cod_variedad') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.cod_variedad_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required">{{ trans('cruds.recibeMaster.fields.estiba_camion') }}</label>
                    <select class="form-control {{ $errors->has('estiba_camion') ? 'is-invalid' : '' }}"
                        name="estiba_camion" id="estiba_camion" required>
                        <option value disabled {{ old('estiba_camion', null) === null ? 'selected' : '' }}>
                            {{ trans('global.pleaseSelect') }}</option>
                        @foreach (App\Models\RecibeMaster::ESTIBA_CAMION_SELECT as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('estiba_camion', $recibeMaster->estiba_camion) === (string) $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('estiba_camion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('estiba_camion') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.estiba_camion_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required">{{ trans('cruds.recibeMaster.fields.esponjas_cloradas') }}</label>
                    <select class="form-control {{ $errors->has('esponjas_cloradas') ? 'is-invalid' : '' }}"
                        name="esponjas_cloradas" id="esponjas_cloradas" required>
                        <option value disabled {{ old('esponjas_cloradas', null) === null ? 'selected' : '' }}>
                            {{ trans('global.pleaseSelect') }}</option>
                        @foreach (App\Models\RecibeMaster::ESPONJAS_CLORADAS_SELECT as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('esponjas_cloradas', $recibeMaster->esponjas_cloradas) === (string) $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('esponjas_cloradas'))
                        <div class="invalid-feedback">
                            {{ $errors->first('esponjas_cloradas') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.esponjas_cloradas_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="nro_bandeja">{{ trans('cruds.recibeMaster.fields.nro_bandeja') }}</label>
                    <input class="form-control {{ $errors->has('nro_bandeja') ? 'is-invalid' : '' }}" type="number"
                        name="nro_bandeja" id="nro_bandeja" value="{{ old('nro_bandeja', $recibeMaster->nro_bandeja) }}"
                        step="1" required>
                    @if ($errors->has('nro_bandeja'))
                        <div class="invalid-feedback">
                            {{ $errors->first('nro_bandeja') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.nro_bandeja_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="hora_llegada">{{ trans('cruds.recibeMaster.fields.hora_llegada') }}</label>
                    <input class="form-control timepicker {{ $errors->has('hora_llegada') ? 'is-invalid' : '' }}"
                        type="text" name="hora_llegada" id="hora_llegada"
                        value="{{ old('hora_llegada', $recibeMaster->hora_llegada) }}" required>
                    @if ($errors->has('hora_llegada'))
                        <div class="invalid-feedback">
                            {{ $errors->first('hora_llegada') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.hora_llegada_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="kilo_muestra">{{ trans('cruds.recibeMaster.fields.kilo_muestra') }}</label>
                    <input class="form-control {{ $errors->has('kilo_muestra') ? 'is-invalid' : '' }}" type="number"
                        name="kilo_muestra" id="kilo_muestra"
                        value="{{ old('kilo_muestra', $recibeMaster->kilo_muestra) }}" step="0.01" required>
                    @if ($errors->has('kilo_muestra'))
                        <div class="invalid-feedback">
                            {{ $errors->first('kilo_muestra') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.kilo_muestra_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="kilo_neto">{{ trans('cruds.recibeMaster.fields.kilo_neto') }}</label>
                    <input class="form-control {{ $errors->has('kilo_neto') ? 'is-invalid' : '' }}" type="number"
                        name="kilo_neto" id="kilo_neto" value="{{ old('kilo_neto', $recibeMaster->kilo_neto) }}"
                        step="0.01" required>
                    @if ($errors->has('kilo_neto'))
                        <div class="invalid-feedback">
                            {{ $errors->first('kilo_neto') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.kilo_neto_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="temp_ingreso">{{ trans('cruds.recibeMaster.fields.temp_ingreso') }}</label>
                    <input class="form-control {{ $errors->has('temp_ingreso') ? 'is-invalid' : '' }}" type="number"
                        name="temp_ingreso" id="temp_ingreso"
                        value="{{ old('temp_ingreso', $recibeMaster->temp_ingreso) }}" step="0.01" required>
                    @if ($errors->has('temp_ingreso'))
                        <div class="invalid-feedback">
                            {{ $errors->first('temp_ingreso') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.temp_ingreso_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="temp_salida">{{ trans('cruds.recibeMaster.fields.temp_salida') }}</label>
                    <input class="form-control {{ $errors->has('temp_salida') ? 'is-invalid' : '' }}" type="number"
                        name="temp_salida" id="temp_salida"
                        value="{{ old('temp_salida', $recibeMaster->temp_salida) }}" step="0.01" required>
                    @if ($errors->has('temp_salida'))
                        <div class="invalid-feedback">
                            {{ $errors->first('temp_salida') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.temp_salida_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="lote">{{ trans('cruds.recibeMaster.fields.lote') }}</label>
                    <input class="form-control {{ $errors->has('lote') ? 'is-invalid' : '' }}" type="text"
                        name="lote" id="lote" value="{{ old('lote', $recibeMaster->lote) }}" required>
                    @if ($errors->has('lote'))
                        <div class="invalid-feedback">
                            {{ $errors->first('lote') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.lote_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="huerto">{{ trans('cruds.recibeMaster.fields.huerto') }}</label>
                    <input class="form-control {{ $errors->has('huerto') ? 'is-invalid' : '' }}" type="text"
                        name="huerto" id="huerto" value="{{ old('huerto', $recibeMaster->huerto) }}" required>
                    @if ($errors->has('huerto'))
                        <div class="invalid-feedback">
                            {{ $errors->first('huerto') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.huerto_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="hidro">{{ trans('cruds.recibeMaster.fields.hidro') }}</label>
                    <input class="form-control {{ $errors->has('hidro') ? 'is-invalid' : '' }}" type="text"
                        name="hidro" id="hidro" value="{{ old('hidro', $recibeMaster->hidro) }}" required>
                    @if ($errors->has('hidro'))
                        <div class="invalid-feedback">
                            {{ $errors->first('hidro') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.hidro_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="fecha_envio">{{ trans('cruds.recibeMaster.fields.fecha_envio') }}</label>
                    <input class="form-control {{ $errors->has('fecha_envio') ? 'is-invalid' : '' }}" type="text"
                        name="fecha_envio" id="fecha_envio"
                        value="{{ old('fecha_envio', $recibeMaster->fecha_envio) }}">
                    @if ($errors->has('fecha_envio'))
                        <div class="invalid-feedback">
                            {{ $errors->first('fecha_envio') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.fecha_envio_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="respuesta_envio">{{ trans('cruds.recibeMaster.fields.respuesta_envio') }}</label>
                    <input class="form-control {{ $errors->has('respuesta_envio') ? 'is-invalid' : '' }}" type="text"
                        name="respuesta_envio" id="respuesta_envio"
                        value="{{ old('respuesta_envio', $recibeMaster->respuesta_envio) }}">
                    @if ($errors->has('respuesta_envio'))
                        <div class="invalid-feedback">
                            {{ $errors->first('respuesta_envio') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.recibeMaster.fields.respuesta_envio_helper') }}</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        Guardar y Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
