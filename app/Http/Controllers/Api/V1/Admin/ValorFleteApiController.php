<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreValorFleteRequest;
use App\Http\Requests\UpdateValorFleteRequest;
use App\Http\Resources\Admin\ValorFleteResource;
use App\Models\ValorFlete;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValorFleteApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('valor_flete_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ValorFleteResource(ValorFlete::with(['productor', 'valor_dolar'])->get());
    }

    public function store(StoreValorFleteRequest $request)
    {
        $valorFlete = ValorFlete::create($request->all());

        return (new ValorFleteResource($valorFlete))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ValorFlete $valorFlete)
    {
        abort_if(Gate::denies('valor_flete_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ValorFleteResource($valorFlete->load(['productor', 'valor_dolar']));
    }

    public function update(UpdateValorFleteRequest $request, ValorFlete $valorFlete)
    {
        $valorFlete->update($request->all());

        return (new ValorFleteResource($valorFlete))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ValorFlete $valorFlete)
    {
        abort_if(Gate::denies('valor_flete_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $valorFlete->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
