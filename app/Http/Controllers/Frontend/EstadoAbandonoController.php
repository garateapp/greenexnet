<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEstadoAbandonoRequest;
use App\Http\Requests\StoreEstadoAbandonoRequest;
use App\Http\Requests\UpdateEstadoAbandonoRequest;
use App\Models\EstadoAbandono;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EstadoAbandonoController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('estado_abandono_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $estadoAbandonos = EstadoAbandono::all();

        return view('frontend.estadoAbandonos.index', compact('estadoAbandonos'));
    }

    public function create()
    {
        abort_if(Gate::denies('estado_abandono_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.estadoAbandonos.create');
    }

    public function store(StoreEstadoAbandonoRequest $request)
    {
        $estadoAbandono = EstadoAbandono::create($request->all());

        return redirect()->route('frontend.estado-abandonos.index');
    }

    public function edit(EstadoAbandono $estadoAbandono)
    {
        abort_if(Gate::denies('estado_abandono_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.estadoAbandonos.edit', compact('estadoAbandono'));
    }

    public function update(UpdateEstadoAbandonoRequest $request, EstadoAbandono $estadoAbandono)
    {
        $estadoAbandono->update($request->all());

        return redirect()->route('frontend.estado-abandonos.index');
    }

    public function show(EstadoAbandono $estadoAbandono)
    {
        abort_if(Gate::denies('estado_abandono_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.estadoAbandonos.show', compact('estadoAbandono'));
    }

    public function destroy(EstadoAbandono $estadoAbandono)
    {
        abort_if(Gate::denies('estado_abandono_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $estadoAbandono->delete();

        return back();
    }

    public function massDestroy(MassDestroyEstadoAbandonoRequest $request)
    {
        $estadoAbandonos = EstadoAbandono::find(request('ids'));

        foreach ($estadoAbandonos as $estadoAbandono) {
            $estadoAbandono->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
