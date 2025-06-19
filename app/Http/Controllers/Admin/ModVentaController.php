<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyModVentumRequest;
use App\Http\Requests\StoreModVentumRequest;
use App\Http\Requests\UpdateModVentumRequest;
use App\Models\ModVentum;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ModVentaController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('mod_ventum_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ModVentum::query()->select(sprintf('%s.*', (new ModVentum)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'mod_ventum_show';
                $editGate      = 'mod_ventum_edit';
                $deleteGate    = 'mod_ventum_delete';
                $crudRoutePart = 'mod-venta';

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

        return view('admin.modVenta.index');
    }

    public function create()
    {
        abort_if(Gate::denies('mod_ventum_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.modVenta.create');
    }

    public function store(StoreModVentumRequest $request)
    {
        $modVentum = ModVentum::create($request->all());

        return redirect()->route('admin.mod-venta.index');
    }

    public function edit(ModVentum $modVentum)
    {
        abort_if(Gate::denies('mod_ventum_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.modVenta.edit', compact('modVentum'));
    }

    public function update(UpdateModVentumRequest $request, ModVentum $modVentum)
    {
        $modVentum->update($request->all());

        return redirect()->route('admin.mod-venta.index');
    }

    public function show(ModVentum $modVentum)
    {
        abort_if(Gate::denies('mod_ventum_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.modVenta.show', compact('modVentum'));
    }

    public function destroy(ModVentum $modVentum)
    {
        abort_if(Gate::denies('mod_ventum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modVentum->delete();

        return back();
    }

    public function massDestroy(MassDestroyModVentumRequest $request)
    {
        $modVenta = ModVentum::find(request('ids'));

        foreach ($modVenta as $modVentum) {
            $modVentum->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
