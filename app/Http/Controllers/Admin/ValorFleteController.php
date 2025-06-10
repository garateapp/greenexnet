<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyValorFleteRequest;
use App\Http\Requests\StoreValorFleteRequest;
use App\Http\Requests\UpdateValorFleteRequest;
use App\Models\Productor;
use App\Models\ValorDolar;
use App\Models\ValorFlete;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ValorFleteController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('valor_flete_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ValorFlete::with(['productor', 'valor_dolar'])->select(sprintf('%s.*', (new ValorFlete)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'valor_flete_show';
                $editGate      = 'valor_flete_edit';
                $deleteGate    = 'valor_flete_delete';
                $crudRoutePart = 'valor-fletes';

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
            $table->editColumn('condicion', function ($row) {
                return $row->condicion ? $row->condicion : '';
            });
            $table->addColumn('productor_nombre', function ($row) {
                return $row->productor ? $row->productor->nombre : '';
            });

            $table->editColumn('valor', function ($row) {
                return $row->valor ? $row->valor : '';
            });
            $table->addColumn('valor_dolar_valor', function ($row) {
                return $row->valor_dolar ? $row->valor_dolar->valor : '';
            });

            $table->editColumn('valor_dolar.fecha_cambio', function ($row) {
                return $row->valor_dolar ? (is_string($row->valor_dolar) ? $row->valor_dolar : $row->valor_dolar->fecha_cambio) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'productor', 'valor_dolar']);

            return $table->make(true);
        }

        $productors   = Productor::get();
        $valor_dolars = ValorDolar::get();

        return view('admin.valorFletes.index', compact('productors', 'valor_dolars'));
    }

    public function create()
    {
        abort_if(Gate::denies('valor_flete_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $valor_dolars = ValorDolar::pluck('valor', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.valorFletes.create', compact('productors', 'valor_dolars'));
    }

    public function store(StoreValorFleteRequest $request)
    {
        $valorFlete = ValorFlete::create($request->all());

        return redirect()->route('admin.valor-fletes.index');
    }

    public function edit(ValorFlete $valorFlete)
    {
        abort_if(Gate::denies('valor_flete_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $valor_dolars = ValorDolar::pluck('valor', 'id')->prepend(trans('global.pleaseSelect'), '');

        $valorFlete->load('productor', 'valor_dolar');

        return view('admin.valorFletes.edit', compact('productors', 'valorFlete', 'valor_dolars'));
    }

    public function update(UpdateValorFleteRequest $request, ValorFlete $valorFlete)
    {
        $valorFlete->update($request->all());

        return redirect()->route('admin.valor-fletes.index');
    }

    public function show(ValorFlete $valorFlete)
    {
        abort_if(Gate::denies('valor_flete_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $valorFlete->load('productor', 'valor_dolar');

        return view('admin.valorFletes.show', compact('valorFlete'));
    }

    public function destroy(ValorFlete $valorFlete)
    {
        abort_if(Gate::denies('valor_flete_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $valorFlete->delete();

        return back();
    }

    public function massDestroy(MassDestroyValorFleteRequest $request)
    {
        $valorFletes = ValorFlete::find(request('ids'));

        foreach ($valorFletes as $valorFlete) {
            $valorFlete->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
