<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTipoequiRequest;
use App\Http\Requests\StoreTipoequiRequest;
use App\Http\Requests\UpdateTipoequiRequest;
use App\Models\Tipoequi;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoequisController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('tipoequi_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoequis = Tipoequi::all();

        return view('frontend.tipoequis.index', compact('tipoequis'));
    }

    public function create()
    {
        abort_if(Gate::denies('tipoequi_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoequis.create');
    }

    public function store(StoreTipoequiRequest $request)
    {
        $tipoequi = Tipoequi::create($request->all());

        return redirect()->route('frontend.tipoequis.index');
    }

    public function edit(Tipoequi $tipoequi)
    {
        abort_if(Gate::denies('tipoequi_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoequis.edit', compact('tipoequi'));
    }

    public function update(UpdateTipoequiRequest $request, Tipoequi $tipoequi)
    {
        $tipoequi->update($request->all());

        return redirect()->route('frontend.tipoequis.index');
    }

    public function show(Tipoequi $tipoequi)
    {
        abort_if(Gate::denies('tipoequi_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoequis.show', compact('tipoequi'));
    }

    public function destroy(Tipoequi $tipoequi)
    {
        abort_if(Gate::denies('tipoequi_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoequi->delete();

        return back();
    }

    public function massDestroy(MassDestroyTipoequiRequest $request)
    {
        $tipoequis = Tipoequi::find(request('ids'));

        foreach ($tipoequis as $tipoequi) {
            $tipoequi->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
