<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDatosCajaRequest;
use App\Http\Requests\UpdateDatosCajaRequest;
use App\Http\Resources\Admin\DatosCajaResource;
use App\Models\DatosCaja;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DatosCajaApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('datos_caja_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DatosCajaResource(DatosCaja::all());
    }

    public function store(StoreDatosCajaRequest $request)
    {
        $datosCaja = DatosCaja::create($request->all());

        return (new DatosCajaResource($datosCaja))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DatosCaja $datosCaja)
    {
        abort_if(Gate::denies('datos_caja_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DatosCajaResource($datosCaja);
    }

    public function update(UpdateDatosCajaRequest $request, DatosCaja $datosCaja)
    {
        $datosCaja->update($request->all());

        return (new DatosCajaResource($datosCaja))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DatosCaja $datosCaja)
    {
        abort_if(Gate::denies('datos_caja_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $datosCaja->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
