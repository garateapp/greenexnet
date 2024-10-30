<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPreCargaManifiestoRequest;
use App\Http\Requests\StorePreCargaManifiestoRequest;
use App\Http\Requests\UpdatePreCargaManifiestoRequest;
use App\Models\PreCargaManifiesto;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreCargaManifiestoController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('pre_carga_manifiesto_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $preCargaManifiestos = PreCargaManifiesto::all();

        return view('frontend.preCargaManifiestos.index', compact('preCargaManifiestos'));
    }

    public function create()
    {
        abort_if(Gate::denies('pre_carga_manifiesto_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.preCargaManifiestos.create');
    }

    public function store(StorePreCargaManifiestoRequest $request)
    {
        $preCargaManifiesto = PreCargaManifiesto::create($request->all());

        return redirect()->route('frontend.pre-carga-manifiestos.index');
    }

    public function edit(PreCargaManifiesto $preCargaManifiesto)
    {
        abort_if(Gate::denies('pre_carga_manifiesto_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.preCargaManifiestos.edit', compact('preCargaManifiesto'));
    }

    public function update(UpdatePreCargaManifiestoRequest $request, PreCargaManifiesto $preCargaManifiesto)
    {
        $preCargaManifiesto->update($request->all());

        return redirect()->route('frontend.pre-carga-manifiestos.index');
    }

    public function show(PreCargaManifiesto $preCargaManifiesto)
    {
        abort_if(Gate::denies('pre_carga_manifiesto_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.preCargaManifiestos.show', compact('preCargaManifiesto'));
    }

    public function destroy(PreCargaManifiesto $preCargaManifiesto)
    {
        abort_if(Gate::denies('pre_carga_manifiesto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $preCargaManifiesto->delete();

        return back();
    }

    public function massDestroy(MassDestroyPreCargaManifiestoRequest $request)
    {
        $preCargaManifiestos = PreCargaManifiesto::find(request('ids'));

        foreach ($preCargaManifiestos as $preCargaManifiesto) {
            $preCargaManifiesto->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
