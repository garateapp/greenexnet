<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAclaracionMawbRequest;
use App\Http\Requests\StoreAclaracionMawbRequest;
use App\Http\Requests\UpdateAclaracionMawbRequest;
use App\Models\AclaracionMawb;
use App\Models\Manifiest;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AclaracionMawbController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('aclaracion_mawb_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $aclaracionMawbs = AclaracionMawb::with(['mawb', 'usuario'])->get();

        $manifiests = Manifiest::get();

        $users = User::get();

        return view('frontend.aclaracionMawbs.index', compact('aclaracionMawbs', 'manifiests', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('aclaracion_mawb_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mawbs = Manifiest::pluck('mawb', 'id')->prepend(trans('global.pleaseSelect'), '');

        $usuarios = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.aclaracionMawbs.create', compact('mawbs', 'usuarios'));
    }

    public function store(StoreAclaracionMawbRequest $request)
    {
        $aclaracionMawb = AclaracionMawb::create($request->all());

        return redirect()->route('frontend.aclaracion-mawbs.index');
    }

    public function edit(AclaracionMawb $aclaracionMawb)
    {
        abort_if(Gate::denies('aclaracion_mawb_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mawbs = Manifiest::pluck('mawb', 'id')->prepend(trans('global.pleaseSelect'), '');

        $usuarios = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $aclaracionMawb->load('mawb', 'usuario');

        return view('frontend.aclaracionMawbs.edit', compact('aclaracionMawb', 'mawbs', 'usuarios'));
    }

    public function update(UpdateAclaracionMawbRequest $request, AclaracionMawb $aclaracionMawb)
    {
        $aclaracionMawb->update($request->all());

        return redirect()->route('frontend.aclaracion-mawbs.index');
    }

    public function show(AclaracionMawb $aclaracionMawb)
    {
        abort_if(Gate::denies('aclaracion_mawb_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $aclaracionMawb->load('mawb', 'usuario');

        return view('frontend.aclaracionMawbs.show', compact('aclaracionMawb'));
    }

    public function destroy(AclaracionMawb $aclaracionMawb)
    {
        abort_if(Gate::denies('aclaracion_mawb_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $aclaracionMawb->delete();

        return back();
    }

    public function massDestroy(MassDestroyAclaracionMawbRequest $request)
    {
        $aclaracionMawbs = AclaracionMawb::find(request('ids'));

        foreach ($aclaracionMawbs as $aclaracionMawb) {
            $aclaracionMawb->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
