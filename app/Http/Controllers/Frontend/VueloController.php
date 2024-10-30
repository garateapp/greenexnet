<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyVueloRequest;
use App\Http\Requests\StoreVueloRequest;
use App\Http\Requests\UpdateVueloRequest;
use App\Models\Airline;
use App\Models\Ciudad;
use App\Models\Vuelo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VueloController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('vuelo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vuelos = Vuelo::with(['codigo', 'ciudad_origen', 'ciudad_destino', 'linea_aerea'])->get();

        $airlines = Airline::get();

        $ciudads = Ciudad::get();

        return view('frontend.vuelos.index', compact('airlines', 'ciudads', 'vuelos'));
    }

    public function create()
    {
        abort_if(Gate::denies('vuelo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $codigos = Airline::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ciudad_origens = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ciudad_destinos = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $linea_aereas = Airline::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.vuelos.create', compact('ciudad_destinos', 'ciudad_origens', 'codigos', 'linea_aereas'));
    }

    public function store(StoreVueloRequest $request)
    {
        $vuelo = Vuelo::create($request->all());

        return redirect()->route('frontend.vuelos.index');
    }

    public function edit(Vuelo $vuelo)
    {
        abort_if(Gate::denies('vuelo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $codigos = Airline::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ciudad_origens = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ciudad_destinos = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $linea_aereas = Airline::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $vuelo->load('codigo', 'ciudad_origen', 'ciudad_destino', 'linea_aerea');

        return view('frontend.vuelos.edit', compact('ciudad_destinos', 'ciudad_origens', 'codigos', 'linea_aereas', 'vuelo'));
    }

    public function update(UpdateVueloRequest $request, Vuelo $vuelo)
    {
        $vuelo->update($request->all());

        return redirect()->route('frontend.vuelos.index');
    }

    public function show(Vuelo $vuelo)
    {
        abort_if(Gate::denies('vuelo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vuelo->load('codigo', 'ciudad_origen', 'ciudad_destino', 'linea_aerea');

        return view('frontend.vuelos.show', compact('vuelo'));
    }

    public function destroy(Vuelo $vuelo)
    {
        abort_if(Gate::denies('vuelo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vuelo->delete();

        return back();
    }

    public function massDestroy(MassDestroyVueloRequest $request)
    {
        $vuelos = Vuelo::find(request('ids'));

        foreach ($vuelos as $vuelo) {
            $vuelo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
