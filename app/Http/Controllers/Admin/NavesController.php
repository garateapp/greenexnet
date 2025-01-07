<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyNafeRequest;
use App\Http\Requests\StoreNafeRequest;
use App\Http\Requests\UpdateNafeRequest;
use App\Models\Nafe;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class NavesController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('nafe_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Nafe::query()->select(sprintf('%s.*', (new Nafe)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'nafe_show';
                $editGate      = 'nafe_edit';
                $deleteGate    = 'nafe_delete';
                $crudRoutePart = 'naves';

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
            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.naves.index');
    }

    public function create()
    {
        abort_if(Gate::denies('nafe_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.naves.create');
    }

    public function store(StoreNafeRequest $request)
    {
        $nafe = Nafe::create($request->all());

        return redirect()->route('admin.naves.index');
    }

    public function edit(Nafe $nafe)
    {
        abort_if(Gate::denies('nafe_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.naves.edit', compact('nafe'));
    }

    public function update(UpdateNafeRequest $request, Nafe $nafe)
    {
        $nafe->update($request->all());

        return redirect()->route('admin.naves.index');
    }

    public function show(Nafe $nafe)
    {
        abort_if(Gate::denies('nafe_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.naves.show', compact('nafe'));
    }

    public function destroy(Nafe $nafe)
    {
        abort_if(Gate::denies('nafe_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nafe->delete();

        return back();
    }

    public function massDestroy(MassDestroyNafeRequest $request)
    {
        $naves = Nafe::find(request('ids'));

        foreach ($naves as $nafe) {
            $nafe->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
