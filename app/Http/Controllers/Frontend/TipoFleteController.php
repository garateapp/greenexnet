<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTipofleteRequest;
use App\Http\Requests\StoreTipofleteRequest;
use App\Http\Requests\UpdateTipofleteRequest;
use App\Models\Tipoflete;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipofleteController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('tipoflete_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipofletes = Tipoflete::all();

        return view('frontend.tipofletes.index', compact('tipofletes'));
    }

    public function create()
    {
        abort_if(Gate::denies('tipoflete_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipofletes.create');
    }

    public function store(StoreTipofleteRequest $request)
    {
        $tipoflete = Tipoflete::create($request->all());

        return redirect()->route('frontend.tipofletes.index');
    }

    public function edit(Tipoflete $tipoflete)
    {
        abort_if(Gate::denies('tipoflete_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipofletes.edit', compact('tipoflete'));
    }

    public function update(UpdateTipofleteRequest $request, Tipoflete $tipoflete)
    {
        $tipoflete->update($request->all());

        return redirect()->route('frontend.tipofletes.index');
    }

    public function show(Tipoflete $tipoflete)
    {
        abort_if(Gate::denies('tipoflete_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipofletes.show', compact('tipoflete'));
    }

    public function destroy(Tipoflete $tipoflete)
    {
        abort_if(Gate::denies('tipoflete_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoflete->delete();

        return back();
    }

    public function massDestroy(MassDestroyTipofleteRequest $request)
    {
        $tipofletes = Tipoflete::find(request('ids'));

        foreach ($tipofletes as $tipoflete) {
            $tipoflete->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
