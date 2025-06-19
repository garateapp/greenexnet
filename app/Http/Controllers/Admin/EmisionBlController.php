<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEmisionBlRequest;
use App\Http\Requests\StoreEmisionBlRequest;
use App\Http\Requests\UpdateEmisionBlRequest;
use App\Models\EmisionBl;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EmisionBlController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('emision_bl_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = EmisionBl::query()->select(sprintf('%s.*', (new EmisionBl)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'emision_bl_show';
                $editGate      = 'emision_bl_edit';
                $deleteGate    = 'emision_bl_delete';
                $crudRoutePart = 'emision-bls';

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

        return view('admin.emisionBls.index');
    }

    public function create()
    {
        abort_if(Gate::denies('emision_bl_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.emisionBls.create');
    }

    public function store(StoreEmisionBlRequest $request)
    {
        $emisionBl = EmisionBl::create($request->all());

        return redirect()->route('admin.emision-bls.index');
    }

    public function edit(EmisionBl $emisionBl)
    {
        abort_if(Gate::denies('emision_bl_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.emisionBls.edit', compact('emisionBl'));
    }

    public function update(UpdateEmisionBlRequest $request, EmisionBl $emisionBl)
    {
        $emisionBl->update($request->all());

        return redirect()->route('admin.emision-bls.index');
    }

    public function show(EmisionBl $emisionBl)
    {
        abort_if(Gate::denies('emision_bl_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.emisionBls.show', compact('emisionBl'));
    }

    public function destroy(EmisionBl $emisionBl)
    {
        abort_if(Gate::denies('emision_bl_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisionBl->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmisionBlRequest $request)
    {
        $emisionBls = EmisionBl::find(request('ids'));

        foreach ($emisionBls as $emisionBl) {
            $emisionBl->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
