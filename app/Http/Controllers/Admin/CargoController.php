<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCargoRequest;
use App\Http\Requests\StoreCargoRequest;
use App\Http\Requests\UpdateCargoRequest;
use App\Models\Cargo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CargoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('cargo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Cargo::query()->select(sprintf('%s.*', (new Cargo)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'cargo_show';
                $editGate      = 'cargo_edit';
                $deleteGate    = 'cargo_delete';
                $crudRoutePart = 'cargos';

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

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.cargos.index');
    }

    public function create()
    {
        abort_if(Gate::denies('cargo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.cargos.create');
    }

    public function store(StoreCargoRequest $request)
    {
        $cargo = Cargo::create($request->all());

        return redirect()->route('admin.cargos.index');
    }

    public function edit(Cargo $cargo)
    {
        abort_if(Gate::denies('cargo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.cargos.edit', compact('cargo'));
    }

    public function update(UpdateCargoRequest $request, Cargo $cargo)
    {
        $cargo->update($request->all());

        return redirect()->route('admin.cargos.index');
    }

    public function show(Cargo $cargo)
    {
        abort_if(Gate::denies('cargo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cargo->load('cargoPersonals');

        return view('admin.cargos.show', compact('cargo'));
    }

    public function destroy(Cargo $cargo)
    {
        abort_if(Gate::denies('cargo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cargo->delete();

        return back();
    }

    public function massDestroy(MassDestroyCargoRequest $request)
    {
        $cargos = Cargo::find(request('ids'));

        foreach ($cargos as $cargo) {
            $cargo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
