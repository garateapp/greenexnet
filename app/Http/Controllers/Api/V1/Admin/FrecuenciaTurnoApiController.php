<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFrecuenciaTurnoRequest;
use App\Http\Requests\UpdateFrecuenciaTurnoRequest;
use App\Http\Resources\Admin\FrecuenciaTurnoResource;
use App\Models\FrecuenciaTurno;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FrecuenciaTurnoApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('frecuencia_turno_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FrecuenciaTurnoResource(FrecuenciaTurno::with(['turno'])->get());
    }

    public function store(StoreFrecuenciaTurnoRequest $request)
    {
        $frecuenciaTurno = FrecuenciaTurno::create($request->all());

        return (new FrecuenciaTurnoResource($frecuenciaTurno))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
    public function obtieneTurno($id)
    {
        $turno = FrecuenciaTurno::where('locacion_id', $id)->where('dia', date('w'))->with('turno')->get();

        return json_encode($turno);
    }
    public function show(FrecuenciaTurno $frecuenciaTurno)
    {
        abort_if(Gate::denies('frecuencia_turno_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FrecuenciaTurnoResource($frecuenciaTurno->load(['turno']));
    }

    public function update(UpdateFrecuenciaTurnoRequest $request, FrecuenciaTurno $frecuenciaTurno)
    {
        $frecuenciaTurno->update($request->all());

        return (new FrecuenciaTurnoResource($frecuenciaTurno))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(FrecuenciaTurno $frecuenciaTurno)
    {
        abort_if(Gate::denies('frecuencia_turno_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $frecuenciaTurno->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
