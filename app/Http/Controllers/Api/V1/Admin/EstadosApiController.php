<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreEstadoRequest;
use App\Http\Requests\UpdateEstadoRequest;
use App\Http\Resources\Admin\EstadoResource;
use App\Models\Estado;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EstadosApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('estado_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EstadoResource(Estado::all());
    }

    public function store(StoreEstadoRequest $request)
    {
        $estado = Estado::create($request->all());

        if ($request->input('icono', false)) {
            $estado->addMedia(storage_path('tmp/uploads/' . basename($request->input('icono'))))->toMediaCollection('icono');
        }

        return (new EstadoResource($estado))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Estado $estado)
    {
        abort_if(Gate::denies('estado_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EstadoResource($estado);
    }

    public function update(UpdateEstadoRequest $request, Estado $estado)
    {
        $estado->update($request->all());

        if ($request->input('icono', false)) {
            if (!$estado->icono || $request->input('icono') !== $estado->icono->file_name) {
                if ($estado->icono) {
                    $estado->icono->delete();
                }
                $estado->addMedia(storage_path('tmp/uploads/' . basename($request->input('icono'))))->toMediaCollection('icono');
            }
        } elseif ($estado->icono) {
            $estado->icono->delete();
        }

        return (new EstadoResource($estado))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Estado $estado)
    {
        abort_if(Gate::denies('estado_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $estado->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
