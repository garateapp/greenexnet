<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCapturadorRequest;
use App\Http\Requests\StoreCapturadorRequest;
use App\Http\Requests\UpdateCapturadorRequest;
use App\Models\Capturador;
use App\Models\ClientesComex;
use App\Models\Funcione;
use App\Models\Modulo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CapturadorController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('capturador_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Capturador::with(['cliente', 'modulo', 'funcion'])->select(sprintf('%s.*', (new Capturador)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'capturador_show';
                $editGate      = 'capturador_edit';
                $deleteGate    = 'capturador_delete';
                $crudRoutePart = 'capturadors';

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
            $table->addColumn('cliente_nombre_fantasia', function ($row) {
                return $row->cliente ? $row->cliente->nombre_fantasia : '';
            });

            $table->editColumn('cliente.codigo_cliente', function ($row) {
                return $row->cliente ? (is_string($row->cliente) ? $row->cliente : $row->cliente->codigo_cliente) : '';
            });
            $table->addColumn('modulo_nombre', function ($row) {
                return $row->modulo ? $row->modulo->nombre : '';
            });

            $table->addColumn('funcion_nombre', function ($row) {
                return $row->funcion ? $row->funcion->nombre : '';
            });

            $table->editColumn('activo', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->activo ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'cliente', 'modulo', 'funcion', 'activo']);

            return $table->make(true);
        }

        $clientes_comexes = ClientesComex::get();
        $modulos          = Modulo::get();
        $funciones        = Funcione::get();

        return view('admin.capturadors.index', compact('clientes_comexes', 'modulos', 'funciones'));
    }

    public function create()
    {
        abort_if(Gate::denies('capturador_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = ClientesComex::pluck('nombre_fantasia', 'id')->prepend(trans('global.pleaseSelect'), '');

        $modulos = Modulo::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $funcions = Funcione::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.capturadors.create', compact('clientes', 'funcions', 'modulos'));
    }

    public function store(StoreCapturadorRequest $request)
    {
        $capturador = Capturador::create($request->all());

        return redirect()->route('admin.capturadors.index');
    }

    public function edit(Capturador $capturador)
    {
        abort_if(Gate::denies('capturador_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = ClientesComex::pluck('nombre_fantasia', 'id')->prepend(trans('global.pleaseSelect'), '');

        $modulos = Modulo::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $funcions = Funcione::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $capturador->load('cliente', 'modulo', 'funcion');

        return view('admin.capturadors.edit', compact('capturador', 'clientes', 'funcions', 'modulos'));
    }

    public function update(UpdateCapturadorRequest $request, Capturador $capturador)
    {
        $capturador->update($request->all());

        return redirect()->route('admin.capturadors.index');
    }

    public function show(Capturador $capturador)
    {
        abort_if(Gate::denies('capturador_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $capturador->load('cliente', 'modulo', 'funcion');

        return view('admin.capturadors.show', compact('capturador'));
    }

    public function destroy(Capturador $capturador)
    {
        abort_if(Gate::denies('capturador_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $capturador->delete();

        return back();
    }

    public function massDestroy(MassDestroyCapturadorRequest $request)
    {
        $capturadors = Capturador::find(request('ids'));

        foreach ($capturadors as $capturador) {
            $capturador->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
