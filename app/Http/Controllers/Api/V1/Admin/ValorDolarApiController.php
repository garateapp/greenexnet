<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreValorDolarRequest;
use App\Http\Requests\UpdateValorDolarRequest;
use App\Http\Resources\Admin\ValorDolarResource;
use App\Models\ValorDolar;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValorDolarApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('valor_dolar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ValorDolarResource(ValorDolar::all());
    }

    public function store(StoreValorDolarRequest $request)
    {
        $valorDolar = ValorDolar::create($request->all());

        return (new ValorDolarResource($valorDolar))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ValorDolar $valorDolar)
    {
        abort_if(Gate::denies('valor_dolar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ValorDolarResource($valorDolar);
    }

    public function update(UpdateValorDolarRequest $request, ValorDolar $valorDolar)
    {
        $valorDolar->update($request->all());

        return (new ValorDolarResource($valorDolar))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ValorDolar $valorDolar)
    {
        abort_if(Gate::denies('valor_dolar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $valorDolar->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
