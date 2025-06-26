<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyInteresAnticipoRequest;
use App\Http\Requests\StoreInteresAnticipoRequest;
use App\Http\Requests\UpdateInteresAnticipoRequest;
use App\Models\Productor;
use App\Models\InteresAnticipo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class InteresAnticipoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('interes_anticipo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = InteresAnticipo::with(['productor'])->select(sprintf('%s.*', (new InteresAnticipo)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'interes_anticipo_show';
                $editGate      = 'interes_anticipo_edit';
                $deleteGate    = 'interes_anticipo_delete';
                $crudRoutePart = 'interes-anticipos';

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
            $table->addColumn('anticipo_fecha_documento', function ($row) {
                return $row->anticipo ? $row->anticipo->fecha_documento : '';
            });

            $table->editColumn('productor.nombre', function ($row) {
                return $row->anticipo ? (is_string($row->anticipo) ? $row->anticipo : $row->anticipo->valor) : '';
            });
            
            $table->editColumn('valor', function ($row) {
                return $row->valor ? $row->valor : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'anticipo']);

            return $table->make(true);
        }

        $productors = Productor::get();

        return view('admin.interesAnticipos.index', compact('productors'));
    }

    public function create()
    {
        abort_if(Gate::denies('interes_anticipo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $anticipos = Anticipo::pluck('fecha_documento', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.interesAnticipos.create', compact('anticipos'));
    }

    public function store(StoreInteresAnticipoRequest $request)
    {
        $interesAnticipo = InteresAnticipo::create($request->all());

        return redirect()->route('admin.interes-anticipos.index');
    }

    public function edit(InteresAnticipo $interesAnticipo)
    {
        abort_if(Gate::denies('interes_anticipo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $anticipos = Anticipo::pluck('fecha_documento', 'id')->prepend(trans('global.pleaseSelect'), '');

        $interesAnticipo->load('productor');

        return view('admin.interesAnticipos.edit', compact('anticipos', 'interesAnticipo'));
    }

    public function update(UpdateInteresAnticipoRequest $request, InteresAnticipo $interesAnticipo)
    {
        $interesAnticipo->update($request->all());

        return redirect()->route('admin.interes-anticipos.index');
    }

    public function show(InteresAnticipo $interesAnticipo)
    {
        abort_if(Gate::denies('interes_anticipo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $interesAnticipo->load('anticipo');

        return view('admin.interesAnticipos.show', compact('interesAnticipo'));
    }

    public function destroy(InteresAnticipo $interesAnticipo)
    {
        abort_if(Gate::denies('interes_anticipo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $interesAnticipo->delete();

        return back();
    }

    public function massDestroy(MassDestroyInteresAnticipoRequest $request)
    {
        $interesAnticipos = InteresAnticipo::find(request('ids'));

        foreach ($interesAnticipos as $interesAnticipo) {
            $interesAnticipo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
