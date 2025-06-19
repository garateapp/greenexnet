<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlantaCargaRequest;
use App\Http\Requests\UpdatePlantaCargaRequest;
use App\Http\Resources\Admin\PlantaCargaResource;
use App\Models\PlantaCarga;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlantaCargaApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('planta_carga_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PlantaCargaResource(PlantaCarga::all());
    }

    public function store(StorePlantaCargaRequest $request)
    {
        $plantaCarga = PlantaCarga::create($request->all());

        return (new PlantaCargaResource($plantaCarga))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PlantaCarga $plantaCarga)
    {
        abort_if(Gate::denies('planta_carga_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PlantaCargaResource($plantaCarga);
    }

    public function update(UpdatePlantaCargaRequest $request, PlantaCarga $plantaCarga)
    {
        $plantaCarga->update($request->all());

        return (new PlantaCargaResource($plantaCarga))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PlantaCarga $plantaCarga)
    {
        abort_if(Gate::denies('planta_carga_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $plantaCarga->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
