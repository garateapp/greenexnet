<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyConsignatarioRequest;
use App\Http\Requests\StoreConsignatarioRequest;
use App\Http\Requests\UpdateConsignatarioRequest;
use App\Models\Comuna;
use App\Models\Consignatario;
use App\Models\Pai;
use App\Models\Region;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConsignatarioController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('consignatario_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $consignatarios = Consignatario::with(['region', 'comuna', 'pais'])->get();

        $regions = Region::get();

        $comunas = Comuna::get();

        $pais = Pai::get();

        return view('frontend.consignatarios.index', compact('comunas', 'consignatarios', 'pais', 'regions'));
    }

    public function create()
    {
        abort_if(Gate::denies('consignatario_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $regions = Region::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comunas = Comuna::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pais = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.consignatarios.create', compact('comunas', 'pais', 'regions'));
    }

    public function store(StoreConsignatarioRequest $request)
    {
        $consignatario = Consignatario::create($request->all());

        return redirect()->route('frontend.consignatarios.index');
    }

    public function edit(Consignatario $consignatario)
    {
        abort_if(Gate::denies('consignatario_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $regions = Region::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comunas = Comuna::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pais = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $consignatario->load('region', 'comuna', 'pais');

        return view('frontend.consignatarios.edit', compact('comunas', 'consignatario', 'pais', 'regions'));
    }

    public function update(UpdateConsignatarioRequest $request, Consignatario $consignatario)
    {
        $consignatario->update($request->all());

        return redirect()->route('frontend.consignatarios.index');
    }

    public function show(Consignatario $consignatario)
    {
        abort_if(Gate::denies('consignatario_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $consignatario->load('region', 'comuna', 'pais');

        return view('frontend.consignatarios.show', compact('consignatario'));
    }

    public function destroy(Consignatario $consignatario)
    {
        abort_if(Gate::denies('consignatario_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $consignatario->delete();

        return back();
    }

    public function massDestroy(MassDestroyConsignatarioRequest $request)
    {
        $consignatarios = Consignatario::find(request('ids'));

        foreach ($consignatarios as $consignatario) {
            $consignatario->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
