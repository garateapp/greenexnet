<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAgenteAduanaRequest;
use App\Http\Requests\UpdateAgenteAduanaRequest;
use App\Http\Resources\Admin\AgenteAduanaResource;
use App\Models\AgenteAduana;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgenteAduanaApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('agente_aduana_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AgenteAduanaResource(AgenteAduana::all());
    }

    public function store(StoreAgenteAduanaRequest $request)
    {
        $agenteAduana = AgenteAduana::create($request->all());

        return (new AgenteAduanaResource($agenteAduana))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AgenteAduana $agenteAduana)
    {
        abort_if(Gate::denies('agente_aduana_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AgenteAduanaResource($agenteAduana);
    }

    public function update(UpdateAgenteAduanaRequest $request, AgenteAduana $agenteAduana)
    {
        $agenteAduana->update($request->all());

        return (new AgenteAduanaResource($agenteAduana))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AgenteAduana $agenteAduana)
    {
        abort_if(Gate::denies('agente_aduana_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $agenteAduana->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
