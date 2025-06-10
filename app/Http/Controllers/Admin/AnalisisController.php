<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAnalisiRequest;
use App\Http\Requests\StoreAnalisiRequest;
use App\Http\Requests\UpdateAnalisiRequest;
use App\Models\Analisi;
use App\Models\Productor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AnalisisController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('analisi_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Analisi::with(['productor'])->select(sprintf('%s.*', (new Analisi)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'analisi_show';
                $editGate      = 'analisi_edit';
                $deleteGate    = 'analisi_delete';
                $crudRoutePart = 'analisis';

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
            $table->addColumn('productor_rut', function ($row) {
                return $row->productor ? $row->productor->rut : '';
            });

            $table->editColumn('productor.nombre', function ($row) {
                return $row->productor ? (is_string($row->productor) ? $row->productor : $row->productor->nombre) : '';
            });
            $table->editColumn('temporada', function ($row) {
                return $row->temporada ? $row->temporada : '';
            });
            $table->editColumn('especie', function ($row) {
                return $row->especie ? $row->especie : '';
            });
            $table->editColumn('csg', function ($row) {
                return $row->csg ? $row->csg : '';
            });
            $table->editColumn('valor', function ($row) {
                return $row->valor ? $row->valor : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'productor']);

            return $table->make(true);
        }

        $productors = Productor::get();

        return view('admin.analisis.index', compact('productors'));
    }

    public function create()
    {
        abort_if(Gate::denies('analisi_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('rut', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.analisis.create', compact('productors'));
    }

    public function store(StoreAnalisiRequest $request)
    {
        $analisi = Analisi::create($request->all());

        return redirect()->route('admin.analisis.index');
    }

    public function edit(Analisi $analisi)
    {
        abort_if(Gate::denies('analisi_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('rut', 'id')->prepend(trans('global.pleaseSelect'), '');

        $analisi->load('productor');

        return view('admin.analisis.edit', compact('analisi', 'productors'));
    }

    public function update(UpdateAnalisiRequest $request, Analisi $analisi)
    {
        $analisi->update($request->all());

        return redirect()->route('admin.analisis.index');
    }

    public function show(Analisi $analisi)
    {
        abort_if(Gate::denies('analisi_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $analisi->load('productor');

        return view('admin.analisis.show', compact('analisi'));
    }

    public function destroy(Analisi $analisi)
    {
        abort_if(Gate::denies('analisi_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $analisi->delete();

        return back();
    }

    public function massDestroy(MassDestroyAnalisiRequest $request)
    {
        $analisis = Analisi::find(request('ids'));

        foreach ($analisis as $analisi) {
            $analisi->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
