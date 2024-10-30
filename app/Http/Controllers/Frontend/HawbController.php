<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyHawbRequest;
use App\Http\Requests\StoreHawbRequest;
use App\Http\Requests\UpdateHawbRequest;
use App\Models\Ciudad;
use App\Models\Comuna;
use App\Models\Hawb;
use App\Models\Manifiest;
use App\Models\Status;
use App\Models\Tipoequi;
use App\Models\TipoHawb;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HawbController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('hawb_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hawbs = Hawb::with(['origen', 'destino', 'tipo_hawb', 'estado', 'comuna', 'x_1', 'mawb'])->get();

        $ciudads = Ciudad::get();

        $tipo_hawbs = TipoHawb::get();

        $statuses = Status::get();

        $comunas = Comuna::get();

        $tipoequis = Tipoequi::get();

        $manifiests = Manifiest::get();

        return view('frontend.hawbs.index', compact('ciudads', 'comunas', 'hawbs', 'manifiests', 'statuses', 'tipo_hawbs', 'tipoequis'));
    }

    public function create()
    {
        abort_if(Gate::denies('hawb_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $origens = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $destinos = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_hawbs = TipoHawb::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = Status::pluck('estado', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comunas = Comuna::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $x_1s = Tipoequi::pluck('valor', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mawbs = Manifiest::pluck('mawb', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.hawbs.create', compact('comunas', 'destinos', 'estados', 'mawbs', 'origens', 'tipo_hawbs', 'x_1s'));
    }

    public function store(StoreHawbRequest $request)
    {
        $hawb = Hawb::create($request->all());

        return redirect()->route('frontend.hawbs.index');
    }

    public function edit(Hawb $hawb)
    {
        abort_if(Gate::denies('hawb_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $origens = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $destinos = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_hawbs = TipoHawb::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = Status::pluck('estado', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comunas = Comuna::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $x_1s = Tipoequi::pluck('valor', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mawbs = Manifiest::pluck('mawb', 'id')->prepend(trans('global.pleaseSelect'), '');

        $hawb->load('origen', 'destino', 'tipo_hawb', 'estado', 'comuna', 'x_1', 'mawb');

        return view('frontend.hawbs.edit', compact('comunas', 'destinos', 'estados', 'hawb', 'mawbs', 'origens', 'tipo_hawbs', 'x_1s'));
    }

    public function update(UpdateHawbRequest $request, Hawb $hawb)
    {
        $hawb->update($request->all());

        return redirect()->route('frontend.hawbs.index');
    }

    public function show(Hawb $hawb)
    {
        abort_if(Gate::denies('hawb_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hawb->load('origen', 'destino', 'tipo_hawb', 'estado', 'comuna', 'x_1', 'mawb');

        return view('frontend.hawbs.show', compact('hawb'));
    }

    public function destroy(Hawb $hawb)
    {
        abort_if(Gate::denies('hawb_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hawb->delete();

        return back();
    }

    public function massDestroy(MassDestroyHawbRequest $request)
    {
        $hawbs = Hawb::find(request('ids'));

        foreach ($hawbs as $hawb) {
            $hawb->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
