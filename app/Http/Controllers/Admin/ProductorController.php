<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyProductorRequest;
use App\Http\Requests\StoreProductorRequest;
use App\Http\Requests\UpdateProductorRequest;
use App\Models\Grupo;
use App\Models\Productor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProductorController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('productor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Productor::with(['grupo'])->select(sprintf('%s.*', (new Productor)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'productor_show';
                $editGate      = 'productor_edit';
                $deleteGate    = 'productor_delete';
                $crudRoutePart = 'productors';

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
            $table->editColumn('rut', function ($row) {
                return $row->rut ? $row->rut : '';
            });
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });
            $table->addColumn('grupo_nombre', function ($row) {
                return $row->grupo ? $row->grupo->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'grupo']);

            return $table->make(true);
        }

        $grupos = Grupo::get();

        return view('admin.productors.index', compact('grupos'));
    }

    public function create()
    {
        abort_if(Gate::denies('productor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $grupos = Grupo::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.productors.create', compact('grupos'));
    }

    public function store(StoreProductorRequest $request)
    {
        $productor = Productor::create($request->all());

        return redirect()->route('admin.productors.index');
    }

    public function edit(Productor $productor)
    {
        abort_if(Gate::denies('productor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $grupos = Grupo::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $productor->load('grupo');

        return view('admin.productors.edit', compact('grupos', 'productor'));
    }

    public function update(UpdateProductorRequest $request, Productor $productor)
    {
        $productor->update($request->all());

        return redirect()->route('admin.productors.index');
    }

    public function show(Productor $productor)
    {
        abort_if(Gate::denies('productor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productor->load('grupo', 'productorValorFletes', 'productorValorEnvases', 'productorAnticipos', 'productorRecepcions', 'productorProcesos');

        return view('admin.productors.show', compact('productor'));
    }

    public function destroy(Productor $productor)
    {
        abort_if(Gate::denies('productor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productor->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductorRequest $request)
    {
        $productors = Productor::find(request('ids'));

        foreach ($productors as $productor) {
            $productor->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
