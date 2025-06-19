<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyChoferRequest;
use App\Http\Requests\StoreChoferRequest;
use App\Http\Requests\UpdateChoferRequest;
use App\Models\Chofer;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ChoferController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('chofer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Chofer::query()->select(sprintf('%s.*', (new Chofer)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'chofer_show';
                $editGate      = 'chofer_edit';
                $deleteGate    = 'chofer_delete';
                $crudRoutePart = 'chofers';

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
            $table->editColumn('telefono', function ($row) {
                return $row->telefono ? $row->telefono : '';
            });
            $table->editColumn('patente', function ($row) {
                return $row->patente ? $row->patente : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.chofers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('chofer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.chofers.create');
    }

    public function store(StoreChoferRequest $request)
    {
        $chofer = Chofer::create($request->all());

        return redirect()->route('admin.chofers.index');
    }

    public function edit(Chofer $chofer)
    {
        abort_if(Gate::denies('chofer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.chofers.edit', compact('chofer'));
    }

    public function update(UpdateChoferRequest $request, Chofer $chofer)
    {
        $chofer->update($request->all());

        return redirect()->route('admin.chofers.index');
    }

    public function show(Chofer $chofer)
    {
        abort_if(Gate::denies('chofer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $chofer->load('conductorInstructivoEmbarques');

        return view('admin.chofers.show', compact('chofer'));
    }

    public function destroy(Chofer $chofer)
    {
        abort_if(Gate::denies('chofer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $chofer->delete();

        return back();
    }

    public function massDestroy(MassDestroyChoferRequest $request)
    {
        $chofers = Chofer::find(request('ids'));

        foreach ($chofers as $chofer) {
            $chofer->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function getchofer(){
        $chofer=Chofer::all();
        return response()->json($chofer);
    }
    public function guardachofer(Request $request)
    {
       
        $chof=Chofer::create([
            'nombre'=>$request->conductor_nombre,
            'rut'=>'',
            'telefono'=>'',
            'patente'=>'',
        ]);
        $chofer=Chofer::all();
        return response()->json($chofer);
    }
}
