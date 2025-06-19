<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyInstructivoEmbarqueRequest;
use App\Http\Requests\StoreInstructivoEmbarqueRequest;
use App\Http\Requests\UpdateInstructivoEmbarqueRequest;
use App\Models\AgenteAduana;
use App\Models\BaseRecibidor;
use App\Models\Chofer;
use App\Models\ClausulaVentum;
use App\Models\Embarcador;
use App\Models\EmisionBl;
use App\Models\FormaPago;
use App\Models\InstructivoEmbarque;
use App\Models\ModVentum;
use App\Models\Moneda;
use App\Models\Naviera;
use App\Models\PlantaCarga;
use App\Models\Puerto;
use App\Models\PuertoCorreo;
use App\Models\Tipoflete;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InstructivoEmbarqueController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('instructivo_embarque_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $instructivoEmbarques = InstructivoEmbarque::with(['embarcador', 'agente_aduana', 'consignee', 'naviera', 'puerto_embarque', 'puerto_destino', 'puerto_descarga', 'conductor', 'planta_carga', 'emision_de_bl', 'tipo_de_flete', 'clausula_de_venta', 'moneda', 'forma_de_pago', 'modalidad_de_venta'])->get();

        $embarcadors = Embarcador::get();

        $agente_aduanas = AgenteAduana::get();

        $base_recibidors = BaseRecibidor::get();

        $navieras = Naviera::get();

        $puerto_correos = PuertoCorreo::get();

        $puertos = Puerto::get();

        $chofers = Chofer::get();

        $planta_cargas = PlantaCarga::get();

        $emision_bls = EmisionBl::get();

        $tipofletes = Tipoflete::get();

        $clausula_venta = ClausulaVentum::get();

        $monedas = Moneda::get();

        $forma_pagos = FormaPago::get();

        $mod_venta = ModVentum::get();

        return view('frontend.instructivoEmbarques.index', compact('agente_aduanas', 'base_recibidors', 'chofers', 'clausula_venta', 'embarcadors', 'emision_bls', 'forma_pagos', 'instructivoEmbarques', 'mod_venta', 'monedas', 'navieras', 'planta_cargas', 'puerto_correos', 'puertos', 'tipofletes'));
    }

    public function create()
    {
        abort_if(Gate::denies('instructivo_embarque_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embarcadors = Embarcador::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $agente_aduanas = AgenteAduana::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $consignees = BaseRecibidor::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $navieras = Naviera::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puerto_embarques = PuertoCorreo::pluck('emails', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puerto_destinos = PuertoCorreo::pluck('emails', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puerto_descargas = Puerto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $conductors = Chofer::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $planta_cargas = PlantaCarga::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $emision_de_bls = EmisionBl::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_de_fletes = Tipoflete::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $clausula_de_ventas = ClausulaVentum::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $monedas = Moneda::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $forma_de_pagos = FormaPago::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $modalidad_de_ventas = ModVentum::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.instructivoEmbarques.create', compact('agente_aduanas', 'clausula_de_ventas', 'conductors', 'consignees', 'embarcadors', 'emision_de_bls', 'forma_de_pagos', 'modalidad_de_ventas', 'monedas', 'navieras', 'planta_cargas', 'puerto_descargas', 'puerto_destinos', 'puerto_embarques', 'tipo_de_fletes'));
    }

    public function store(StoreInstructivoEmbarqueRequest $request)
    {
        $instructivoEmbarque = InstructivoEmbarque::create($request->all());

        return redirect()->route('frontend.instructivo-embarques.index');
    }

    public function edit(InstructivoEmbarque $instructivoEmbarque)
    {
        abort_if(Gate::denies('instructivo_embarque_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embarcadors = Embarcador::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $agente_aduanas = AgenteAduana::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $consignees = BaseRecibidor::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $navieras = Naviera::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puerto_embarques = PuertoCorreo::pluck('emails', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puerto_destinos = PuertoCorreo::pluck('emails', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puerto_descargas = Puerto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $conductors = Chofer::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $planta_cargas = PlantaCarga::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $emision_de_bls = EmisionBl::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_de_fletes = Tipoflete::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $clausula_de_ventas = ClausulaVentum::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $monedas = Moneda::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $forma_de_pagos = FormaPago::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $modalidad_de_ventas = ModVentum::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $instructivoEmbarque->load('embarcador', 'agente_aduana', 'consignee', 'naviera', 'puerto_embarque', 'puerto_destino', 'puerto_descarga', 'conductor', 'planta_carga', 'emision_de_bl', 'tipo_de_flete', 'clausula_de_venta', 'moneda', 'forma_de_pago', 'modalidad_de_venta');

        return view('frontend.instructivoEmbarques.edit', compact('agente_aduanas', 'clausula_de_ventas', 'conductors', 'consignees', 'embarcadors', 'emision_de_bls', 'forma_de_pagos', 'instructivoEmbarque', 'modalidad_de_ventas', 'monedas', 'navieras', 'planta_cargas', 'puerto_descargas', 'puerto_destinos', 'puerto_embarques', 'tipo_de_fletes'));
    }

    public function update(UpdateInstructivoEmbarqueRequest $request, InstructivoEmbarque $instructivoEmbarque)
    {
        $instructivoEmbarque->update($request->all());

        return redirect()->route('frontend.instructivo-embarques.index');
    }

    public function show(InstructivoEmbarque $instructivoEmbarque)
    {
        abort_if(Gate::denies('instructivo_embarque_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $instructivoEmbarque->load('embarcador', 'agente_aduana', 'consignee', 'naviera', 'puerto_embarque', 'puerto_destino', 'puerto_descarga', 'conductor', 'planta_carga', 'emision_de_bl', 'tipo_de_flete', 'clausula_de_venta', 'moneda', 'forma_de_pago', 'modalidad_de_venta');

        return view('frontend.instructivoEmbarques.show', compact('instructivoEmbarque'));
    }

    public function destroy(InstructivoEmbarque $instructivoEmbarque)
    {
        abort_if(Gate::denies('instructivo_embarque_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $instructivoEmbarque->delete();

        return back();
    }

    public function massDestroy(MassDestroyInstructivoEmbarqueRequest $request)
    {
        $instructivoEmbarques = InstructivoEmbarque::find(request('ids'));

        foreach ($instructivoEmbarques as $instructivoEmbarque) {
            $instructivoEmbarque->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
