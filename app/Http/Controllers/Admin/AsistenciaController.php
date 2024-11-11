<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAsistenciumRequest;
use App\Http\Requests\StoreAsistenciumRequest;
use App\Http\Requests\UpdateAsistenciumRequest;
use App\Models\Asistencium;
use App\Models\FrecuenciaTurno;
use App\Models\Locacion;
use App\Models\Personal;
use Gate;
use Illuminate\Auth\Access\Gate as AccessGate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Faker\Provider\ar_EG\Person;

class AsistenciaController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('asistencium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Asistencium::with(['locacion', 'turno', 'personal'])->select(sprintf('%s.*', (new Asistencium)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'asistencium_show';
                $editGate      = 'asistencium_edit';
                $deleteGate    = 'asistencium_delete';
                $crudRoutePart = 'asistencia';

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
            $table->addColumn('locacion_nombre', function ($row) {
                return $row->locacion ? $row->locacion->nombre : '';
            });

            $table->addColumn('turno_nombre', function ($row) {
                return $row->turno ? $row->turno->nombre : '';
            });

            $table->addColumn('personal_nombre', function ($row) {
                return $row->personal ? $row->personal->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'locacion', 'turno', 'personal']);

            return $table->make(true);
        }

        $locacions         = Locacion::get();
        $frecuencia_turnos = FrecuenciaTurno::get();
        $personals         = Personal::get();

        return view('admin.asistencia.index', compact('locacions', 'frecuencia_turnos', 'personals'));
    }

    public function create()
    {
        abort_if(Gate::denies('asistencium_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $locacions = Locacion::where("id", "!=", 1)->pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $turnos = FrecuenciaTurno::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $personals = Personal::select(
            DB::raw("CONCAT(rut, ' - ', nombre) AS full_name"),
            'id',
            'rut'
        )
            ->pluck('full_name', 'rut')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.asistencia.create', compact('locacions', 'personals', 'turnos'));
    }

    public function store(StoreAsistenciumRequest $request)
    {

        $personal_id = Personal::where('rut', $request->personal_id)->first()->id;
        $asistencium = new Asistencium();
        $asistencium->personal_id = $personal_id;
        $asistencium->locacion_id = $request->locacion_id;
        $asistencium->turno_id = $request->turno_id;
        $asistencium->fecha_hora = $request->fecha_hora;
        $asistencium->save();

        return redirect()->route('admin.asistencia.index');
    }

    public function edit(Asistencium $asistencium)
    {
        abort_if(Gate::denies('asistencium_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $locacions = Locacion::where("id", "!=", 1)->pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $turnos = FrecuenciaTurno::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $personals = Personal::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $asistencium->load('locacion', 'turno', 'personal');

        return view('admin.asistencia.edit', compact('asistencium', 'locacions', 'personals', 'turnos'));
    }

    public function update(UpdateAsistenciumRequest $request, Asistencium $asistencium)
    {
        $asistencium->update($request->all());

        return redirect()->route('admin.asistencia.index');
    }

    public function show(Asistencium $asistencium)
    {
        abort_if(Gate::denies('asistencium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $asistencium->load('locacion', 'turno', 'personal');

        return view('admin.asistencia.show', compact('asistencium'));
    }

    public function destroy(Asistencium $asistencium)
    {
        abort_if(Gate::denies('asistencium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $asistencium->delete();

        return back();
    }

    public function massDestroy(MassDestroyAsistenciumRequest $request)
    {
        $asistencia = Asistencium::find(request('ids'));

        foreach ($asistencia as $asistencium) {
            $asistencium->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
