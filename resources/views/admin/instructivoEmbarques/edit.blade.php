@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.instructivoEmbarque.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.instructivo-embarques.update", [$instructivoEmbarque->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="instructivo">{{ trans('cruds.instructivoEmbarque.fields.instructivo') }}</label>
                <input class="form-control {{ $errors->has('instructivo') ? 'is-invalid' : '' }}" type="text" name="instructivo" id="instructivo" value="{{ old('instructivo', $instructivoEmbarque->instructivo) }}" required>
                @if($errors->has('instructivo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('instructivo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.instructivo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="fecha">{{ trans('cruds.instructivoEmbarque.fields.fecha') }}</label>
                <input class="form-control date {{ $errors->has('fecha') ? 'is-invalid' : '' }}" type="text" name="fecha" id="fecha" value="{{ old('fecha', $instructivoEmbarque->fecha) }}" required>
                @if($errors->has('fecha'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.fecha_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="embarcador_id">{{ trans('cruds.instructivoEmbarque.fields.embarcador') }}</label>
                <select class="form-control select2 {{ $errors->has('embarcador') ? 'is-invalid' : '' }}" name="embarcador_id" id="embarcador_id" required>
                    @foreach($embarcadors as $id => $entry)
                        <option value="{{ $id }}" {{ (old('embarcador_id') ? old('embarcador_id') : $instructivoEmbarque->embarcador->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('embarcador'))
                    <div class="invalid-feedback">
                        {{ $errors->first('embarcador') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.embarcador_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="agente_aduana_id">{{ trans('cruds.instructivoEmbarque.fields.agente_aduana') }}</label>
                <select class="form-control select2 {{ $errors->has('agente_aduana') ? 'is-invalid' : '' }}" name="agente_aduana_id" id="agente_aduana_id" required>
                    @foreach($agente_aduanas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('agente_aduana_id') ? old('agente_aduana_id') : $instructivoEmbarque->agente_aduana->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('agente_aduana'))
                    <div class="invalid-feedback">
                        {{ $errors->first('agente_aduana') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.agente_aduana_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="consignee_id">{{ trans('cruds.instructivoEmbarque.fields.consignee') }}</label>
                <select class="form-control select2 {{ $errors->has('consignee') ? 'is-invalid' : '' }}" name="consignee_id" id="consignee_id" required>
                    @foreach($consignees as $id => $entry)
                        <option value="{{ $id }}" {{ (old('consignee_id') ? old('consignee_id') : $instructivoEmbarque->consignee->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('consignee'))
                    <div class="invalid-feedback">
                        {{ $errors->first('consignee') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.consignee_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="naviera_id">{{ trans('cruds.instructivoEmbarque.fields.naviera') }}</label>
                <select class="form-control select2 {{ $errors->has('naviera') ? 'is-invalid' : '' }}" name="naviera_id" id="naviera_id">
                    @foreach($navieras as $id => $entry)
                        <option value="{{ $id }}" {{ (old('naviera_id') ? old('naviera_id') : $instructivoEmbarque->naviera->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('naviera'))
                    <div class="invalid-feedback">
                        {{ $errors->first('naviera') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.naviera_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="num_booking">{{ trans('cruds.instructivoEmbarque.fields.num_booking') }}</label>
                <input class="form-control {{ $errors->has('num_booking') ? 'is-invalid' : '' }}" type="text" name="num_booking" id="num_booking" value="{{ old('num_booking', $instructivoEmbarque->num_booking) }}" required>
                @if($errors->has('num_booking'))
                    <div class="invalid-feedback">
                        {{ $errors->first('num_booking') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.num_booking_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nave">{{ trans('cruds.instructivoEmbarque.fields.nave') }}</label>
                <input class="form-control {{ $errors->has('nave') ? 'is-invalid' : '' }}" type="text" name="nave" id="nave" value="{{ old('nave', $instructivoEmbarque->nave) }}">
                @if($errors->has('nave'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nave') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.nave_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="cut_off">{{ trans('cruds.instructivoEmbarque.fields.cut_off') }}</label>
                <input class="form-control {{ $errors->has('cut_off') ? 'is-invalid' : '' }}" type="text" name="cut_off" id="cut_off" value="{{ old('cut_off', $instructivoEmbarque->cut_off) }}" required>
                @if($errors->has('cut_off'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cut_off') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.cut_off_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="stacking_ini">{{ trans('cruds.instructivoEmbarque.fields.stacking_ini') }}</label>
                <input class="form-control datetime {{ $errors->has('stacking_ini') ? 'is-invalid' : '' }}" type="text" name="stacking_ini" id="stacking_ini" value="{{ old('stacking_ini', $instructivoEmbarque->stacking_ini) }}" required>
                @if($errors->has('stacking_ini'))
                    <div class="invalid-feedback">
                        {{ $errors->first('stacking_ini') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.stacking_ini_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="stacking_end">{{ trans('cruds.instructivoEmbarque.fields.stacking_end') }}</label>
                <input class="form-control datetime {{ $errors->has('stacking_end') ? 'is-invalid' : '' }}" type="text" name="stacking_end" id="stacking_end" value="{{ old('stacking_end', $instructivoEmbarque->stacking_end) }}">
                @if($errors->has('stacking_end'))
                    <div class="invalid-feedback">
                        {{ $errors->first('stacking_end') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.stacking_end_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="etd">{{ trans('cruds.instructivoEmbarque.fields.etd') }}</label>
                <input class="form-control date {{ $errors->has('etd') ? 'is-invalid' : '' }}" type="text" name="etd" id="etd" value="{{ old('etd', $instructivoEmbarque->etd) }}">
                @if($errors->has('etd'))
                    <div class="invalid-feedback">
                        {{ $errors->first('etd') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.etd_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="eta">{{ trans('cruds.instructivoEmbarque.fields.eta') }}</label>
                <input class="form-control date {{ $errors->has('eta') ? 'is-invalid' : '' }}" type="text" name="eta" id="eta" value="{{ old('eta', $instructivoEmbarque->eta) }}">
                @if($errors->has('eta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('eta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.eta_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="puerto_embarque_id">{{ trans('cruds.instructivoEmbarque.fields.puerto_embarque') }}</label>
                <select class="form-control select2 {{ $errors->has('puerto_embarque') ? 'is-invalid' : '' }}" name="puerto_embarque_id" id="puerto_embarque_id" required>
                    @foreach($puerto_embarques as $id => $entry)
                        <option value="{{ $id }}" {{ (old('puerto_embarque_id') ? old('puerto_embarque_id') : $instructivoEmbarque->puerto_embarque->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('puerto_embarque'))
                    <div class="invalid-feedback">
                        {{ $errors->first('puerto_embarque') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.puerto_embarque_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="puerto_destino_id">{{ trans('cruds.instructivoEmbarque.fields.puerto_destino') }}</label>
                <select class="form-control select2 {{ $errors->has('puerto_destino') ? 'is-invalid' : '' }}" name="puerto_destino_id" id="puerto_destino_id">
                    @foreach($puerto_destinos as $id => $entry)
                        <option value="{{ $id }}" {{ (old('puerto_destino_id') ? old('puerto_destino_id') : $instructivoEmbarque->puerto_destino->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('puerto_destino'))
                    <div class="invalid-feedback">
                        {{ $errors->first('puerto_destino') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.puerto_destino_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="puerto_descarga_id">{{ trans('cruds.instructivoEmbarque.fields.puerto_descarga') }}</label>
                <select class="form-control select2 {{ $errors->has('puerto_descarga') ? 'is-invalid' : '' }}" name="puerto_descarga_id" id="puerto_descarga_id">
                    @foreach($puerto_descargas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('puerto_descarga_id') ? old('puerto_descarga_id') : $instructivoEmbarque->puerto_descarga->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('puerto_descarga'))
                    <div class="invalid-feedback">
                        {{ $errors->first('puerto_descarga') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.puerto_descarga_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="punto_de_entrada">{{ trans('cruds.instructivoEmbarque.fields.punto_de_entrada') }}</label>
                <input class="form-control {{ $errors->has('punto_de_entrada') ? 'is-invalid' : '' }}" type="text" name="punto_de_entrada" id="punto_de_entrada" value="{{ old('punto_de_entrada', $instructivoEmbarque->punto_de_entrada) }}">
                @if($errors->has('punto_de_entrada'))
                    <div class="invalid-feedback">
                        {{ $errors->first('punto_de_entrada') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.punto_de_entrada_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="num_contenedor">{{ trans('cruds.instructivoEmbarque.fields.num_contenedor') }}</label>
                <input class="form-control {{ $errors->has('num_contenedor') ? 'is-invalid' : '' }}" type="text" name="num_contenedor" id="num_contenedor" value="{{ old('num_contenedor', $instructivoEmbarque->num_contenedor) }}" required>
                @if($errors->has('num_contenedor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('num_contenedor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.num_contenedor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="ventilacion">{{ trans('cruds.instructivoEmbarque.fields.ventilacion') }}</label>
                <input class="form-control {{ $errors->has('ventilacion') ? 'is-invalid' : '' }}" type="text" name="ventilacion" id="ventilacion" value="{{ old('ventilacion', $instructivoEmbarque->ventilacion) }}" required>
                @if($errors->has('ventilacion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ventilacion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.ventilacion_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tara_contenedor">{{ trans('cruds.instructivoEmbarque.fields.tara_contenedor') }}</label>
                <input class="form-control {{ $errors->has('tara_contenedor') ? 'is-invalid' : '' }}" type="text" name="tara_contenedor" id="tara_contenedor" value="{{ old('tara_contenedor', $instructivoEmbarque->tara_contenedor) }}">
                @if($errors->has('tara_contenedor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tara_contenedor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.tara_contenedor_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="quest">{{ trans('cruds.instructivoEmbarque.fields.quest') }}</label>
                <input class="form-control {{ $errors->has('quest') ? 'is-invalid' : '' }}" type="text" name="quest" id="quest" value="{{ old('quest', $instructivoEmbarque->quest) }}">
                @if($errors->has('quest'))
                    <div class="invalid-feedback">
                        {{ $errors->first('quest') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.quest_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="num_sello">{{ trans('cruds.instructivoEmbarque.fields.num_sello') }}</label>
                <input class="form-control {{ $errors->has('num_sello') ? 'is-invalid' : '' }}" type="text" name="num_sello" id="num_sello" value="{{ old('num_sello', $instructivoEmbarque->num_sello) }}">
                @if($errors->has('num_sello'))
                    <div class="invalid-feedback">
                        {{ $errors->first('num_sello') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.num_sello_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="temperatura">{{ trans('cruds.instructivoEmbarque.fields.temperatura') }}</label>
                <input class="form-control {{ $errors->has('temperatura') ? 'is-invalid' : '' }}" type="number" name="temperatura" id="temperatura" value="{{ old('temperatura', $instructivoEmbarque->temperatura) }}" step="0.01">
                @if($errors->has('temperatura'))
                    <div class="invalid-feedback">
                        {{ $errors->first('temperatura') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.temperatura_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="empresa_transportista">{{ trans('cruds.instructivoEmbarque.fields.empresa_transportista') }}</label>
                <input class="form-control {{ $errors->has('empresa_transportista') ? 'is-invalid' : '' }}" type="text" name="empresa_transportista" id="empresa_transportista" value="{{ old('empresa_transportista', $instructivoEmbarque->empresa_transportista) }}" required>
                @if($errors->has('empresa_transportista'))
                    <div class="invalid-feedback">
                        {{ $errors->first('empresa_transportista') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.empresa_transportista_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="conductor_id">{{ trans('cruds.instructivoEmbarque.fields.conductor') }}</label>
                <select class="form-control select2 {{ $errors->has('conductor') ? 'is-invalid' : '' }}" name="conductor_id" id="conductor_id" required>
                    @foreach($conductors as $id => $entry)
                        <option value="{{ $id }}" {{ (old('conductor_id') ? old('conductor_id') : $instructivoEmbarque->conductor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('conductor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('conductor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.conductor_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="rut_conductor">{{ trans('cruds.instructivoEmbarque.fields.rut_conductor') }}</label>
                <input class="form-control {{ $errors->has('rut_conductor') ? 'is-invalid' : '' }}" type="text" name="rut_conductor" id="rut_conductor" value="{{ old('rut_conductor', $instructivoEmbarque->rut_conductor) }}">
                @if($errors->has('rut_conductor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rut_conductor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.rut_conductor_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ppu">{{ trans('cruds.instructivoEmbarque.fields.ppu') }}</label>
                <input class="form-control {{ $errors->has('ppu') ? 'is-invalid' : '' }}" type="text" name="ppu" id="ppu" value="{{ old('ppu', $instructivoEmbarque->ppu) }}">
                @if($errors->has('ppu'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ppu') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.ppu_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="telefono">{{ trans('cruds.instructivoEmbarque.fields.telefono') }}</label>
                <input class="form-control {{ $errors->has('telefono') ? 'is-invalid' : '' }}" type="text" name="telefono" id="telefono" value="{{ old('telefono', $instructivoEmbarque->telefono) }}">
                @if($errors->has('telefono'))
                    <div class="invalid-feedback">
                        {{ $errors->first('telefono') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.telefono_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="planta_carga_id">{{ trans('cruds.instructivoEmbarque.fields.planta_carga') }}</label>
                <select class="form-control select2 {{ $errors->has('planta_carga') ? 'is-invalid' : '' }}" name="planta_carga_id" id="planta_carga_id">
                    @foreach($planta_cargas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('planta_carga_id') ? old('planta_carga_id') : $instructivoEmbarque->planta_carga->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('planta_carga'))
                    <div class="invalid-feedback">
                        {{ $errors->first('planta_carga') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.planta_carga_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="direccion">{{ trans('cruds.instructivoEmbarque.fields.direccion') }}</label>
                <input class="form-control {{ $errors->has('direccion') ? 'is-invalid' : '' }}" type="text" name="direccion" id="direccion" value="{{ old('direccion', $instructivoEmbarque->direccion) }}" required>
                @if($errors->has('direccion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('direccion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.direccion_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fecha_carga">{{ trans('cruds.instructivoEmbarque.fields.fecha_carga') }}</label>
                <input class="form-control date {{ $errors->has('fecha_carga') ? 'is-invalid' : '' }}" type="text" name="fecha_carga" id="fecha_carga" value="{{ old('fecha_carga', $instructivoEmbarque->fecha_carga) }}">
                @if($errors->has('fecha_carga'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha_carga') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.fecha_carga_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="hora_carga">{{ trans('cruds.instructivoEmbarque.fields.hora_carga') }}</label>
                <input class="form-control timepicker {{ $errors->has('hora_carga') ? 'is-invalid' : '' }}" type="text" name="hora_carga" id="hora_carga" value="{{ old('hora_carga', $instructivoEmbarque->hora_carga) }}" required>
                @if($errors->has('hora_carga'))
                    <div class="invalid-feedback">
                        {{ $errors->first('hora_carga') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.hora_carga_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="guia_despacho_dirigida">{{ trans('cruds.instructivoEmbarque.fields.guia_despacho_dirigida') }}</label>
                <input class="form-control {{ $errors->has('guia_despacho_dirigida') ? 'is-invalid' : '' }}" type="text" name="guia_despacho_dirigida" id="guia_despacho_dirigida" value="{{ old('guia_despacho_dirigida', $instructivoEmbarque->guia_despacho_dirigida) }}" required>
                @if($errors->has('guia_despacho_dirigida'))
                    <div class="invalid-feedback">
                        {{ $errors->first('guia_despacho_dirigida') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.guia_despacho_dirigida_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="planilla_sag_dirigida">{{ trans('cruds.instructivoEmbarque.fields.planilla_sag_dirigida') }}</label>
                <input class="form-control {{ $errors->has('planilla_sag_dirigida') ? 'is-invalid' : '' }}" type="text" name="planilla_sag_dirigida" id="planilla_sag_dirigida" value="{{ old('planilla_sag_dirigida', $instructivoEmbarque->planilla_sag_dirigida) }}" required>
                @if($errors->has('planilla_sag_dirigida'))
                    <div class="invalid-feedback">
                        {{ $errors->first('planilla_sag_dirigida') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.planilla_sag_dirigida_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="num_po">{{ trans('cruds.instructivoEmbarque.fields.num_po') }}</label>
                <input class="form-control {{ $errors->has('num_po') ? 'is-invalid' : '' }}" type="text" name="num_po" id="num_po" value="{{ old('num_po', $instructivoEmbarque->num_po) }}">
                @if($errors->has('num_po'))
                    <div class="invalid-feedback">
                        {{ $errors->first('num_po') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.num_po_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="emision_de_bl_id">{{ trans('cruds.instructivoEmbarque.fields.emision_de_bl') }}</label>
                <select class="form-control select2 {{ $errors->has('emision_de_bl') ? 'is-invalid' : '' }}" name="emision_de_bl_id" id="emision_de_bl_id" required>
                    @foreach($emision_de_bls as $id => $entry)
                        <option value="{{ $id }}" {{ (old('emision_de_bl_id') ? old('emision_de_bl_id') : $instructivoEmbarque->emision_de_bl->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('emision_de_bl'))
                    <div class="invalid-feedback">
                        {{ $errors->first('emision_de_bl') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.emision_de_bl_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tipo_de_flete_id">{{ trans('cruds.instructivoEmbarque.fields.tipo_de_flete') }}</label>
                <select class="form-control select2 {{ $errors->has('tipo_de_flete') ? 'is-invalid' : '' }}" name="tipo_de_flete_id" id="tipo_de_flete_id">
                    @foreach($tipo_de_fletes as $id => $entry)
                        <option value="{{ $id }}" {{ (old('tipo_de_flete_id') ? old('tipo_de_flete_id') : $instructivoEmbarque->tipo_de_flete->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('tipo_de_flete'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo_de_flete') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.tipo_de_flete_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="clausula_de_venta_id">{{ trans('cruds.instructivoEmbarque.fields.clausula_de_venta') }}</label>
                <select class="form-control select2 {{ $errors->has('clausula_de_venta') ? 'is-invalid' : '' }}" name="clausula_de_venta_id" id="clausula_de_venta_id" required>
                    @foreach($clausula_de_ventas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('clausula_de_venta_id') ? old('clausula_de_venta_id') : $instructivoEmbarque->clausula_de_venta->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('clausula_de_venta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('clausula_de_venta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.clausula_de_venta_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="moneda_id">{{ trans('cruds.instructivoEmbarque.fields.moneda') }}</label>
                <select class="form-control select2 {{ $errors->has('moneda') ? 'is-invalid' : '' }}" name="moneda_id" id="moneda_id" required>
                    @foreach($monedas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('moneda_id') ? old('moneda_id') : $instructivoEmbarque->moneda->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('moneda'))
                    <div class="invalid-feedback">
                        {{ $errors->first('moneda') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.moneda_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="forma_de_pago_id">{{ trans('cruds.instructivoEmbarque.fields.forma_de_pago') }}</label>
                <select class="form-control select2 {{ $errors->has('forma_de_pago') ? 'is-invalid' : '' }}" name="forma_de_pago_id" id="forma_de_pago_id" required>
                    @foreach($forma_de_pagos as $id => $entry)
                        <option value="{{ $id }}" {{ (old('forma_de_pago_id') ? old('forma_de_pago_id') : $instructivoEmbarque->forma_de_pago->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('forma_de_pago'))
                    <div class="invalid-feedback">
                        {{ $errors->first('forma_de_pago') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.forma_de_pago_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="modalidad_de_venta_id">{{ trans('cruds.instructivoEmbarque.fields.modalidad_de_venta') }}</label>
                <select class="form-control select2 {{ $errors->has('modalidad_de_venta') ? 'is-invalid' : '' }}" name="modalidad_de_venta_id" id="modalidad_de_venta_id">
                    @foreach($modalidad_de_ventas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('modalidad_de_venta_id') ? old('modalidad_de_venta_id') : $instructivoEmbarque->modalidad_de_venta->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('modalidad_de_venta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('modalidad_de_venta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.instructivoEmbarque.fields.modalidad_de_venta_helper') }}</span>
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