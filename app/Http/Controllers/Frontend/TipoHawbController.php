<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTipoHawbRequest;
use App\Http\Requests\StoreTipoHawbRequest;
use App\Http\Requests\UpdateTipoHawbRequest;
use App\Models\TipoHawb;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoHawbController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('tipo_hawb_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoHawbs = TipoHawb::all();

        return view('frontend.tipoHawbs.index', compact('tipoHawbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('tipo_hawb_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoHawbs.create');
    }

    public function store(StoreTipoHawbRequest $request)
    {
        $tipoHawb = TipoHawb::create($request->all());

        return redirect()->route('frontend.tipo-hawbs.index');
    }

    public function edit(TipoHawb $tipoHawb)
    {
        abort_if(Gate::denies('tipo_hawb_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoHawbs.edit', compact('tipoHawb'));
    }

    public function update(UpdateTipoHawbRequest $request, TipoHawb $tipoHawb)
    {
        $tipoHawb->update($request->all());

        return redirect()->route('frontend.tipo-hawbs.index');
    }

    public function show(TipoHawb $tipoHawb)
    {
        abort_if(Gate::denies('tipo_hawb_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoHawbs.show', compact('tipoHawb'));
    }

    public function destroy(TipoHawb $tipoHawb)
    {
        abort_if(Gate::denies('tipo_hawb_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoHawb->delete();

        return back();
    }

    public function massDestroy(MassDestroyTipoHawbRequest $request)
    {
        $tipoHawbs = TipoHawb::find(request('ids'));

        foreach ($tipoHawbs as $tipoHawb) {
            $tipoHawb->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
