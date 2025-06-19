<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTipofleteRequest;
use App\Http\Requests\StoreTipofleteRequest;
use App\Http\Requests\UpdateTipofleteRequest;
use App\Models\Tipoflete;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TipofleteController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('tipoflete_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Tipoflete::query()->select(sprintf('%s.*', (new Tipoflete)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'tipoflete_show';
                $editGate      = 'tipoflete_edit';
                $deleteGate    = 'tipoflete_delete';
                $crudRoutePart = 'tipofletes';

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
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.tipofletes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('tipoflete_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tipofletes.create');
    }

    public function store(StoreTipofleteRequest $request)
    {
        $tipoflete = Tipoflete::create($request->all());

        return redirect()->route('admin.tipofletes.index');
    }

    public function edit(Tipoflete $tipoflete)
    {
        abort_if(Gate::denies('tipoflete_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tipofletes.edit', compact('tipoflete'));
    }

    public function update(UpdateTipofleteRequest $request, Tipoflete $tipoflete)
    {
        $tipoflete->update($request->all());

        return redirect()->route('admin.tipofletes.index');
    }

    public function show(Tipoflete $tipoflete)
    {
        abort_if(Gate::denies('tipoflete_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tipofletes.show', compact('tipoflete'));
    }

    public function destroy(Tipoflete $tipoflete)
    {
        abort_if(Gate::denies('tipoflete_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoflete->delete();

        return back();
    }

    public function massDestroy(MassDestroyTipofleteRequest $request)
    {
        $tipofletes = Tipoflete::find(request('ids'));

        foreach ($tipofletes as $tipoflete) {
            $tipoflete->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
