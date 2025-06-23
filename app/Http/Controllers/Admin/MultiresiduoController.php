<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyMultiresiduoRequest;
use App\Http\Requests\StoreMultiresiduoRequest;
use App\Http\Requests\UpdateMultiresiduoRequest;
use App\Models\Multiresiduo;
use App\Models\Productor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MultiresiduoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('multiresiduo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Multiresiduo::with(['productor'])->select(sprintf('%s.*', (new Multiresiduo)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'multiresiduo_show';
                $editGate      = 'multiresiduo_edit';
                $deleteGate    = 'multiresiduo_delete';
                $crudRoutePart = 'multiresiduos';

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

        return view('admin.multiresiduos.index', compact('productors'));
    }

    public function create()
    {
        abort_if(Gate::denies('multiresiduo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.multiresiduos.create', compact('productors'));
    }

    public function store(StoreMultiresiduoRequest $request)
    {
        $multiresiduo = Multiresiduo::create($request->all());

        return redirect()->route('admin.multiresiduos.index');
    }

    public function edit(Multiresiduo $multiresiduo)
    {
        abort_if(Gate::denies('multiresiduo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $multiresiduo->load('productor');

        return view('admin.multiresiduos.edit', compact('multiresiduo', 'productors'));
    }

    public function update(UpdateMultiresiduoRequest $request, Multiresiduo $multiresiduo)
    {
        $multiresiduo->update($request->all());

        return redirect()->route('admin.multiresiduos.index');
    }

    public function show(Multiresiduo $multiresiduo)
    {
        abort_if(Gate::denies('multiresiduo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $multiresiduo->load('productor');

        return view('admin.multiresiduos.show', compact('multiresiduo'));
    }

    public function destroy(Multiresiduo $multiresiduo)
    {
        abort_if(Gate::denies('multiresiduo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $multiresiduo->delete();

        return back();
    }

    public function massDestroy(MassDestroyMultiresiduoRequest $request)
    {
        $multiresiduos = Multiresiduo::find(request('ids'));

        foreach ($multiresiduos as $multiresiduo) {
            $multiresiduo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
