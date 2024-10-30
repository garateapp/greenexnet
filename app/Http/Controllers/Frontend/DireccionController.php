<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyDireccionRequest;
use App\Http\Requests\StoreDireccionRequest;
use App\Http\Requests\UpdateDireccionRequest;
use App\Models\Consignatario;
use App\Models\Direccion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DireccionController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('direccion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $direccions = Direccion::with(['consignatario'])->get();

        $consignatarios = Consignatario::get();

        return view('frontend.direccions.index', compact('consignatarios', 'direccions'));
    }

    public function create()
    {
        abort_if(Gate::denies('direccion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $consignatarios = Consignatario::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.direccions.create', compact('consignatarios'));
    }

    public function store(StoreDireccionRequest $request)
    {
        $direccion = Direccion::create($request->all());

        return redirect()->route('frontend.direccions.index');
    }

    public function edit(Direccion $direccion)
    {
        abort_if(Gate::denies('direccion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $consignatarios = Consignatario::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $direccion->load('consignatario');

        return view('frontend.direccions.edit', compact('consignatarios', 'direccion'));
    }

    public function update(UpdateDireccionRequest $request, Direccion $direccion)
    {
        $direccion->update($request->all());

        return redirect()->route('frontend.direccions.index');
    }

    public function show(Direccion $direccion)
    {
        abort_if(Gate::denies('direccion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $direccion->load('consignatario');

        return view('frontend.direccions.show', compact('direccion'));
    }

    public function destroy(Direccion $direccion)
    {
        abort_if(Gate::denies('direccion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $direccion->delete();

        return back();
    }

    public function massDestroy(MassDestroyDireccionRequest $request)
    {
        $direccions = Direccion::find(request('ids'));

        foreach ($direccions as $direccion) {
            $direccion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
