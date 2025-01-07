<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLiqCostoRequest;
use App\Http\Requests\StoreLiqCostoRequest;
use App\Http\Requests\UpdateLiqCostoRequest;
use App\Models\LiqCosto;
use App\Models\LiqCxCabecera;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LiqCostosController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('liq_costo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = LiqCosto::with(['liq_cabecera'])->select(sprintf('%s.*', (new LiqCosto)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'liq_costo_show';
                $editGate      = 'liq_costo_edit';
                $deleteGate    = 'liq_costo_delete';
                $crudRoutePart = 'liq-costos';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('liq_cabecera_instructivo', function ($row) {
                return $row->liq_cabecera ? $row->liq_cabecera->instructivo : '';
            });

            $table->editColumn('nombre_costo', function ($row) {
                return $row->nombre_costo ? $row->nombre_costo : '';
            });
            $table->editColumn('valor', function ($row) {
                return $row->valor ? $row->valor : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'liq_cabecera']);

            return $table->make(true);
        }

        $liq_cx_cabeceras = LiqCxCabecera::get();

        return view('admin.liqCostos.index', compact('liq_cx_cabeceras'));
    }

    public function create()
    {
        abort_if(Gate::denies('liq_costo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $liq_cabeceras = LiqCxCabecera::pluck('instructivo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.liqCostos.create', compact('liq_cabeceras'));
    }

    public function store(StoreLiqCostoRequest $request)
    {
        $liqCosto = LiqCosto::create($request->all());

        return redirect()->route('admin.liq-costos.index');
    }

    public function edit(LiqCosto $liqCosto)
    {
        abort_if(Gate::denies('liq_costo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $liq_cabeceras = LiqCxCabecera::pluck('instructivo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $liqCosto->load('liq_cabecera');

        return view('admin.liqCostos.edit', compact('liqCosto', 'liq_cabeceras'));
    }

    public function update(UpdateLiqCostoRequest $request, LiqCosto $liqCosto)
    {
        $liqCosto->update($request->all());

        return redirect()->route('admin.liq-costos.index');
    }

    public function show(LiqCosto $liqCosto)
    {
        abort_if(Gate::denies('liq_costo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $liqCosto->load('liq_cabecera');

        return view('admin.liqCostos.show', compact('liqCosto'));
    }

    public function destroy(LiqCosto $liqCosto)
    {
        abort_if(Gate::denies('liq_costo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $liqCosto->delete();

        return back();
    }

    public function massDestroy(MassDestroyLiqCostoRequest $request)
    {
        $liqCostos = LiqCosto::find(request('ids'));

        foreach ($liqCostos as $liqCosto) {
            $liqCosto->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
