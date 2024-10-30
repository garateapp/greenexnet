<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTurnosFrecuenciumRequest;
use App\Http\Requests\StoreTurnosFrecuenciumRequest;
use App\Http\Requests\UpdateTurnosFrecuenciumRequest;
use App\Models\FrecuenciaTurno;
use App\Models\Locacion;
use App\Models\TurnosFrecuencium;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TurnosFrecuenciaController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('turnos_frecuencium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TurnosFrecuencium::with(['frecuencia', 'locacion'])->select(sprintf('%s.*', (new TurnosFrecuencium)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'turnos_frecuencium_show';
                $editGate      = 'turnos_frecuencium_edit';
                $deleteGate    = 'turnos_frecuencium_delete';
                $crudRoutePart = 'turnos-frecuencia';

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
            $table->addColumn('frecuencia_nombre', function ($row) {
                return $row->frecuencia ? $row->frecuencia->nombre : '';
            });

            $table->addColumn('locacion_nombre', function ($row) {
                return $row->locacion ? $row->locacion->nombre : '';
            });

            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'frecuencia', 'locacion']);

            return $table->make(true);
        }

        return view('admin.turnosFrecuencia.index');
    }

    public function create()
    {
        abort_if(Gate::denies('turnos_frecuencium_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $frecuencias = FrecuenciaTurno::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $locacions = Locacion::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.turnosFrecuencia.create', compact('frecuencias', 'locacions'));
    }

    public function store(StoreTurnosFrecuenciumRequest $request)
    {
        $turnosFrecuencium = TurnosFrecuencium::create($request->all());

        return redirect()->route('admin.turnos-frecuencia.index');
    }

    public function edit(TurnosFrecuencium $turnosFrecuencium)
    {
        abort_if(Gate::denies('turnos_frecuencium_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $frecuencias = FrecuenciaTurno::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $locacions = Locacion::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $turnosFrecuencium->load('frecuencia', 'locacion');

        return view('admin.turnosFrecuencia.edit', compact('frecuencias', 'locacions', 'turnosFrecuencium'));
    }

    public function update(UpdateTurnosFrecuenciumRequest $request, TurnosFrecuencium $turnosFrecuencium)
    {
        $turnosFrecuencium->update($request->all());

        return redirect()->route('admin.turnos-frecuencia.index');
    }

    public function show(TurnosFrecuencium $turnosFrecuencium)
    {
        abort_if(Gate::denies('turnos_frecuencium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $turnosFrecuencium->load('frecuencia', 'locacion');

        return view('admin.turnosFrecuencia.show', compact('turnosFrecuencium'));
    }

    public function destroy(TurnosFrecuencium $turnosFrecuencium)
    {
        abort_if(Gate::denies('turnos_frecuencium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $turnosFrecuencium->delete();

        return back();
    }

    public function massDestroy(MassDestroyTurnosFrecuenciumRequest $request)
    {
        $turnosFrecuencia = TurnosFrecuencium::find(request('ids'));

        foreach ($turnosFrecuencia as $turnosFrecuencium) {
            $turnosFrecuencium->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
