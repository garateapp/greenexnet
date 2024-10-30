<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyDatosCajaRequest;
use App\Http\Requests\StoreDatosCajaRequest;
use App\Http\Requests\UpdateDatosCajaRequest;
use App\Models\DatosCaja;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;

class DatosCajaController extends Controller
{
    use CsvImportTrait;
    public function index(Request $request)
    {
        abort_if(Gate::denies('datos_caja_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('admin.datosCajas.index');
    }
    /*************  ✨ Codeium Command ⭐  *************/
    /**
     * Busca los datos de caja para mostrar en la datatable.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    /******  52885eb4-2672-4767-a2dc-972f00fa5c29  *******/
    public function buscaDatosCaja(Request $request)
    {
        abort_if(Gate::denies('datos_caja_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //dd($request);
        $datos = DB::connection("sqlsrvUnitec")->table('dbo.DatosCajas')
            ->select(
                'Proceso',
                'FechaProduccion',
                'Turno',
                'CodLinea',
                'CAT',
                'VariedadReal',
                'VariedadTimbrada',
                'Salida',
                'Marca',
                'ProductorReal',
                'Especie',
                'CodCaja',
                'CodConfeccion',
                'CalibreTimbrado',
                'PesoTimbrado',
                'Lote'
            )
            ->where('codCaja', '=', $request->codCaja)
            ->first(); //DatosCaja::whereBetween('FechaProduccion', ['2023-11-11', '2023-11-12'])->get(); //dd($request->fecha_inicio)


        return response()->json($datos, 200);
    }

    public function create()
    {
        abort_if(Gate::denies('datos_caja_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.datosCajas.create');
    }

    public function store(StoreDatosCajaRequest $request)
    {
        $datosCaja = DatosCaja::create($request->all());

        return redirect()->route('admin.datos-cajas.index');
    }

    public function edit(DatosCaja $datosCaja)
    {
        abort_if(Gate::denies('datos_caja_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.datosCajas.edit', compact('datosCaja'));
    }

    public function update(UpdateDatosCajaRequest $request, DatosCaja $datosCaja)
    {
        $datosCaja->update($request->all());

        return redirect()->route('admin.datos-cajas.index');
    }

    public function show(DatosCaja $datosCaja)
    {
        abort_if(Gate::denies('datos_caja_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.datosCajas.show', compact('datosCaja'));
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
