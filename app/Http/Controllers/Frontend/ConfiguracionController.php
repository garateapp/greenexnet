<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyConfiguracionRequest;
use App\Http\Requests\StoreConfiguracionRequest;
use App\Http\Requests\UpdateConfiguracionRequest;
use App\Models\Configuracion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfiguracionController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('configuracion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracions = Configuracion::all();

        return view('frontend.configuracions.index', compact('configuracions'));
    }

    public function create()
    {
        abort_if(Gate::denies('configuracion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.configuracions.create');
    }

    public function store(StoreConfiguracionRequest $request)
    {
        $configuracion = Configuracion::create($request->all());

        return redirect()->route('frontend.configuracions.index');
    }

    public function edit(Configuracion $configuracion)
    {
        abort_if(Gate::denies('configuracion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.configuracions.edit', compact('configuracion'));
    }

    public function update(UpdateConfiguracionRequest $request, Configuracion $configuracion)
    {
        $configuracion->update($request->all());

        return redirect()->route('frontend.configuracions.index');
    }

    public function show(Configuracion $configuracion)
    {
        abort_if(Gate::denies('configuracion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.configuracions.show', compact('configuracion'));
    }

    public function destroy(Configuracion $configuracion)
    {
        abort_if(Gate::denies('configuracion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracion->delete();

        return back();
    }

    public function massDestroy(MassDestroyConfiguracionRequest $request)
    {
        $configuracions = Configuracion::find(request('ids'));

        foreach ($configuracions as $configuracion) {
            $configuracion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
