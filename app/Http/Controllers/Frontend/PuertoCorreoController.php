<?php

namespace App\Http\Controllers\Frontend;

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

class PuertoCorreoController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('puerto_correo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $puertoCorreos = PuertoCorreo::with(['puerto_embarque', 'pais'])->get();

        $puertos = Puerto::get();

        $countries = Country::get();

        return view('frontend.puertoCorreos.index', compact('countries', 'puertoCorreos', 'puertos'));
    }

    public function create()
    {
        abort_if(Gate::denies('puerto_correo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $puerto_embarques = Puerto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pais = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.puertoCorreos.create', compact('pais', 'puerto_embarques'));
    }

    public function store(StorePuertoCorreoRequest $request)
    {
        $puertoCorreo = PuertoCorreo::create($request->all());

        return redirect()->route('frontend.puerto-correos.index');
    }

    public function edit(PuertoCorreo $puertoCorreo)
    {
        abort_if(Gate::denies('puerto_correo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $puerto_embarques = Puerto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pais = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puertoCorreo->load('puerto_embarque', 'pais');

        return view('frontend.puertoCorreos.edit', compact('pais', 'puertoCorreo', 'puerto_embarques'));
    }

    public function update(UpdatePuertoCorreoRequest $request, PuertoCorreo $puertoCorreo)
    {
        $puertoCorreo->update($request->all());

        return redirect()->route('frontend.puerto-correos.index');
    }

    public function show(PuertoCorreo $puertoCorreo)
    {
        abort_if(Gate::denies('puerto_correo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $puertoCorreo->load('puerto_embarque', 'pais');

        return view('frontend.puertoCorreos.show', compact('puertoCorreo'));
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
