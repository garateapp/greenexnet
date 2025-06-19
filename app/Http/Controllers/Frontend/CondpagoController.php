<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCondpagoRequest;
use App\Http\Requests\StoreCondpagoRequest;
use App\Http\Requests\UpdateCondpagoRequest;
use App\Models\Condpago;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CondpagoController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('condpago_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $condpagos = Condpago::all();

        return view('frontend.condpagos.index', compact('condpagos'));
    }

    public function create()
    {
        abort_if(Gate::denies('condpago_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.condpagos.create');
    }

    public function store(StoreCondpagoRequest $request)
    {
        $condpago = Condpago::create($request->all());

        return redirect()->route('frontend.condpagos.index');
    }

    public function edit(Condpago $condpago)
    {
        abort_if(Gate::denies('condpago_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.condpagos.edit', compact('condpago'));
    }

    public function update(UpdateCondpagoRequest $request, Condpago $condpago)
    {
        $condpago->update($request->all());

        return redirect()->route('frontend.condpagos.index');
    }

    public function show(Condpago $condpago)
    {
        abort_if(Gate::denies('condpago_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.condpagos.show', compact('condpago'));
    }

    public function destroy(Condpago $condpago)
    {
        abort_if(Gate::denies('condpago_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $condpago->delete();

        return back();
    }

    public function massDestroy(MassDestroyCondpagoRequest $request)
    {
        $condpagos = Condpago::find(request('ids'));

        foreach ($condpagos as $condpago) {
            $condpago->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
