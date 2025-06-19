<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnticipoRequest;
use App\Http\Requests\UpdateAnticipoRequest;
use App\Http\Resources\Admin\AnticipoResource;
use App\Models\Anticipo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnticipoApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('anticipo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AnticipoResource(Anticipo::with(['productor', 'tipo_cambio', 'especie'])->get());
    }

    public function store(StoreAnticipoRequest $request)
    {
        $anticipo = Anticipo::create($request->all());

        return (new AnticipoResource($anticipo))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Anticipo $anticipo)
    {
        abort_if(Gate::denies('anticipo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AnticipoResource($anticipo->load(['productor', 'tipo_cambio', 'especie']));
    }

    public function update(UpdateAnticipoRequest $request, Anticipo $anticipo)
    {
        $anticipo->update($request->all());

        return (new AnticipoResource($anticipo))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Anticipo $anticipo)
    {
        abort_if(Gate::denies('anticipo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $anticipo->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
