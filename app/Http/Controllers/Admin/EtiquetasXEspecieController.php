<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEtiquetasXEspecyRequest;
use App\Http\Requests\StoreEtiquetasXEspecyRequest;
use App\Http\Requests\UpdateEtiquetasXEspecyRequest;
use App\Models\Especy;
use App\Models\EtiquetasXEspecy;
use App\Models\Etiquetum;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EtiquetasXEspecieController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('etiquetas_x_especy_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = EtiquetasXEspecy::with(['especie', 'etiqueta'])->select(sprintf('%s.*', (new EtiquetasXEspecy)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'etiquetas_x_especy_show';
                $editGate      = 'etiquetas_x_especy_edit';
                $deleteGate    = 'etiquetas_x_especy_delete';
                $crudRoutePart = 'etiquetas-x-especies';

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
            $table->addColumn('especie_nombre', function ($row) {
                return $row->especie ? $row->especie->nombre : '';
            });

            $table->editColumn('especie.nombre', function ($row) {
                return $row->especie ? (is_string($row->especie) ? $row->especie : $row->especie->nombre) : '';
            });
            $table->addColumn('etiqueta_nombre', function ($row) {
                return $row->etiqueta ? $row->etiqueta->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'especie', 'etiqueta']);

            return $table->make(true);
        }

        $especies = Especy::get();
        $etiqueta = Etiquetum::get();

        return view('admin.etiquetasXEspecies.index', compact('especies', 'etiqueta'));
    }

    public function create()
    {
        abort_if(Gate::denies('etiquetas_x_especy_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $especies = Especy::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $etiquetas = Etiquetum::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.etiquetasXEspecies.create', compact('especies', 'etiquetas'));
    }

    public function store(StoreEtiquetasXEspecyRequest $request)
    {
        $etiquetasXEspecy = EtiquetasXEspecy::create($request->all());

        return redirect()->route('admin.etiquetas-x-especies.index');
    }

    public function edit(EtiquetasXEspecy $etiquetasXEspecy)
    {
        abort_if(Gate::denies('etiquetas_x_especy_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $especies = Especy::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $etiquetas = Etiquetum::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $etiquetasXEspecy->load('especie', 'etiqueta');

        return view('admin.etiquetasXEspecies.edit', compact('especies', 'etiquetas', 'etiquetasXEspecy'));
    }

    public function update(UpdateEtiquetasXEspecyRequest $request, EtiquetasXEspecy $etiquetasXEspecy)
    {
        $etiquetasXEspecy->update($request->all());

        return redirect()->route('admin.etiquetas-x-especies.index');
    }

    public function show(EtiquetasXEspecy $etiquetasXEspecy)
    {
        abort_if(Gate::denies('etiquetas_x_especy_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $etiquetasXEspecy->load('especie', 'etiqueta');

        return view('admin.etiquetasXEspecies.show', compact('etiquetasXEspecy'));
    }

    public function destroy(EtiquetasXEspecy $etiquetasXEspecy)
    {
        abort_if(Gate::denies('etiquetas_x_especy_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $etiquetasXEspecy->delete();

        return back();
    }

    public function massDestroy(MassDestroyEtiquetasXEspecyRequest $request)
    {
        $etiquetasXEspecies = EtiquetasXEspecy::find(request('ids'));

        foreach ($etiquetasXEspecies as $etiquetasXEspecy) {
            $etiquetasXEspecy->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
