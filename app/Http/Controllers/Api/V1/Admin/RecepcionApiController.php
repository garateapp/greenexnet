<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecepcionRequest;
use App\Http\Requests\UpdateRecepcionRequest;
use App\Http\Resources\Admin\RecepcionResource;
use App\Models\Recepcion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecepcionApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('recepcion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RecepcionResource(Recepcion::with(['productor'])->get());
    }

    public function store(StoreRecepcionRequest $request)
    {
        $recepcion = Recepcion::create($request->all());

        return (new RecepcionResource($recepcion))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Recepcion $recepcion)
    {
        abort_if(Gate::denies('recepcion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RecepcionResource($recepcion->load(['productor']));
    }

    public function update(UpdateRecepcionRequest $request, Recepcion $recepcion)
    {
        $recepcion->update($request->all());

        return (new RecepcionResource($recepcion))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Recepcion $recepcion)
    {
        abort_if(Gate::denies('recepcion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recepcion->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
