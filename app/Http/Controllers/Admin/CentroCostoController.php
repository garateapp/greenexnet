<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCentroCostoRequest;
use App\Http\Requests\StoreCentroCostoRequest;
use App\Http\Requests\UpdateCentroCostoRequest;
use App\Models\CentroCosto;
use App\Models\Entidad;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CentroCostoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('centro_costo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CentroCosto::with(['entidad'])->select(sprintf('%s.*', (new CentroCosto)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'centro_costo_show';
                $editGate      = 'centro_costo_edit';
                $deleteGate    = 'centro_costo_delete';
                $crudRoutePart = 'centro-costos';

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
            $table->addColumn('entidad_nombre', function ($row) {
                return $row->entidad ? $row->entidad->nombre : '';
            });
            $table->editColumn('id_centrocosto', function ($row) {
                return $row->id_centrocosto ? $row->id_centrocosto : '';
            });
            $table->editColumn('c_centrocosto', function ($row) {
                return $row->c_centrocosto ? $row->c_centrocosto : '';
            });
            $table->editColumn('n_centrocosto', function ($row) {
                return $row->n_centrocosto ? $row->n_centrocosto : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'entidad']);

            return $table->make(true);
        }

        return view('admin.centroCostos.index');
    }

    public function create()
    {
        abort_if(Gate::denies('centro_costo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entidads = Entidad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.centroCostos.create', compact('entidads'));
    }

    public function store(StoreCentroCostoRequest $request)
    {
        CentroCosto::create($request->all());

        return redirect()->route('admin.centro-costos.index');
    }

    public function edit(CentroCosto $centroCosto)
    {
        abort_if(Gate::denies('centro_costo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entidads = Entidad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $centroCosto->load('entidad');

        return view('admin.centroCostos.edit', compact('centroCosto', 'entidads'));
    }

    public function update(UpdateCentroCostoRequest $request, CentroCosto $centroCosto)
    {
        $centroCosto->update($request->all());

        return redirect()->route('admin.centro-costos.index');
    }

    public function show(CentroCosto $centroCosto)
    {
        abort_if(Gate::denies('centro_costo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $centroCosto->load('entidad');

        return view('admin.centroCostos.show', compact('centroCosto'));
    }

    public function destroy(CentroCosto $centroCosto)
    {
        abort_if(Gate::denies('centro_costo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $centroCosto->delete();

        return back();
    }

    public function massDestroy(MassDestroyCentroCostoRequest $request)
    {
        $centroCostos = CentroCosto::find(request('ids'));

        foreach ($centroCostos as $centroCosto) {
            $centroCosto->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
