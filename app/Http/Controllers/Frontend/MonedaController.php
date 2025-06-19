<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyMonedaRequest;
use App\Http\Requests\StoreMonedaRequest;
use App\Http\Requests\UpdateMonedaRequest;
use App\Models\Moneda;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MonedaController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('moneda_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $monedas = Moneda::all();

        return view('frontend.monedas.index', compact('monedas'));
    }

    public function create()
    {
        abort_if(Gate::denies('moneda_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.monedas.create');
    }

    public function store(StoreMonedaRequest $request)
    {
        $moneda = Moneda::create($request->all());

        return redirect()->route('frontend.monedas.index');
    }

    public function edit(Moneda $moneda)
    {
        abort_if(Gate::denies('moneda_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.monedas.edit', compact('moneda'));
    }

    public function update(UpdateMonedaRequest $request, Moneda $moneda)
    {
        $moneda->update($request->all());

        return redirect()->route('frontend.monedas.index');
    }

    public function show(Moneda $moneda)
    {
        abort_if(Gate::denies('moneda_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.monedas.show', compact('moneda'));
    }

    public function destroy(Moneda $moneda)
    {
        abort_if(Gate::denies('moneda_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $moneda->delete();

        return back();
    }

    public function massDestroy(MassDestroyMonedaRequest $request)
    {
        $monedas = Moneda::find(request('ids'));

        foreach ($monedas as $moneda) {
            $moneda->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
