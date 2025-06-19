<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyClausulaVentumRequest;
use App\Http\Requests\StoreClausulaVentumRequest;
use App\Http\Requests\UpdateClausulaVentumRequest;
use App\Models\ClausulaVentum;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ClausulaVentaController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('clausula_ventum_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ClausulaVentum::query()->select(sprintf('%s.*', (new ClausulaVentum)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'clausula_ventum_show';
                $editGate      = 'clausula_ventum_edit';
                $deleteGate    = 'clausula_ventum_delete';
                $crudRoutePart = 'clausula-venta';

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

        return view('admin.clausulaVenta.index');
    }

    public function create()
    {
        abort_if(Gate::denies('clausula_ventum_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.clausulaVenta.create');
    }

    public function store(StoreClausulaVentumRequest $request)
    {
        $clausulaVentum = ClausulaVentum::create($request->all());

        return redirect()->route('admin.clausula-venta.index');
    }

    public function edit(ClausulaVentum $clausulaVentum)
    {
        abort_if(Gate::denies('clausula_ventum_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.clausulaVenta.edit', compact('clausulaVentum'));
    }

    public function update(UpdateClausulaVentumRequest $request, ClausulaVentum $clausulaVentum)
    {
        $clausulaVentum->update($request->all());

        return redirect()->route('admin.clausula-venta.index');
    }

    public function show(ClausulaVentum $clausulaVentum)
    {
        abort_if(Gate::denies('clausula_ventum_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.clausulaVenta.show', compact('clausulaVentum'));
    }

    public function destroy(ClausulaVentum $clausulaVentum)
    {
        abort_if(Gate::denies('clausula_ventum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clausulaVentum->delete();

        return back();
    }

    public function massDestroy(MassDestroyClausulaVentumRequest $request)
    {
        $clausulaVenta = ClausulaVentum::find(request('ids'));

        foreach ($clausulaVenta as $clausulaVentum) {
            $clausulaVentum->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
