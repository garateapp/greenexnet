<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTipoDeInspeccionRequest;
use App\Http\Requests\StoreTipoDeInspeccionRequest;
use App\Http\Requests\UpdateTipoDeInspeccionRequest;
use App\Models\TipoDeInspeccion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoDeInspeccionController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('tipo_de_inspeccion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoDeInspeccions = TipoDeInspeccion::all();

        return view('frontend.tipoDeInspeccions.index', compact('tipoDeInspeccions'));
    }

    public function create()
    {
        abort_if(Gate::denies('tipo_de_inspeccion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoDeInspeccions.create');
    }

    public function store(StoreTipoDeInspeccionRequest $request)
    {
        $tipoDeInspeccion = TipoDeInspeccion::create($request->all());

        return redirect()->route('frontend.tipo-de-inspeccions.index');
    }

    public function edit(TipoDeInspeccion $tipoDeInspeccion)
    {
        abort_if(Gate::denies('tipo_de_inspeccion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoDeInspeccions.edit', compact('tipoDeInspeccion'));
    }

    public function update(UpdateTipoDeInspeccionRequest $request, TipoDeInspeccion $tipoDeInspeccion)
    {
        $tipoDeInspeccion->update($request->all());

        return redirect()->route('frontend.tipo-de-inspeccions.index');
    }

    public function show(TipoDeInspeccion $tipoDeInspeccion)
    {
        abort_if(Gate::denies('tipo_de_inspeccion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoDeInspeccions.show', compact('tipoDeInspeccion'));
    }

    public function destroy(TipoDeInspeccion $tipoDeInspeccion)
    {
        abort_if(Gate::denies('tipo_de_inspeccion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoDeInspeccion->delete();

        return back();
    }

    public function massDestroy(MassDestroyTipoDeInspeccionRequest $request)
    {
        $tipoDeInspeccions = TipoDeInspeccion::find(request('ids'));

        foreach ($tipoDeInspeccions as $tipoDeInspeccion) {
            $tipoDeInspeccion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
