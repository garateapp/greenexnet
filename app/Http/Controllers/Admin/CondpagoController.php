<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCondpagoRequest;
use App\Http\Requests\StoreCondpagoRequest;
use App\Http\Requests\UpdateCondpagoRequest;
use App\Models\Condpago;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CondpagoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('condpago_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Condpago::query()->select(sprintf('%s.*', (new Condpago)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'condpago_show';
                $editGate      = 'condpago_edit';
                $deleteGate    = 'condpago_delete';
                $crudRoutePart = 'condpagos';

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
            $table->editColumn('cond_pago', function ($row) {
                return $row->cond_pago ? $row->cond_pago : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.condpagos.index');
    }

    public function create()
    {
        abort_if(Gate::denies('condpago_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.condpagos.create');
    }

    public function store(StoreCondpagoRequest $request)
    {
        $condpago = Condpago::create($request->all());

        return redirect()->route('admin.condpagos.index');
    }

    public function edit(Condpago $condpago)
    {
        abort_if(Gate::denies('condpago_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.condpagos.edit', compact('condpago'));
    }

    public function update(UpdateCondpagoRequest $request, Condpago $condpago)
    {
        $condpago->update($request->all());

        return redirect()->route('admin.condpagos.index');
    }

    public function show(Condpago $condpago)
    {
        abort_if(Gate::denies('condpago_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.condpagos.show', compact('condpago'));
    }

    public function destroy(Condpago $condpago)
    {
        abort_if(Gate::denies('condpago_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $condpago->delete();

        return back();
    }

    public function massDestroy(MassDestroyCondpagoRequest $request)
    {
        $condpagos = Condpago::find(request('ids'));

        foreach ($condpagos as $condpago) {
            $condpago->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
