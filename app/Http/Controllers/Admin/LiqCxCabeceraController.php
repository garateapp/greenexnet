<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLiqCxCabeceraRequest;
use App\Http\Requests\StoreLiqCxCabeceraRequest;
use App\Http\Requests\UpdateLiqCxCabeceraRequest;
use App\Models\ClientesComex;
use App\Models\LiqCxCabecera;
use App\Models\Nafe;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LiqCxCabeceraController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('liq_cx_cabecera_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = LiqCxCabecera::with(['cliente', 'nave'])->select(sprintf('%s.*', (new LiqCxCabecera)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'liq_cx_cabecera_show';
                $editGate      = 'liq_cx_cabecera_edit';
                $deleteGate    = 'liq_cx_cabecera_delete';
                $crudRoutePart = 'liq-cx-cabeceras';

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
            $table->editColumn('instructivo', function ($row) {
                return $row->instructivo ? $row->instructivo : '';
            });
            $table->addColumn('cliente_nombre_fantasia', function ($row) {
                return $row->cliente ? $row->cliente->nombre_fantasia : '';
            });

            $table->editColumn('cliente.codigo_cliente', function ($row) {
                return $row->cliente ? (is_string($row->cliente) ? $row->cliente : $row->cliente->codigo_cliente) : '';
            });
            $table->addColumn('nave_nombre', function ($row) {
                return $row->nave ? $row->nave->nombre : '';
            });

            $table->editColumn('nave.codigo', function ($row) {
                return $row->nave ? (is_string($row->nave) ? $row->nave : $row->nave->codigo) : '';
            });

            $table->editColumn('tasa_intercambio', function ($row) {
                return $row->tasa_intercambio ? $row->tasa_intercambio : '';
            });
            $table->editColumn('total_costo', function ($row) {
                return $row->total_costo ? $row->total_costo : '';
            });
            $table->editColumn('total_bruto', function ($row) {
                return $row->total_bruto ? $row->total_bruto : '';
            });
            $table->editColumn('total_neto', function ($row) {
                return $row->total_neto ? $row->total_neto : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'cliente', 'nave']);

            return $table->make(true);
        }

        $clientes_comexes = ClientesComex::get();
        $naves            = Nafe::get();

        return view('admin.liqCxCabeceras.index', compact('clientes_comexes', 'naves'));
    }

    public function create()
    {
        abort_if(Gate::denies('liq_cx_cabecera_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = ClientesComex::pluck('nombre_fantasia', 'id')->prepend(trans('global.pleaseSelect'), '');

        $naves = Nafe::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.liqCxCabeceras.create', compact('clientes', 'naves'));
    }

    public function store(StoreLiqCxCabeceraRequest $request)
    {
        $liqCxCabecera = LiqCxCabecera::create($request->all());

        return redirect()->route('admin.liq-cx-cabeceras.index');
    }

    public function edit(LiqCxCabecera $liqCxCabecera)
    {
        abort_if(Gate::denies('liq_cx_cabecera_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = ClientesComex::pluck('nombre_fantasia', 'id')->prepend(trans('global.pleaseSelect'), '');

        $naves = Nafe::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $liqCxCabecera->load('cliente', 'nave');

        return view('admin.liqCxCabeceras.edit', compact('clientes', 'liqCxCabecera', 'naves'));
    }

    public function update(UpdateLiqCxCabeceraRequest $request, LiqCxCabecera $liqCxCabecera)
    {
        $liqCxCabecera->update($request->all());

        return redirect()->route('admin.liq-cx-cabeceras.index');
    }

    public function show(LiqCxCabecera $liqCxCabecera)
    {
        abort_if(Gate::denies('liq_cx_cabecera_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $liqCxCabecera->load('cliente', 'nave');

        return view('admin.liqCxCabeceras.show', compact('liqCxCabecera'));
    }

    public function destroy(LiqCxCabecera $liqCxCabecera)
    {
        abort_if(Gate::denies('liq_cx_cabecera_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $liqCxCabecera->delete();

        return back();
    }

    public function massDestroy(MassDestroyLiqCxCabeceraRequest $request)
    {
        $liqCxCabeceras = LiqCxCabecera::find(request('ids'));

        foreach ($liqCxCabeceras as $liqCxCabecera) {
            $liqCxCabecera->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
