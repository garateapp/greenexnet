<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBaseContactoRequest;
use App\Http\Requests\UpdateBaseContactoRequest;
use App\Http\Resources\Admin\BaseContactoResource;
use App\Models\BaseContacto;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseContactoApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('base_contacto_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BaseContactoResource(BaseContacto::with(['cliente'])->get());
    }

    public function store(StoreBaseContactoRequest $request)
    {
        $baseContacto = BaseContacto::create($request->all());

        return (new BaseContactoResource($baseContacto))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(BaseContacto $baseContacto)
    {
        abort_if(Gate::denies('base_contacto_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BaseContactoResource($baseContacto->load(['cliente']));
    }

    public function update(UpdateBaseContactoRequest $request, BaseContacto $baseContacto)
    {
        $baseContacto->update($request->all());

        return (new BaseContactoResource($baseContacto))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(BaseContacto $baseContacto)
    {
        abort_if(Gate::denies('base_contacto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $baseContacto->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
