<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyClientesComexRequest;
use App\Http\Requests\StoreClientesComexRequest;
use App\Http\Requests\UpdateClientesComexRequest;
use App\Models\ClientesComex;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ClientesComexController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('clientes_comex_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ClientesComex::query()->select(sprintf('%s.*', (new ClientesComex)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'clientes_comex_show';
                $editGate      = 'clientes_comex_edit';
                $deleteGate    = 'clientes_comex_delete';
                $crudRoutePart = 'clientes-comexes';

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
            $table->editColumn('codigo_cliente', function ($row) {
                return $row->codigo_cliente ? $row->codigo_cliente : '';
            });
            $table->editColumn('nombre_empresa', function ($row) {
                return $row->nombre_empresa ? $row->nombre_empresa : '';
            });
            $table->editColumn('nombre_fantasia', function ($row) {
                return $row->nombre_fantasia ? $row->nombre_fantasia : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.clientesComexes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('clientes_comex_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.clientesComexes.create');
    }

    public function store(StoreClientesComexRequest $request)
    {
        $clientesComex = ClientesComex::create($request->all());

        return redirect()->route('admin.clientes-comexes.index');
    }

    public function edit(ClientesComex $clientesComex)
    {
        abort_if(Gate::denies('clientes_comex_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.clientesComexes.edit', compact('clientesComex'));
    }

    public function update(UpdateClientesComexRequest $request, ClientesComex $clientesComex)
    {
        $clientesComex->update($request->all());

        return redirect()->route('admin.clientes-comexes.index');
    }

    public function show(ClientesComex $clientesComex)
    {
        abort_if(Gate::denies('clientes_comex_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.clientesComexes.show', compact('clientesComex'));
    }

    public function destroy(ClientesComex $clientesComex)
    {
        abort_if(Gate::denies('clientes_comex_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientesComex->delete();

        return back();
    }

    public function massDestroy(MassDestroyClientesComexRequest $request)
    {
        $clientesComexes = ClientesComex::find(request('ids'));

        foreach ($clientesComexes as $clientesComex) {
            $clientesComex->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
