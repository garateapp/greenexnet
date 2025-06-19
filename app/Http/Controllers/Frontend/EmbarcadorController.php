<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEmbarcadorRequest;
use App\Http\Requests\StoreEmbarcadorRequest;
use App\Http\Requests\UpdateEmbarcadorRequest;
use App\Models\Embarcador;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmbarcadorController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('embarcador_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embarcadors = Embarcador::all();

        return view('frontend.embarcadors.index', compact('embarcadors'));
    }

    public function create()
    {
        abort_if(Gate::denies('embarcador_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.embarcadors.create');
    }

    public function store(StoreEmbarcadorRequest $request)
    {
        $embarcador = Embarcador::create($request->all());

        return redirect()->route('frontend.embarcadors.index');
    }

    public function edit(Embarcador $embarcador)
    {
        abort_if(Gate::denies('embarcador_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.embarcadors.edit', compact('embarcador'));
    }

    public function update(UpdateEmbarcadorRequest $request, Embarcador $embarcador)
    {
        $embarcador->update($request->all());

        return redirect()->route('frontend.embarcadors.index');
    }

    public function show(Embarcador $embarcador)
    {
        abort_if(Gate::denies('embarcador_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.embarcadors.show', compact('embarcador'));
    }

    public function destroy(Embarcador $embarcador)
    {
        abort_if(Gate::denies('embarcador_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embarcador->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmbarcadorRequest $request)
    {
        $embarcadors = Embarcador::find(request('ids'));

        foreach ($embarcadors as $embarcador) {
            $embarcador->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
