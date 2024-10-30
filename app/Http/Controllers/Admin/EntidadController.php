<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEntidadRequest;
use App\Http\Requests\StoreEntidadRequest;
use App\Http\Requests\UpdateEntidadRequest;
use App\Models\Entidad;
use App\Models\Tipo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EntidadController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('entidad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Entidad::with(['tipo'])->select(sprintf('%s.*', (new Entidad)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'entidad_show';
                $editGate      = 'entidad_edit';
                $deleteGate    = 'entidad_delete';
                $crudRoutePart = 'entidads';

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
            $table->addColumn('tipo_nombre', function ($row) {
                return $row->tipo ? $row->tipo->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'tipo']);

            return $table->make(true);
        }

        $tipos = Tipo::get();

        return view('admin.entidads.index', compact('tipos'));
    }

    public function create()
    {
        abort_if(Gate::denies('entidad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipos = Tipo::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.entidads.create', compact('tipos'));
    }

    public function store(StoreEntidadRequest $request)
    {
        $entidad = Entidad::create($request->all());

        return redirect()->route('admin.entidads.index');
    }

    public function edit(Entidad $entidad)
    {
        abort_if(Gate::denies('entidad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipos = Tipo::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $entidad->load('tipo');

        return view('admin.entidads.edit', compact('entidad', 'tipos'));
    }

    public function update(UpdateEntidadRequest $request, Entidad $entidad)
    {
        $entidad->update($request->all());

        return redirect()->route('admin.entidads.index');
    }

    public function show(Entidad $entidad)
    {
        abort_if(Gate::denies('entidad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entidad->load('tipo', 'entidadAreas', 'entidadPersonals');

        return view('admin.entidads.show', compact('entidad'));
    }

    public function destroy(Entidad $entidad)
    {
        abort_if(Gate::denies('entidad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entidad->delete();

        return back();
    }

    public function massDestroy(MassDestroyEntidadRequest $request)
    {
        $entidads = Entidad::find(request('ids'));

        foreach ($entidads as $entidad) {
            $entidad->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
