<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModVentumRequest;
use App\Http\Requests\UpdateModVentumRequest;
use App\Http\Resources\Admin\ModVentumResource;
use App\Models\ModVentum;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModVentaApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('mod_ventum_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ModVentumResource(ModVentum::all());
    }

    public function store(StoreModVentumRequest $request)
    {
        $modVentum = ModVentum::create($request->all());

        return (new ModVentumResource($modVentum))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ModVentum $modVentum)
    {
        abort_if(Gate::denies('mod_ventum_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ModVentumResource($modVentum);
    }

    public function update(UpdateModVentumRequest $request, ModVentum $modVentum)
    {
        $modVentum->update($request->all());

        return (new ModVentumResource($modVentum))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ModVentum $modVentum)
    {
        abort_if(Gate::denies('mod_ventum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modVentum->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
