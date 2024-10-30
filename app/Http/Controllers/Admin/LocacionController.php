<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLocacionRequest;
use App\Http\Requests\StoreLocacionRequest;
use App\Http\Requests\UpdateLocacionRequest;
use App\Models\Area;
use App\Models\Estado;
use App\Models\Locacion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LocacionController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('locacion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Locacion::with(['area', 'estado', 'locacion_padre'])->select(sprintf('%s.*', (new Locacion)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'locacion_show';
                $editGate      = 'locacion_edit';
                $deleteGate    = 'locacion_delete';
                $crudRoutePart = 'locacions';

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
            $table->addColumn('area_nombre', function ($row) {
                return $row->area ? $row->area->nombre : '';
            });

            $table->editColumn('cantidad_personal', function ($row) {
                return $row->cantidad_personal ? $row->cantidad_personal : '';
            });
            $table->addColumn('estado_nombre', function ($row) {
                return $row->estado ? $row->estado->nombre : '';
            });

            $table->addColumn('locacion_padre_nombre', function ($row) {
                return $row->locacion_padre ? $row->locacion_padre->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'area', 'estado', 'locacion_padre']);

            return $table->make(true);
        }

        return view('admin.locacions.index');
    }

    public function create()
    {
        abort_if(Gate::denies('locacion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $areas = Area::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = Estado::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $locacion_padres = Locacion::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.locacions.create', compact('areas', 'estados', 'locacion_padres'));
    }

    public function store(StoreLocacionRequest $request)
    {
        $locacion = Locacion::create($request->all());

        return redirect()->route('admin.locacions.index');
    }

    public function edit(Locacion $locacion)
    {
        abort_if(Gate::denies('locacion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $areas = Area::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = Estado::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $locacion_padres = Locacion::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $locacion->load('area', 'estado', 'locacion_padre');

        return view('admin.locacions.edit', compact('areas', 'estados', 'locacion', 'locacion_padres'));
    }

    public function update(UpdateLocacionRequest $request, Locacion $locacion)
    {
        $locacion->update($request->all());

        return redirect()->route('admin.locacions.index');
    }

    public function show(Locacion $locacion)
    {
        abort_if(Gate::denies('locacion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $locacion->load('area', 'estado', 'locacion_padre', 'locacionTurnosFrecuencia');

        return view('admin.locacions.show', compact('locacion'));
    }

    public function destroy(Locacion $locacion)
    {
        abort_if(Gate::denies('locacion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $locacion->delete();

        return back();
    }

    public function massDestroy(MassDestroyLocacionRequest $request)
    {
        $locacions = Locacion::find(request('ids'));

        foreach ($locacions as $locacion) {
            $locacion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
