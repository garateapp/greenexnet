<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCorreoalsoAirRequest;
use App\Http\Requests\StoreCorreoalsoAirRequest;
use App\Http\Requests\UpdateCorreoalsoAirRequest;
use App\Models\BaseRecibidor;
use App\Models\CorreoalsoAir;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorreoalsoAirController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('correoalso_air_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $correoalsoAirs = CorreoalsoAir::with(['cliente'])->get();

        $base_recibidors = BaseRecibidor::get();

        return view('frontend.correoalsoAirs.index', compact('base_recibidors', 'correoalsoAirs'));
    }

    public function create()
    {
        abort_if(Gate::denies('correoalso_air_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = BaseRecibidor::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.correoalsoAirs.create', compact('clientes'));
    }

    public function store(StoreCorreoalsoAirRequest $request)
    {
        $correoalsoAir = CorreoalsoAir::create($request->all());

        return redirect()->route('frontend.correoalso-airs.index');
    }

    public function edit(CorreoalsoAir $correoalsoAir)
    {
        abort_if(Gate::denies('correoalso_air_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = BaseRecibidor::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $correoalsoAir->load('cliente');

        return view('frontend.correoalsoAirs.edit', compact('clientes', 'correoalsoAir'));
    }

    public function update(UpdateCorreoalsoAirRequest $request, CorreoalsoAir $correoalsoAir)
    {
        $correoalsoAir->update($request->all());

        return redirect()->route('frontend.correoalso-airs.index');
    }

    public function show(CorreoalsoAir $correoalsoAir)
    {
        abort_if(Gate::denies('correoalso_air_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $correoalsoAir->load('cliente');

        return view('frontend.correoalsoAirs.show', compact('correoalsoAir'));
    }

    public function destroy(CorreoalsoAir $correoalsoAir)
    {
        abort_if(Gate::denies('correoalso_air_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $correoalsoAir->delete();

        return back();
    }

    public function massDestroy(MassDestroyCorreoalsoAirRequest $request)
    {
        $correoalsoAirs = CorreoalsoAir::find(request('ids'));

        foreach ($correoalsoAirs as $correoalsoAir) {
            $correoalsoAir->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
