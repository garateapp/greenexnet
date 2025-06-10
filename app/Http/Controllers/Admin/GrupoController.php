<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyGrupoRequest;
use App\Http\Requests\StoreGrupoRequest;
use App\Http\Requests\UpdateGrupoRequest;
use App\Models\Conjunto;
use App\Models\Grupo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class GrupoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('grupo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Grupo::with(['conjunto'])->select(sprintf('%s.*', (new Grupo)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'grupo_show';
                $editGate      = 'grupo_edit';
                $deleteGate    = 'grupo_delete';
                $crudRoutePart = 'grupos';

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
            $table->editColumn('rut', function ($row) {
                return $row->rut ? $row->rut : '';
            });
            $table->addColumn('conjunto_nombre', function ($row) {
                return $row->conjunto ? $row->conjunto->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'conjunto']);

            return $table->make(true);
        }

        $conjuntos = Conjunto::get();

        return view('admin.grupos.index', compact('conjuntos'));
    }

    public function create()
    {
        abort_if(Gate::denies('grupo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $conjuntos = Conjunto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.grupos.create', compact('conjuntos'));
    }

    public function store(StoreGrupoRequest $request)
    {
        $grupo = Grupo::create($request->all());

        return redirect()->route('admin.grupos.index');
    }

    public function edit(Grupo $grupo)
    {
        abort_if(Gate::denies('grupo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $conjuntos = Conjunto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $grupo->load('conjunto');

        return view('admin.grupos.edit', compact('conjuntos', 'grupo'));
    }

    public function update(UpdateGrupoRequest $request, Grupo $grupo)
    {
        $grupo->update($request->all());

        return redirect()->route('admin.grupos.index');
    }

    public function show(Grupo $grupo)
    {
        abort_if(Gate::denies('grupo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $grupo->load('conjunto', 'grupoProductors');

        return view('admin.grupos.show', compact('grupo'));
    }

    public function destroy(Grupo $grupo)
    {
        abort_if(Gate::denies('grupo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $grupo->delete();

        return back();
    }

    public function massDestroy(MassDestroyGrupoRequest $request)
    {
        $grupos = Grupo::find(request('ids'));

        foreach ($grupos as $grupo) {
            $grupo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
