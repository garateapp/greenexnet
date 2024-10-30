<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTipoBultoRequest;
use App\Http\Requests\StoreTipoBultoRequest;
use App\Http\Requests\UpdateTipoBultoRequest;
use App\Models\TipoBulto;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoBultoController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('tipo_bulto_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoBultos = TipoBulto::all();

        return view('frontend.tipoBultos.index', compact('tipoBultos'));
    }

    public function create()
    {
        abort_if(Gate::denies('tipo_bulto_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoBultos.create');
    }

    public function store(StoreTipoBultoRequest $request)
    {
        $tipoBulto = TipoBulto::create($request->all());

        return redirect()->route('frontend.tipo-bultos.index');
    }

    public function edit(TipoBulto $tipoBulto)
    {
        abort_if(Gate::denies('tipo_bulto_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoBultos.edit', compact('tipoBulto'));
    }

    public function update(UpdateTipoBultoRequest $request, TipoBulto $tipoBulto)
    {
        $tipoBulto->update($request->all());

        return redirect()->route('frontend.tipo-bultos.index');
    }

    public function show(TipoBulto $tipoBulto)
    {
        abort_if(Gate::denies('tipo_bulto_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoBultos.show', compact('tipoBulto'));
    }

    public function destroy(TipoBulto $tipoBulto)
    {
        abort_if(Gate::denies('tipo_bulto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoBulto->delete();

        return back();
    }

    public function massDestroy(MassDestroyTipoBultoRequest $request)
    {
        $tipoBultos = TipoBulto::find(request('ids'));

        foreach ($tipoBultos as $tipoBulto) {
            $tipoBulto->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
