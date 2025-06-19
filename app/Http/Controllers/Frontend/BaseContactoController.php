<?php

namespace App\Http\Controllers\Frontend;

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

class BaseContactoController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('base_contacto_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $baseContactos = BaseContacto::with(['cliente'])->get();

        $base_recibidors = BaseRecibidor::get();

        return view('frontend.baseContactos.index', compact('baseContactos', 'base_recibidors'));
    }

    public function create()
    {
        abort_if(Gate::denies('base_contacto_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = BaseRecibidor::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.baseContactos.create', compact('clientes'));
    }

    public function store(StoreBaseContactoRequest $request)
    {
        $baseContacto = BaseContacto::create($request->all());

        return redirect()->route('frontend.base-contactos.index');
    }

    public function edit(BaseContacto $baseContacto)
    {
        abort_if(Gate::denies('base_contacto_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = BaseRecibidor::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $baseContacto->load('cliente');

        return view('frontend.baseContactos.edit', compact('baseContacto', 'clientes'));
    }

    public function update(UpdateBaseContactoRequest $request, BaseContacto $baseContacto)
    {
        $baseContacto->update($request->all());

        return redirect()->route('frontend.base-contactos.index');
    }

    public function show(BaseContacto $baseContacto)
    {
        abort_if(Gate::denies('base_contacto_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $baseContacto->load('cliente');

        return view('frontend.baseContactos.show', compact('baseContacto'));
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
