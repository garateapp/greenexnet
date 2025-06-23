<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyOtroCobroRequest;
use App\Http\Requests\StoreOtroCobroRequest;
use App\Http\Requests\UpdateOtroCobroRequest;
use App\Models\OtroCobro;
use App\Models\Productor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class OtroCobroController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('otro_cobro_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = OtroCobro::with(['productor'])->select(sprintf('%s.*', (new OtroCobro)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'otro_cobro_show';
                $editGate      = 'otro_cobro_edit';
                $deleteGate    = 'otro_cobro_delete';
                $crudRoutePart = 'otro-cobros';

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
            $table->addColumn('productor_nombre', function ($row) {
                return $row->productor ? $row->productor->nombre : '';
            });

            $table->editColumn('valor', function ($row) {
                return $row->valor ? $row->valor : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'productor']);

            return $table->make(true);
        }

        $productors = Productor::get();

        return view('admin.otroCobros.index', compact('productors'));
    }

    public function create()
    {
        abort_if(Gate::denies('otro_cobro_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.otroCobros.create', compact('productors'));
    }

    public function store(StoreOtroCobroRequest $request)
    {
        $otroCobro = OtroCobro::create($request->all());

        return redirect()->route('admin.otro-cobros.index');
    }

    public function edit(OtroCobro $otroCobro)
    {
        abort_if(Gate::denies('otro_cobro_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $otroCobro->load('productor');

        return view('admin.otroCobros.edit', compact('otroCobro', 'productors'));
    }

    public function update(UpdateOtroCobroRequest $request, OtroCobro $otroCobro)
    {
        $otroCobro->update($request->all());

        return redirect()->route('admin.otro-cobros.index');
    }

    public function show(OtroCobro $otroCobro)
    {
        abort_if(Gate::denies('otro_cobro_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $otroCobro->load('productor');

        return view('admin.otroCobros.show', compact('otroCobro'));
    }

    public function destroy(OtroCobro $otroCobro)
    {
        abort_if(Gate::denies('otro_cobro_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $otroCobro->delete();

        return back();
    }

    public function massDestroy(MassDestroyOtroCobroRequest $request)
    {
        $otroCobros = OtroCobro::find(request('ids'));

        foreach ($otroCobros as $otroCobro) {
            $otroCobro->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
