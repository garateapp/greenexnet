<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmbarcadorRequest;
use App\Http\Requests\UpdateEmbarcadorRequest;
use App\Http\Resources\Admin\EmbarcadorResource;
use App\Models\Embarcador;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmbarcadorApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('embarcador_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EmbarcadorResource(Embarcador::all());
    }

    public function store(StoreEmbarcadorRequest $request)
    {
        $embarcador = Embarcador::create($request->all());

        return (new EmbarcadorResource($embarcador))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Embarcador $embarcador)
    {
        abort_if(Gate::denies('embarcador_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EmbarcadorResource($embarcador);
    }

    public function update(UpdateEmbarcadorRequest $request, Embarcador $embarcador)
    {
        $embarcador->update($request->all());

        return (new EmbarcadorResource($embarcador))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Embarcador $embarcador)
    {
        abort_if(Gate::denies('embarcador_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embarcador->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
