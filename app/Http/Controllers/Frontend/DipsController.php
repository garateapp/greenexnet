<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyDipRequest;
use App\Http\Requests\StoreDipRequest;
use App\Http\Requests\UpdateDipRequest;
use App\Models\Arancel;
use App\Models\Comuna;
use App\Models\Consignatario;
use App\Models\Dip;
use App\Models\Guium;
use App\Models\Manifiest;
use App\Models\Pai;
use App\Models\Regiman;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DipsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('dip_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dips = Dip::with(['mawb', 'hawb', 'regimen', 'comuna', 'consig', 'pais_origen', 'cod_arancelario'])->get();

        $manifiests = Manifiest::get();

        $guia = Guium::get();

        $regimen = Regiman::get();

        $comunas = Comuna::get();

        $consignatarios = Consignatario::get();

        $pais = Pai::get();

        $arancels = Arancel::get();

        return view('frontend.dips.index', compact('arancels', 'comunas', 'consignatarios', 'dips', 'guia', 'manifiests', 'pais', 'regimen'));
    }

    public function create()
    {
        abort_if(Gate::denies('dip_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mawbs = Manifiest::pluck('mawb', 'id')->prepend(trans('global.pleaseSelect'), '');

        $hawbs = Guium::pluck('guia_courier', 'id')->prepend(trans('global.pleaseSelect'), '');

        $regimens = Regiman::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comunas = Comuna::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $consigs = Consignatario::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pais_origens = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $cod_arancelarios = Arancel::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.dips.create', compact('cod_arancelarios', 'comunas', 'consigs', 'hawbs', 'mawbs', 'pais_origens', 'regimens'));
    }

    public function store(StoreDipRequest $request)
    {
        $dip = Dip::create($request->all());

        return redirect()->route('frontend.dips.index');
    }

    public function edit(Dip $dip)
    {
        abort_if(Gate::denies('dip_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mawbs = Manifiest::pluck('mawb', 'id')->prepend(trans('global.pleaseSelect'), '');

        $hawbs = Guium::pluck('guia_courier', 'id')->prepend(trans('global.pleaseSelect'), '');

        $regimens = Regiman::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comunas = Comuna::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $consigs = Consignatario::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pais_origens = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $cod_arancelarios = Arancel::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $dip->load('mawb', 'hawb', 'regimen', 'comuna', 'consig', 'pais_origen', 'cod_arancelario');

        return view('frontend.dips.edit', compact('cod_arancelarios', 'comunas', 'consigs', 'dip', 'hawbs', 'mawbs', 'pais_origens', 'regimens'));
    }

    public function update(UpdateDipRequest $request, Dip $dip)
    {
        $dip->update($request->all());

        return redirect()->route('frontend.dips.index');
    }

    public function show(Dip $dip)
    {
        abort_if(Gate::denies('dip_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dip->load('mawb', 'hawb', 'regimen', 'comuna', 'consig', 'pais_origen', 'cod_arancelario');

        return view('frontend.dips.show', compact('dip'));
    }

    public function destroy(Dip $dip)
    {
        abort_if(Gate::denies('dip_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dip->delete();

        return back();
    }

    public function massDestroy(MassDestroyDipRequest $request)
    {
        $dips = Dip::find(request('ids'));

        foreach ($dips as $dip) {
            $dip->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
