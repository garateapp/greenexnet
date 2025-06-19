<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPesoEmbalajeRequest;
use App\Http\Requests\StorePesoEmbalajeRequest;
use App\Http\Requests\UpdatePesoEmbalajeRequest;
use App\Models\Especy;
use App\Models\Etiquetum;
use App\Models\PesoEmbalaje;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PesoEmbalajeController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('peso_embalaje_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PesoEmbalaje::with(['especie', 'etiqueta'])->select(sprintf('%s.*', (new PesoEmbalaje)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'peso_embalaje_show';
                $editGate      = 'peso_embalaje_edit';
                $deleteGate    = 'peso_embalaje_delete';
                $crudRoutePart = 'peso-embalajes';

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

            $table->addColumn('etiqueta_nombre', function ($row) {
                return $row->etiqueta ? $row->etiqueta->nombre : '';
            });

            $table->editColumn('embalajes', function ($row) {
                return $row->embalajes ? $row->embalajes : '';
            });
            $table->editColumn('peso_neto', function ($row) {
                return $row->peso_neto ? $row->peso_neto : '';
            });
            $table->editColumn('peso_bruto', function ($row) {
                return $row->peso_bruto ? $row->peso_bruto : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'especie', 'etiqueta']);

            return $table->make(true);
        }

        $especies = Especy::get();
        $etiqueta = Etiquetum::get();

        return view('admin.pesoEmbalajes.index', compact('especies', 'etiqueta'));
    }

    public function create()
    {
        abort_if(Gate::denies('peso_embalaje_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $especies = Especy::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $etiquetas = Etiquetum::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.pesoEmbalajes.create', compact('especies', 'etiquetas'));
    }

    public function store(StorePesoEmbalajeRequest $request)
    {
        $pesoEmbalaje = PesoEmbalaje::create($request->all());

        return redirect()->route('admin.peso-embalajes.index');
    }

    public function edit(PesoEmbalaje $pesoEmbalaje)
    {
        abort_if(Gate::denies('peso_embalaje_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $especies = Especy::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $etiquetas = Etiquetum::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pesoEmbalaje->load('especie', 'etiqueta');

        return view('admin.pesoEmbalajes.edit', compact('especies', 'etiquetas', 'pesoEmbalaje'));
    }

    public function update(UpdatePesoEmbalajeRequest $request, PesoEmbalaje $pesoEmbalaje)
    {
        $pesoEmbalaje->update($request->all());

        return redirect()->route('admin.peso-embalajes.index');
    }

    public function show(PesoEmbalaje $pesoEmbalaje)
    {
        abort_if(Gate::denies('peso_embalaje_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pesoEmbalaje->load('especie', 'etiqueta');

        return view('admin.pesoEmbalajes.show', compact('pesoEmbalaje'));
    }

    public function destroy(PesoEmbalaje $pesoEmbalaje)
    {
        abort_if(Gate::denies('peso_embalaje_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pesoEmbalaje->delete();

        return back();
    }

    public function massDestroy(MassDestroyPesoEmbalajeRequest $request)
    {
        $pesoEmbalajes = PesoEmbalaje::find(request('ids'));

        foreach ($pesoEmbalajes as $pesoEmbalaje) {
            $pesoEmbalaje->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
