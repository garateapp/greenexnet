<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTipoFleteRequest;
use App\Http\Requests\StoreTipoFleteRequest;
use App\Http\Requests\UpdateTipoFleteRequest;
use App\Models\TipoFlete;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoFleteController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('tipo_flete_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoFletes = TipoFlete::all();

        return view('frontend.tipoFletes.index', compact('tipoFletes'));
    }

    public function create()
    {
        abort_if(Gate::denies('tipo_flete_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoFletes.create');
    }

    public function store(StoreTipoFleteRequest $request)
    {
        $tipoFlete = TipoFlete::create($request->all());

        return redirect()->route('frontend.tipo-fletes.index');
    }

    public function edit(TipoFlete $tipoFlete)
    {
        abort_if(Gate::denies('tipo_flete_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoFletes.edit', compact('tipoFlete'));
    }

    public function update(UpdateTipoFleteRequest $request, TipoFlete $tipoFlete)
    {
        $tipoFlete->update($request->all());

        return redirect()->route('frontend.tipo-fletes.index');
    }

    public function show(TipoFlete $tipoFlete)
    {
        abort_if(Gate::denies('tipo_flete_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoFletes.show', compact('tipoFlete'));
    }

    public function destroy(TipoFlete $tipoFlete)
    {
        abort_if(Gate::denies('tipo_flete_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoFlete->delete();

        return back();
    }

    public function massDestroy(MassDestroyTipoFleteRequest $request)
    {
        $tipoFletes = TipoFlete::find(request('ids'));

        foreach ($tipoFletes as $tipoFlete) {
            $tipoFlete->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
