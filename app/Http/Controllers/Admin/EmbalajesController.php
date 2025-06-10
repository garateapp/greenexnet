<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEmbalajeRequest;
use App\Http\Requests\StoreEmbalajeRequest;
use App\Http\Requests\UpdateEmbalajeRequest;
use App\Models\Embalaje;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EmbalajesController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('embalaje_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Embalaje::query()->select(sprintf('%s.*', (new Embalaje)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'embalaje_show';
                $editGate      = 'embalaje_edit';
                $deleteGate    = 'embalaje_delete';
                $crudRoutePart = 'embalajes';

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
            $table->editColumn('c_embalaje', function ($row) {
                return $row->c_embalaje ? $row->c_embalaje : '';
            });
            $table->editColumn('kgxcaja', function ($row) {
                return $row->kgxcaja ? $row->kgxcaja : '';
            });
            $table->editColumn('cajaxpallet', function ($row) {
                return $row->cajaxpallet ? $row->cajaxpallet : '';
            });
            $table->editColumn('altura_pallet', function ($row) {
                return $row->altura_pallet ? $row->altura_pallet : '';
            });
            $table->editColumn('tipo_embarque', function ($row) {
                return $row->tipo_embarque ? Embalaje::TIPO_EMBARQUE_SELECT[$row->tipo_embarque] : '';
            });
            $table->editColumn('caja', function ($row) {
                return $row->caja ? $row->caja : '';
            });
            $table->editColumn('cajasxlinea', function ($row) {
                return $row->cajasxlinea ? $row->cajasxlinea : '';
            });
            $table->editColumn('lineasxpallet', function ($row) {
                return $row->lineasxpallet ? $row->lineasxpallet : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.embalajes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('embalaje_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.embalajes.create');
    }

    public function store(StoreEmbalajeRequest $request)
    {
        $embalaje = Embalaje::create($request->all());

        return redirect()->route('admin.embalajes.index');
    }

    public function edit(Embalaje $embalaje)
    {
        abort_if(Gate::denies('embalaje_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.embalajes.edit', compact('embalaje'));
    }

    public function update(UpdateEmbalajeRequest $request, Embalaje $embalaje)
    {
        $embalaje->update($request->all());

        return redirect()->route('admin.embalajes.index');
    }

    public function show(Embalaje $embalaje)
    {
        abort_if(Gate::denies('embalaje_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embalaje->load('embalajeMaterialProductos');

        return view('admin.embalajes.show', compact('embalaje'));
    }

    public function destroy(Embalaje $embalaje)
    {
        abort_if(Gate::denies('embalaje_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embalaje->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmbalajeRequest $request)
    {
        $embalajes = Embalaje::find(request('ids'));

        foreach ($embalajes as $embalaje) {
            $embalaje->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
