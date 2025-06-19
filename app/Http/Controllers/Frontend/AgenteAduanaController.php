<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAgenteAduanaRequest;
use App\Http\Requests\StoreAgenteAduanaRequest;
use App\Http\Requests\UpdateAgenteAduanaRequest;
use App\Models\AgenteAduana;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgenteAduanaController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('agente_aduana_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $agenteAduanas = AgenteAduana::all();

        return view('frontend.agenteAduanas.index', compact('agenteAduanas'));
    }

    public function create()
    {
        abort_if(Gate::denies('agente_aduana_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.agenteAduanas.create');
    }

    public function store(StoreAgenteAduanaRequest $request)
    {
        $agenteAduana = AgenteAduana::create($request->all());

        return redirect()->route('frontend.agente-aduanas.index');
    }

    public function edit(AgenteAduana $agenteAduana)
    {
        abort_if(Gate::denies('agente_aduana_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.agenteAduanas.edit', compact('agenteAduana'));
    }

    public function update(UpdateAgenteAduanaRequest $request, AgenteAduana $agenteAduana)
    {
        $agenteAduana->update($request->all());

        return redirect()->route('frontend.agente-aduanas.index');
    }

    public function show(AgenteAduana $agenteAduana)
    {
        abort_if(Gate::denies('agente_aduana_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.agenteAduanas.show', compact('agenteAduana'));
    }

    public function destroy(AgenteAduana $agenteAduana)
    {
        abort_if(Gate::denies('agente_aduana_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $agenteAduana->delete();

        return back();
    }

    public function massDestroy(MassDestroyAgenteAduanaRequest $request)
    {
        $agenteAduanas = AgenteAduana::find(request('ids'));

        foreach ($agenteAduanas as $agenteAduana) {
            $agenteAduana->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
