@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.embarque.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.embarques.update", [$embarque->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label>{{ trans('cruds.embarque.fields.temporada') }}</label>
                <select class="form-control {{ $errors->has('temporada') ? 'is-invalid' : '' }}" name="temporada" id="temporada">
                    <option value disabled {{ old('temporada', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Embarque::TEMPORADA_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('temporada', $embarque->temporada) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('temporada'))
                    <div class="invalid-feedback">
                        {{ $errors->first('temporada') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.temporada_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="num_embarque">{{ trans('cruds.embarque.fields.num_embarque') }}</label>
                <input class="form-control {{ $errors->has('num_embarque') ? 'is-invalid' : '' }}" type="text" name="num_embarque" id="num_embarque" value="{{ old('num_embarque', $embarque->num_embarque) }}" required>
                @if($errors->has('num_embarque'))
                    <div class="invalid-feedback">
                        {{ $errors->first('num_embarque') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.num_embarque_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="id_cliente">{{ trans('cruds.embarque.fields.id_cliente') }}</label>
                <input class="form-control {{ $errors->has('id_cliente') ? 'is-invalid' : '' }}" type="text" name="id_cliente" id="id_cliente" value="{{ old('id_cliente', $embarque->id_cliente) }}" required>
                @if($errors->has('id_cliente'))
                    <div class="invalid-feedback">
                        {{ $errors->first('id_cliente') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.id_cliente_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="n_cliente">{{ trans('cruds.embarque.fields.n_cliente') }}</label>
                <input class="form-control {{ $errors->has('n_cliente') ? 'is-invalid' : '' }}" type="text" name="n_cliente" id="n_cliente" value="{{ old('n_cliente', $embarque->n_cliente) }}" required>
                @if($errors->has('n_cliente'))
                    <div class="invalid-feedback">
                        {{ $errors->first('n_cliente') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.n_cliente_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="planta_carga">{{ trans('cruds.embarque.fields.planta_carga') }}</label>
                <input class="form-control {{ $errors->has('planta_carga') ? 'is-invalid' : '' }}" type="text" name="planta_carga" id="planta_carga" value="{{ old('planta_carga', $embarque->planta_carga) }}">
                @if($errors->has('planta_carga'))
                    <div class="invalid-feedback">
                        {{ $errors->first('planta_carga') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.planta_carga_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="n_naviera">{{ trans('cruds.embarque.fields.n_naviera') }}</label>
                <input class="form-control {{ $errors->has('n_naviera') ? 'is-invalid' : '' }}" type="text" name="n_naviera" id="n_naviera" value="{{ old('n_naviera', $embarque->n_naviera) }}">
                @if($errors->has('n_naviera'))
                    <div class="invalid-feedback">
                        {{ $errors->first('n_naviera') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.n_naviera_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nave">{{ trans('cruds.embarque.fields.nave') }}</label>
                <input class="form-control {{ $errors->has('nave') ? 'is-invalid' : '' }}" type="text" name="nave" id="nave" value="{{ old('nave', $embarque->nave) }}">
                @if($errors->has('nave'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nave') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.nave_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="num_contenedor">{{ trans('cruds.embarque.fields.num_contenedor') }}</label>
                <input class="form-control {{ $errors->has('num_contenedor') ? 'is-invalid' : '' }}" type="text" name="num_contenedor" id="num_contenedor" value="{{ old('num_contenedor', $embarque->num_contenedor) }}">
                @if($errors->has('num_contenedor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('num_contenedor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.num_contenedor_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="especie">{{ trans('cruds.embarque.fields.especie') }}</label>
                <input class="form-control {{ $errors->has('especie') ? 'is-invalid' : '' }}" type="text" name="especie" id="especie" value="{{ old('especie', $embarque->especie) }}">
                @if($errors->has('especie'))
                    <div class="invalid-feedback">
                        {{ $errors->first('especie') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.especie_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="variedad">{{ trans('cruds.embarque.fields.variedad') }}</label>
                <input class="form-control {{ $errors->has('variedad') ? 'is-invalid' : '' }}" type="text" name="variedad" id="variedad" value="{{ old('variedad', $embarque->variedad) }}">
                @if($errors->has('variedad'))
                    <div class="invalid-feedback">
                        {{ $errors->first('variedad') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.variedad_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="embalajes">{{ trans('cruds.embarque.fields.embalajes') }}</label>
                <input class="form-control {{ $errors->has('embalajes') ? 'is-invalid' : '' }}" type="text" name="embalajes" id="embalajes" value="{{ old('embalajes', $embarque->embalajes) }}">
                @if($errors->has('embalajes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('embalajes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.embalajes_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="etiqueta">{{ trans('cruds.embarque.fields.etiqueta') }}</label>
                <input class="form-control {{ $errors->has('etiqueta') ? 'is-invalid' : '' }}" type="text" name="etiqueta" id="etiqueta" value="{{ old('etiqueta', $embarque->etiqueta) }}">
                @if($errors->has('etiqueta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('etiqueta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.etiqueta_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cajas">{{ trans('cruds.embarque.fields.cajas') }}</label>
                <input class="form-control {{ $errors->has('cajas') ? 'is-invalid' : '' }}" type="text" name="cajas" id="cajas" value="{{ old('cajas', $embarque->cajas) }}">
                @if($errors->has('cajas'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cajas') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.cajas_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="peso_neto">{{ trans('cruds.embarque.fields.peso_neto') }}</label>
                <input class="form-control {{ $errors->has('peso_neto') ? 'is-invalid' : '' }}" type="text" name="peso_neto" id="peso_neto" value="{{ old('peso_neto', $embarque->peso_neto) }}">
                @if($errors->has('peso_neto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('peso_neto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.peso_neto_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="puerto_embarque">{{ trans('cruds.embarque.fields.puerto_embarque') }}</label>
                <input class="form-control {{ $errors->has('puerto_embarque') ? 'is-invalid' : '' }}" type="text" name="puerto_embarque" id="puerto_embarque" value="{{ old('puerto_embarque', $embarque->puerto_embarque) }}">
                @if($errors->has('puerto_embarque'))
                    <div class="invalid-feedback">
                        {{ $errors->first('puerto_embarque') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.puerto_embarque_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="pais_destino">{{ trans('cruds.embarque.fields.pais_destino') }}</label>
                <input class="form-control {{ $errors->has('pais_destino') ? 'is-invalid' : '' }}" type="text" name="pais_destino" id="pais_destino" value="{{ old('pais_destino', $embarque->pais_destino) }}">
                @if($errors->has('pais_destino'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pais_destino') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.pais_destino_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="puerto_destino">{{ trans('cruds.embarque.fields.puerto_destino') }}</label>
                <input class="form-control {{ $errors->has('puerto_destino') ? 'is-invalid' : '' }}" type="text" name="puerto_destino" id="puerto_destino" value="{{ old('puerto_destino', $embarque->puerto_destino) }}">
                @if($errors->has('puerto_destino'))
                    <div class="invalid-feedback">
                        {{ $errors->first('puerto_destino') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.puerto_destino_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="mercado">{{ trans('cruds.embarque.fields.mercado') }}</label>
                <input class="form-control {{ $errors->has('mercado') ? 'is-invalid' : '' }}" type="text" name="mercado" id="mercado" value="{{ old('mercado', $embarque->mercado) }}">
                @if($errors->has('mercado'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mercado') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.mercado_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="etd_estimado">{{ trans('cruds.embarque.fields.etd_estimado') }}</label>
                <input class="form-control date {{ $errors->has('etd_estimado') ? 'is-invalid' : '' }}" type="text" name="etd_estimado" id="etd_estimado" value="{{ old('etd_estimado', $embarque->etd_estimado) }}">
                @if($errors->has('etd_estimado'))
                    <div class="invalid-feedback">
                        {{ $errors->first('etd_estimado') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.etd_estimado_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="eta_estimado">{{ trans('cruds.embarque.fields.eta_estimado') }}</label>
                <input class="form-control {{ $errors->has('eta_estimado') ? 'is-invalid' : '' }}" type="text" name="eta_estimado" id="eta_estimado" value="{{ old('eta_estimado', $embarque->eta_estimado) }}">
                @if($errors->has('eta_estimado'))
                    <div class="invalid-feedback">
                        {{ $errors->first('eta_estimado') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.eta_estimado_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fecha_zarpe_real">{{ trans('cruds.embarque.fields.fecha_zarpe_real') }}</label>
                <input class="form-control date {{ $errors->has('fecha_zarpe_real') ? 'is-invalid' : '' }}" type="text" name="fecha_zarpe_real" id="fecha_zarpe_real" value="{{ old('fecha_zarpe_real', $embarque->fecha_zarpe_real) }}">
                @if($errors->has('fecha_zarpe_real'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha_zarpe_real') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.fecha_zarpe_real_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fecha_arribo_real">{{ trans('cruds.embarque.fields.fecha_arribo_real') }}</label>
                <input class="form-control date {{ $errors->has('fecha_arribo_real') ? 'is-invalid' : '' }}" type="text" name="fecha_arribo_real" id="fecha_arribo_real" value="{{ old('fecha_arribo_real', $embarque->fecha_arribo_real) }}">
                @if($errors->has('fecha_arribo_real'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha_arribo_real') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.fecha_arribo_real_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="dias_transito_real">{{ trans('cruds.embarque.fields.dias_transito_real') }}</label>
                <input class="form-control {{ $errors->has('dias_transito_real') ? 'is-invalid' : '' }}" type="number" name="dias_transito_real" id="dias_transito_real" value="{{ old('dias_transito_real', $embarque->dias_transito_real) }}" step="1">
                @if($errors->has('dias_transito_real'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dias_transito_real') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.dias_transito_real_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.embarque.fields.estado') }}</label>
                <select class="form-control {{ $errors->has('estado') ? 'is-invalid' : '' }}" name="estado" id="estado">
                    <option value disabled {{ old('estado', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Embarque::ESTADO_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('estado', $embarque->estado) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('estado'))
                    <div class="invalid-feedback">
                        {{ $errors->first('estado') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.estado_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="descargado">{{ trans('cruds.embarque.fields.descargado') }}</label>
                <input class="form-control {{ $errors->has('descargado') ? 'is-invalid' : '' }}" type="text" name="descargado" id="descargado" value="{{ old('descargado', $embarque->descargado) }}">
                @if($errors->has('descargado'))
                    <div class="invalid-feedback">
                        {{ $errors->first('descargado') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.descargado_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="retirado_full">{{ trans('cruds.embarque.fields.retirado_full') }}</label>
                <input class="form-control {{ $errors->has('retirado_full') ? 'is-invalid' : '' }}" type="text" name="retirado_full" id="retirado_full" value="{{ old('retirado_full', $embarque->retirado_full) }}">
                @if($errors->has('retirado_full'))
                    <div class="invalid-feedback">
                        {{ $errors->first('retirado_full') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.retirado_full_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="devuelto_vacio">{{ trans('cruds.embarque.fields.devuelto_vacio') }}</label>
                <input class="form-control {{ $errors->has('devuelto_vacio') ? 'is-invalid' : '' }}" type="text" name="devuelto_vacio" id="devuelto_vacio" value="{{ old('devuelto_vacio', $embarque->devuelto_vacio) }}">
                @if($errors->has('devuelto_vacio'))
                    <div class="invalid-feedback">
                        {{ $errors->first('devuelto_vacio') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.devuelto_vacio_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="notas">{{ trans('cruds.embarque.fields.notas') }}</label>
                <textarea class="form-control {{ $errors->has('notas') ? 'is-invalid' : '' }}" name="notas" id="notas">{{ old('notas', $embarque->notas) }}</textarea>
                @if($errors->has('notas'))
                    <div class="invalid-feedback">
                        {{ $errors->first('notas') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.notas_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="calificacion">{{ trans('cruds.embarque.fields.calificacion') }}</label>
                <input class="form-control {{ $errors->has('calificacion') ? 'is-invalid' : '' }}" type="text" name="calificacion" id="calificacion" value="{{ old('calificacion', $embarque->calificacion) }}">
                @if($errors->has('calificacion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('calificacion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.calificacion_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="conexiones">{{ trans('cruds.embarque.fields.conexiones') }}</label>
                <input class="form-control {{ $errors->has('conexiones') ? 'is-invalid' : '' }}" type="text" name="conexiones" id="conexiones" value="{{ old('conexiones', $embarque->conexiones) }}">
                @if($errors->has('conexiones'))
                    <div class="invalid-feedback">
                        {{ $errors->first('conexiones') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.conexiones_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="con_fecha_hora">{{ trans('cruds.embarque.fields.con_fecha_hora') }}</label>
                <input class="form-control datetime {{ $errors->has('con_fecha_hora') ? 'is-invalid' : '' }}" type="text" name="con_fecha_hora" id="con_fecha_hora" value="{{ old('con_fecha_hora', $embarque->con_fecha_hora) }}">
                @if($errors->has('con_fecha_hora'))
                    <div class="invalid-feedback">
                        {{ $errors->first('con_fecha_hora') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.con_fecha_hora_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.embarque.fields.status_aereo') }}</label>
                <select class="form-control {{ $errors->has('status_aereo') ? 'is-invalid' : '' }}" name="status_aereo" id="status_aereo">
                    <option value disabled {{ old('status_aereo', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Embarque::STATUS_AEREO_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status_aereo', $embarque->status_aereo) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status_aereo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status_aereo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.status_aereo_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="num_pallets">{{ trans('cruds.embarque.fields.num_pallets') }}</label>
                <input class="form-control {{ $errors->has('num_pallets') ? 'is-invalid' : '' }}" type="text" name="num_pallets" id="num_pallets" value="{{ old('num_pallets', $embarque->num_pallets) }}">
                @if($errors->has('num_pallets'))
                    <div class="invalid-feedback">
                        {{ $errors->first('num_pallets') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.num_pallets_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="embalaje_std">{{ trans('cruds.embarque.fields.embalaje_std') }}</label>
                <input class="form-control {{ $errors->has('embalaje_std') ? 'is-invalid' : '' }}" type="text" name="embalaje_std" id="embalaje_std" value="{{ old('embalaje_std', $embarque->embalaje_std) }}">
                @if($errors->has('embalaje_std'))
                    <div class="invalid-feedback">
                        {{ $errors->first('embalaje_std') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.embalaje_std_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="num_orden">{{ trans('cruds.embarque.fields.num_orden') }}</label>
                <input class="form-control {{ $errors->has('num_orden') ? 'is-invalid' : '' }}" type="text" name="num_orden" id="num_orden" value="{{ old('num_orden', $embarque->num_orden) }}">
                @if($errors->has('num_orden'))
                    <div class="invalid-feedback">
                        {{ $errors->first('num_orden') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.num_orden_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tipo_especie">{{ trans('cruds.embarque.fields.tipo_especie') }}</label>
                <input class="form-control {{ $errors->has('tipo_especie') ? 'is-invalid' : '' }}" type="text" name="tipo_especie" id="tipo_especie" value="{{ old('tipo_especie', $embarque->tipo_especie) }}">
                @if($errors->has('tipo_especie'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo_especie') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.embarque.fields.tipo_especie_helper') }}</span>
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