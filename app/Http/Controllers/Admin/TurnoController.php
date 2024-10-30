<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTurnoRequest;
use App\Http\Requests\StoreTurnoRequest;
use App\Http\Requests\UpdateTurnoRequest;
use App\Models\Turno;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TurnoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('turno_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Turno::query()->select(sprintf('%s.*', (new Turno)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'turno_show';
                $editGate      = 'turno_edit';
                $deleteGate    = 'turno_delete';
                $crudRoutePart = 'turnos';

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
            $table->editColumn('hora_inicio', function ($row) {
                return $row->hora_inicio ? $row->hora_inicio : '';
            });
            $table->editColumn('hora_fin', function ($row) {
                return $row->hora_fin ? $row->hora_fin : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.turnos.index');
    }

    public function create()
    {
        abort_if(Gate::denies('turno_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.turnos.create');
    }

    public function store(StoreTurnoRequest $request)
    {
        $turno = Turno::create($request->all());

        return redirect()->route('admin.turnos.index');
    }

    public function edit(Turno $turno)
    {
        abort_if(Gate::denies('turno_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.turnos.edit', compact('turno'));
    }

    public function update(UpdateTurnoRequest $request, Turno $turno)
    {
        $turno->update($request->all());

        return redirect()->route('admin.turnos.index');
    }

    public function show(Turno $turno)
    {
        abort_if(Gate::denies('turno_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $turno->load('turnoFrecuenciaTurnos');

        return view('admin.turnos.show', compact('turno'));
    }

    public function destroy(Turno $turno)
    {
        abort_if(Gate::denies('turno_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $turno->delete();

        return back();
    }

    public function massDestroy(MassDestroyTurnoRequest $request)
    {
        $turnos = Turno::find(request('ids'));

        foreach ($turnos as $turno) {
            $turno->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
