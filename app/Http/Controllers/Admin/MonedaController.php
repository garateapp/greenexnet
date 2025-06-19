<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyMonedaRequest;
use App\Http\Requests\StoreMonedaRequest;
use App\Http\Requests\UpdateMonedaRequest;
use App\Models\Moneda;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MonedaController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('moneda_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Moneda::query()->select(sprintf('%s.*', (new Moneda)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'moneda_show';
                $editGate      = 'moneda_edit';
                $deleteGate    = 'moneda_delete';
                $crudRoutePart = 'monedas';

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
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });
            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.monedas.index');
    }

    public function create()
    {
        abort_if(Gate::denies('moneda_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.monedas.create');
    }

    public function store(StoreMonedaRequest $request)
    {
        $moneda = Moneda::create($request->all());

        return redirect()->route('admin.monedas.index');
    }

    public function edit(Moneda $moneda)
    {
        abort_if(Gate::denies('moneda_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.monedas.edit', compact('moneda'));
    }

    public function update(UpdateMonedaRequest $request, Moneda $moneda)
    {
        $moneda->update($request->all());

        return redirect()->route('admin.monedas.index');
    }

    public function show(Moneda $moneda)
    {
        abort_if(Gate::denies('moneda_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.monedas.show', compact('moneda'));
    }

    public function destroy(Moneda $moneda)
    {
        abort_if(Gate::denies('moneda_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $moneda->delete();

        return back();
    }

    public function massDestroy(MassDestroyMonedaRequest $request)
    {
        $monedas = Moneda::find(request('ids'));

        foreach ($monedas as $moneda) {
            $moneda->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
