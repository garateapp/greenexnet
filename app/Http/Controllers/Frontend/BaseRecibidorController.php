<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyBaseRecibidorRequest;
use App\Http\Requests\StoreBaseRecibidorRequest;
use App\Http\Requests\UpdateBaseRecibidorRequest;
use App\Models\BaseRecibidor;
use App\Models\ClientesComex;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseRecibidorController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('base_recibidor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $baseRecibidors = BaseRecibidor::with(['cliente'])->get();

        $clientes_comexes = ClientesComex::get();

        return view('frontend.baseRecibidors.index', compact('baseRecibidors', 'clientes_comexes'));
    }

    public function create()
    {
        abort_if(Gate::denies('base_recibidor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = ClientesComex::pluck('nombre_fantasia', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.baseRecibidors.create', compact('clientes'));
    }

    public function store(StoreBaseRecibidorRequest $request)
    {
        $baseRecibidor = BaseRecibidor::create($request->all());

        return redirect()->route('frontend.base-recibidors.index');
    }

    public function edit(BaseRecibidor $baseRecibidor)
    {
        abort_if(Gate::denies('base_recibidor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = ClientesComex::pluck('nombre_fantasia', 'id')->prepend(trans('global.pleaseSelect'), '');

        $baseRecibidor->load('cliente');

        return view('frontend.baseRecibidors.edit', compact('baseRecibidor', 'clientes'));
    }

    public function update(UpdateBaseRecibidorRequest $request, BaseRecibidor $baseRecibidor)
    {
        $baseRecibidor->update($request->all());

        return redirect()->route('frontend.base-recibidors.index');
    }

    public function show(BaseRecibidor $baseRecibidor)
    {
        abort_if(Gate::denies('base_recibidor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $baseRecibidor->load('cliente');

        return view('frontend.baseRecibidors.show', compact('baseRecibidor'));
    }

    public function destroy(BaseRecibidor $baseRecibidor)
    {
        abort_if(Gate::denies('base_recibidor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $baseRecibidor->delete();

        return back();
    }

    public function massDestroy(MassDestroyBaseRecibidorRequest $request)
    {
        $baseRecibidors = BaseRecibidor::find(request('ids'));

        foreach ($baseRecibidors as $baseRecibidor) {
            $baseRecibidor->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
