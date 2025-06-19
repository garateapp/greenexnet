<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyFormaPagoRequest;
use App\Http\Requests\StoreFormaPagoRequest;
use App\Http\Requests\UpdateFormaPagoRequest;
use App\Models\FormaPago;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FormaPagoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('forma_pago_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = FormaPago::query()->select(sprintf('%s.*', (new FormaPago)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'forma_pago_show';
                $editGate      = 'forma_pago_edit';
                $deleteGate    = 'forma_pago_delete';
                $crudRoutePart = 'forma-pagos';

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

        return view('admin.formaPagos.index');
    }

    public function create()
    {
        abort_if(Gate::denies('forma_pago_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.formaPagos.create');
    }

    public function store(StoreFormaPagoRequest $request)
    {
        $formaPago = FormaPago::create($request->all());

        return redirect()->route('admin.forma-pagos.index');
    }

    public function edit(FormaPago $formaPago)
    {
        abort_if(Gate::denies('forma_pago_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.formaPagos.edit', compact('formaPago'));
    }

    public function update(UpdateFormaPagoRequest $request, FormaPago $formaPago)
    {
        $formaPago->update($request->all());

        return redirect()->route('admin.forma-pagos.index');
    }

    public function show(FormaPago $formaPago)
    {
        abort_if(Gate::denies('forma_pago_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.formaPagos.show', compact('formaPago'));
    }

    public function destroy(FormaPago $formaPago)
    {
        abort_if(Gate::denies('forma_pago_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $formaPago->delete();

        return back();
    }

    public function massDestroy(MassDestroyFormaPagoRequest $request)
    {
        $formaPagos = FormaPago::find(request('ids'));

        foreach ($formaPagos as $formaPago) {
            $formaPago->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
