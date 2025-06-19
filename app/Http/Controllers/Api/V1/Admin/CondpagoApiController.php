<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCondpagoRequest;
use App\Http\Requests\UpdateCondpagoRequest;
use App\Http\Resources\Admin\CondpagoResource;
use App\Models\Condpago;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CondpagoApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('condpago_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CondpagoResource(Condpago::all());
    }

    public function store(StoreCondpagoRequest $request)
    {
        $condpago = Condpago::create($request->all());

        return (new CondpagoResource($condpago))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Condpago $condpago)
    {
        abort_if(Gate::denies('condpago_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CondpagoResource($condpago);
    }

    public function update(UpdateCondpagoRequest $request, Condpago $condpago)
    {
        $condpago->update($request->all());

        return (new CondpagoResource($condpago))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Condpago $condpago)
    {
        abort_if(Gate::denies('condpago_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $condpago->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
