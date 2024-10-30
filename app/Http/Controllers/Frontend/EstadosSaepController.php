<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEstadosSaepRequest;
use App\Http\Requests\StoreEstadosSaepRequest;
use App\Http\Requests\UpdateEstadosSaepRequest;
use App\Models\EstadosSaep;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EstadosSaepController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('estados_saep_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $estadosSaeps = EstadosSaep::all();

        return view('frontend.estadosSaeps.index', compact('estadosSaeps'));
    }

    public function create()
    {
        abort_if(Gate::denies('estados_saep_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.estadosSaeps.create');
    }

    public function store(StoreEstadosSaepRequest $request)
    {
        $estadosSaep = EstadosSaep::create($request->all());

        return redirect()->route('frontend.estados-saeps.index');
    }

    public function edit(EstadosSaep $estadosSaep)
    {
        abort_if(Gate::denies('estados_saep_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.estadosSaeps.edit', compact('estadosSaep'));
    }

    public function update(UpdateEstadosSaepRequest $request, EstadosSaep $estadosSaep)
    {
        $estadosSaep->update($request->all());

        return redirect()->route('frontend.estados-saeps.index');
    }

    public function show(EstadosSaep $estadosSaep)
    {
        abort_if(Gate::denies('estados_saep_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.estadosSaeps.show', compact('estadosSaep'));
    }

    public function destroy(EstadosSaep $estadosSaep)
    {
        abort_if(Gate::denies('estados_saep_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $estadosSaep->delete();

        return back();
    }

    public function massDestroy(MassDestroyEstadosSaepRequest $request)
    {
        $estadosSaeps = EstadosSaep::find(request('ids'));

        foreach ($estadosSaeps as $estadosSaep) {
            $estadosSaep->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
