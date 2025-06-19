<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyFormaPagoRequest;
use App\Http\Requests\StoreFormaPagoRequest;
use App\Http\Requests\UpdateFormaPagoRequest;
use App\Models\FormaPago;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FormaPagoController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('forma_pago_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $formaPagos = FormaPago::all();

        return view('frontend.formaPagos.index', compact('formaPagos'));
    }

    public function create()
    {
        abort_if(Gate::denies('forma_pago_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.formaPagos.create');
    }

    public function store(StoreFormaPagoRequest $request)
    {
        $formaPago = FormaPago::create($request->all());

        return redirect()->route('frontend.forma-pagos.index');
    }

    public function edit(FormaPago $formaPago)
    {
        abort_if(Gate::denies('forma_pago_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.formaPagos.edit', compact('formaPago'));
    }

    public function update(UpdateFormaPagoRequest $request, FormaPago $formaPago)
    {
        $formaPago->update($request->all());

        return redirect()->route('frontend.forma-pagos.index');
    }

    public function show(FormaPago $formaPago)
    {
        abort_if(Gate::denies('forma_pago_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.formaPagos.show', compact('formaPago'));
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
