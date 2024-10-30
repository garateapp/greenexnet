<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyDussiRequest;
use App\Http\Requests\StoreDussiRequest;
use App\Http\Requests\UpdateDussiRequest;
use App\Models\Aduana;
use App\Models\Almacenistum;
use App\Models\Arancel;
use App\Models\Batch;
use App\Models\Comuna;
use App\Models\Dussi;
use App\Models\Pai;
use App\Models\Puerto;
use App\Models\TipoBulto;
use App\Models\Vuelo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DussiController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('dussi_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dussis = Dussi::with(['vuelo', 'aduana', 'despachador', 'comuna', 'puerto_embarque', 'pais_destino', 'arancel', 'tipo_bulto', 'batch'])->get();

        $vuelos = Vuelo::get();

        $aduanas = Aduana::get();

        $almacenista = Almacenistum::get();

        $comunas = Comuna::get();

        $puertos = Puerto::get();

        $pais = Pai::get();

        $arancels = Arancel::get();

        $tipo_bultos = TipoBulto::get();

        $batches = Batch::get();

        return view('frontend.dussis.index', compact('aduanas', 'almacenista', 'arancels', 'batches', 'comunas', 'dussis', 'pais', 'puertos', 'tipo_bultos', 'vuelos'));
    }

    public function create()
    {
        abort_if(Gate::denies('dussi_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vuelos = Vuelo::pluck('vuelo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $aduanas = Aduana::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $despachadors = Almacenistum::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comunas = Comuna::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puerto_embarques = Puerto::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pais_destinos = Pai::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $arancels = Arancel::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_bultos = TipoBulto::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $batches = Batch::pluck('num_batch', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.dussis.create', compact('aduanas', 'arancels', 'batches', 'comunas', 'despachadors', 'pais_destinos', 'puerto_embarques', 'tipo_bultos', 'vuelos'));
    }

    public function store(StoreDussiRequest $request)
    {
        $dussi = Dussi::create($request->all());

        return redirect()->route('frontend.dussis.index');
    }

    public function edit(Dussi $dussi)
    {
        abort_if(Gate::denies('dussi_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vuelos = Vuelo::pluck('vuelo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $aduanas = Aduana::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $despachadors = Almacenistum::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comunas = Comuna::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puerto_embarques = Puerto::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pais_destinos = Pai::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $arancels = Arancel::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_bultos = TipoBulto::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $batches = Batch::pluck('num_batch', 'id')->prepend(trans('global.pleaseSelect'), '');

        $dussi->load('vuelo', 'aduana', 'despachador', 'comuna', 'puerto_embarque', 'pais_destino', 'arancel', 'tipo_bulto', 'batch');

        return view('frontend.dussis.edit', compact('aduanas', 'arancels', 'batches', 'comunas', 'despachadors', 'dussi', 'pais_destinos', 'puerto_embarques', 'tipo_bultos', 'vuelos'));
    }

    public function update(UpdateDussiRequest $request, Dussi $dussi)
    {
        $dussi->update($request->all());

        return redirect()->route('frontend.dussis.index');
    }

    public function show(Dussi $dussi)
    {
        abort_if(Gate::denies('dussi_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dussi->load('vuelo', 'aduana', 'despachador', 'comuna', 'puerto_embarque', 'pais_destino', 'arancel', 'tipo_bulto', 'batch');

        return view('frontend.dussis.show', compact('dussi'));
    }

    public function destroy(Dussi $dussi)
    {
        abort_if(Gate::denies('dussi_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dussi->delete();

        return back();
    }

    public function massDestroy(MassDestroyDussiRequest $request)
    {
        $dussis = Dussi::find(request('ids'));

        foreach ($dussis as $dussi) {
            $dussi->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
