<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyConfiguracionRequest;
use App\Http\Requests\StoreConfiguracionRequest;
use App\Http\Requests\UpdateConfiguracionRequest;
use App\Models\Configuracion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ConfiguracionController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('configuracion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Configuracion::query()->select(sprintf('%s.*', (new Configuracion)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'configuracion_show';
                $editGate      = 'configuracion_edit';
                $deleteGate    = 'configuracion_delete';
                $crudRoutePart = 'configuracions';

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
            $table->editColumn('variable', function ($row) {
                return $row->variable ? $row->variable : '';
            });
            $table->editColumn('valor', function ($row) {
                return $row->valor ? $row->valor : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.configuracions.index');
    }

    public function create()
    {
        abort_if(Gate::denies('configuracion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.configuracions.create');
    }

    public function store(StoreConfiguracionRequest $request)
    {
        $configuracion = Configuracion::create($request->all());

        return redirect()->route('admin.configuracions.index');
    }

    public function edit(Configuracion $configuracion)
    {
        abort_if(Gate::denies('configuracion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.configuracions.edit', compact('configuracion'));
    }

    public function update(UpdateConfiguracionRequest $request, Configuracion $configuracion)
    {
        $configuracion->update($request->all());

        return redirect()->route('admin.configuracions.index');
    }

    public function show(Configuracion $configuracion)
    {
        abort_if(Gate::denies('configuracion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.configuracions.show', compact('configuracion'));
    }

    public function destroy(Configuracion $configuracion)
    {
        abort_if(Gate::denies('configuracion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracion->delete();

        return back();
    }

    public function massDestroy(MassDestroyConfiguracionRequest $request)
    {
        $configuracions = Configuracion::find(request('ids'));

        foreach ($configuracions as $configuracion) {
            $configuracion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
