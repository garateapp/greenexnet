<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyImportacionMarcasManifiestoRequest;
use App\Http\Requests\StoreImportacionMarcasManifiestoRequest;
use App\Http\Requests\UpdateImportacionMarcasManifiestoRequest;
use App\Models\ImportacionMarcasManifiesto;
use App\Models\Manifiest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImportacionMarcasManifiestoController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('importacion_marcas_manifiesto_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $importacionMarcasManifiestos = ImportacionMarcasManifiesto::with(['manifiesto'])->get();

        $manifiests = Manifiest::get();

        return view('frontend.importacionMarcasManifiestos.index', compact('importacionMarcasManifiestos', 'manifiests'));
    }

    public function create()
    {
        abort_if(Gate::denies('importacion_marcas_manifiesto_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $manifiestos = Manifiest::pluck('mawb', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.importacionMarcasManifiestos.create', compact('manifiestos'));
    }

    public function store(StoreImportacionMarcasManifiestoRequest $request)
    {
        $importacionMarcasManifiesto = ImportacionMarcasManifiesto::create($request->all());

        return redirect()->route('frontend.importacion-marcas-manifiestos.index');
    }

    public function edit(ImportacionMarcasManifiesto $importacionMarcasManifiesto)
    {
        abort_if(Gate::denies('importacion_marcas_manifiesto_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $manifiestos = Manifiest::pluck('mawb', 'id')->prepend(trans('global.pleaseSelect'), '');

        $importacionMarcasManifiesto->load('manifiesto');

        return view('frontend.importacionMarcasManifiestos.edit', compact('importacionMarcasManifiesto', 'manifiestos'));
    }

    public function update(UpdateImportacionMarcasManifiestoRequest $request, ImportacionMarcasManifiesto $importacionMarcasManifiesto)
    {
        $importacionMarcasManifiesto->update($request->all());

        return redirect()->route('frontend.importacion-marcas-manifiestos.index');
    }

    public function show(ImportacionMarcasManifiesto $importacionMarcasManifiesto)
    {
        abort_if(Gate::denies('importacion_marcas_manifiesto_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $importacionMarcasManifiesto->load('manifiesto');

        return view('frontend.importacionMarcasManifiestos.show', compact('importacionMarcasManifiesto'));
    }

    public function destroy(ImportacionMarcasManifiesto $importacionMarcasManifiesto)
    {
        abort_if(Gate::denies('importacion_marcas_manifiesto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $importacionMarcasManifiesto->delete();

        return back();
    }

    public function massDestroy(MassDestroyImportacionMarcasManifiestoRequest $request)
    {
        $importacionMarcasManifiestos = ImportacionMarcasManifiesto::find(request('ids'));

        foreach ($importacionMarcasManifiestos as $importacionMarcasManifiesto) {
            $importacionMarcasManifiesto->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
