<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreValorEnvaseRequest;
use App\Http\Requests\UpdateValorEnvaseRequest;
use App\Http\Resources\Admin\ValorEnvaseResource;
use App\Models\ValorEnvase;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValorEnvaseApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('valor_envase_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ValorEnvaseResource(ValorEnvase::with(['productor'])->get());
    }

    public function store(StoreValorEnvaseRequest $request)
    {
        $valorEnvase = ValorEnvase::create($request->all());

        return (new ValorEnvaseResource($valorEnvase))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ValorEnvase $valorEnvase)
    {
        abort_if(Gate::denies('valor_envase_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ValorEnvaseResource($valorEnvase->load(['productor']));
    }

    public function update(UpdateValorEnvaseRequest $request, ValorEnvase $valorEnvase)
    {
        $valorEnvase->update($request->all());

        return (new ValorEnvaseResource($valorEnvase))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ValorEnvase $valorEnvase)
    {
        abort_if(Gate::denies('valor_envase_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $valorEnvase->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
