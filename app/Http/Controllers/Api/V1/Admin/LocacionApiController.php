<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocacionRequest;
use App\Http\Requests\UpdateLocacionRequest;
use App\Http\Resources\Admin\LocacionResource;
use App\Models\Locacion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocacionApiController extends Controller
{
    public function index()
    {
        //abort_if(Gate::denies('locacion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $locacion_padre = Locacion::where('estado_id', 1)->where('locacion_padre_id', '=', '1')->with('area')->get();
        $locacion = Locacion::where('estado_id', 1)->where('id', '!=', '1')->get();
        return response()->json(['locacion_padre' => $locacion_padre, 'locacion' => $locacion]); //$locacion, $locacion_padre]);
        //return new LocacionResource(Locacion::with(['area', 'estado', 'locacion_padre'])->get());
    }
    public function obtieneLocaciones()
    {
        //abort_if(Gate::denies('locacion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $locacion_padre = Locacion::where('estado_id', 1)->where('locacion_padre_id', '=', '1')->with('area')->get();
        $locacion = Locacion::where('estado_id', 1)->where('id', '!=', '1')->get();
        response()->json([$locacion, $locacion_padre]);
        //return new LocacionResource(Locacion::with(['area', 'estado', 'locacion_padre'])->get());
    }
    public function store(StoreLocacionRequest $request)
    {
        $locacion = Locacion::create($request->all());

        return (new LocacionResource($locacion))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Locacion $locacion)
    {
        //abort_if(Gate::denies('locacion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LocacionResource($locacion->load(['area', 'estado', 'locacion_padre']));
    }

    public function update(UpdateLocacionRequest $request, Locacion $locacion)
    {
        $locacion->update($request->all());

        return (new LocacionResource($locacion))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Locacion $locacion)
    {
        abort_if(Gate::denies('locacion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $locacion->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
