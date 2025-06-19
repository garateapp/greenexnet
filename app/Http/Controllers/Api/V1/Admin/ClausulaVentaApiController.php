<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClausulaVentumRequest;
use App\Http\Requests\UpdateClausulaVentumRequest;
use App\Http\Resources\Admin\ClausulaVentumResource;
use App\Models\ClausulaVentum;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClausulaVentaApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('clausula_ventum_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ClausulaVentumResource(ClausulaVentum::all());
    }

    public function store(StoreClausulaVentumRequest $request)
    {
        $clausulaVentum = ClausulaVentum::create($request->all());

        return (new ClausulaVentumResource($clausulaVentum))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ClausulaVentum $clausulaVentum)
    {
        abort_if(Gate::denies('clausula_ventum_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ClausulaVentumResource($clausulaVentum);
    }

    public function update(UpdateClausulaVentumRequest $request, ClausulaVentum $clausulaVentum)
    {
        $clausulaVentum->update($request->all());

        return (new ClausulaVentumResource($clausulaVentum))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ClausulaVentum $clausulaVentum)
    {
        abort_if(Gate::denies('clausula_ventum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clausulaVentum->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
