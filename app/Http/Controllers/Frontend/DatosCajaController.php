<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyDatosCajaRequest;
use App\Http\Requests\StoreDatosCajaRequest;
use App\Http\Requests\UpdateDatosCajaRequest;
use App\Models\DatosCaja;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DatosCajaController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('datos_caja_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $datosCajas = DatosCaja::all();

        return view('frontend.datosCajas.index', compact('datosCajas'));
    }

    public function create()
    {
        abort_if(Gate::denies('datos_caja_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.datosCajas.create');
    }

    public function store(StoreDatosCajaRequest $request)
    {
        $datosCaja = DatosCaja::create($request->all());

        return redirect()->route('frontend.datos-cajas.index');
    }

    public function edit(DatosCaja $datosCaja)
    {
        abort_if(Gate::denies('datos_caja_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.datosCajas.edit', compact('datosCaja'));
    }

    public function update(UpdateDatosCajaRequest $request, DatosCaja $datosCaja)
    {
        $datosCaja->update($request->all());

        return redirect()->route('frontend.datos-cajas.index');
    }

    public function show(DatosCaja $datosCaja)
    {
        abort_if(Gate::denies('datos_caja_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.datosCajas.show', compact('datosCaja'));
    }

    public function destroy(DatosCaja $datosCaja)
    {
        abort_if(Gate::denies('datos_caja_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $datosCaja->delete();

        return back();
    }

    public function massDestroy(MassDestroyDatosCajaRequest $request)
    {
        $datosCajas = DatosCaja::find(request('ids'));

        foreach ($datosCajas as $datosCaja) {
            $datosCaja->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
