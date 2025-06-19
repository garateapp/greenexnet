<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePesoEmbalajeRequest;
use App\Http\Requests\UpdatePesoEmbalajeRequest;
use App\Http\Resources\Admin\PesoEmbalajeResource;
use App\Models\PesoEmbalaje;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PesoEmbalajeApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('peso_embalaje_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PesoEmbalajeResource(PesoEmbalaje::with(['especie', 'etiqueta'])->get());
    }

    public function store(StorePesoEmbalajeRequest $request)
    {
        $pesoEmbalaje = PesoEmbalaje::create($request->all());

        return (new PesoEmbalajeResource($pesoEmbalaje))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PesoEmbalaje $pesoEmbalaje)
    {
        abort_if(Gate::denies('peso_embalaje_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PesoEmbalajeResource($pesoEmbalaje->load(['especie', 'etiqueta']));
    }

    public function update(UpdatePesoEmbalajeRequest $request, PesoEmbalaje $pesoEmbalaje)
    {
        $pesoEmbalaje->update($request->all());

        return (new PesoEmbalajeResource($pesoEmbalaje))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PesoEmbalaje $pesoEmbalaje)
    {
        abort_if(Gate::denies('peso_embalaje_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pesoEmbalaje->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
