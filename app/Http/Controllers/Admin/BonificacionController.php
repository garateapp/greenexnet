<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyBonificacionRequest;
use App\Http\Requests\StoreBonificacionRequest;
use App\Http\Requests\UpdateBonificacionRequest;
use App\Models\Bonificacion;
use App\Models\Productor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BonificacionController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('bonificacion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Bonificacion::with(['productor'])->select(sprintf('%s.*', (new Bonificacion)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'bonificacion_show';
                $editGate      = 'bonificacion_edit';
                $deleteGate    = 'bonificacion_delete';
                $crudRoutePart = 'bonificacions';

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
            $table->addColumn('productor_nombre', function ($row) {
                return $row->productor ? $row->productor->nombre : '';
            });

            $table->editColumn('valor', function ($row) {
                return $row->valor ? $row->valor : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'productor']);

            return $table->make(true);
        }

        $productors = Productor::get();

        return view('admin.bonificacions.index', compact('productors'));
    }

    public function create()
    {
        abort_if(Gate::denies('bonificacion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.bonificacions.create', compact('productors'));
    }

    public function store(StoreBonificacionRequest $request)
    {
        $bonificacion = Bonificacion::create($request->all());

        return redirect()->route('admin.bonificacions.index');
    }

    public function edit(Bonificacion $bonificacion)
    {
        abort_if(Gate::denies('bonificacion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bonificacion->load('productor');

        return view('admin.bonificacions.edit', compact('bonificacion', 'productors'));
    }

    public function update(UpdateBonificacionRequest $request, Bonificacion $bonificacion)
    {
        $bonificacion->update($request->all());

        return redirect()->route('admin.bonificacions.index');
    }

    public function show(Bonificacion $bonificacion)
    {
        abort_if(Gate::denies('bonificacion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bonificacion->load('productor');

        return view('admin.bonificacions.show', compact('bonificacion'));
    }

    public function destroy(Bonificacion $bonificacion)
    {
        abort_if(Gate::denies('bonificacion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bonificacion->delete();

        return back();
    }

    public function massDestroy(MassDestroyBonificacionRequest $request)
    {
        $bonificacions = Bonificacion::find(request('ids'));

        foreach ($bonificacions as $bonificacion) {
            $bonificacion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
