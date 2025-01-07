<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTiposSeccionConversorRequest;
use App\Http\Requests\StoreTiposSeccionConversorRequest;
use App\Http\Requests\UpdateTiposSeccionConversorRequest;
use App\Models\TiposSeccionConversor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TiposSeccionConversorController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('tipos_seccion_conversor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TiposSeccionConversor::query()->select(sprintf('%s.*', (new TiposSeccionConversor)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'tipos_seccion_conversor_show';
                $editGate      = 'tipos_seccion_conversor_edit';
                $deleteGate    = 'tipos_seccion_conversor_delete';
                $crudRoutePart = 'tipos-seccion-conversors';

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
            $table->editColumn('eslistado', function ($row) {
                return $row->eslistado ? $row->eslistado : '';
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.tiposSeccionConversors.index');
    }

    public function create()
    {
        abort_if(Gate::denies('tipos_seccion_conversor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tiposSeccionConversors.create');
    }

    public function store(StoreTiposSeccionConversorRequest $request)
    {
        $tiposSeccionConversor = TiposSeccionConversor::create($request->all());

        return redirect()->route('admin.tipos-seccion-conversors.index');
    }

    public function edit(TiposSeccionConversor $tiposSeccionConversor)
    {
        abort_if(Gate::denies('tipos_seccion_conversor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tiposSeccionConversors.edit', compact('tiposSeccionConversor'));
    }

    public function update(UpdateTiposSeccionConversorRequest $request, TiposSeccionConversor $tiposSeccionConversor)
    {
       
        $tiposSeccionConversor->update($request->all());

        return redirect()->route('admin.tipos-seccion-conversors.index');
    }

    public function show(TiposSeccionConversor $tiposSeccionConversor)
    {
        abort_if(Gate::denies('tipos_seccion_conversor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tiposSeccionConversors.show', compact('tiposSeccionConversor'));
    }

    public function destroy(TiposSeccionConversor $tiposSeccionConversor)
    {
        abort_if(Gate::denies('tipos_seccion_conversor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tiposSeccionConversor->delete();

        return back();
    }

    public function massDestroy(MassDestroyTiposSeccionConversorRequest $request)
    {
        $tiposSeccionConversors = TiposSeccionConversor::find(request('ids'));

        foreach ($tiposSeccionConversors as $tiposSeccionConversor) {
            $tiposSeccionConversor->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
