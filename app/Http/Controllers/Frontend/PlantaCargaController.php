<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPlantaCargaRequest;
use App\Http\Requests\StorePlantaCargaRequest;
use App\Http\Requests\UpdatePlantaCargaRequest;
use App\Models\PlantaCarga;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlantaCargaController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('planta_carga_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $plantaCargas = PlantaCarga::all();

        return view('frontend.plantaCargas.index', compact('plantaCargas'));
    }

    public function create()
    {
        abort_if(Gate::denies('planta_carga_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.plantaCargas.create');
    }

    public function store(StorePlantaCargaRequest $request)
    {
        $plantaCarga = PlantaCarga::create($request->all());

        return redirect()->route('frontend.planta-cargas.index');
    }

    public function edit(PlantaCarga $plantaCarga)
    {
        abort_if(Gate::denies('planta_carga_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.plantaCargas.edit', compact('plantaCarga'));
    }

    public function update(UpdatePlantaCargaRequest $request, PlantaCarga $plantaCarga)
    {
        $plantaCarga->update($request->all());

        return redirect()->route('frontend.planta-cargas.index');
    }

    public function show(PlantaCarga $plantaCarga)
    {
        abort_if(Gate::denies('planta_carga_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.plantaCargas.show', compact('plantaCarga'));
    }

    public function destroy(PlantaCarga $plantaCarga)
    {
        abort_if(Gate::denies('planta_carga_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $plantaCarga->delete();

        return back();
    }

    public function massDestroy(MassDestroyPlantaCargaRequest $request)
    {
        $plantaCargas = PlantaCarga::find(request('ids'));

        foreach ($plantaCargas as $plantaCarga) {
            $plantaCarga->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
