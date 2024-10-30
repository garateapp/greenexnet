<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAduanaRequest;
use App\Http\Requests\StoreAduanaRequest;
use App\Http\Requests\UpdateAduanaRequest;
use App\Models\Aduana;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AduanaController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('aduana_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $aduanas = Aduana::all();

        return view('frontend.aduanas.index', compact('aduanas'));
    }

    public function create()
    {
        abort_if(Gate::denies('aduana_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.aduanas.create');
    }

    public function store(StoreAduanaRequest $request)
    {
        $aduana = Aduana::create($request->all());

        return redirect()->route('frontend.aduanas.index');
    }

    public function edit(Aduana $aduana)
    {
        abort_if(Gate::denies('aduana_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.aduanas.edit', compact('aduana'));
    }

    public function update(UpdateAduanaRequest $request, Aduana $aduana)
    {
        $aduana->update($request->all());

        return redirect()->route('frontend.aduanas.index');
    }

    public function show(Aduana $aduana)
    {
        abort_if(Gate::denies('aduana_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.aduanas.show', compact('aduana'));
    }

    public function destroy(Aduana $aduana)
    {
        abort_if(Gate::denies('aduana_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $aduana->delete();

        return back();
    }

    public function massDestroy(MassDestroyAduanaRequest $request)
    {
        $aduanas = Aduana::find(request('ids'));

        foreach ($aduanas as $aduana) {
            $aduana->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
