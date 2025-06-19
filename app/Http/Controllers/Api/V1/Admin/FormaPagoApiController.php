<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFormaPagoRequest;
use App\Http\Requests\UpdateFormaPagoRequest;
use App\Http\Resources\Admin\FormaPagoResource;
use App\Models\FormaPago;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FormaPagoApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('forma_pago_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FormaPagoResource(FormaPago::all());
    }

    public function store(StoreFormaPagoRequest $request)
    {
        $formaPago = FormaPago::create($request->all());

        return (new FormaPagoResource($formaPago))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(FormaPago $formaPago)
    {
        abort_if(Gate::denies('forma_pago_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FormaPagoResource($formaPago);
    }

    public function update(UpdateFormaPagoRequest $request, FormaPago $formaPago)
    {
        $formaPago->update($request->all());

        return (new FormaPagoResource($formaPago))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(FormaPago $formaPago)
    {
        abort_if(Gate::denies('forma_pago_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $formaPago->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
