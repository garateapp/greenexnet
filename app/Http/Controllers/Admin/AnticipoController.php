<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAnticipoRequest;
use App\Http\Requests\StoreAnticipoRequest;
use App\Http\Requests\UpdateAnticipoRequest;
use App\Models\Anticipo;
use App\Models\Especy;
use App\Models\Productor;
use App\Models\ValorDolar;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AnticipoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('anticipo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Anticipo::with(['productor', 'especie'])->select(sprintf('%s.*', (new Anticipo)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'anticipo_show';
                $editGate      = 'anticipo_edit';
                $deleteGate    = 'anticipo_delete';
                $crudRoutePart = 'anticipos';

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
            $table->editColumn('valor', function ($row) {
                return $row->valor ? $row->valor : '';
            });
            $table->editColumn('num_docto', function ($row) {
                return $row->num_docto ? $row->num_docto : '';
            });

            $table->addColumn('tipo_cambio_valor', function ($row) {
                return $row->tipo_cambio_id ? $row->tipo_cambio_id : '';
            });

            $table->editColumn('tipo_cambio.fecha_cambio', function ($row) {
                return $row->tipo_cambio ? (is_string($row->tipo_cambio) ? $row->tipo_cambio : $row->tipo_cambio->fecha_cambio) : '';
            });
            $table->addColumn('especie_nombre', function ($row) {
                return $row->especie ? $row->especie->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'productor', 'tipo_cambio', 'especie']);

            return $table->make(true);
        }

        $productors   = Productor::get();

        $especies     = Especy::get();

        return view('admin.anticipos.index', compact('productors',  'especies'));
    }

    public function create()
    {
        abort_if(Gate::denies('anticipo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        //$tipo_cambios = ValorDolar::pluck('valor', 'id')->prepend(trans('global.pleaseSelect'), '');

        $especies = Especy::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.anticipos.create', compact('especies', 'productors'));
    }

    public function store(StoreAnticipoRequest $request)
    {
        $anticipo = Anticipo::create($request->all());

        return redirect()->route('admin.anticipos.index');
    }

    public function edit(Anticipo $anticipo)
    {
        abort_if(Gate::denies('anticipo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_cambios = ValorDolar::pluck('valor', 'id')->prepend(trans('global.pleaseSelect'), '');

        $especies = Especy::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $anticipo->load('productor',  'especie');

        return view('admin.anticipos.edit', compact('anticipo', 'especies', 'productors'));
    }

    public function update(UpdateAnticipoRequest $request, Anticipo $anticipo)
    {
        $anticipo->update($request->all());

        return redirect()->route('admin.anticipos.index');
    }

    public function show(Anticipo $anticipo)
    {
        abort_if(Gate::denies('anticipo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $anticipo->load('productor', 'tipo_cambio', 'especie');

        return view('admin.anticipos.show', compact('anticipo'));
    }

    public function destroy(Anticipo $anticipo)
    {
        abort_if(Gate::denies('anticipo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $anticipo->delete();

        return back();
    }

    public function massDestroy(MassDestroyAnticipoRequest $request)
    {
        $anticipos = Anticipo::find(request('ids'));

        foreach ($anticipos as $anticipo) {
            $anticipo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
