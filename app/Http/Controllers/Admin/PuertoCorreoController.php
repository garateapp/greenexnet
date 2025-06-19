<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPuertoCorreoRequest;
use App\Http\Requests\StorePuertoCorreoRequest;
use App\Http\Requests\UpdatePuertoCorreoRequest;
use App\Models\Country;
use App\Models\Puerto;
use App\Models\PuertoCorreo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PuertoCorreoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('puerto_correo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PuertoCorreo::with(['puerto_embarque', 'pais'])->select(sprintf('%s.*', (new PuertoCorreo)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'puerto_correo_show';
                $editGate      = 'puerto_correo_edit';
                $deleteGate    = 'puerto_correo_delete';
                $crudRoutePart = 'puerto-correos';

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
            $table->addColumn('puerto_embarque_nombre', function ($row) {
                return $row->puerto_embarque ? $row->puerto_embarque->nombre : '';
            });

            $table->editColumn('emails', function ($row) {
                return $row->emails ? $row->emails : '';
            });
            $table->addColumn('pais_name', function ($row) {
                return $row->pais ? $row->pais->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'puerto_embarque', 'pais']);

            return $table->make(true);
        }

        $puertos   = Puerto::get();
        $countries = Country::get();

        return view('admin.puertoCorreos.index', compact('puertos', 'countries'));
    }

    public function create()
    {
        abort_if(Gate::denies('puerto_correo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $puerto_embarques = Puerto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pais = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.puertoCorreos.create', compact('pais', 'puerto_embarques'));
    }

    public function store(StorePuertoCorreoRequest $request)
    {
        $puertoCorreo = PuertoCorreo::create($request->all());

        return redirect()->route('admin.puerto-correos.index');
    }

    public function edit(PuertoCorreo $puertoCorreo)
    {
        abort_if(Gate::denies('puerto_correo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $puerto_embarques = Puerto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pais = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puertoCorreo->load('puerto_embarque', 'pais');

        return view('admin.puertoCorreos.edit', compact('pais', 'puertoCorreo', 'puerto_embarques'));
    }

    public function update(UpdatePuertoCorreoRequest $request, PuertoCorreo $puertoCorreo)
    {
        $puertoCorreo->update($request->all());

        return redirect()->route('admin.puerto-correos.index');
    }

    public function show(PuertoCorreo $puertoCorreo)
    {
        abort_if(Gate::denies('puerto_correo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $puertoCorreo->load('puerto_embarque', 'pais');

        return view('admin.puertoCorreos.show', compact('puertoCorreo'));
    }

    public function destroy(PuertoCorreo $puertoCorreo)
    {
        abort_if(Gate::denies('puerto_correo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $puertoCorreo->delete();

        return back();
    }

    public function massDestroy(MassDestroyPuertoCorreoRequest $request)
    {
        $puertoCorreos = PuertoCorreo::find(request('ids'));

        foreach ($puertoCorreos as $puertoCorreo) {
            $puertoCorreo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
