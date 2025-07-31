<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyProcesoRequest;
use App\Http\Requests\StoreProcesoRequest;
use App\Http\Requests\UpdateProcesoRequest;
use App\Models\Proceso;
use App\Models\Productor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProcesoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('proceso_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Proceso::with(['productor'])->select(sprintf('%s.*', (new Proceso)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'proceso_show';
                $editGate      = 'proceso_edit';
                $deleteGate    = 'proceso_delete';
                $crudRoutePart = 'procesos';

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
            $table->addColumn('productor_nombre', function ($row) {
                return $row->productor ? $row->productor->nombre : '';
            });

            $table->editColumn('productor.rut', function ($row) {
                return $row->productor ? (is_string($row->productor) ? $row->productor : $row->productor->rut) : '';
            });

            $table->editColumn('variedad', function ($row) {
                return $row->variedad ? $row->variedad : '';
            });
            $table->editColumn('categoria', function ($row) {
                return $row->categoria ? $row->categoria : '';
            });
            $table->editColumn('etiqueta', function ($row) {
                return $row->etiqueta ? $row->etiqueta : '';
            });
            $table->editColumn('calibre', function ($row) {
                return $row->calibre ? $row->calibre : '';
            });
            $table->editColumn('color', function ($row) {
                return $row->color ? $row->color : '';
            });
            $table->editColumn('total_kilos', function ($row) {
                return $row->total_kilos ? $row->total_kilos : '';
            });
            $table->editColumn('etd_week', function ($row) {
                return $row->etd_week ? $row->etd_week : '';
            });
            $table->editColumn('eta_week', function ($row) {
                return $row->eta_week ? $row->eta_week : '';
            });
            $table->editColumn('resultado_kilo', function ($row) {
                return $row->resultado_kilo ? $row->resultado_kilo : '';
            });
            $table->editColumn('resultado_total', function ($row) {
                return $row->resultado_total ? $row->resultado_total : '';
            });
            $table->editColumn('precio_comercial', function ($row) {
                return $row->precio_comercial ? $row->precio_comercial : '';
            });
            $table->editColumn('total_comercial', function ($row) {
                return $row->total_comercial ? $row->total_comercial : '';
            });
            $table->editColumn('costo_comercial', function ($row) {
                return $row->costo_comercial ? $row->costo_comercial : '';
            });
            $table->editColumn('norma', function ($row) {
                return $row->norma ? $row->norma : '';
            });
            $table->rawColumns(['actions', 'placeholder', 'productor']);

            return $table->make(true);
        }

        $productors = Productor::get();

        return view('admin.procesos.index', compact('productors'));
    }

    public function create()
    {
        abort_if(Gate::denies('proceso_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.procesos.create', compact('productors'));
    }

    public function store(StoreProcesoRequest $request)
    {
        $proceso = Proceso::create($request->all());

        return redirect()->route('admin.procesos.index');
    }

    public function edit(Proceso $proceso)
    {
        abort_if(Gate::denies('proceso_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $proceso->load('productor');

        return view('admin.procesos.edit', compact('proceso', 'productors'));
    }

    public function update(UpdateProcesoRequest $request, Proceso $proceso)
    {
        $proceso->update($request->all());

        return redirect()->route('admin.procesos.index');
    }

    public function show(Proceso $proceso)
    {
        abort_if(Gate::denies('proceso_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $proceso->load('productor');

        return view('admin.procesos.show', compact('proceso'));
    }

    public function destroy(Proceso $proceso)
    {
        abort_if(Gate::denies('proceso_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $proceso->delete();

        return back();
    }

    public function massDestroy(MassDestroyProcesoRequest $request)
    {
        $procesos = Proceso::find(request('ids'));

        foreach ($procesos as $proceso) {
            $proceso->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function deleteAll()
    {
        abort_if(Gate::denies('proceso_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Proceso::truncate();

        return back();
    }
}
