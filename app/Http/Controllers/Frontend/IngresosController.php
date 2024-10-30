<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyIngresoRequest;
use App\Http\Requests\StoreIngresoRequest;
use App\Http\Requests\UpdateIngresoRequest;
use App\Models\Ingreso;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IngresosController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ingreso_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ingresos = Ingreso::with(['usuario'])->get();

        return view('frontend.ingresos.index', compact('ingresos'));
    }

    public function create()
    {
        abort_if(Gate::denies('ingreso_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $usuarios = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.ingresos.create', compact('usuarios'));
    }

    public function store(StoreIngresoRequest $request)
    {
        $ingreso = Ingreso::create($request->all());

        return redirect()->route('frontend.ingresos.index');
    }

    public function edit(Ingreso $ingreso)
    {
        abort_if(Gate::denies('ingreso_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $usuarios = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ingreso->load('usuario');

        return view('frontend.ingresos.edit', compact('ingreso', 'usuarios'));
    }

    public function update(UpdateIngresoRequest $request, Ingreso $ingreso)
    {
        $ingreso->update($request->all());

        return redirect()->route('frontend.ingresos.index');
    }

    public function show(Ingreso $ingreso)
    {
        abort_if(Gate::denies('ingreso_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ingreso->load('usuario');

        return view('frontend.ingresos.show', compact('ingreso'));
    }

    public function destroy(Ingreso $ingreso)
    {
        abort_if(Gate::denies('ingreso_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ingreso->delete();

        return back();
    }

    public function massDestroy(MassDestroyIngresoRequest $request)
    {
        $ingresos = Ingreso::find(request('ids'));

        foreach ($ingresos as $ingreso) {
            $ingreso->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
