<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLiquidacionesCxRequest;
use App\Http\Requests\StoreLiquidacionesCxRequest;
use App\Http\Requests\UpdateLiquidacionesCxRequest;
use App\Models\Etiquetum;
use App\Models\ItemEmbalaje;
use App\Models\LiqCxCabecera;
use App\Models\LiquidacionesCx;
use App\Models\Variedad;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LiquidacionesCxController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('liquidaciones_cx_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = LiquidacionesCx::with(['variedad', 'etiqueta', 'embalaje', 'liqcabecera'])->select(sprintf('%s.*', (new LiquidacionesCx)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'liquidaciones_cx_show';
                $editGate      = 'liquidaciones_cx_edit';
                $deleteGate    = 'liquidaciones_cx_delete';
                $crudRoutePart = 'liquidaciones-cxes';

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
            $table->editColumn('contenedor', function ($row) {
                return $row->contenedor ? $row->contenedor : '';
            });

            $table->addColumn('variedad_nombre', function ($row) {
                return $row->variedad ? $row->variedad->nombre : '';
            });

            $table->editColumn('pallet', function ($row) {
                return $row->pallet ? $row->pallet : '';
            });
            $table->addColumn('etiqueta_nombre', function ($row) {
                return $row->etiqueta ? $row->etiqueta->nombre : '';
            });

            $table->editColumn('calibre', function ($row) {
                return $row->calibre ? $row->calibre : '';
            });
            $table->addColumn('embalaje_nombre', function ($row) {
                return $row->embalaje ? $row->embalaje->nombre : '';
            });

            $table->editColumn('cantidad', function ($row) {
                return $row->cantidad ? $row->cantidad : '';
            });

            $table->editColumn('ventas', function ($row) {
                return $row->ventas ? $row->ventas : '';
            });
            $table->editColumn('precio_unitario', function ($row) {
                return $row->precio_unitario ? $row->precio_unitario : '';
            });
            $table->editColumn('monto_rmb', function ($row) {
                return $row->monto_rmb ? $row->monto_rmb : '';
            });
            $table->editColumn('observaciones', function ($row) {
                return $row->observaciones ? $row->observaciones : '';
            });
            $table->addColumn('liqcabecera_instructivo', function ($row) {
                return $row->liqcabecera ? $row->liqcabecera->instructivo : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'variedad', 'etiqueta', 'embalaje', 'liqcabecera']);

            return $table->make(true);
        }

        $variedads        = Variedad::get();
        $etiqueta         = Etiquetum::get();
        $item_embalajes   = ItemEmbalaje::get();
        $liq_cx_cabeceras = LiqCxCabecera::get();

        return view('admin.liquidacionesCxes.index', compact('variedads', 'etiqueta', 'item_embalajes', 'liq_cx_cabeceras'));
    }

    public function create()
    {
        abort_if(Gate::denies('liquidaciones_cx_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $variedads = Variedad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $etiquetas = Etiquetum::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $embalajes = ItemEmbalaje::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $liqcabeceras = LiqCxCabecera::pluck('instructivo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.liquidacionesCxes.create', compact('embalajes', 'etiquetas', 'liqcabeceras', 'variedads'));
    }

    public function store(StoreLiquidacionesCxRequest $request)
    {
        
        $liquidacionesCx = LiquidacionesCx::create($request->all());

        return redirect()->route('admin.liquidaciones-cxes.index');
    }

    public function edit(LiquidacionesCx $liquidacionesCx)
    {
        abort_if(Gate::denies('liquidaciones_cx_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $variedads = Variedad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $etiquetas = Etiquetum::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $embalajes = ItemEmbalaje::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $liqcabeceras = LiqCxCabecera::pluck('instructivo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $liquidacionesCx->load('variedad', 'etiqueta', 'embalaje', 'liqcabecera');

        return view('admin.liquidacionesCxes.edit', compact('embalajes', 'etiquetas', 'liqcabeceras', 'liquidacionesCx', 'variedads'));
    }

    public function update(UpdateLiquidacionesCxRequest $request, LiquidacionesCx $liquidacionesCx)
    {
        $liquidacionesCx->update($request->all());

        return redirect()->route('admin.liquidaciones-cxes.index');
    }

    public function show(LiquidacionesCx $liquidacionesCx)
    {
        abort_if(Gate::denies('liquidaciones_cx_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $liquidacionesCx->load('variedad', 'etiqueta', 'embalaje', 'liqcabecera');

        return view('admin.liquidacionesCxes.show', compact('liquidacionesCx'));
    }

    public function destroy(LiquidacionesCx $liquidacionesCx)
    {
        abort_if(Gate::denies('liquidaciones_cx_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $liquidacionesCx->delete();

        return back();
    }

    public function massDestroy(MassDestroyLiquidacionesCxRequest $request)
    {
        $liquidacionesCxes = LiquidacionesCx::find(request('ids'));

        foreach ($liquidacionesCxes as $liquidacionesCx) {
            $liquidacionesCx->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
