<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyManifiestRequest;
use App\Http\Requests\StoreManifiestRequest;
use App\Http\Requests\UpdateManifiestRequest;
use App\Models\Airline;
use App\Models\Ciudad;
use App\Models\Manifiest;
use App\Models\User;
use App\Models\Vuelo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManifiestController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('manifiest_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $manifiests = Manifiest::with(['linea_aerea', 'numero_vuelo', 'origen', 'destino', 'usuario'])->get();

        $airlines = Airline::get();

        $vuelos = Vuelo::get();

        $ciudads = Ciudad::get();

        $users = User::get();

        return view('frontend.manifiests.index', compact('airlines', 'ciudads', 'manifiests', 'users', 'vuelos'));
    }

    public function create()
    {
        abort_if(Gate::denies('manifiest_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $linea_aereas = Airline::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $numero_vuelos = Vuelo::pluck('vuelo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $origens = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $destinos = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $usuarios = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.manifiests.create', compact('destinos', 'linea_aereas', 'numero_vuelos', 'origens', 'usuarios'));
    }

    public function store(StoreManifiestRequest $request)
    {
        $manifiest = Manifiest::create($request->all());

        return redirect()->route('frontend.manifiests.index');
    }

    public function edit(Manifiest $manifiest)
    {
        abort_if(Gate::denies('manifiest_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $linea_aereas = Airline::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $numero_vuelos = Vuelo::pluck('vuelo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $origens = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $destinos = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $usuarios = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $manifiest->load('linea_aerea', 'numero_vuelo', 'origen', 'destino', 'usuario');

        return view('frontend.manifiests.edit', compact('destinos', 'linea_aereas', 'manifiest', 'numero_vuelos', 'origens', 'usuarios'));
    }

    public function update(UpdateManifiestRequest $request, Manifiest $manifiest)
    {
        $manifiest->update($request->all());

        return redirect()->route('frontend.manifiests.index');
    }

    public function show(Manifiest $manifiest)
    {
        abort_if(Gate::denies('manifiest_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $manifiest->load('linea_aerea', 'numero_vuelo', 'origen', 'destino', 'usuario', 'manifiestoImportacionMarcasManifiestos', 'mawbHawbs', 'mawbAdicionales', 'mawbGuia');

        return view('frontend.manifiests.show', compact('manifiest'));
    }

    public function destroy(Manifiest $manifiest)
    {
        abort_if(Gate::denies('manifiest_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $manifiest->delete();

        return back();
    }

    public function massDestroy(MassDestroyManifiestRequest $request)
    {
        $manifiests = Manifiest::find(request('ids'));

        foreach ($manifiests as $manifiest) {
            $manifiest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
