<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyBaseRecibidorRequest;
use App\Http\Requests\StoreBaseRecibidorRequest;
use App\Http\Requests\UpdateBaseRecibidorRequest;
use App\Models\BaseRecibidor;
use App\Models\ClientesComex;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BaseRecibidorController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('base_recibidor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = BaseRecibidor::with(['cliente'])->select(sprintf('%s.*', (new BaseRecibidor)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'base_recibidor_show';
                $editGate      = 'base_recibidor_edit';
                $deleteGate    = 'base_recibidor_delete';
                $crudRoutePart = 'base-recibidors';

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
            $table->addColumn('cliente_nombre_fantasia', function ($row) {
                return $row->cliente ? $row->cliente->nombre_fantasia : '';
            });

            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });
            $table->editColumn('rut_sistema', function ($row) {
                return $row->rut_sistema ? $row->rut_sistema : '';
            });
            $table->editColumn('estado', function ($row) {
                return $row->estado ? BaseRecibidor::ESTADO_RADIO[$row->estado] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'cliente']);

            return $table->make(true);
        }

        $clientes_comexes = ClientesComex::get();

        return view('admin.baseRecibidors.index', compact('clientes_comexes'));
    }

    public function create()
    {
        abort_if(Gate::denies('base_recibidor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = ClientesComex::pluck('nombre_fantasia', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.baseRecibidors.create', compact('clientes'));
    }

    public function store(StoreBaseRecibidorRequest $request)
    {
        $baseRecibidor = BaseRecibidor::create($request->all());

        return redirect()->route('admin.base-recibidors.index');
    }

    public function edit(BaseRecibidor $baseRecibidor)
    {
        abort_if(Gate::denies('base_recibidor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = ClientesComex::pluck('nombre_fantasia', 'id')->prepend(trans('global.pleaseSelect'), '');

        $baseRecibidor->load('cliente');

        return view('admin.baseRecibidors.edit', compact('baseRecibidor', 'clientes'));
    }

    public function update(UpdateBaseRecibidorRequest $request, BaseRecibidor $baseRecibidor)
    {
        $baseRecibidor->update($request->all());

        return redirect()->route('admin.base-recibidors.index');
    }

    public function show(BaseRecibidor $baseRecibidor)
    {
        abort_if(Gate::denies('base_recibidor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $baseRecibidor->load('cliente', 'clienteCorreoalsoAirs');

        return view('admin.baseRecibidors.show', compact('baseRecibidor'));
    }

    public function destroy(BaseRecibidor $baseRecibidor)
    {
        abort_if(Gate::denies('base_recibidor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $baseRecibidor->delete();

        return back();
    }

    public function massDestroy(MassDestroyBaseRecibidorRequest $request)
    {
        $baseRecibidors = BaseRecibidor::find(request('ids'));

        foreach ($baseRecibidors as $baseRecibidor) {
            $baseRecibidor->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
