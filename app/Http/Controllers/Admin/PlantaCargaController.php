<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPlantaCargaRequest;
use App\Http\Requests\StorePlantaCargaRequest;
use App\Http\Requests\UpdatePlantaCargaRequest;
use App\Models\PlantaCarga;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PlantaCargaController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('planta_carga_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PlantaCarga::query()->select(sprintf('%s.*', (new PlantaCarga)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'planta_carga_show';
                $editGate      = 'planta_carga_edit';
                $deleteGate    = 'planta_carga_delete';
                $crudRoutePart = 'planta-cargas';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });
            $table->editColumn('direccion', function ($row) {
                return $row->direccion ? $row->direccion : '';
            });
            $table->editColumn('id_fx', function ($row) {
                return $row->id_fx ? $row->id_fx : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.plantaCargas.index');
    }

    public function create()
    {
        abort_if(Gate::denies('planta_carga_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.plantaCargas.create');
    }

    public function store(StorePlantaCargaRequest $request)
    {
        $plantaCarga = PlantaCarga::create($request->all());

        return redirect()->route('admin.planta-cargas.index');
    }

    public function edit(PlantaCarga $plantaCarga)
    {
        abort_if(Gate::denies('planta_carga_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.plantaCargas.edit', compact('plantaCarga'));
    }

    public function update(UpdatePlantaCargaRequest $request, PlantaCarga $plantaCarga)
    {
        $plantaCarga->update($request->all());

        return redirect()->route('admin.planta-cargas.index');
    }

    public function show(PlantaCarga $plantaCarga)
    {
        abort_if(Gate::denies('planta_carga_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.plantaCargas.show', compact('plantaCarga'));
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
