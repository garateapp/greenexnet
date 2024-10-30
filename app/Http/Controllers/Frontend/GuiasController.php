<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyGuiumRequest;
use App\Http\Requests\StoreGuiumRequest;
use App\Http\Requests\UpdateGuiumRequest;
use App\Models\Ciudad;
use App\Models\Comuna;
use App\Models\Guium;
use App\Models\Manifiest;
use App\Models\Status;
use App\Models\Tipoequi;
use App\Models\TipoFlete;
use App\Models\TipoHawb;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuiasController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('guium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guia = Guium::with(['origen', 'destino', 'tipo_hawb', 'estado', 'comuna', 'x_1', 'mawb', 'tipo_flete', 'usuario'])->get();

        $ciudads = Ciudad::get();

        $tipo_hawbs = TipoHawb::get();

        $statuses = Status::get();

        $comunas = Comuna::get();

        $tipoequis = Tipoequi::get();

        $manifiests = Manifiest::get();

        $tipo_fletes = TipoFlete::get();

        $users = User::get();

        return view('frontend.guia.index', compact('ciudads', 'comunas', 'guia', 'manifiests', 'statuses', 'tipo_fletes', 'tipo_hawbs', 'tipoequis', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('guium_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $origens = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $destinos = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_hawbs = TipoHawb::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = Status::pluck('estado', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comunas = Comuna::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $x_1s = Tipoequi::pluck('valor', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mawbs = Manifiest::pluck('mawb', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_fletes = TipoFlete::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $usuarios = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.guia.create', compact('comunas', 'destinos', 'estados', 'mawbs', 'origens', 'tipo_fletes', 'tipo_hawbs', 'usuarios', 'x_1s'));
    }

    public function store(StoreGuiumRequest $request)
    {
        $guium = Guium::create($request->all());

        return redirect()->route('frontend.guia.index');
    }

    public function edit(Guium $guium)
    {
        abort_if(Gate::denies('guium_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $origens = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $destinos = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_hawbs = TipoHawb::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = Status::pluck('estado', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comunas = Comuna::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $x_1s = Tipoequi::pluck('valor', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mawbs = Manifiest::pluck('mawb', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_fletes = TipoFlete::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $usuarios = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $guium->load('origen', 'destino', 'tipo_hawb', 'estado', 'comuna', 'x_1', 'mawb', 'tipo_flete', 'usuario');

        return view('frontend.guia.edit', compact('comunas', 'destinos', 'estados', 'guium', 'mawbs', 'origens', 'tipo_fletes', 'tipo_hawbs', 'usuarios', 'x_1s'));
    }

    public function update(UpdateGuiumRequest $request, Guium $guium)
    {
        $guium->update($request->all());

        return redirect()->route('frontend.guia.index');
    }

    public function show(Guium $guium)
    {
        abort_if(Gate::denies('guium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guium->load('origen', 'destino', 'tipo_hawb', 'estado', 'comuna', 'x_1', 'mawb', 'tipo_flete', 'usuario', 'mawbDips');

        return view('frontend.guia.show', compact('guium'));
    }

    public function destroy(Guium $guium)
    {
        abort_if(Gate::denies('guium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guium->delete();

        return back();
    }

    public function massDestroy(MassDestroyGuiumRequest $request)
    {
        $guia = Guium::find(request('ids'));

        foreach ($guia as $guium) {
            $guium->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
