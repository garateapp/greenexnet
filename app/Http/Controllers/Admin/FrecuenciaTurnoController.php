<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyFrecuenciaTurnoRequest;
use App\Http\Requests\StoreFrecuenciaTurnoRequest;
use App\Http\Requests\UpdateFrecuenciaTurnoRequest;
use App\Models\FrecuenciaTurno;
use App\Models\Turno;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FrecuenciaTurnoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('frecuencia_turno_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = FrecuenciaTurno::with(['turno'])->select(sprintf('%s.*', (new FrecuenciaTurno)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'frecuencia_turno_show';
                $editGate      = 'frecuencia_turno_edit';
                $deleteGate    = 'frecuencia_turno_delete';
                $crudRoutePart = 'frecuencia-turnos';

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
            $table->editColumn('dia', function ($row) {
                return $row->dia ? FrecuenciaTurno::DIA_SELECT[$row->dia] : '';
            });
            $table->addColumn('turno_nombre', function ($row) {
                return $row->turno ? $row->turno->nombre : '';
            });

            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'turno']);

            return $table->make(true);
        }

        return view('admin.frecuenciaTurnos.index');
    }

    public function create()
    {
        abort_if(Gate::denies('frecuencia_turno_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $turnos = Turno::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.frecuenciaTurnos.create', compact('turnos'));
    }

    public function store(StoreFrecuenciaTurnoRequest $request)
    {
        $frecuenciaTurno = FrecuenciaTurno::create($request->all());

        return redirect()->route('admin.frecuencia-turnos.index');
    }

    public function edit(FrecuenciaTurno $frecuenciaTurno)
    {
        abort_if(Gate::denies('frecuencia_turno_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $turnos = Turno::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $frecuenciaTurno->load('turno');

        return view('admin.frecuenciaTurnos.edit', compact('frecuenciaTurno', 'turnos'));
    }

    public function update(UpdateFrecuenciaTurnoRequest $request, FrecuenciaTurno $frecuenciaTurno)
    {
        $frecuenciaTurno->update($request->all());

        return redirect()->route('admin.frecuencia-turnos.index');
    }

    public function show(FrecuenciaTurno $frecuenciaTurno)
    {
        abort_if(Gate::denies('frecuencia_turno_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $frecuenciaTurno->load('turno', 'frecuenciaTurnosFrecuencia');

        return view('admin.frecuenciaTurnos.show', compact('frecuenciaTurno'));
    }

    public function destroy(FrecuenciaTurno $frecuenciaTurno)
    {
        abort_if(Gate::denies('frecuencia_turno_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $frecuenciaTurno->delete();

        return back();
    }

    public function massDestroy(MassDestroyFrecuenciaTurnoRequest $request)
    {
        $frecuenciaTurnos = FrecuenciaTurno::find(request('ids'));

        foreach ($frecuenciaTurnos as $frecuenciaTurno) {
            $frecuenciaTurno->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
