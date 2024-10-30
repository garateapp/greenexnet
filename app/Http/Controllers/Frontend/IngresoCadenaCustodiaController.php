<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyIngresoCadenaCustodiumRequest;
use App\Http\Requests\StoreIngresoCadenaCustodiumRequest;
use App\Http\Requests\UpdateIngresoCadenaCustodiumRequest;
use App\Models\Bodega;
use App\Models\EstadosSaep;
use App\Models\IngresoCadenaCustodium;
use App\Models\MotivoSaep;
use App\Models\TipoBulto;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IngresoCadenaCustodiaController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ingreso_cadena_custodium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ingresoCadenaCustodia = IngresoCadenaCustodium::with(['tipo_bulto', 'estado', 'motivo', 'bodega'])->get();

        return view('frontend.ingresoCadenaCustodia.index', compact('ingresoCadenaCustodia'));
    }

    public function create()
    {
        abort_if(Gate::denies('ingreso_cadena_custodium_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipo_bultos = TipoBulto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = EstadosSaep::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $motivos = MotivoSaep::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bodegas = Bodega::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.ingresoCadenaCustodia.create', compact('bodegas', 'estados', 'motivos', 'tipo_bultos'));
    }

    public function store(StoreIngresoCadenaCustodiumRequest $request)
    {
        $ingresoCadenaCustodium = IngresoCadenaCustodium::create($request->all());

        return redirect()->route('frontend.ingreso-cadena-custodia.index');
    }

    public function edit(IngresoCadenaCustodium $ingresoCadenaCustodium)
    {
        abort_if(Gate::denies('ingreso_cadena_custodium_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipo_bultos = TipoBulto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = EstadosSaep::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $motivos = MotivoSaep::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bodegas = Bodega::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ingresoCadenaCustodium->load('tipo_bulto', 'estado', 'motivo', 'bodega');

        return view('frontend.ingresoCadenaCustodia.edit', compact('bodegas', 'estados', 'ingresoCadenaCustodium', 'motivos', 'tipo_bultos'));
    }

    public function update(UpdateIngresoCadenaCustodiumRequest $request, IngresoCadenaCustodium $ingresoCadenaCustodium)
    {
        $ingresoCadenaCustodium->update($request->all());

        return redirect()->route('frontend.ingreso-cadena-custodia.index');
    }

    public function show(IngresoCadenaCustodium $ingresoCadenaCustodium)
    {
        abort_if(Gate::denies('ingreso_cadena_custodium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ingresoCadenaCustodium->load('tipo_bulto', 'estado', 'motivo', 'bodega');

        return view('frontend.ingresoCadenaCustodia.show', compact('ingresoCadenaCustodium'));
    }

    public function destroy(IngresoCadenaCustodium $ingresoCadenaCustodium)
    {
        abort_if(Gate::denies('ingreso_cadena_custodium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ingresoCadenaCustodium->delete();

        return back();
    }

    public function massDestroy(MassDestroyIngresoCadenaCustodiumRequest $request)
    {
        $ingresoCadenaCustodia = IngresoCadenaCustodium::find(request('ids'));

        foreach ($ingresoCadenaCustodia as $ingresoCadenaCustodium) {
            $ingresoCadenaCustodium->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
