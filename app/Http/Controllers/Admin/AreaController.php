<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAreaRequest;
use App\Http\Requests\StoreAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
use App\Models\Area;
use App\Models\Entidad;
use App\Models\Estado;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AreaController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('area_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Area::with(['entidad', 'estado'])->select(sprintf('%s.*', (new Area)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'area_show';
                $editGate      = 'area_edit';
                $deleteGate    = 'area_delete';
                $crudRoutePart = 'areas';

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
            $table->addColumn('entidad_nombre', function ($row) {
                return $row->entidad ? $row->entidad->nombre : '';
            });

            $table->editColumn('descripcion', function ($row) {
                return $row->descripcion ? $row->descripcion : '';
            });
            $table->addColumn('estado_nombre', function ($row) {
                return $row->estado ? $row->estado->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'entidad', 'estado']);

            return $table->make(true);
        }

        return view('admin.areas.index');
    }

    public function create()
    {
        abort_if(Gate::denies('area_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entidads = Entidad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = Estado::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.areas.create', compact('entidads', 'estados'));
    }

    public function store(StoreAreaRequest $request)
    {
        $area = Area::create($request->all());

        return redirect()->route('admin.areas.index');
    }

    public function edit(Area $area)
    {
        abort_if(Gate::denies('area_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entidads = Entidad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = Estado::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $area->load('entidad', 'estado');

        return view('admin.areas.edit', compact('area', 'entidads', 'estados'));
    }

    public function update(UpdateAreaRequest $request, Area $area)
    {
        $area->update($request->all());

        return redirect()->route('admin.areas.index');
    }

    public function show(Area $area)
    {
        abort_if(Gate::denies('area_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $area->load('entidad', 'estado', 'areaLocacions');

        return view('admin.areas.show', compact('area'));
    }

    public function destroy(Area $area)
    {
        abort_if(Gate::denies('area_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $area->delete();

        return back();
    }

    public function massDestroy(MassDestroyAreaRequest $request)
    {
        $areas = Area::find(request('ids'));

        foreach ($areas as $area) {
            $area->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
