<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyBaseContactoRequest;
use App\Http\Requests\StoreBaseContactoRequest;
use App\Http\Requests\UpdateBaseContactoRequest;
use App\Models\BaseContacto;
use App\Models\BaseRecibidor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BaseContactoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('base_contacto_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = BaseContacto::with(['cliente'])->select(sprintf('%s.*', (new BaseContacto)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'base_contacto_show';
                $editGate      = 'base_contacto_edit';
                $deleteGate    = 'base_contacto_delete';
                $crudRoutePart = 'base-contactos';

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
            $table->addColumn('cliente_codigo', function ($row) {
                return $row->cliente ? $row->cliente->codigo : '';
            });

            $table->editColumn('tipoydestino', function ($row) {
                return $row->tipoydestino ? $row->tipoydestino : '';
            });
            $table->editColumn('consignee', function ($row) {
                return $row->consignee ? $row->consignee : '';
            });
            $table->editColumn('rut_recibidor', function ($row) {
                return $row->rut_recibidor ? $row->rut_recibidor : '';
            });
            $table->editColumn('direccion', function ($row) {
                return $row->direccion ? $row->direccion : '';
            });
            $table->editColumn('contacto', function ($row) {
                return $row->contacto ? $row->contacto : '';
            });
            $table->editColumn('telefono', function ($row) {
                return $row->telefono ? $row->telefono : '';
            });
            $table->editColumn('fax', function ($row) {
                return $row->fax ? $row->fax : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('notify', function ($row) {
                return $row->notify ? $row->notify : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'cliente']);

            return $table->make(true);
        }

        $base_recibidors = BaseRecibidor::get();

        return view('admin.baseContactos.index', compact('base_recibidors'));
    }

    public function create()
    {
        abort_if(Gate::denies('base_contacto_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = BaseRecibidor::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.baseContactos.create', compact('clientes'));
    }

    public function store(StoreBaseContactoRequest $request)
    {
        $baseContacto = BaseContacto::create($request->all());

        return redirect()->route('admin.base-contactos.index');
    }

    public function edit(BaseContacto $baseContacto)
    {
        abort_if(Gate::denies('base_contacto_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = BaseRecibidor::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $baseContacto->load('cliente');

        return view('admin.baseContactos.edit', compact('baseContacto', 'clientes'));
    }

    public function update(UpdateBaseContactoRequest $request, BaseContacto $baseContacto)
    {
        $baseContacto->update($request->all());

        return redirect()->route('admin.base-contactos.index');
    }

    public function show(BaseContacto $baseContacto)
    {
        abort_if(Gate::denies('base_contacto_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $baseContacto->load('cliente');

        return view('admin.baseContactos.show', compact('baseContacto'));
    }

    public function destroy(BaseContacto $baseContacto)
    {
        abort_if(Gate::denies('base_contacto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $baseContacto->delete();

        return back();
    }

    public function massDestroy(MassDestroyBaseContactoRequest $request)
    {
        $baseContactos = BaseContacto::find(request('ids'));

        foreach ($baseContactos as $baseContacto) {
            $baseContacto->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
