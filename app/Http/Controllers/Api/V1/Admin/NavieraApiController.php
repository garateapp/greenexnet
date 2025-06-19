<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNavieraRequest;
use App\Http\Requests\UpdateNavieraRequest;
use App\Http\Resources\Admin\NavieraResource;
use App\Models\Naviera;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NavieraApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('naviera_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NavieraResource(Naviera::all());
    }

    public function store(StoreNavieraRequest $request)
    {
        $naviera = Naviera::create($request->all());

        return (new NavieraResource($naviera))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Naviera $naviera)
    {
        abort_if(Gate::denies('naviera_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NavieraResource($naviera);
    }

    public function update(UpdateNavieraRequest $request, Naviera $naviera)
    {
        $naviera->update($request->all());

        return (new NavieraResource($naviera))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Naviera $naviera)
    {
        abort_if(Gate::denies('naviera_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $naviera->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
