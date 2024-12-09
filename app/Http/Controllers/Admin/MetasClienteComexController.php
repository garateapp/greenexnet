<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyMetasClienteComexRequest;
use App\Http\Requests\StoreMetasClienteComexRequest;
use App\Http\Requests\UpdateMetasClienteComexRequest;
use App\Models\ClientesComex;
use App\Models\MetasClienteComex;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MetasClienteComexController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('metas_cliente_comex_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = MetasClienteComex::with(['clientecomex'])->select(sprintf('%s.*', (new MetasClienteComex)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'metas_cliente_comex_show';
                $editGate      = 'metas_cliente_comex_edit';
                $deleteGate    = 'metas_cliente_comex_delete';
                $crudRoutePart = 'metas-cliente-comexes';

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
            $table->addColumn('clientecomex_nombre_fantasia', function ($row) {
                return $row->clientecomex ? $row->clientecomex->nombre_fantasia : '';
            });

            $table->editColumn('clientecomex.nombre_fantasia', function ($row) {
                return $row->clientecomex ? (is_string($row->clientecomex) ? $row->clientecomex : $row->clientecomex->nombre_fantasia) : '';
            });
            $table->editColumn('cantidad', function ($row) {
                return $row->cantidad ? $row->cantidad : '';
            });
            $table->editColumn('semana', function ($row) {
                return $row->semana ? $row->semana : '';
            });
            $table->editColumn('anno', function ($row) {
                return $row->anno ? $row->anno : '';
            });

            $table->editColumn('observaciones', function ($row) {
                return $row->observaciones ? $row->observaciones : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'clientecomex']);

            return $table->make(true);
        }

        $clientes_comexes = ClientesComex::get();

        return view('admin.metasClienteComexes.index', compact('clientes_comexes'));
    }

    public function create()
    {
        abort_if(Gate::denies('metas_cliente_comex_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientecomexes = ClientesComex::pluck('nombre_fantasia', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.metasClienteComexes.create', compact('clientecomexes'));
    }

    public function store(StoreMetasClienteComexRequest $request)
    {
        $metasClienteComex = MetasClienteComex::create($request->all());

        return redirect()->route('admin.metas-cliente-comexes.index');
    }

    public function edit(MetasClienteComex $metasClienteComex)
    {
        abort_if(Gate::denies('metas_cliente_comex_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientecomexes = ClientesComex::pluck('nombre_fantasia', 'id')->prepend(trans('global.pleaseSelect'), '');

        $metasClienteComex->load('clientecomex');

        return view('admin.metasClienteComexes.edit', compact('clientecomexes', 'metasClienteComex'));
    }

    public function update(UpdateMetasClienteComexRequest $request, MetasClienteComex $metasClienteComex)
    {
        $metasClienteComex->update($request->all());

        return redirect()->route('admin.metas-cliente-comexes.index');
    }

    public function show(MetasClienteComex $metasClienteComex)
    {
        abort_if(Gate::denies('metas_cliente_comex_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $metasClienteComex->load('clientecomex');

        return view('admin.metasClienteComexes.show', compact('metasClienteComex'));
    }

    public function destroy(MetasClienteComex $metasClienteComex)
    {
        abort_if(Gate::denies('metas_cliente_comex_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $metasClienteComex->delete();

        return back();
    }

    public function massDestroy(MassDestroyMetasClienteComexRequest $request)
    {
        $metasClienteComexes = MetasClienteComex::find(request('ids'));

        foreach ($metasClienteComexes as $metasClienteComex) {
            $metasClienteComex->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
