<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCorrelativoRequest;
use App\Http\Requests\StoreCorrelativoRequest;
use App\Http\Requests\UpdateCorrelativoRequest;
use App\Models\Correlativo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorrelativoController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('correlativo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $correlativos = Correlativo::all();

        return view('frontend.correlativos.index', compact('correlativos'));
    }

    public function create()
    {
        abort_if(Gate::denies('correlativo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.correlativos.create');
    }

    public function store(StoreCorrelativoRequest $request)
    {
        $correlativo = Correlativo::create($request->all());

        return redirect()->route('frontend.correlativos.index');
    }

    public function edit(Correlativo $correlativo)
    {
        abort_if(Gate::denies('correlativo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.correlativos.edit', compact('correlativo'));
    }

    public function update(UpdateCorrelativoRequest $request, Correlativo $correlativo)
    {
        $correlativo->update($request->all());

        return redirect()->route('frontend.correlativos.index');
    }

    public function show(Correlativo $correlativo)
    {
        abort_if(Gate::denies('correlativo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.correlativos.show', compact('correlativo'));
    }

    public function destroy(Correlativo $correlativo)
    {
        abort_if(Gate::denies('correlativo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $correlativo->delete();

        return back();
    }

    public function massDestroy(MassDestroyCorrelativoRequest $request)
    {
        $correlativos = Correlativo::find(request('ids'));

        foreach ($correlativos as $correlativo) {
            $correlativo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
