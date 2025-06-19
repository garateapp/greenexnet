<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInstructivoEmbarqueRequest;
use App\Http\Requests\UpdateInstructivoEmbarqueRequest;
use App\Http\Resources\Admin\InstructivoEmbarqueResource;
use App\Models\InstructivoEmbarque;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InstructivoEmbarqueApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('instructivo_embarque_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InstructivoEmbarqueResource(InstructivoEmbarque::with(['embarcador', 'agente_aduana', 'consignee', 'naviera', 'puerto_embarque', 'puerto_destino', 'puerto_descarga', 'conductor', 'planta_carga', 'emision_de_bl', 'tipo_de_flete', 'clausula_de_venta', 'moneda', 'forma_de_pago', 'modalidad_de_venta'])->get());
    }

    public function store(StoreInstructivoEmbarqueRequest $request)
    {
        $instructivoEmbarque = InstructivoEmbarque::create($request->all());

        return (new InstructivoEmbarqueResource($instructivoEmbarque))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(InstructivoEmbarque $instructivoEmbarque)
    {
        abort_if(Gate::denies('instructivo_embarque_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InstructivoEmbarqueResource($instructivoEmbarque->load(['embarcador', 'agente_aduana', 'consignee', 'naviera', 'puerto_embarque', 'puerto_destino', 'puerto_descarga', 'conductor', 'planta_carga', 'emision_de_bl', 'tipo_de_flete', 'clausula_de_venta', 'moneda', 'forma_de_pago', 'modalidad_de_venta']));
    }

    public function update(UpdateInstructivoEmbarqueRequest $request, InstructivoEmbarque $instructivoEmbarque)
    {
        $instructivoEmbarque->update($request->all());

        return (new InstructivoEmbarqueResource($instructivoEmbarque))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(InstructivoEmbarque $instructivoEmbarque)
    {
        abort_if(Gate::denies('instructivo_embarque_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $instructivoEmbarque->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
