<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPesoEmbalajeRequest;
use App\Http\Requests\StorePesoEmbalajeRequest;
use App\Http\Requests\UpdatePesoEmbalajeRequest;
use App\Models\Especy;
use App\Models\Etiquetum;
use App\Models\PesoEmbalaje;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PesoEmbalajeController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('peso_embalaje_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pesoEmbalajes = PesoEmbalaje::with(['especie', 'etiqueta'])->get();

        $especies = Especy::get();

        $etiqueta = Etiquetum::get();

        return view('frontend.pesoEmbalajes.index', compact('especies', 'etiqueta', 'pesoEmbalajes'));
    }

    public function create()
    {
        abort_if(Gate::denies('peso_embalaje_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $especies = Especy::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $etiquetas = Etiquetum::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.pesoEmbalajes.create', compact('especies', 'etiquetas'));
    }

    public function store(StorePesoEmbalajeRequest $request)
    {
        $pesoEmbalaje = PesoEmbalaje::create($request->all());

        return redirect()->route('frontend.peso-embalajes.index');
    }

    public function edit(PesoEmbalaje $pesoEmbalaje)
    {
        abort_if(Gate::denies('peso_embalaje_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $especies = Especy::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $etiquetas = Etiquetum::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pesoEmbalaje->load('especie', 'etiqueta');

        return view('frontend.pesoEmbalajes.edit', compact('especies', 'etiquetas', 'pesoEmbalaje'));
    }

    public function update(UpdatePesoEmbalajeRequest $request, PesoEmbalaje $pesoEmbalaje)
    {
        $pesoEmbalaje->update($request->all());

        return redirect()->route('frontend.peso-embalajes.index');
    }

    public function show(PesoEmbalaje $pesoEmbalaje)
    {
        abort_if(Gate::denies('peso_embalaje_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pesoEmbalaje->load('especie', 'etiqueta');

        return view('frontend.pesoEmbalajes.show', compact('pesoEmbalaje'));
    }

    public function destroy(PesoEmbalaje $pesoEmbalaje)
    {
        abort_if(Gate::denies('peso_embalaje_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pesoEmbalaje->delete();

        return back();
    }

    public function massDestroy(MassDestroyPesoEmbalajeRequest $request)
    {
        $pesoEmbalajes = PesoEmbalaje::find(request('ids'));

        foreach ($pesoEmbalajes as $pesoEmbalaje) {
            $pesoEmbalaje->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
