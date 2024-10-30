<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersonalRequest;
use App\Http\Requests\UpdatePersonalRequest;
use App\Http\Resources\Admin\PersonalResource;
use App\Models\Personal;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonalApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('personal_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PersonalResource(Personal::with(['cargo', 'estado', 'entidad'])->get());
    }

    public function store(StorePersonalRequest $request)
    {
        $personal = Personal::create($request->all());

        return (new PersonalResource($personal))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Personal $personal)
    {
        abort_if(Gate::denies('personal_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PersonalResource($personal->load(['cargo', 'estado', 'entidad']));
    }

    public function update(UpdatePersonalRequest $request, Personal $personal)
    {
        $personal->update($request->all());

        return (new PersonalResource($personal))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Personal $personal)
    {
        abort_if(Gate::denies('personal_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $personal->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
