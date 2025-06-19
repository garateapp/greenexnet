<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEmisionBlRequest;
use App\Http\Requests\StoreEmisionBlRequest;
use App\Http\Requests\UpdateEmisionBlRequest;
use App\Models\EmisionBl;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmisionBlController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('emision_bl_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisionBls = EmisionBl::all();

        return view('frontend.emisionBls.index', compact('emisionBls'));
    }

    public function create()
    {
        abort_if(Gate::denies('emision_bl_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.emisionBls.create');
    }

    public function store(StoreEmisionBlRequest $request)
    {
        $emisionBl = EmisionBl::create($request->all());

        return redirect()->route('frontend.emision-bls.index');
    }

    public function edit(EmisionBl $emisionBl)
    {
        abort_if(Gate::denies('emision_bl_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.emisionBls.edit', compact('emisionBl'));
    }

    public function update(UpdateEmisionBlRequest $request, EmisionBl $emisionBl)
    {
        $emisionBl->update($request->all());

        return redirect()->route('frontend.emision-bls.index');
    }

    public function show(EmisionBl $emisionBl)
    {
        abort_if(Gate::denies('emision_bl_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.emisionBls.show', compact('emisionBl'));
    }

    public function destroy(EmisionBl $emisionBl)
    {
        abort_if(Gate::denies('emision_bl_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisionBl->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmisionBlRequest $request)
    {
        $emisionBls = EmisionBl::find(request('ids'));

        foreach ($emisionBls as $emisionBl) {
            $emisionBl->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
