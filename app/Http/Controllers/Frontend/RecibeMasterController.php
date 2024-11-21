<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyRecibeMasterRequest;
use App\Http\Requests\StoreRecibeMasterRequest;
use App\Http\Requests\UpdateRecibeMasterRequest;
use App\Models\RecibeMaster;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecibeMasterController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('recibe_master_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recibeMasters = RecibeMaster::all();

        return view('frontend.recibeMasters.index', compact('recibeMasters'));
    }

    public function create()
    {
        abort_if(Gate::denies('recibe_master_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.recibeMasters.create');
    }

    public function store(StoreRecibeMasterRequest $request)
    {
        $recibeMaster = RecibeMaster::create($request->all());

        return redirect()->route('frontend.recibe-masters.index');
    }

    public function edit(RecibeMaster $recibeMaster)
    {
        abort_if(Gate::denies('recibe_master_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.recibeMasters.edit', compact('recibeMaster'));
    }

    public function update(UpdateRecibeMasterRequest $request, RecibeMaster $recibeMaster)
    {
        $recibeMaster->update($request->all());

        return redirect()->route('frontend.recibe-masters.index');
    }

    public function show(RecibeMaster $recibeMaster)
    {
        abort_if(Gate::denies('recibe_master_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.recibeMasters.show', compact('recibeMaster'));
    }

    public function destroy(RecibeMaster $recibeMaster)
    {
        abort_if(Gate::denies('recibe_master_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recibeMaster->delete();

        return back();
    }

    public function massDestroy(MassDestroyRecibeMasterRequest $request)
    {
        $recibeMasters = RecibeMaster::find(request('ids'));

        foreach ($recibeMasters as $recibeMaster) {
            $recibeMaster->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
