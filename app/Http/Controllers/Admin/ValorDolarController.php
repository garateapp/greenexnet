<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyValorDolarRequest;
use App\Http\Requests\StoreValorDolarRequest;
use App\Http\Requests\UpdateValorDolarRequest;
use App\Models\ValorDolar;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ValorDolarController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('valor_dolar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ValorDolar::query()->select(sprintf('%s.*', (new ValorDolar)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'valor_dolar_show';
                $editGate      = 'valor_dolar_edit';
                $deleteGate    = 'valor_dolar_delete';
                $crudRoutePart = 'valor-dolars';

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

            $table->editColumn('valor', function ($row) {
                return $row->valor ? $row->valor : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.valorDolars.index');
    }

    public function create()
    {
        abort_if(Gate::denies('valor_dolar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.valorDolars.create');
    }

    public function store(StoreValorDolarRequest $request)
    {
        $valorDolar = ValorDolar::create($request->all());

        return redirect()->route('admin.valor-dolars.index');
    }

    public function edit(ValorDolar $valorDolar)
    {
        abort_if(Gate::denies('valor_dolar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.valorDolars.edit', compact('valorDolar'));
    }

    public function update(UpdateValorDolarRequest $request, ValorDolar $valorDolar)
    {
        $valorDolar->update($request->all());

        return redirect()->route('admin.valor-dolars.index');
    }

    public function show(ValorDolar $valorDolar)
    {
        abort_if(Gate::denies('valor_dolar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $valorDolar->load('valorDolarValorFletes', 'tipoCambioAnticipos');

        return view('admin.valorDolars.show', compact('valorDolar'));
    }

    public function destroy(ValorDolar $valorDolar)
    {
        abort_if(Gate::denies('valor_dolar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $valorDolar->delete();

        return back();
    }

    public function massDestroy(MassDestroyValorDolarRequest $request)
    {
        $valorDolars = ValorDolar::find(request('ids'));

        foreach ($valorDolars as $valorDolar) {
            $valorDolar->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
