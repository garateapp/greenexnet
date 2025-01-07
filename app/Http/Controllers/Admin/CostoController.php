<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCostoRequest;
use App\Http\Requests\StoreCostoRequest;
use App\Http\Requests\UpdateCostoRequest;
use App\Models\Costo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CostoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('costo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Costo::query()->select(sprintf('%s.*', (new Costo)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'costo_show';
                $editGate      = 'costo_edit';
                $deleteGate    = 'costo_delete';
                $crudRoutePart = 'costos';

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
            $table->editColumn('valor_x_defecto', function ($row) {
                return $row->valor_x_defecto ? $row->valor_x_defecto : '';
            });
            $table->editColumn('categoria', function ($row) {
                return $row->categoria ? Costo::CATEGORIA_SELECT[$row->categoria] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.costos.index');
    }

    public function create()
    {
        abort_if(Gate::denies('costo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.costos.create');
    }

    public function store(StoreCostoRequest $request)
    {
        $costo = Costo::create($request->all());

        return redirect()->route('admin.costos.index');
    }

    public function edit(Costo $costo)
    {
        abort_if(Gate::denies('costo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.costos.edit', compact('costo'));
    }

    public function update(UpdateCostoRequest $request, Costo $costo)
    {
        $costo->update($request->all());

        return redirect()->route('admin.costos.index');
    }

    public function show(Costo $costo)
    {
        abort_if(Gate::denies('costo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.costos.show', compact('costo'));
    }

    public function destroy(Costo $costo)
    {
        abort_if(Gate::denies('costo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $costo->delete();

        return back();
    }

    public function massDestroy(MassDestroyCostoRequest $request)
    {
        $costos = Costo::find(request('ids'));

        foreach ($costos as $costo) {
            $costo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
