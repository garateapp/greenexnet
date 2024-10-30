<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyInhumadoRequest;
use App\Http\Requests\StoreInhumadoRequest;
use App\Http\Requests\UpdateInhumadoRequest;
use App\Models\Inhumado;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InhumadosController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('inhumado_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inhumados = Inhumado::all();

        return view('frontend.inhumados.index', compact('inhumados'));
    }

    public function create()
    {
        abort_if(Gate::denies('inhumado_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.inhumados.create');
    }

    public function store(StoreInhumadoRequest $request)
    {
        $inhumado = Inhumado::create($request->all());

        return redirect()->route('frontend.inhumados.index');
    }

    public function edit(Inhumado $inhumado)
    {
        abort_if(Gate::denies('inhumado_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.inhumados.edit', compact('inhumado'));
    }

    public function update(UpdateInhumadoRequest $request, Inhumado $inhumado)
    {
        $inhumado->update($request->all());

        return redirect()->route('frontend.inhumados.index');
    }

    public function show(Inhumado $inhumado)
    {
        abort_if(Gate::denies('inhumado_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.inhumados.show', compact('inhumado'));
    }

    public function destroy(Inhumado $inhumado)
    {
        abort_if(Gate::denies('inhumado_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inhumado->delete();

        return back();
    }

    public function massDestroy(MassDestroyInhumadoRequest $request)
    {
        $inhumados = Inhumado::find(request('ids'));

        foreach ($inhumados as $inhumado) {
            $inhumado->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
