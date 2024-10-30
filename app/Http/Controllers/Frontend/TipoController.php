<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTipoRequest;
use App\Http\Requests\StoreTipoRequest;
use App\Http\Requests\UpdateTipoRequest;
use App\Models\Tipo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('tipo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipos = Tipo::all();

        return view('frontend.tipos.index', compact('tipos'));
    }

    public function create()
    {
        abort_if(Gate::denies('tipo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipos.create');
    }

    public function store(StoreTipoRequest $request)
    {
        $tipo = Tipo::create($request->all());

        return redirect()->route('frontend.tipos.index');
    }

    public function edit(Tipo $tipo)
    {
        abort_if(Gate::denies('tipo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipos.edit', compact('tipo'));
    }

    public function update(UpdateTipoRequest $request, Tipo $tipo)
    {
        $tipo->update($request->all());

        return redirect()->route('frontend.tipos.index');
    }

    public function show(Tipo $tipo)
    {
        abort_if(Gate::denies('tipo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipos.show', compact('tipo'));
    }

    public function destroy(Tipo $tipo)
    {
        abort_if(Gate::denies('tipo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipo->delete();

        return back();
    }

    public function massDestroy(MassDestroyTipoRequest $request)
    {
        $tipos = Tipo::find(request('ids'));

        foreach ($tipos as $tipo) {
            $tipo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
