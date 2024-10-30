<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPuertoRequest;
use App\Http\Requests\StorePuertoRequest;
use App\Http\Requests\UpdatePuertoRequest;
use App\Models\Puerto;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PuertoController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('puerto_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $puertos = Puerto::all();

        return view('frontend.puertos.index', compact('puertos'));
    }

    public function create()
    {
        abort_if(Gate::denies('puerto_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.puertos.create');
    }

    public function store(StorePuertoRequest $request)
    {
        $puerto = Puerto::create($request->all());

        return redirect()->route('frontend.puertos.index');
    }

    public function edit(Puerto $puerto)
    {
        abort_if(Gate::denies('puerto_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.puertos.edit', compact('puerto'));
    }

    public function update(UpdatePuertoRequest $request, Puerto $puerto)
    {
        $puerto->update($request->all());

        return redirect()->route('frontend.puertos.index');
    }

    public function show(Puerto $puerto)
    {
        abort_if(Gate::denies('puerto_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.puertos.show', compact('puerto'));
    }

    public function destroy(Puerto $puerto)
    {
        abort_if(Gate::denies('puerto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $puerto->delete();

        return back();
    }

    public function massDestroy(MassDestroyPuertoRequest $request)
    {
        $puertos = Puerto::find(request('ids'));

        foreach ($puertos as $puerto) {
            $puerto->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
