<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMonedaRequest;
use App\Http\Requests\UpdateMonedaRequest;
use App\Http\Resources\Admin\MonedaResource;
use App\Models\Moneda;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MonedaApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('moneda_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MonedaResource(Moneda::all());
    }

    public function store(StoreMonedaRequest $request)
    {
        $moneda = Moneda::create($request->all());

        return (new MonedaResource($moneda))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Moneda $moneda)
    {
        abort_if(Gate::denies('moneda_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MonedaResource($moneda);
    }

    public function update(UpdateMonedaRequest $request, Moneda $moneda)
    {
        $moneda->update($request->all());

        return (new MonedaResource($moneda))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Moneda $moneda)
    {
        abort_if(Gate::denies('moneda_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $moneda->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
