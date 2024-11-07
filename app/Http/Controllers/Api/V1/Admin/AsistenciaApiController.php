<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAsistenciumRequest;
use App\Http\Requests\UpdateAsistenciumRequest;
use App\Http\Resources\Admin\AsistenciumResource;
use App\Models\Asistencium;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AsistenciaApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('asistencium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AsistenciumResource(Asistencium::with(['locacion', 'turno', 'personal'])->get());
    }
    public function guardarAsistencia(Request $request)
    {
        dd($request->all());
    }
    public function store(StoreAsistenciumRequest $request)
    {
        $asistencium = Asistencium::create($request->all());

        return (new AsistenciumResource($asistencium))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Asistencium $asistencium)
    {
        abort_if(Gate::denies('asistencium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AsistenciumResource($asistencium->load(['locacion', 'turno', 'personal']));
    }

    public function update(UpdateAsistenciumRequest $request, Asistencium $asistencium)
    {
        $asistencium->update($request->all());

        return (new AsistenciumResource($asistencium))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Asistencium $asistencium)
    {
        abort_if(Gate::denies('asistencium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $asistencium->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
