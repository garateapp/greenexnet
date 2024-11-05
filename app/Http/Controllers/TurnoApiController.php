<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTurnoRequest;
use App\Http\Requests\UpdateTurnoRequest;
use App\Http\Resources\Admin\TurnoResource;
use App\Models\Turno;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TurnoApiController extends Controller
{
    public function getDatoTurno()
    {

        //Obtengo que día es hoy
        $dia = date('w');
        $turnos = Turno::where('dia', $dia)->get();
        return response()->json($turnos);
    }
    public function index()
    {
        abort_if(Gate::denies('turno_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TurnoResource(Turno::all());
    }

    public function store(StoreTurnoRequest $request)
    {
        $turno = Turno::create($request->all());

        return (new TurnoResource($turno))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Turno $turno)
    {
        abort_if(Gate::denies('turno_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TurnoResource($turno);
    }

    public function update(UpdateTurnoRequest $request, Turno $turno)
    {
        $turno->update($request->all());

        return (new TurnoResource($turno))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Turno $turno)
    {
        abort_if(Gate::denies('turno_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $turno->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
