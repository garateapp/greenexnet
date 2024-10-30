<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAclaracionguiumRequest;
use App\Http\Requests\StoreAclaracionguiumRequest;
use App\Http\Requests\UpdateAclaracionguiumRequest;
use App\Models\Aclaracionguium;
use App\Models\Guium;
use App\Models\Manifiest;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AclaracionguiaController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('aclaracionguium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $aclaracionguia = Aclaracionguium::with(['numero_guia', 'usuario', 'mawb'])->get();

        $guia = Guium::get();

        $users = User::get();

        $manifiests = Manifiest::get();

        return view('frontend.aclaracionguia.index', compact('aclaracionguia', 'guia', 'manifiests', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('aclaracionguium_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $numero_guias = Guium::pluck('guia_courier', 'id')->prepend(trans('global.pleaseSelect'), '');

        $usuarios = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mawbs = Manifiest::pluck('mawb', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.aclaracionguia.create', compact('mawbs', 'numero_guias', 'usuarios'));
    }

    public function store(StoreAclaracionguiumRequest $request)
    {
        $aclaracionguium = Aclaracionguium::create($request->all());

        return redirect()->route('frontend.aclaracionguia.index');
    }

    public function edit(Aclaracionguium $aclaracionguium)
    {
        abort_if(Gate::denies('aclaracionguium_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $numero_guias = Guium::pluck('guia_courier', 'id')->prepend(trans('global.pleaseSelect'), '');

        $usuarios = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mawbs = Manifiest::pluck('mawb', 'id')->prepend(trans('global.pleaseSelect'), '');

        $aclaracionguium->load('numero_guia', 'usuario', 'mawb');

        return view('frontend.aclaracionguia.edit', compact('aclaracionguium', 'mawbs', 'numero_guias', 'usuarios'));
    }

    public function update(UpdateAclaracionguiumRequest $request, Aclaracionguium $aclaracionguium)
    {
        $aclaracionguium->update($request->all());

        return redirect()->route('frontend.aclaracionguia.index');
    }

    public function show(Aclaracionguium $aclaracionguium)
    {
        abort_if(Gate::denies('aclaracionguium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $aclaracionguium->load('numero_guia', 'usuario', 'mawb');

        return view('frontend.aclaracionguia.show', compact('aclaracionguium'));
    }

    public function destroy(Aclaracionguium $aclaracionguium)
    {
        abort_if(Gate::denies('aclaracionguium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $aclaracionguium->delete();

        return back();
    }

    public function massDestroy(MassDestroyAclaracionguiumRequest $request)
    {
        $aclaracionguia = Aclaracionguium::find(request('ids'));

        foreach ($aclaracionguia as $aclaracionguium) {
            $aclaracionguium->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
