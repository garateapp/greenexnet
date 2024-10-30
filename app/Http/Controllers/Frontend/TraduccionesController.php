<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTraduccioneRequest;
use App\Http\Requests\StoreTraduccioneRequest;
use App\Http\Requests\UpdateTraduccioneRequest;
use App\Models\Traduccione;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TraduccionesController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('traduccione_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $traducciones = Traduccione::all();

        return view('frontend.traducciones.index', compact('traducciones'));
    }

    public function create()
    {
        abort_if(Gate::denies('traduccione_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.traducciones.create');
    }

    public function store(StoreTraduccioneRequest $request)
    {
        $traduccione = Traduccione::create($request->all());

        return redirect()->route('frontend.traducciones.index');
    }

    public function edit(Traduccione $traduccione)
    {
        abort_if(Gate::denies('traduccione_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.traducciones.edit', compact('traduccione'));
    }

    public function update(UpdateTraduccioneRequest $request, Traduccione $traduccione)
    {
        $traduccione->update($request->all());

        return redirect()->route('frontend.traducciones.index');
    }

    public function show(Traduccione $traduccione)
    {
        abort_if(Gate::denies('traduccione_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.traducciones.show', compact('traduccione'));
    }

    public function destroy(Traduccione $traduccione)
    {
        abort_if(Gate::denies('traduccione_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $traduccione->delete();

        return back();
    }

    public function massDestroy(MassDestroyTraduccioneRequest $request)
    {
        $traducciones = Traduccione::find(request('ids'));

        foreach ($traducciones as $traduccione) {
            $traduccione->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
