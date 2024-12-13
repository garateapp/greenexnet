<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmbarqueRequest;
use App\Http\Requests\UpdateEmbarqueRequest;
use App\Http\Resources\Admin\EmbarqueResource;
use App\Models\Embarque;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmbarquesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('embarque_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EmbarqueResource(Embarque::all());
    }

    public function store(StoreEmbarqueRequest $request)
    {
        $embarque = Embarque::create($request->all());

        return (new EmbarqueResource($embarque))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Embarque $embarque)
    {
        abort_if(Gate::denies('embarque_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EmbarqueResource($embarque);
    }

    public function update(UpdateEmbarqueRequest $request, Embarque $embarque)
    {
        $embarque->update($request->all());

        return (new EmbarqueResource($embarque))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Embarque $embarque)
    {
        abort_if(Gate::denies('embarque_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embarque->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
