<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEtiquetumRequest;
use App\Http\Requests\StoreEtiquetumRequest;
use App\Http\Requests\UpdateEtiquetumRequest;
use App\Models\Etiquetum;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EtiquetaController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('etiquetum_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Etiquetum::query()->select(sprintf('%s.*', (new Etiquetum)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'etiquetum_show';
                $editGate      = 'etiquetum_edit';
                $deleteGate    = 'etiquetum_delete';
                $crudRoutePart = 'etiqueta';

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

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.etiqueta.index');
    }

    public function create()
    {
        abort_if(Gate::denies('etiquetum_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.etiqueta.create');
    }

    public function store(StoreEtiquetumRequest $request)
    {
        $etiquetum = Etiquetum::create($request->all());

        return redirect()->route('admin.etiqueta.index');
    }

    public function edit(Etiquetum $etiquetum)
    {
        abort_if(Gate::denies('etiquetum_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.etiqueta.edit', compact('etiquetum'));
    }

    public function update(UpdateEtiquetumRequest $request, Etiquetum $etiquetum)
    {
        $etiquetum->update($request->all());

        return redirect()->route('admin.etiqueta.index');
    }

    public function show(Etiquetum $etiquetum)
    {
        abort_if(Gate::denies('etiquetum_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $etiquetum->load('etiquetaEtiquetasXEspecies');

        return view('admin.etiqueta.show', compact('etiquetum'));
    }

    public function destroy(Etiquetum $etiquetum)
    {
        abort_if(Gate::denies('etiquetum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $etiquetum->delete();

        return back();
    }

    public function massDestroy(MassDestroyEtiquetumRequest $request)
    {
        $etiqueta = Etiquetum::find(request('ids'));

        foreach ($etiqueta as $etiquetum) {
            $etiquetum->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
