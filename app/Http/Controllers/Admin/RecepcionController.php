<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyRecepcionRequest;
use App\Http\Requests\StoreRecepcionRequest;
use App\Http\Requests\UpdateRecepcionRequest;
use App\Models\Productor;
use App\Models\Recepcion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RecepcionController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('recepcion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Recepcion::with(['productor'])->select(sprintf('%s.*', (new Recepcion)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'recepcion_show';
                $editGate      = 'recepcion_edit';
                $deleteGate    = 'recepcion_delete';
                $crudRoutePart = 'recepcions';

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

            $table->editColumn('productor.rut', function ($row) {
                return $row->productor ? (is_string($row->productor) ? $row->productor : $row->productor->rut) : '';
            });
            $table->editColumn('variedad', function ($row) {
                return $row->variedad ? $row->variedad : '';
            });
            $table->editColumn('total_kilos', function ($row) {
                return $row->total_kilos ? $row->total_kilos : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'productor']);

            return $table->make(true);
        }

        $productors = Productor::get();

        return view('admin.recepcions.index', compact('productors'));
    }

    public function create()
    {
        abort_if(Gate::denies('recepcion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.recepcions.create', compact('productors'));
    }

    public function store(StoreRecepcionRequest $request)
    {
        $recepcion = Recepcion::create($request->all());

        return redirect()->route('admin.recepcions.index');
    }

    public function edit(Recepcion $recepcion)
    {
        abort_if(Gate::denies('recepcion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $recepcion->load('productor');

        return view('admin.recepcions.edit', compact('productors', 'recepcion'));
    }

    public function update(UpdateRecepcionRequest $request, Recepcion $recepcion)
    {
        $recepcion->update($request->all());

        return redirect()->route('admin.recepcions.index');
    }

    public function show(Recepcion $recepcion)
    {
        abort_if(Gate::denies('recepcion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recepcion->load('productor');

        return view('admin.recepcions.show', compact('recepcion'));
    }

    public function destroy(Recepcion $recepcion)
    {
        abort_if(Gate::denies('recepcion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recepcion->delete();

        return back();
    }

    public function massDestroy(MassDestroyRecepcionRequest $request)
    {
        $recepcions = Recepcion::find(request('ids'));

        foreach ($recepcions as $recepcion) {
            $recepcion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
