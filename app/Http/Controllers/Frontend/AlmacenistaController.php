<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAlmacenistumRequest;
use App\Http\Requests\StoreAlmacenistumRequest;
use App\Http\Requests\UpdateAlmacenistumRequest;
use App\Models\Almacenistum;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AlmacenistaController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('almacenistum_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $almacenista = Almacenistum::all();

        return view('frontend.almacenista.index', compact('almacenista'));
    }

    public function create()
    {
        abort_if(Gate::denies('almacenistum_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.almacenista.create');
    }

    public function store(StoreAlmacenistumRequest $request)
    {
        $almacenistum = Almacenistum::create($request->all());

        return redirect()->route('frontend.almacenista.index');
    }

    public function edit(Almacenistum $almacenistum)
    {
        abort_if(Gate::denies('almacenistum_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.almacenista.edit', compact('almacenistum'));
    }

    public function update(UpdateAlmacenistumRequest $request, Almacenistum $almacenistum)
    {
        $almacenistum->update($request->all());

        return redirect()->route('frontend.almacenista.index');
    }

    public function show(Almacenistum $almacenistum)
    {
        abort_if(Gate::denies('almacenistum_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.almacenista.show', compact('almacenistum'));
    }

    public function destroy(Almacenistum $almacenistum)
    {
        abort_if(Gate::denies('almacenistum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $almacenistum->delete();

        return back();
    }

    public function massDestroy(MassDestroyAlmacenistumRequest $request)
    {
        $almacenista = Almacenistum::find(request('ids'));

        foreach ($almacenista as $almacenistum) {
            $almacenistum->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
