<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyFuncioneRequest;
use App\Http\Requests\StoreFuncioneRequest;
use App\Http\Requests\UpdateFuncioneRequest;
use App\Models\Funcione;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FuncionesController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('funcione_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Funcione::query()->select(sprintf('%s.*', (new Funcione)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'funcione_show';
                $editGate      = 'funcione_edit';
                $deleteGate    = 'funcione_delete';
                $crudRoutePart = 'funciones';

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

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.funciones.index');
    }

    public function create()
    {
        abort_if(Gate::denies('funcione_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.funciones.create');
    }

    public function store(StoreFuncioneRequest $request)
    {
        $funcione = Funcione::create($request->all());

        return redirect()->route('admin.funciones.index');
    }

    public function edit(Funcione $funcione)
    {
        abort_if(Gate::denies('funcione_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.funciones.edit', compact('funcione'));
    }

    public function update(UpdateFuncioneRequest $request, Funcione $funcione)
    {
        $funcione->update($request->all());

        return redirect()->route('admin.funciones.index');
    }

    public function show(Funcione $funcione)
    {
        abort_if(Gate::denies('funcione_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.funciones.show', compact('funcione'));
    }

    public function destroy(Funcione $funcione)
    {
        abort_if(Gate::denies('funcione_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $funcione->delete();

        return back();
    }

    public function massDestroy(MassDestroyFuncioneRequest $request)
    {
        $funciones = Funcione::find(request('ids'));

        foreach ($funciones as $funcione) {
            $funcione->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
