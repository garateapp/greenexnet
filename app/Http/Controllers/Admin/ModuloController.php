<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyModuloRequest;
use App\Http\Requests\StoreModuloRequest;
use App\Http\Requests\UpdateModuloRequest;
use App\Models\Modulo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ModuloController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('modulo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Modulo::query()->select(sprintf('%s.*', (new Modulo)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'modulo_show';
                $editGate      = 'modulo_edit';
                $deleteGate    = 'modulo_delete';
                $crudRoutePart = 'modulos';

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

        return view('admin.modulos.index');
    }

    public function create()
    {
        abort_if(Gate::denies('modulo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.modulos.create');
    }

    public function store(StoreModuloRequest $request)
    {
        $modulo = Modulo::create($request->all());

        return redirect()->route('admin.modulos.index');
    }

    public function edit(Modulo $modulo)
    {
        abort_if(Gate::denies('modulo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.modulos.edit', compact('modulo'));
    }

    public function update(UpdateModuloRequest $request, Modulo $modulo)
    {
        $modulo->update($request->all());

        return redirect()->route('admin.modulos.index');
    }

    public function show(Modulo $modulo)
    {
        abort_if(Gate::denies('modulo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.modulos.show', compact('modulo'));
    }

    public function destroy(Modulo $modulo)
    {
        abort_if(Gate::denies('modulo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modulo->delete();

        return back();
    }

    public function massDestroy(MassDestroyModuloRequest $request)
    {
        $modulos = Modulo::find(request('ids'));

        foreach ($modulos as $modulo) {
            $modulo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
