<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmbalajeRequest;
use App\Http\Requests\UpdateEmbalajeRequest;
use App\Http\Resources\Admin\EmbalajeResource;
use App\Models\Embalaje;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmbalajesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('embalaje_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EmbalajeResource(Embalaje::all());
    }

    public function store(StoreEmbalajeRequest $request)
    {
        $embalaje = Embalaje::create($request->all());

        return (new EmbalajeResource($embalaje))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Embalaje $embalaje)
    {
        abort_if(Gate::denies('embalaje_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EmbalajeResource($embalaje);
    }

    public function update(UpdateEmbalajeRequest $request, Embalaje $embalaje)
    {
        $embalaje->update($request->all());

        return (new EmbalajeResource($embalaje))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Embalaje $embalaje)
    {
        abort_if(Gate::denies('embalaje_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embalaje->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
