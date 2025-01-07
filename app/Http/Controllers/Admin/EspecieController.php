<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEspecyRequest;
use App\Http\Requests\StoreEspecyRequest;
use App\Http\Requests\UpdateEspecyRequest;
use App\Models\Especy;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EspecieController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('especy_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Especy::query()->select(sprintf('%s.*', (new Especy)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'especy_show';
                $editGate      = 'especy_edit';
                $deleteGate    = 'especy_delete';
                $crudRoutePart = 'especies';

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
            $table->editColumn('id_pro_p_familias', function ($row) {
                return $row->id_pro_p_familias ? $row->id_pro_p_familias : '';
            });
            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.especies.index');
    }

    public function create()
    {
        abort_if(Gate::denies('especy_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.especies.create');
    }

    public function store(StoreEspecyRequest $request)
    {
        $especy = Especy::create($request->all());

        return redirect()->route('admin.especies.index');
    }

    public function edit(Especy $especy)
    {
        abort_if(Gate::denies('especy_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.especies.edit', compact('especy'));
    }

    public function update(UpdateEspecyRequest $request, Especy $especy)
    {
        $especy->update($request->all());

        return redirect()->route('admin.especies.index');
    }

    public function show(Especy $especy)
    {
        abort_if(Gate::denies('especy_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $especy->load('especieVariedads', 'especieEtiquetasXEspecies');

        return view('admin.especies.show', compact('especy'));
    }

    public function destroy(Especy $especy)
    {
        abort_if(Gate::denies('especy_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $especy->delete();

        return back();
    }

    public function massDestroy(MassDestroyEspecyRequest $request)
    {
        $especies = Especy::find(request('ids'));

        foreach ($especies as $especy) {
            $especy->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
