<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCorreoalsoAirRequest;
use App\Http\Requests\StoreCorreoalsoAirRequest;
use App\Http\Requests\UpdateCorreoalsoAirRequest;
use App\Models\BaseRecibidor;
use App\Models\CorreoalsoAir;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CorreoalsoAirController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('correoalso_air_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CorreoalsoAir::with(['cliente'])->select(sprintf('%s.*', (new CorreoalsoAir)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'correoalso_air_show';
                $editGate      = 'correoalso_air_edit';
                $deleteGate    = 'correoalso_air_delete';
                $crudRoutePart = 'correoalso-airs';

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
            $table->addColumn('cliente_codigo', function ($row) {
                return $row->cliente ? $row->cliente->codigo : '';
            });

            $table->editColumn('puerto_requerido', function ($row) {
                return $row->puerto_requerido ? $row->puerto_requerido : '';
            });
            $table->editColumn('correos', function ($row) {
                return $row->correos ? $row->correos : '';
            });
            $table->editColumn('also_notify', function ($row) {
                return $row->also_notify ? $row->also_notify : '';
            });
            $table->editColumn('transporte', function ($row) {
                return $row->transporte ? CorreoalsoAir::TRANSPORTE_SELECT[$row->transporte] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'cliente']);

            return $table->make(true);
        }

        $base_recibidors = BaseRecibidor::get();

        return view('admin.correoalsoAirs.index', compact('base_recibidors'));
    }

    public function create()
    {
        abort_if(Gate::denies('correoalso_air_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = BaseRecibidor::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.correoalsoAirs.create', compact('clientes'));
    }

    public function store(StoreCorreoalsoAirRequest $request)
    {
        $correoalsoAir = CorreoalsoAir::create($request->all());

        return redirect()->route('admin.correoalso-airs.index');
    }

    public function edit(CorreoalsoAir $correoalsoAir)
    {
        abort_if(Gate::denies('correoalso_air_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = BaseRecibidor::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $correoalsoAir->load('cliente');

        return view('admin.correoalsoAirs.edit', compact('clientes', 'correoalsoAir'));
    }

    public function update(UpdateCorreoalsoAirRequest $request, CorreoalsoAir $correoalsoAir)
    {
        $correoalsoAir->update($request->all());

        return redirect()->route('admin.correoalso-airs.index');
    }

    public function show(CorreoalsoAir $correoalsoAir)
    {
        abort_if(Gate::denies('correoalso_air_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $correoalsoAir->load('cliente');

        return view('admin.correoalsoAirs.show', compact('correoalsoAir'));
    }

    public function destroy(CorreoalsoAir $correoalsoAir)
    {
        abort_if(Gate::denies('correoalso_air_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $correoalsoAir->delete();

        return back();
    }

    public function massDestroy(MassDestroyCorreoalsoAirRequest $request)
    {
        $correoalsoAirs = CorreoalsoAir::find(request('ids'));

        foreach ($correoalsoAirs as $correoalsoAir) {
            $correoalsoAir->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
