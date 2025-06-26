<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyOtroscargoRequest;
use App\Http\Requests\StoreOtroscargoRequest;
use App\Http\Requests\UpdateOtroscargoRequest;
use App\Models\Otroscargo;
use App\Models\Productor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class OtroscargoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('otroscargo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Otroscargo::with(['productor'])->select(sprintf('%s.*', (new Otroscargo)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'otroscargo_show';
                $editGate      = 'otroscargo_edit';
                $deleteGate    = 'otroscargo_delete';
                $crudRoutePart = 'otroscargos';

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

        return view('admin.otroscargos.index', compact('productors'));
    }

    public function create()
    {
        abort_if(Gate::denies('otroscargo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.otroscargos.create', compact('productors'));
    }

    public function store(StoreOtroscargoRequest $request)
    {
        $otroscargo = Otroscargo::create($request->all());

        return redirect()->route('admin.otroscargos.index');
    }

    public function edit(Otroscargo $otroscargo)
    {
        abort_if(Gate::denies('otroscargo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $otroscargo->load('productor');

        return view('admin.otroscargos.edit', compact('otroscargo', 'productors'));
    }

    public function update(UpdateOtroscargoRequest $request, Otroscargo $otroscargo)
    {
        $otroscargo->update($request->all());

        return redirect()->route('admin.otroscargos.index');
    }

    public function show(Otroscargo $otroscargo)
    {
        abort_if(Gate::denies('otroscargo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $otroscargo->load('productor');

        return view('admin.otroscargos.show', compact('otroscargo'));
    }

    public function destroy(Otroscargo $otroscargo)
    {
        abort_if(Gate::denies('otroscargo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $otroscargo->delete();

        return back();
    }

    public function massDestroy(MassDestroyOtroscargoRequest $request)
    {
        $otroscargos = Otroscargo::find(request('ids'));

        foreach ($otroscargos as $otroscargo) {
            $otroscargo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
