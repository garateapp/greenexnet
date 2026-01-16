<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPoliticaCotizacionRequest;
use App\Http\Requests\StorePoliticaCotizacionRequest;
use App\Http\Requests\UpdatePoliticaCotizacionRequest;
use App\Models\PoliticaCotizacion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PoliticaCotizacionesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('politica_cotizacion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PoliticaCotizacion::query()->select(sprintf('%s.*', (new PoliticaCotizacion)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'politica_cotizacion_show';
                $editGate      = 'politica_cotizacion_edit';
                $deleteGate    = 'politica_cotizacion_delete';
                $crudRoutePart = 'politica-cotizaciones';

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
            $table->editColumn('monto_min', function ($row) {
                return $row->monto_min ? $row->monto_min : '';
            });
            $table->editColumn('monto_max', function ($row) {
                return $row->monto_max ? $row->monto_max : '';
            });
            $table->editColumn('cotizaciones_requeridas', function ($row) {
                return $row->cotizaciones_requeridas ? $row->cotizaciones_requeridas : '';
            });
            $table->editColumn('activo', function ($row) {
                return $row->activo ? 'Si' : 'No';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.politicaCotizaciones.index');
    }

    public function create()
    {
        abort_if(Gate::denies('politica_cotizacion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.politicaCotizaciones.create');
    }

    public function store(StorePoliticaCotizacionRequest $request)
    {
        PoliticaCotizacion::create($request->all());

        return redirect()->route('admin.politica-cotizaciones.index');
    }

    public function edit(PoliticaCotizacion $politicaCotizacion)
    {
        abort_if(Gate::denies('politica_cotizacion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.politicaCotizaciones.edit', compact('politicaCotizacion'));
    }

    public function update(UpdatePoliticaCotizacionRequest $request, PoliticaCotizacion $politicaCotizacion)
    {
        $politicaCotizacion->update($request->all());

        return redirect()->route('admin.politica-cotizaciones.index');
    }

    public function show(PoliticaCotizacion $politicaCotizacion)
    {
        abort_if(Gate::denies('politica_cotizacion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.politicaCotizaciones.show', compact('politicaCotizacion'));
    }

    public function destroy(PoliticaCotizacion $politicaCotizacion)
    {
        abort_if(Gate::denies('politica_cotizacion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $politicaCotizacion->delete();

        return back();
    }

    public function massDestroy(MassDestroyPoliticaCotizacionRequest $request)
    {
        $politicas = PoliticaCotizacion::find(request('ids'));

        foreach ($politicas as $politica) {
            $politica->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
