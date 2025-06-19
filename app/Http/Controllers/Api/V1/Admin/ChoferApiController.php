<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChoferRequest;
use App\Http\Requests\UpdateChoferRequest;
use App\Http\Resources\Admin\ChoferResource;
use App\Models\Chofer;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChoferApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('chofer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ChoferResource(Chofer::all());
    }

    public function store(StoreChoferRequest $request)
    {
        $chofer = Chofer::create($request->all());

        return (new ChoferResource($chofer))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Chofer $chofer)
    {
        abort_if(Gate::denies('chofer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ChoferResource($chofer);
    }

    public function update(UpdateChoferRequest $request, Chofer $chofer)
    {
        $chofer->update($request->all());

        return (new ChoferResource($chofer))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Chofer $chofer)
    {
        abort_if(Gate::denies('chofer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $chofer->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
