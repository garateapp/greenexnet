<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyFamiliumRequest;
use App\Http\Requests\StoreFamiliumRequest;
use App\Http\Requests\UpdateFamiliumRequest;
use App\Models\Familium;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FamiliaController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('familium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Familium::query()->select(sprintf('%s.*', (new Familium)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'familium_show';
                $editGate      = 'familium_edit';
                $deleteGate    = 'familium_delete';
                $crudRoutePart = 'familia';

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
            $table->editColumn('cap', function ($row) {
                return $row->cap ? $row->cap : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.familia.index');
    }

    public function create()
    {
        abort_if(Gate::denies('familium_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.familia.create');
    }

    public function store(StoreFamiliumRequest $request)
    {
        $familium = Familium::create($request->all());

        return redirect()->route('admin.familia.index');
    }

    public function edit(Familium $familium)
    {
        abort_if(Gate::denies('familium_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.familia.edit', compact('familium'));
    }

    public function update(UpdateFamiliumRequest $request, Familium $familium)
    {
        $familium->update($request->all());

        return redirect()->route('admin.familia.index');
    }

    public function show(Familium $familium)
    {
        abort_if(Gate::denies('familium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.familia.show', compact('familium'));
    }

    public function destroy(Familium $familium)
    {
        abort_if(Gate::denies('familium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $familium->delete();

        return back();
    }

    public function massDestroy(MassDestroyFamiliumRequest $request)
    {
        $familia = Familium::find(request('ids'));

        foreach ($familia as $familium) {
            $familium->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
