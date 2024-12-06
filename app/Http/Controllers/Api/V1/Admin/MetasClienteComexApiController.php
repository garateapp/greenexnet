<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMetasClienteComexRequest;
use App\Http\Requests\UpdateMetasClienteComexRequest;
use App\Http\Resources\Admin\MetasClienteComexResource;
use App\Models\MetasClienteComex;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MetasClienteComexApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('metas_cliente_comex_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MetasClienteComexResource(MetasClienteComex::with(['clientecomex'])->get());
    }

    public function store(StoreMetasClienteComexRequest $request)
    {
        $metasClienteComex = MetasClienteComex::create($request->all());

        return (new MetasClienteComexResource($metasClienteComex))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(MetasClienteComex $metasClienteComex)
    {
        abort_if(Gate::denies('metas_cliente_comex_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MetasClienteComexResource($metasClienteComex->load(['clientecomex']));
    }

    public function update(UpdateMetasClienteComexRequest $request, MetasClienteComex $metasClienteComex)
    {
        $metasClienteComex->update($request->all());

        return (new MetasClienteComexResource($metasClienteComex))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(MetasClienteComex $metasClienteComex)
    {
        abort_if(Gate::denies('metas_cliente_comex_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $metasClienteComex->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
