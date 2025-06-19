<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyNavieraRequest;
use App\Http\Requests\StoreNavieraRequest;
use App\Http\Requests\UpdateNavieraRequest;
use App\Models\Naviera;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class NavieraController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('naviera_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Naviera::query()->select(sprintf('%s.*', (new Naviera)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'naviera_show';
                $editGate      = 'naviera_edit';
                $deleteGate    = 'naviera_delete';
                $crudRoutePart = 'navieras';

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

        return view('admin.navieras.index');
    }

    public function create()
    {
        abort_if(Gate::denies('naviera_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.navieras.create');
    }

    public function store(StoreNavieraRequest $request)
    {
        $naviera = Naviera::create($request->all());

        return redirect()->route('admin.navieras.index');
    }

    public function edit(Naviera $naviera)
    {
        abort_if(Gate::denies('naviera_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.navieras.edit', compact('naviera'));
    }

    public function update(UpdateNavieraRequest $request, Naviera $naviera)
    {
        $naviera->update($request->all());

        return redirect()->route('admin.navieras.index');
    }

    public function show(Naviera $naviera)
    {
        abort_if(Gate::denies('naviera_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $naviera->load('navieraInstructivoEmbarques');

        return view('admin.navieras.show', compact('naviera'));
    }

    public function destroy(Naviera $naviera)
    {
        abort_if(Gate::denies('naviera_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $naviera->delete();

        return back();
    }

    public function massDestroy(MassDestroyNavieraRequest $request)
    {
        $navieras = Naviera::find(request('ids'));

        foreach ($navieras as $naviera) {
            $naviera->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
