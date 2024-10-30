<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyBodegaRequest;
use App\Http\Requests\StoreBodegaRequest;
use App\Http\Requests\UpdateBodegaRequest;
use App\Models\Bodega;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BodegaController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('bodega_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bodegas = Bodega::all();

        return view('frontend.bodegas.index', compact('bodegas'));
    }

    public function create()
    {
        abort_if(Gate::denies('bodega_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.bodegas.create');
    }

    public function store(StoreBodegaRequest $request)
    {
        $bodega = Bodega::create($request->all());

        return redirect()->route('frontend.bodegas.index');
    }

    public function edit(Bodega $bodega)
    {
        abort_if(Gate::denies('bodega_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.bodegas.edit', compact('bodega'));
    }

    public function update(UpdateBodegaRequest $request, Bodega $bodega)
    {
        $bodega->update($request->all());

        return redirect()->route('frontend.bodegas.index');
    }

    public function show(Bodega $bodega)
    {
        abort_if(Gate::denies('bodega_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.bodegas.show', compact('bodega'));
    }

    public function destroy(Bodega $bodega)
    {
        abort_if(Gate::denies('bodega_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bodega->delete();

        return back();
    }

    public function massDestroy(MassDestroyBodegaRequest $request)
    {
        $bodegas = Bodega::find(request('ids'));

        foreach ($bodegas as $bodega) {
            $bodega->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
