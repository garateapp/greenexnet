<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyClausulaVentumRequest;
use App\Http\Requests\StoreClausulaVentumRequest;
use App\Http\Requests\UpdateClausulaVentumRequest;
use App\Models\ClausulaVentum;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClausulaVentaController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('clausula_ventum_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clausulaVenta = ClausulaVentum::all();

        return view('frontend.clausulaVenta.index', compact('clausulaVenta'));
    }

    public function create()
    {
        abort_if(Gate::denies('clausula_ventum_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.clausulaVenta.create');
    }

    public function store(StoreClausulaVentumRequest $request)
    {
        $clausulaVentum = ClausulaVentum::create($request->all());

        return redirect()->route('frontend.clausula-venta.index');
    }

    public function edit(ClausulaVentum $clausulaVentum)
    {
        abort_if(Gate::denies('clausula_ventum_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.clausulaVenta.edit', compact('clausulaVentum'));
    }

    public function update(UpdateClausulaVentumRequest $request, ClausulaVentum $clausulaVentum)
    {
        $clausulaVentum->update($request->all());

        return redirect()->route('frontend.clausula-venta.index');
    }

    public function show(ClausulaVentum $clausulaVentum)
    {
        abort_if(Gate::denies('clausula_ventum_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.clausulaVenta.show', compact('clausulaVentum'));
    }

    public function destroy(ClausulaVentum $clausulaVentum)
    {
        abort_if(Gate::denies('clausula_ventum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clausulaVentum->delete();

        return back();
    }

    public function massDestroy(MassDestroyClausulaVentumRequest $request)
    {
        $clausulaVenta = ClausulaVentum::find(request('ids'));

        foreach ($clausulaVenta as $clausulaVentum) {
            $clausulaVentum->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
