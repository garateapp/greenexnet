<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyValorEnvaseRequest;
use App\Http\Requests\StoreValorEnvaseRequest;
use App\Http\Requests\UpdateValorEnvaseRequest;
use App\Models\Productor;
use App\Models\ValorEnvase;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ValorEnvaseController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('valor_envase_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ValorEnvase::with(['productor'])->select(sprintf('%s.*', (new ValorEnvase)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'valor_envase_show';
                $editGate      = 'valor_envase_edit';
                $deleteGate    = 'valor_envase_delete';
                $crudRoutePart = 'valor-envases';

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
            $table->editColumn('valor', function ($row) {
                return $row->valor ? $row->valor : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'productor']);

            return $table->make(true);
        }

        $productors = Productor::get();

        return view('admin.valorEnvases.index', compact('productors'));
    }

    public function create()
    {
        abort_if(Gate::denies('valor_envase_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.valorEnvases.create', compact('productors'));
    }

    public function store(StoreValorEnvaseRequest $request)
    {
        $valorEnvase = ValorEnvase::create($request->all());

        return redirect()->route('admin.valor-envases.index');
    }

    public function edit(ValorEnvase $valorEnvase)
    {
        abort_if(Gate::denies('valor_envase_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $valorEnvase->load('productor');

        return view('admin.valorEnvases.edit', compact('productors', 'valorEnvase'));
    }

    public function update(UpdateValorEnvaseRequest $request, ValorEnvase $valorEnvase)
    {
        $valorEnvase->update($request->all());

        return redirect()->route('admin.valor-envases.index');
    }

    public function show(ValorEnvase $valorEnvase)
    {
        abort_if(Gate::denies('valor_envase_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $valorEnvase->load('productor');

        return view('admin.valorEnvases.show', compact('valorEnvase'));
    }

    public function destroy(ValorEnvase $valorEnvase)
    {
        abort_if(Gate::denies('valor_envase_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $valorEnvase->delete();

        return back();
    }

    public function massDestroy(MassDestroyValorEnvaseRequest $request)
    {
        $valorEnvases = ValorEnvase::find(request('ids'));

        foreach ($valorEnvases as $valorEnvase) {
            $valorEnvase->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
