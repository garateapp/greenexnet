@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.instructivoEmbarque.title') }}
            <div class="d-flex justify-content-end">
                <a class="btn btn-info mr-2" href="{{ route('admin.instructivo-embarques.download', $instructivoEmbarque->id) }}">
                    Descargar Excel
                </a>
                <button class="btn btn-primary" id="sendEmailBtn" data-id="{{ $instructivoEmbarque->id }}">
                    Enviar por Email
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Column 1 -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="instructivo">{{ trans('cruds.instructivoEmbarque.fields.instructivo') }}</label>
                        <p>{{ $instructivoEmbarque->instructivo }}</p>
                    </div>
                    <div class="form-group">
                        <label for="embarcador_id">{{ trans('cruds.instructivoEmbarque.fields.embarcador') }}</label>
                        <p>{{ $instructivoEmbarque->embarcador->nombre ?? '' }}</p>
                    </div>
                    <div class="form-group">
                        <label for="consignee_id">{{ trans('cruds.instructivoEmbarque.fields.consignee') }}</label>
                        <p>{{ $instructivoEmbarque->consignee->codigo ?? '' }}</p>
                    </div>
                </div>
                <!-- Column 2 -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fecha">{{ trans('cruds.instructivoEmbarque.fields.fecha') }}</label>
                        <p>{{ $instructivoEmbarque->fecha }}</p>
                    </div>
                    <div class="form-group">
                        <label for="agente_aduana_id">{{ trans('cruds.instructivoEmbarque.fields.agente_aduana') }}</label>
                        <p>{{ $instructivoEmbarque->agente_aduana->nombre ?? '' }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detalle de Embarque</span>
                    <p>Tipo de Embarque: {{ $instructivoEmbarque->tipo_embarque == 1 ? 'Marítimo' : ($instructivoEmbarque->tipo_embarque == 2 ? 'Aéreo' : ($instructivoEmbarque->tipo_embarque == 3 ? 'Terrestre' : 'N/A')) }}</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Left Column (col-md-6) -->
                        <div class="col-md-6">
                            @if ($instructivoEmbarque->tipo_embarque == 1)
                                <div class="form-group">
                                    <label for="naviera_id">{{ trans('cruds.instructivoEmbarque.fields.naviera') }}</label>
                                    <p>{{ $instructivoEmbarque->naviera->nombre ?? '' }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="num_booking">{{ trans('cruds.instructivoEmbarque.fields.num_booking') }}</label>
                                    <p>{{ $instructivoEmbarque->num_booking }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="nave">{{ trans('cruds.instructivoEmbarque.fields.nave') }}</label>
                                    <p>{{ $instructivoEmbarque->nave }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="cut_off">{{ trans('cruds.instructivoEmbarque.fields.cut_off') }}</label>
                                    <p>{{ $instructivoEmbarque->cut_off }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="stacking_ini">{{ trans('cruds.instructivoEmbarque.fields.stacking_ini') }}</label>
                                    <p>{{ $instructivoEmbarque->stacking_ini }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="stacking_end">{{ trans('cruds.instructivoEmbarque.fields.stacking_end') }}</label>
                                    <p>{{ $instructivoEmbarque->stacking_end }}</p>
                                </div>
                            @elseif ($instructivoEmbarque->tipo_embarque == 2)
                                <div class="form-group">
                                    <label for="awb">Guía Aérea N°</label>
                                    <p>{{ $instructivoEmbarque->awb }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="linea_aerea">Línea Aérea</label>
                                    <p>{{ $instructivoEmbarque->linea_aerea }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="num_vuelo">N° Vuelo</label>
                                    <p>{{ $instructivoEmbarque->num_vuelo }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="tipo_vuelo">Tipo Vuelo</label>
                                    <p>{{ $instructivoEmbarque->tipo_vuelo }}</p>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="etd">{{ trans('cruds.instructivoEmbarque.fields.etd') }}</label>
                                <p>{{ $instructivoEmbarque->etd }}</p>
                            </div>
                            <div class="form-group">
                                <label for="eta">{{ trans('cruds.instructivoEmbarque.fields.eta') }}</label>
                                <p>{{ $instructivoEmbarque->eta }}</p>
                            </div>
                            <div class="form-group">
                                <label for="puerto_embarque_id">{{ trans('cruds.instructivoEmbarque.fields.puerto_embarque') }}</label>
                                <p>{{ $instructivoEmbarque->puerto_embarque->nombre ?? '' }}</p>
                            </div>
                            <div class="form-group">
                                <label for="puerto_destino_id">{{ trans('cruds.instructivoEmbarque.fields.puerto_destino') }}</label>
                                <p>{{ $instructivoEmbarque->puerto_destino->nombre ?? '' }}</p>
                            </div>
                            <div class="form-group">
                                <label for="puerto_descarga_id">{{ trans('cruds.instructivoEmbarque.fields.puerto_descarga') }}</label>
                                <p>{{ $instructivoEmbarque->puerto_descarga->nombre ?? '' }}</p>
                            </div>
                            <div class="form-group">
                                <label for="punto_de_entrada">{{ trans('cruds.instructivoEmbarque.fields.punto_de_entrada') }}</label>
                                <p>{{ $instructivoEmbarque->punto_de_entrada }}</p>
                            </div>
                            <div class="form-group">
                                <label for="pais_embarque">País Embarque</label>
                                <p>{{ $instructivoEmbarque->pais_embarque->name ?? '' }}</p>
                            </div>
                            <div class="form-group">
                                <label for="pais_destino">País Destino</label>
                                <p>{{ $instructivoEmbarque->pais_destino->name ?? '' }}</p>
                            </div>
                        </div>

                        <!-- Right Column (col-md-6) -->
                        <div class="col-md-6">
                            @if ($instructivoEmbarque->tipo_embarque == 1)
                                <div class="form-group">
                                    <label for="num_contenedor">{{ trans('cruds.instructivoEmbarque.fields.num_contenedor') }}</label>
                                    <p>{{ $instructivoEmbarque->num_contenedor }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="ventilacion">{{ trans('cruds.instructivoEmbarque.fields.ventilacion') }}</label>
                                    <p>{{ $instructivoEmbarque->ventilacion }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="tara_contenedor">{{ trans('cruds.instructivoEmbarque.fields.tara_contenedor') }}</label>
                                    <p>{{ $instructivoEmbarque->tara_contenedor }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="quest">{{ trans('cruds.instructivoEmbarque.fields.quest') }}</label>
                                    <p>{{ $instructivoEmbarque->quest }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="num_sello">{{ trans('cruds.instructivoEmbarque.fields.num_sello') }}</label>
                                    <p>{{ $instructivoEmbarque->num_sello }}</p>
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="temperatura">{{ trans('cruds.instructivoEmbarque.fields.temperatura') }}</label>
                                <p>{{ $instructivoEmbarque->temperatura }}</p>
                            </div>
                            <div class="form-group">
                                <label for="empresa_transportista">{{ trans('cruds.instructivoEmbarque.fields.empresa_transportista') }}</label>
                                <p>{{ $instructivoEmbarque->empresa_transportista }}</p>
                            </div>
                            <div class="form-group">
                                <label for="conductor_id">{{ trans('cruds.instructivoEmbarque.fields.conductor') }}</label>
                                <p>{{ $instructivoEmbarque->conductor->nombre ?? '' }}</p>
                            </div>
                            <div class="form-group">
                                <label for="rut_conductor">{{ trans('cruds.instructivoEmbarque.fields.rut_conductor') }}</label>
                                <p>{{ $instructivoEmbarque->rut_conductor }}</p>
                            </div>
                            <div class="form-group">
                                <label for="ppu">{{ trans('cruds.instructivoEmbarque.fields.ppu') }}</label>
                                <p>{{ $instructivoEmbarque->ppu }}</p>
                            </div>
                            <div class="form-group">
                                <label for="telefono">{{ trans('cruds.instructivoEmbarque.fields.telefono') }}</label>
                                <p>{{ $instructivoEmbarque->telefono }}</p>
                            </div>
                            <div class="form-group">
                                <label for="planta_carga_id">{{ trans('cruds.instructivoEmbarque.fields.planta_carga') }}</label>
                                <p>{{ $instructivoEmbarque->planta_carga->nombre ?? '' }}</p>
                            </div>
                            <div class="form-group">
                                <label for="direccion">{{ trans('cruds.instructivoEmbarque.fields.direccion') }}</label>
                                <p>{{ $instructivoEmbarque->direccion }}</p>
                            </div>
                            <div class="form-group">
                                <label for="fecha_carga">{{ trans('cruds.instructivoEmbarque.fields.fecha_carga') }}</label>
                                <p>{{ $instructivoEmbarque->fecha_carga }}</p>
                            </div>
                            <div class="form-group">
                                <label for="hora_carga">{{ trans('cruds.instructivoEmbarque.fields.hora_carga') }}</label>
                                <p>{{ $instructivoEmbarque->hora_carga }}</p>
                            </div>
                            <div class="form-group">
                                <label for="guia_despacho_dirigida">{{ trans('cruds.instructivoEmbarque.fields.guia_despacho_dirigida') }}</label>
                                <p>{{ $instructivoEmbarque->guia_despacho_dirigida }}</p>
                            </div>
                            <div class="form-group">
                                <label for="planilla_sag_dirigida">{{ trans('cruds.instructivoEmbarque.fields.planilla_sag_dirigida') }}</label>
                                <p>{{ $instructivoEmbarque->planilla_sag_dirigida }}</p>
                            </div>
                            @if ($instructivoEmbarque->tipo_embarque == 2 || $instructivoEmbarque->tipo_embarque == 3)
                                <div class="form-group">
                                    <label for="dus">N° DUS</label>
                                    <p>{{ $instructivoEmbarque->dus }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="sps">N° SPS</label>
                                    <p>{{ $instructivoEmbarque->sps }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    Antecedentes Comerciales
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Left Column (col-md-6) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="num_po">{{ trans('cruds.instructivoEmbarque.fields.num_po') }}</label>
                                <p>{{ $instructivoEmbarque->num_po }}</p>
                            </div>
                            <div class="form-group">
                                <label for="moneda_id">{{ trans('cruds.instructivoEmbarque.fields.moneda') }}</label>
                                <p>{{ $instructivoEmbarque->moneda->nombre ?? '' }}</p>
                            </div>
                            <div class="form-group">
                                <label for="forma_de_pago_id">{{ trans('cruds.instructivoEmbarque.fields.forma_de_pago') }}</label>
                                <p>{{ $instructivoEmbarque->forma_de_pago->nombre ?? '' }}</p>
                            </div>
                            <div class="form-group">
                                <label for="modalidad_de_venta_id">{{ trans('cruds.instructivoEmbarque.fields.modalidad_de_venta') }}</label>
                                <p>{{ $instructivoEmbarque->modalidad_de_venta->nombre ?? '' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="emision_de_bl_id">{{ trans('cruds.instructivoEmbarque.fields.emision_de_bl') }}</label>
                                <p>{{ $instructivoEmbarque->emision_de_bl->nombre ?? '' }}</p>
                            </div>
                            <div class="form-group">
                                <label for="tipo_de_flete_id">{{ trans('cruds.instructivoEmbarque.fields.tipo_de_flete') }}</label>
                                <p>{{ $instructivoEmbarque->tipo_de_flete->nombre ?? '' }}</p>
                            </div>
                            <div class="form-group">
                                <label for="clausula_de_venta_id">{{ trans('cruds.instructivoEmbarque.fields.clausula_de_venta') }}</label>
                                <p>{{ $instructivoEmbarque->clausula_de_venta->nombre ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.instructivo-embarques.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
                <a class="btn btn-info mr-2" href="{{ route('admin.instructivo-embarques.download', $instructivoEmbarque->id) }}">
                    Descargar Excel
                </a>
                <button class="btn btn-primary" id="sendEmailBtnBottom" data-id="{{ $instructivoEmbarque->id }}">
                    Enviar por Email
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('#sendEmailBtn, #sendEmailBtnBottom').on('click', function() {
                var instructivoId = $(this).data('id');
                if (confirm('¿Estás seguro de que quieres enviar este instructivo por correo electrónico?')) {
                    $.ajax({
                        url: '{{ route('admin.instructivo-embarques.send-email', ':id') }}'.replace(':id', instructivoId),
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            alert(response.message);
                        },
                        error: function(xhr) {
                            alert('Error al enviar el correo electrónico: ' + xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endsection