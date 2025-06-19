<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipofleteRequest;
use App\Http\Requests\UpdateTipofleteRequest;
use App\Http\Resources\Admin\TipofleteResource;
use App\Models\Tipoflete;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipofleteApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tipoflete_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TipofleteResource(Tipoflete::all());
    }

    public function store(StoreTipofleteRequest $request)
    {
        $tipoflete = Tipoflete::create($request->all());

        return (new TipofleteResource($tipoflete))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Tipoflete $tipoflete)
    {
        abort_if(Gate::denies('tipoflete_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TipofleteResource($tipoflete);
    }

    public function update(UpdateTipofleteRequest $request, Tipoflete $tipoflete)
    {
        $tipoflete->update($request->all());

        return (new TipofleteResource($tipoflete))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Tipoflete $tipoflete)
    {
        abort_if(Gate::denies('tipoflete_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoflete->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
