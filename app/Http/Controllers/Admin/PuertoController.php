<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPuertoRequest;
use App\Http\Requests\StorePuertoRequest;
use App\Http\Requests\UpdatePuertoRequest;
use App\Models\Puerto;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PuertoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('puerto_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Puerto::query()->select(sprintf('%s.*', (new Puerto)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'puerto_show';
                $editGate      = 'puerto_edit';
                $deleteGate    = 'puerto_delete';
                $crudRoutePart = 'puertos';

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

        return view('admin.puertos.index');
    }

    public function create()
    {
        abort_if(Gate::denies('puerto_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.puertos.create');
    }

    public function store(StorePuertoRequest $request)
    {
        $puerto = Puerto::create($request->all());

        return redirect()->route('admin.puertos.index');
    }

    public function edit(Puerto $puerto)
    {
        abort_if(Gate::denies('puerto_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.puertos.edit', compact('puerto'));
    }

    public function update(UpdatePuertoRequest $request, Puerto $puerto)
    {
        $puerto->update($request->all());

        return redirect()->route('admin.puertos.index');
    }

    public function show(Puerto $puerto)
    {
        abort_if(Gate::denies('puerto_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.puertos.show', compact('puerto'));
    }

    public function destroy(Puerto $puerto)
    {
        abort_if(Gate::denies('puerto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $puerto->delete();

        return back();
    }

    public function massDestroy(MassDestroyPuertoRequest $request)
    {
        $puertos = Puerto::find(request('ids'));

        foreach ($puertos as $puerto) {
            $puerto->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
