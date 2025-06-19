<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAgenteAduanaRequest;
use App\Http\Requests\StoreAgenteAduanaRequest;
use App\Http\Requests\UpdateAgenteAduanaRequest;
use App\Models\AgenteAduana;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AgenteAduanaController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('agente_aduana_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AgenteAduana::query()->select(sprintf('%s.*', (new AgenteAduana)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'agente_aduana_show';
                $editGate      = 'agente_aduana_edit';
                $deleteGate    = 'agente_aduana_delete';
                $crudRoutePart = 'agente-aduanas';

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
            $table->editColumn('rut', function ($row) {
                return $row->rut ? $row->rut : '';
            });
            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });
            $table->editColumn('direccion', function ($row) {
                return $row->direccion ? $row->direccion : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('telefono', function ($row) {
                return $row->telefono ? $row->telefono : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.agenteAduanas.index');
    }

    public function create()
    {
        abort_if(Gate::denies('agente_aduana_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.agenteAduanas.create');
    }

    public function store(StoreAgenteAduanaRequest $request)
    {
        $agenteAduana = AgenteAduana::create($request->all());

        return redirect()->route('admin.agente-aduanas.index');
    }

    public function edit(AgenteAduana $agenteAduana)
    {
        abort_if(Gate::denies('agente_aduana_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.agenteAduanas.edit', compact('agenteAduana'));
    }

    public function update(UpdateAgenteAduanaRequest $request, AgenteAduana $agenteAduana)
    {
        $agenteAduana->update($request->all());

        return redirect()->route('admin.agente-aduanas.index');
    }

    public function show(AgenteAduana $agenteAduana)
    {
        abort_if(Gate::denies('agente_aduana_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $agenteAduana->load('agenteAduanaInstructivoEmbarques');

        return view('admin.agenteAduanas.show', compact('agenteAduana'));
    }

    public function destroy(AgenteAduana $agenteAduana)
    {
        abort_if(Gate::denies('agente_aduana_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $agenteAduana->delete();

        return back();
    }

    public function massDestroy(MassDestroyAgenteAduanaRequest $request)
    {
        $agenteAduanas = AgenteAduana::find(request('ids'));

        foreach ($agenteAduanas as $agenteAduana) {
            $agenteAduana->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
