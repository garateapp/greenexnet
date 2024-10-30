<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTipoRequest;
use App\Http\Requests\StoreTipoRequest;
use App\Http\Requests\UpdateTipoRequest;
use App\Models\Tipo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TipoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('tipo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Tipo::query()->select(sprintf('%s.*', (new Tipo)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'tipo_show';
                $editGate      = 'tipo_edit';
                $deleteGate    = 'tipo_delete';
                $crudRoutePart = 'tipos';

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
            $table->editColumn('sigla', function ($row) {
                return $row->sigla ? $row->sigla : '';
            });
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.tipos.index');
    }

    public function create()
    {
        abort_if(Gate::denies('tipo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tipos.create');
    }

    public function store(StoreTipoRequest $request)
    {
        $tipo = Tipo::create($request->all());

        return redirect()->route('admin.tipos.index');
    }

    public function edit(Tipo $tipo)
    {
        abort_if(Gate::denies('tipo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tipos.edit', compact('tipo'));
    }

    public function update(UpdateTipoRequest $request, Tipo $tipo)
    {
        $tipo->update($request->all());

        return redirect()->route('admin.tipos.index');
    }

    public function show(Tipo $tipo)
    {
        abort_if(Gate::denies('tipo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipo->load('tipoEntidads');

        return view('admin.tipos.show', compact('tipo'));
    }

    public function destroy(Tipo $tipo)
    {
        abort_if(Gate::denies('tipo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipo->delete();

        return back();
    }

    public function massDestroy(MassDestroyTipoRequest $request)
    {
        $tipos = Tipo::find(request('ids'));

        foreach ($tipos as $tipo) {
            $tipo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
