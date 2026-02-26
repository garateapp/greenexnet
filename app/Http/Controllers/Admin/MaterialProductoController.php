<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyMaterialProductoRequest;
use App\Http\Requests\StoreMaterialProductoRequest;
use App\Http\Requests\UpdateMaterialProductoRequest;
use App\Models\Embalaje;
use App\Models\Material;
use App\Models\MaterialProducto;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MaterialProductoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('material_producto_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = MaterialProducto::with(['embalaje', 'material'])->select(sprintf('%s.*', (new MaterialProducto)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'material_producto_show';
                $editGate      = 'material_producto_edit';
                $deleteGate    = 'material_producto_delete';
                $crudRoutePart = 'material-productos';

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
            $table->addColumn('embalaje_c_embalaje', function ($row) {
                return $row->embalaje ? $row->embalaje->c_embalaje : '';
            });

            $table->addColumn('material_nombre', function ($row) {
                return $row->material ? $row->material->nombre : '';
            });

            $table->editColumn('material.codigo', function ($row) {
                return $row->material ? (is_string($row->material) ? $row->material : $row->material->codigo) : '';
            });
            $table->editColumn('unidadxcaja', function ($row) {
                return $row->unidadxcaja ? $row->unidadxcaja : '';
            });
            $table->editColumn('unidadxpallet', function ($row) {
                return $row->unidadxpallet ? $row->unidadxpallet : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'embalaje', 'material']);

            return $table->make(true);
        }

        $embalajes = Embalaje::get();
        $materials = Material::get();

        return view('admin.materialProductos.index', compact('embalajes', 'materials'));
    }

    public function create()
    {
        abort_if(Gate::denies('material_producto_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embalajes = Embalaje::pluck('c_embalaje', 'id')->prepend(trans('global.pleaseSelect'), '');

        $materials = Material::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.materialProductos.create', compact('embalajes', 'materials'));
    }

    public function store(StoreMaterialProductoRequest $request)
    {
        $materialProducto = MaterialProducto::create($request->all());

        return redirect()->route('admin.material-productos.index');
    }

    public function edit(MaterialProducto $materialProducto)
    {
        abort_if(Gate::denies('material_producto_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embalajes = Embalaje::pluck('c_embalaje', 'id')->prepend(trans('global.pleaseSelect'), '');

        $materials = Material::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $materialProducto->load('embalaje', 'material');

        return view('admin.materialProductos.edit', compact('embalajes', 'materialProducto', 'materials'));
    }

    public function update(UpdateMaterialProductoRequest $request, MaterialProducto $materialProducto)
    {
        $materialProducto->update($request->all());

        return redirect()->route('admin.material-productos.index');
    }

    public function show(MaterialProducto $materialProducto)
    {
        abort_if(Gate::denies('material_producto_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $materialProducto->load('embalaje', 'material');

        return view('admin.materialProductos.show', compact('materialProducto'));
    }

    public function destroy(MaterialProducto $materialProducto)
    {
        abort_if(Gate::denies('material_producto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $materialProducto->delete();

        return back();
    }

    public function massDestroy(MassDestroyMaterialProductoRequest $request)
    {
        $materialProductos = MaterialProducto::find(request('ids'));

        foreach ($materialProductos as $materialProducto) {
            $materialProducto->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
