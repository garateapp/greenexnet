<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEntidadRequest;
use App\Http\Requests\UpdateEntidadRequest;
use App\Http\Resources\Admin\EntidadResource;
use App\Models\Entidad;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EntidadApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('entidad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EntidadResource(Entidad::with(['tipo'])->get());
    }

    public function store(StoreEntidadRequest $request)
    {
        $entidad = Entidad::create($request->all());

        return (new EntidadResource($entidad))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Entidad $entidad)
    {
        abort_if(Gate::denies('entidad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EntidadResource($entidad->load(['tipo']));
    }

    public function update(UpdateEntidadRequest $request, Entidad $entidad)
    {
        $entidad->update($request->all());

        return (new EntidadResource($entidad))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Entidad $entidad)
    {
        abort_if(Gate::denies('entidad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entidad->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
