<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProcesoRequest;
use App\Http\Requests\UpdateProcesoRequest;
use App\Http\Resources\Admin\ProcesoResource;
use App\Models\Proceso;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProcesoApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('proceso_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProcesoResource(Proceso::with(['productor'])->get());
    }

    public function store(StoreProcesoRequest $request)
    {
        $proceso = Proceso::create($request->all());

        return (new ProcesoResource($proceso))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Proceso $proceso)
    {
        abort_if(Gate::denies('proceso_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProcesoResource($proceso->load(['productor']));
    }

    public function update(UpdateProcesoRequest $request, Proceso $proceso)
    {
        $proceso->update($request->all());

        return (new ProcesoResource($proceso))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Proceso $proceso)
    {
        abort_if(Gate::denies('proceso_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $proceso->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
