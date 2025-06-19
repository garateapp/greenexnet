<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePuertoCorreoRequest;
use App\Http\Requests\UpdatePuertoCorreoRequest;
use App\Http\Resources\Admin\PuertoCorreoResource;
use App\Models\PuertoCorreo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PuertoCorreoApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('puerto_correo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PuertoCorreoResource(PuertoCorreo::with(['puerto_embarque', 'pais'])->get());
    }

    public function store(StorePuertoCorreoRequest $request)
    {
        $puertoCorreo = PuertoCorreo::create($request->all());

        return (new PuertoCorreoResource($puertoCorreo))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PuertoCorreo $puertoCorreo)
    {
        abort_if(Gate::denies('puerto_correo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PuertoCorreoResource($puertoCorreo->load(['puerto_embarque', 'pais']));
    }

    public function update(UpdatePuertoCorreoRequest $request, PuertoCorreo $puertoCorreo)
    {
        $puertoCorreo->update($request->all());

        return (new PuertoCorreoResource($puertoCorreo))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PuertoCorreo $puertoCorreo)
    {
        abort_if(Gate::denies('puerto_correo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $puertoCorreo->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
