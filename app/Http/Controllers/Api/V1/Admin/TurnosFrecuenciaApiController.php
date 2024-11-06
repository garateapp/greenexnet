<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTurnosFrecuenciumRequest;
use App\Http\Requests\UpdateTurnosFrecuenciumRequest;
use App\Http\Resources\Admin\TurnosFrecuenciumResource;
use App\Models\TurnosFrecuencium;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TurnosFrecuenciaApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('turnos_frecuencium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TurnosFrecuenciumResource(TurnosFrecuencium::with(['frecuencia', 'locacion'])->get());
    }

    public function store(StoreTurnosFrecuenciumRequest $request)
    {
        $turnosFrecuencium = TurnosFrecuencium::create($request->all());

        return (new TurnosFrecuenciumResource($turnosFrecuencium))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Request $request)
    {
        abort_if(Gate::denies('turnos_frecuencium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $turnosFrec = TurnosFrecuencium::where('locacion_id', $request->id)->with('locacion')->get();
        return $turnosFrec;
    }

    public function update(UpdateTurnosFrecuenciumRequest $request, TurnosFrecuencium $turnosFrecuencium)
    {
        $turnosFrecuencium->update($request->all());

        return (new TurnosFrecuenciumResource($turnosFrecuencium))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(TurnosFrecuencium $turnosFrecuencium)
    {
        abort_if(Gate::denies('turnos_frecuencium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $turnosFrecuencium->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
