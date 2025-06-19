<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductorRequest;
use App\Http\Requests\UpdateProductorRequest;
use App\Http\Resources\Admin\ProductorResource;
use App\Models\Productor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductorApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('productor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductorResource(Productor::with(['grupo'])->get());
    }

    public function store(StoreProductorRequest $request)
    {
        $productor = Productor::create($request->all());

        return (new ProductorResource($productor))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Productor $productor)
    {
        abort_if(Gate::denies('productor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductorResource($productor->load(['grupo']));
    }

    public function update(UpdateProductorRequest $request, Productor $productor)
    {
        $productor->update($request->all());

        return (new ProductorResource($productor))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Productor $productor)
    {
        abort_if(Gate::denies('productor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productor->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
