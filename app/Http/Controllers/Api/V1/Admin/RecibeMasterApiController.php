<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecibeMasterRequest;
use App\Http\Requests\UpdateRecibeMasterRequest;
use App\Http\Resources\Admin\RecibeMasterResource;
use App\Models\RecibeMaster;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecibeMasterApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('recibe_master_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RecibeMasterResource(RecibeMaster::all());
    }

    public function store(StoreRecibeMasterRequest $request)
    {
        $recibeMaster = RecibeMaster::create($request->all());

        return (new RecibeMasterResource($recibeMaster))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(RecibeMaster $recibeMaster)
    {
        abort_if(Gate::denies('recibe_master_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RecibeMasterResource($recibeMaster);
    }

    public function update(UpdateRecibeMasterRequest $request, RecibeMaster $recibeMaster)
    {
        $recibeMaster->update($request->all());

        return (new RecibeMasterResource($recibeMaster))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(RecibeMaster $recibeMaster)
    {
        abort_if(Gate::denies('recibe_master_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recibeMaster->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
