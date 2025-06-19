<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmisionBlRequest;
use App\Http\Requests\UpdateEmisionBlRequest;
use App\Http\Resources\Admin\EmisionBlResource;
use App\Models\EmisionBl;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmisionBlApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('emision_bl_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EmisionBlResource(EmisionBl::all());
    }

    public function store(StoreEmisionBlRequest $request)
    {
        $emisionBl = EmisionBl::create($request->all());

        return (new EmisionBlResource($emisionBl))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(EmisionBl $emisionBl)
    {
        abort_if(Gate::denies('emision_bl_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EmisionBlResource($emisionBl);
    }

    public function update(UpdateEmisionBlRequest $request, EmisionBl $emisionBl)
    {
        $emisionBl->update($request->all());

        return (new EmisionBlResource($emisionBl))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(EmisionBl $emisionBl)
    {
        abort_if(Gate::denies('emision_bl_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisionBl->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
