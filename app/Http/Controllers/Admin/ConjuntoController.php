<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyConjuntoRequest;
use App\Http\Requests\StoreConjuntoRequest;
use App\Http\Requests\UpdateConjuntoRequest;
use App\Models\Conjunto;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ConjuntoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('conjunto_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Conjunto::query()->select(sprintf('%s.*', (new Conjunto)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'conjunto_show';
                $editGate      = 'conjunto_edit';
                $deleteGate    = 'conjunto_delete';
                $crudRoutePart = 'conjuntos';

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

        return view('admin.conjuntos.index');
    }

    public function create()
    {
        abort_if(Gate::denies('conjunto_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.conjuntos.create');
    }

    public function store(StoreConjuntoRequest $request)
    {
        $conjunto = Conjunto::create($request->all());

        return redirect()->route('admin.conjuntos.index');
    }

    public function edit(Conjunto $conjunto)
    {
        abort_if(Gate::denies('conjunto_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.conjuntos.edit', compact('conjunto'));
    }

    public function update(UpdateConjuntoRequest $request, Conjunto $conjunto)
    {
        $conjunto->update($request->all());

        return redirect()->route('admin.conjuntos.index');
    }

    public function show(Conjunto $conjunto)
    {
        abort_if(Gate::denies('conjunto_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $conjunto->load('conjuntoGrupos');

        return view('admin.conjuntos.show', compact('conjunto'));
    }

    public function destroy(Conjunto $conjunto)
    {
        abort_if(Gate::denies('conjunto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $conjunto->delete();

        return back();
    }

    public function massDestroy(MassDestroyConjuntoRequest $request)
    {
        $conjuntos = Conjunto::find(request('ids'));

        foreach ($conjuntos as $conjunto) {
            $conjunto->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
