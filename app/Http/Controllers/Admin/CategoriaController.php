<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCategoriumRequest;
use App\Http\Requests\StoreCategoriumRequest;
use App\Http\Requests\UpdateCategoriumRequest;
use App\Models\Categorium;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriaController extends Controller
{

    use CsvImportTrait;
    public function index()
    {
        abort_if(Gate::denies('categorium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categoria = Categorium::all();

        return view('admin.categoria.index', compact('categoria'));
    }

    public function create()
    {
        abort_if(Gate::denies('categorium_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.categoria.create');
    }

    public function store(StoreCategoriumRequest $request)
    {
        $categorium = Categorium::create($request->all());

        return redirect()->route('admin.categoria.index');
    }

    public function edit(Categorium $categorium)
    {
        abort_if(Gate::denies('categorium_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.categoria.edit', compact('categorium'));
    }

    public function update(UpdateCategoriumRequest $request, Categorium $categorium)
    {
        $categorium->update($request->all());

        return redirect()->route('admin.categoria.index');
    }

    public function show(Categorium $categorium)
    {
        abort_if(Gate::denies('categorium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.categoria.show', compact('categorium'));
    }

    public function destroy(Categorium $categorium)
    {
        abort_if(Gate::denies('categorium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categorium->delete();

        return back();
    }

    public function massDestroy(MassDestroyCategoriumRequest $request)
    {
        $categoria = Categorium::find(request('ids'));

        foreach ($categoria as $categorium) {
            $categorium->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
