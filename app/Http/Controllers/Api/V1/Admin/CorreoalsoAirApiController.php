<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCorreoalsoAirRequest;
use App\Http\Requests\UpdateCorreoalsoAirRequest;
use App\Http\Resources\Admin\CorreoalsoAirResource;
use App\Models\CorreoalsoAir;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorreoalsoAirApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('correoalso_air_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CorreoalsoAirResource(CorreoalsoAir::with(['cliente'])->get());
    }

    public function store(StoreCorreoalsoAirRequest $request)
    {
        $correoalsoAir = CorreoalsoAir::create($request->all());

        return (new CorreoalsoAirResource($correoalsoAir))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CorreoalsoAir $correoalsoAir)
    {
        abort_if(Gate::denies('correoalso_air_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CorreoalsoAirResource($correoalsoAir->load(['cliente']));
    }

    public function update(UpdateCorreoalsoAirRequest $request, CorreoalsoAir $correoalsoAir)
    {
        $correoalsoAir->update($request->all());

        return (new CorreoalsoAirResource($correoalsoAir))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CorreoalsoAir $correoalsoAir)
    {
        abort_if(Gate::denies('correoalso_air_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $correoalsoAir->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
