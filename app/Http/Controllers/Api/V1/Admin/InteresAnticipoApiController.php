<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInteresAnticipoRequest;
use App\Http\Requests\UpdateInteresAnticipoRequest;
use App\Http\Resources\Admin\InteresAnticipoResource;
use App\Models\InteresAnticipo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InteresAnticipoApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('interes_anticipo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InteresAnticipoResource(InteresAnticipo::with(['anticipo'])->get());
    }

    public function store(StoreInteresAnticipoRequest $request)
    {
        $interesAnticipo = InteresAnticipo::create($request->all());

        return (new InteresAnticipoResource($interesAnticipo))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(InteresAnticipo $interesAnticipo)
    {
        abort_if(Gate::denies('interes_anticipo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InteresAnticipoResource($interesAnticipo->load(['anticipo']));
    }

    public function update(UpdateInteresAnticipoRequest $request, InteresAnticipo $interesAnticipo)
    {
        $interesAnticipo->update($request->all());

        return (new InteresAnticipoResource($interesAnticipo))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(InteresAnticipo $interesAnticipo)
    {
        abort_if(Gate::denies('interes_anticipo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $interesAnticipo->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
