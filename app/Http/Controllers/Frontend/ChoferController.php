<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyChoferRequest;
use App\Http\Requests\StoreChoferRequest;
use App\Http\Requests\UpdateChoferRequest;
use App\Models\Chofer;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChoferController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('chofer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $chofers = Chofer::all();

        return view('frontend.chofers.index', compact('chofers'));
    }

    public function create()
    {
        abort_if(Gate::denies('chofer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.chofers.create');
    }

    public function store(StoreChoferRequest $request)
    {
        $chofer = Chofer::create($request->all());

        return redirect()->route('frontend.chofers.index');
    }

    public function edit(Chofer $chofer)
    {
        abort_if(Gate::denies('chofer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.chofers.edit', compact('chofer'));
    }

    public function update(UpdateChoferRequest $request, Chofer $chofer)
    {
        $chofer->update($request->all());

        return redirect()->route('frontend.chofers.index');
    }

    public function show(Chofer $chofer)
    {
        abort_if(Gate::denies('chofer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.chofers.show', compact('chofer'));
    }

    public function destroy(Chofer $chofer)
    {
        abort_if(Gate::denies('chofer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $chofer->delete();

        return back();
    }

    public function massDestroy(MassDestroyChoferRequest $request)
    {
        $chofers = Chofer::find(request('ids'));

        foreach ($chofers as $chofer) {
            $chofer->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
