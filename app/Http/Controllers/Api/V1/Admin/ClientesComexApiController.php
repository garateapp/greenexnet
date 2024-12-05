<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientesComexRequest;
use App\Http\Requests\UpdateClientesComexRequest;
use App\Http\Resources\Admin\ClientesComexResource;
use App\Models\ClientesComex;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientesComexApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('clientes_comex_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ClientesComexResource(ClientesComex::all());
    }

    public function store(StoreClientesComexRequest $request)
    {
        $clientesComex = ClientesComex::create($request->all());

        return (new ClientesComexResource($clientesComex))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ClientesComex $clientesComex)
    {
        abort_if(Gate::denies('clientes_comex_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ClientesComexResource($clientesComex);
    }

    public function update(UpdateClientesComexRequest $request, ClientesComex $clientesComex)
    {
        $clientesComex->update($request->all());

        return (new ClientesComexResource($clientesComex))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ClientesComex $clientesComex)
    {
        abort_if(Gate::denies('clientes_comex_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientesComex->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
