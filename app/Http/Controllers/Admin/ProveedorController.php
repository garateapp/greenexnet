<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyProveedorRequest;
use App\Http\Requests\StoreProveedorRequest;
use App\Http\Requests\UpdateProveedorRequest;
use App\Models\Proveedor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProveedorController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('proveedor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Proveedor::query()->select(sprintf('%s.*', (new Proveedor)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'proveedor_show';
                $editGate      = 'proveedor_edit';
                $deleteGate    = 'proveedor_delete';
                $crudRoutePart = 'proveedors';

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
            $table->editColumn('rut', function ($row) {
                return $row->rut ? $row->rut : '';
            });
            $table->editColumn('cobro', function ($row) {
                return $row->cobro ? $row->cobro : '';
            });
            $table->editColumn('nombre_simple', function ($row) {
                return $row->nombre_simple ? $row->nombre_simple : '';
            });
            $table->editColumn('razon_social', function ($row) {
                return $row->razon_social ? $row->razon_social : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.proveedors.index');
    }

    public function create()
    {
        abort_if(Gate::denies('proveedor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.proveedors.create');
    }

    public function store(StoreProveedorRequest $request)
    {
        $proveedor = Proveedor::create($request->all());

        return redirect()->route('admin.proveedors.index');
    }

    public function edit(Proveedor $proveedor)
    {
        abort_if(Gate::denies('proveedor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.proveedors.edit', compact('proveedor'));
    }

    public function update(UpdateProveedorRequest $request, Proveedor $proveedor)
    {
        $proveedor->update($request->all());

        return redirect()->route('admin.proveedors.index');
    }

    public function show(Proveedor $proveedor)
    {
        abort_if(Gate::denies('proveedor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.proveedors.show', compact('proveedor'));
    }

    public function destroy(Proveedor $proveedor)
    {
        abort_if(Gate::denies('proveedor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $proveedor->delete();

        return back();
    }

    public function massDestroy(MassDestroyProveedorRequest $request)
    {
        $proveedors = Proveedor::find(request('ids'));

        foreach ($proveedors as $proveedor) {
            $proveedor->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
