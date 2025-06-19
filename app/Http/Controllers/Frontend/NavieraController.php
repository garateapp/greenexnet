<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyNavieraRequest;
use App\Http\Requests\StoreNavieraRequest;
use App\Http\Requests\UpdateNavieraRequest;
use App\Models\Naviera;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NavieraController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('naviera_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $navieras = Naviera::all();

        return view('frontend.navieras.index', compact('navieras'));
    }

    public function create()
    {
        abort_if(Gate::denies('naviera_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.navieras.create');
    }

    public function store(StoreNavieraRequest $request)
    {
        $naviera = Naviera::create($request->all());

        return redirect()->route('frontend.navieras.index');
    }

    public function edit(Naviera $naviera)
    {
        abort_if(Gate::denies('naviera_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.navieras.edit', compact('naviera'));
    }

    public function update(UpdateNavieraRequest $request, Naviera $naviera)
    {
        $naviera->update($request->all());

        return redirect()->route('frontend.navieras.index');
    }

    public function show(Naviera $naviera)
    {
        abort_if(Gate::denies('naviera_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.navieras.show', compact('naviera'));
    }

    public function destroy(Naviera $naviera)
    {
        abort_if(Gate::denies('naviera_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $naviera->delete();

        return back();
    }

    public function massDestroy(MassDestroyNavieraRequest $request)
    {
        $navieras = Naviera::find(request('ids'));

        foreach ($navieras as $naviera) {
            $naviera->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
