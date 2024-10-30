<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyGuiasDussiRequest;
use App\Http\Requests\StoreGuiasDussiRequest;
use App\Http\Requests\UpdateGuiasDussiRequest;
use App\Models\GuiasDussi;
use App\Models\Vuelo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuiasDussiController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('guias_dussi_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guiasDussis = GuiasDussi::with(['vuelo'])->get();

        $vuelos = Vuelo::get();

        return view('frontend.guiasDussis.index', compact('guiasDussis', 'vuelos'));
    }

    public function create()
    {
        abort_if(Gate::denies('guias_dussi_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vuelos = Vuelo::pluck('vuelo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.guiasDussis.create', compact('vuelos'));
    }

    public function store(StoreGuiasDussiRequest $request)
    {
        $guiasDussi = GuiasDussi::create($request->all());

        return redirect()->route('frontend.guias-dussis.index');
    }

    public function edit(GuiasDussi $guiasDussi)
    {
        abort_if(Gate::denies('guias_dussi_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vuelos = Vuelo::pluck('vuelo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $guiasDussi->load('vuelo');

        return view('frontend.guiasDussis.edit', compact('guiasDussi', 'vuelos'));
    }

    public function update(UpdateGuiasDussiRequest $request, GuiasDussi $guiasDussi)
    {
        $guiasDussi->update($request->all());

        return redirect()->route('frontend.guias-dussis.index');
    }

    public function show(GuiasDussi $guiasDussi)
    {
        abort_if(Gate::denies('guias_dussi_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guiasDussi->load('vuelo');

        return view('frontend.guiasDussis.show', compact('guiasDussi'));
    }

    public function destroy(GuiasDussi $guiasDussi)
    {
        abort_if(Gate::denies('guias_dussi_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guiasDussi->delete();

        return back();
    }

    public function massDestroy(MassDestroyGuiasDussiRequest $request)
    {
        $guiasDussis = GuiasDussi::find(request('ids'));

        foreach ($guiasDussis as $guiasDussi) {
            $guiasDussi->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
