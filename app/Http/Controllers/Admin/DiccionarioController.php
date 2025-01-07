<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyDiccionarioRequest;
use App\Http\Requests\StoreDiccionarioRequest;
use App\Http\Requests\UpdateDiccionarioRequest;
use App\Models\Diccionario;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DiccionarioController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('diccionario_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Diccionario::query()->select(sprintf('%s.*', (new Diccionario)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'diccionario_show';
                $editGate      = 'diccionario_edit';
                $deleteGate    = 'diccionario_delete';
                $crudRoutePart = 'diccionarios';

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
            $table->editColumn('tipo', function ($row) {
                return $row->tipo ? Diccionario::TIPO_SELECT[$row->tipo] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.diccionarios.index');
    }

    public function create()
    {
        abort_if(Gate::denies('diccionario_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.diccionarios.create');
    }

    public function store(StoreDiccionarioRequest $request)
    {
        $diccionario = Diccionario::create($request->all());

        return redirect()->route('admin.diccionarios.index');
    }

    public function edit(Diccionario $diccionario)
    {
        abort_if(Gate::denies('diccionario_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.diccionarios.edit', compact('diccionario'));
    }

    public function update(UpdateDiccionarioRequest $request, Diccionario $diccionario)
    {
        $diccionario->update($request->all());

        return redirect()->route('admin.diccionarios.index');
    }

    public function show(Diccionario $diccionario)
    {
        abort_if(Gate::denies('diccionario_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.diccionarios.show', compact('diccionario'));
    }

    public function destroy(Diccionario $diccionario)
    {
        abort_if(Gate::denies('diccionario_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $diccionario->delete();

        return back();
    }

    public function massDestroy(MassDestroyDiccionarioRequest $request)
    {
        $diccionarios = Diccionario::find(request('ids'));

        foreach ($diccionarios as $diccionario) {
            $diccionario->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
