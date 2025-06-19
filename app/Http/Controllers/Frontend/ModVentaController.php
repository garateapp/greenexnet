<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyModVentumRequest;
use App\Http\Requests\StoreModVentumRequest;
use App\Http\Requests\UpdateModVentumRequest;
use App\Models\ModVentum;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModVentaController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('mod_ventum_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modVenta = ModVentum::all();

        return view('frontend.modVenta.index', compact('modVenta'));
    }

    public function create()
    {
        abort_if(Gate::denies('mod_ventum_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.modVenta.create');
    }

    public function store(StoreModVentumRequest $request)
    {
        $modVentum = ModVentum::create($request->all());

        return redirect()->route('frontend.mod-venta.index');
    }

    public function edit(ModVentum $modVentum)
    {
        abort_if(Gate::denies('mod_ventum_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.modVenta.edit', compact('modVentum'));
    }

    public function update(UpdateModVentumRequest $request, ModVentum $modVentum)
    {
        $modVentum->update($request->all());

        return redirect()->route('frontend.mod-venta.index');
    }

    public function show(ModVentum $modVentum)
    {
        abort_if(Gate::denies('mod_ventum_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.modVenta.show', compact('modVentum'));
    }

    public function destroy(ModVentum $modVentum)
    {
        abort_if(Gate::denies('mod_ventum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modVentum->delete();

        return back();
    }

    public function massDestroy(MassDestroyModVentumRequest $request)
    {
        $modVenta = ModVentum::find(request('ids'));

        foreach ($modVenta as $modVentum) {
            $modVentum->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
