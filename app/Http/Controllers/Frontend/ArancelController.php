<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyArancelRequest;
use App\Http\Requests\StoreArancelRequest;
use App\Http\Requests\UpdateArancelRequest;
use App\Models\Arancel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArancelController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('arancel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $arancels = Arancel::all();

        return view('frontend.arancels.index', compact('arancels'));
    }

    public function create()
    {
        abort_if(Gate::denies('arancel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.arancels.create');
    }

    public function store(StoreArancelRequest $request)
    {
        $arancel = Arancel::create($request->all());

        return redirect()->route('frontend.arancels.index');
    }

    public function edit(Arancel $arancel)
    {
        abort_if(Gate::denies('arancel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.arancels.edit', compact('arancel'));
    }

    public function update(UpdateArancelRequest $request, Arancel $arancel)
    {
        $arancel->update($request->all());

        return redirect()->route('frontend.arancels.index');
    }

    public function show(Arancel $arancel)
    {
        abort_if(Gate::denies('arancel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.arancels.show', compact('arancel'));
    }

    public function destroy(Arancel $arancel)
    {
        abort_if(Gate::denies('arancel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $arancel->delete();

        return back();
    }

    public function massDestroy(MassDestroyArancelRequest $request)
    {
        $arancels = Arancel::find(request('ids'));

        foreach ($arancels as $arancel) {
            $arancel->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
