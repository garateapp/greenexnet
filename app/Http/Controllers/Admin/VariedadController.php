<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyVariedadRequest;
use App\Http\Requests\StoreVariedadRequest;
use App\Http\Requests\UpdateVariedadRequest;
use App\Models\Especy;
use App\Models\Variedad;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class VariedadController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('variedad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Variedad::with(['especie'])->select(sprintf('%s.*', (new Variedad)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'variedad_show';
                $editGate      = 'variedad_edit';
                $deleteGate    = 'variedad_delete';
                $crudRoutePart = 'variedads';

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
            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });
            $table->addColumn('especie_nombre', function ($row) {
                return $row->especie ? $row->especie->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'especie']);

            return $table->make(true);
        }

        $especies = Especy::get();

        return view('admin.variedads.index', compact('especies'));
    }

    public function create()
    {
        abort_if(Gate::denies('variedad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $especies = Especy::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.variedads.create', compact('especies'));
    }

    public function store(StoreVariedadRequest $request)
    {
        $variedad = Variedad::create($request->all());

        return redirect()->route('admin.variedads.index');
    }

    public function edit(Variedad $variedad)
    {
        abort_if(Gate::denies('variedad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $especies = Especy::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $variedad->load('especie');

        return view('admin.variedads.edit', compact('especies', 'variedad'));
    }

    public function update(UpdateVariedadRequest $request, Variedad $variedad)
    {
        $variedad->update($request->all());

        return redirect()->route('admin.variedads.index');
    }

    public function show(Variedad $variedad)
    {
        abort_if(Gate::denies('variedad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $variedad->load('especie');

        return view('admin.variedads.show', compact('variedad'));
    }

    public function destroy(Variedad $variedad)
    {
        abort_if(Gate::denies('variedad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $variedad->delete();

        return back();
    }

    public function massDestroy(MassDestroyVariedadRequest $request)
    {
        $variedads = Variedad::find(request('ids'));

        foreach ($variedads as $variedad) {
            $variedad->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
