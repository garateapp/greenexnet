<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyComunaRequest;
use App\Http\Requests\StoreComunaRequest;
use App\Http\Requests\UpdateComunaRequest;
use App\Models\Comuna;
use App\Models\Region;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ComunaController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('comuna_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $comunas = Comuna::with(['region'])->get();

        $regions = Region::get();

        return view('frontend.comunas.index', compact('comunas', 'regions'));
    }

    public function create()
    {
        abort_if(Gate::denies('comuna_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $regions = Region::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.comunas.create', compact('regions'));
    }

    public function store(StoreComunaRequest $request)
    {
        $comuna = Comuna::create($request->all());

        return redirect()->route('frontend.comunas.index');
    }

    public function edit(Comuna $comuna)
    {
        abort_if(Gate::denies('comuna_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $regions = Region::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comuna->load('region');

        return view('frontend.comunas.edit', compact('comuna', 'regions'));
    }

    public function update(UpdateComunaRequest $request, Comuna $comuna)
    {
        $comuna->update($request->all());

        return redirect()->route('frontend.comunas.index');
    }

    public function show(Comuna $comuna)
    {
        abort_if(Gate::denies('comuna_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $comuna->load('region');

        return view('frontend.comunas.show', compact('comuna'));
    }

    public function destroy(Comuna $comuna)
    {
        abort_if(Gate::denies('comuna_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $comuna->delete();

        return back();
    }

    public function massDestroy(MassDestroyComunaRequest $request)
    {
        $comunas = Comuna::find(request('ids'));

        foreach ($comunas as $comuna) {
            $comuna->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
