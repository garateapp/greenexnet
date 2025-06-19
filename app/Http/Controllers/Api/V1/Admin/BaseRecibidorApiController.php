<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBaseRecibidorRequest;
use App\Http\Requests\UpdateBaseRecibidorRequest;
use App\Http\Resources\Admin\BaseRecibidorResource;
use App\Models\BaseRecibidor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseRecibidorApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('base_recibidor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BaseRecibidorResource(BaseRecibidor::with(['cliente'])->get());
    }

    public function store(StoreBaseRecibidorRequest $request)
    {
        $baseRecibidor = BaseRecibidor::create($request->all());

        return (new BaseRecibidorResource($baseRecibidor))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(BaseRecibidor $baseRecibidor)
    {
        abort_if(Gate::denies('base_recibidor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BaseRecibidorResource($baseRecibidor->load(['cliente']));
    }

    public function update(UpdateBaseRecibidorRequest $request, BaseRecibidor $baseRecibidor)
    {
        $baseRecibidor->update($request->all());

        return (new BaseRecibidorResource($baseRecibidor))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(BaseRecibidor $baseRecibidor)
    {
        abort_if(Gate::denies('base_recibidor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $baseRecibidor->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
