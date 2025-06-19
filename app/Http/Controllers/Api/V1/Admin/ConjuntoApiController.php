<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConjuntoRequest;
use App\Http\Requests\UpdateConjuntoRequest;
use App\Http\Resources\Admin\ConjuntoResource;
use App\Models\Conjunto;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConjuntoApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('conjunto_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ConjuntoResource(Conjunto::all());
    }

    public function store(StoreConjuntoRequest $request)
    {
        $conjunto = Conjunto::create($request->all());

        return (new ConjuntoResource($conjunto))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Conjunto $conjunto)
    {
        abort_if(Gate::denies('conjunto_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ConjuntoResource($conjunto);
    }

    public function update(UpdateConjuntoRequest $request, Conjunto $conjunto)
    {
        $conjunto->update($request->all());

        return (new ConjuntoResource($conjunto))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Conjunto $conjunto)
    {
        abort_if(Gate::denies('conjunto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $conjunto->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
