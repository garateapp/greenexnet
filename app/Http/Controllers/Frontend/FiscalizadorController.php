<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyFiscalizadorRequest;
use App\Http\Requests\StoreFiscalizadorRequest;
use App\Http\Requests\UpdateFiscalizadorRequest;
use App\Models\Fiscalizador;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FiscalizadorController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('fiscalizador_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fiscalizadors = Fiscalizador::all();

        return view('frontend.fiscalizadors.index', compact('fiscalizadors'));
    }

    public function create()
    {
        abort_if(Gate::denies('fiscalizador_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.fiscalizadors.create');
    }

    public function store(StoreFiscalizadorRequest $request)
    {
        $fiscalizador = Fiscalizador::create($request->all());

        return redirect()->route('frontend.fiscalizadors.index');
    }

    public function edit(Fiscalizador $fiscalizador)
    {
        abort_if(Gate::denies('fiscalizador_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.fiscalizadors.edit', compact('fiscalizador'));
    }

    public function update(UpdateFiscalizadorRequest $request, Fiscalizador $fiscalizador)
    {
        $fiscalizador->update($request->all());

        return redirect()->route('frontend.fiscalizadors.index');
    }

    public function show(Fiscalizador $fiscalizador)
    {
        abort_if(Gate::denies('fiscalizador_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.fiscalizadors.show', compact('fiscalizador'));
    }

    public function destroy(Fiscalizador $fiscalizador)
    {
        abort_if(Gate::denies('fiscalizador_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fiscalizador->delete();

        return back();
    }

    public function massDestroy(MassDestroyFiscalizadorRequest $request)
    {
        $fiscalizadors = Fiscalizador::find(request('ids'));

        foreach ($fiscalizadors as $fiscalizador) {
            $fiscalizador->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
