<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyIngresoBultoRequest;
use App\Http\Requests\StoreIngresoBultoRequest;
use App\Http\Requests\UpdateIngresoBultoRequest;
use App\Models\Bodega;
use App\Models\EstadosSaep;
use App\Models\IngresoBulto;
use App\Models\MotivoSaep;
use App\Models\TipoBulto;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IngresoBultoController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ingreso_bulto_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ingresoBultos = IngresoBulto::with(['tipo_bulto', 'estado', 'motivo', 'bodega'])->get();

        return view('frontend.ingresoBultos.index', compact('ingresoBultos'));
    }

    public function create()
    {
        abort_if(Gate::denies('ingreso_bulto_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipo_bultos = TipoBulto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = EstadosSaep::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $motivos = MotivoSaep::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bodegas = Bodega::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.ingresoBultos.create', compact('bodegas', 'estados', 'motivos', 'tipo_bultos'));
    }

    public function store(StoreIngresoBultoRequest $request)
    {
        $ingresoBulto = IngresoBulto::create($request->all());

        return redirect()->route('frontend.ingreso-bultos.index');
    }

    public function edit(IngresoBulto $ingresoBulto)
    {
        abort_if(Gate::denies('ingreso_bulto_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipo_bultos = TipoBulto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = EstadosSaep::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $motivos = MotivoSaep::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bodegas = Bodega::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ingresoBulto->load('tipo_bulto', 'estado', 'motivo', 'bodega');

        return view('frontend.ingresoBultos.edit', compact('bodegas', 'estados', 'ingresoBulto', 'motivos', 'tipo_bultos'));
    }

    public function update(UpdateIngresoBultoRequest $request, IngresoBulto $ingresoBulto)
    {
        $ingresoBulto->update($request->all());

        return redirect()->route('frontend.ingreso-bultos.index');
    }

    public function show(IngresoBulto $ingresoBulto)
    {
        abort_if(Gate::denies('ingreso_bulto_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ingresoBulto->load('tipo_bulto', 'estado', 'motivo', 'bodega');

        return view('frontend.ingresoBultos.show', compact('ingresoBulto'));
    }

    public function destroy(IngresoBulto $ingresoBulto)
    {
        abort_if(Gate::denies('ingreso_bulto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ingresoBulto->delete();

        return back();
    }

    public function massDestroy(MassDestroyIngresoBultoRequest $request)
    {
        $ingresoBultos = IngresoBulto::find(request('ids'));

        foreach ($ingresoBultos as $ingresoBulto) {
            $ingresoBulto->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
