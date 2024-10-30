<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyMotivoSaepRequest;
use App\Http\Requests\StoreMotivoSaepRequest;
use App\Http\Requests\UpdateMotivoSaepRequest;
use App\Models\MotivoSaep;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MotivoSaepController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('motivo_saep_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $motivoSaeps = MotivoSaep::all();

        return view('frontend.motivoSaeps.index', compact('motivoSaeps'));
    }

    public function create()
    {
        abort_if(Gate::denies('motivo_saep_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.motivoSaeps.create');
    }

    public function store(StoreMotivoSaepRequest $request)
    {
        $motivoSaep = MotivoSaep::create($request->all());

        return redirect()->route('frontend.motivo-saeps.index');
    }

    public function edit(MotivoSaep $motivoSaep)
    {
        abort_if(Gate::denies('motivo_saep_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.motivoSaeps.edit', compact('motivoSaep'));
    }

    public function update(UpdateMotivoSaepRequest $request, MotivoSaep $motivoSaep)
    {
        $motivoSaep->update($request->all());

        return redirect()->route('frontend.motivo-saeps.index');
    }

    public function show(MotivoSaep $motivoSaep)
    {
        abort_if(Gate::denies('motivo_saep_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.motivoSaeps.show', compact('motivoSaep'));
    }

    public function destroy(MotivoSaep $motivoSaep)
    {
        abort_if(Gate::denies('motivo_saep_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $motivoSaep->delete();

        return back();
    }

    public function massDestroy(MassDestroyMotivoSaepRequest $request)
    {
        $motivoSaeps = MotivoSaep::find(request('ids'));

        foreach ($motivoSaeps as $motivoSaep) {
            $motivoSaep->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
