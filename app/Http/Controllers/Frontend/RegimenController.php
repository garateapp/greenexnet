<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyRegimanRequest;
use App\Http\Requests\StoreRegimanRequest;
use App\Http\Requests\UpdateRegimanRequest;
use App\Models\Regiman;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegimenController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('regiman_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $regimen = Regiman::all();

        return view('frontend.regimen.index', compact('regimen'));
    }

    public function create()
    {
        abort_if(Gate::denies('regiman_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.regimen.create');
    }

    public function store(StoreRegimanRequest $request)
    {
        $regiman = Regiman::create($request->all());

        return redirect()->route('frontend.regimen.index');
    }

    public function edit(Regiman $regiman)
    {
        abort_if(Gate::denies('regiman_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.regimen.edit', compact('regiman'));
    }

    public function update(UpdateRegimanRequest $request, Regiman $regiman)
    {
        $regiman->update($request->all());

        return redirect()->route('frontend.regimen.index');
    }

    public function show(Regiman $regiman)
    {
        abort_if(Gate::denies('regiman_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.regimen.show', compact('regiman'));
    }

    public function destroy(Regiman $regiman)
    {
        abort_if(Gate::denies('regiman_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $regiman->delete();

        return back();
    }

    public function massDestroy(MassDestroyRegimanRequest $request)
    {
        $regimen = Regiman::find(request('ids'));

        foreach ($regimen as $regiman) {
            $regiman->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
