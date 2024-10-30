<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCrearBatchRequest;
use App\Http\Requests\StoreCrearBatchRequest;
use App\Http\Requests\UpdateCrearBatchRequest;
use App\Models\CrearBatch;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CrearBatchController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('crear_batch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $crearBatches = CrearBatch::with(['usuario'])->get();

        $users = User::get();

        return view('frontend.crearBatches.index', compact('crearBatches', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('crear_batch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $usuarios = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.crearBatches.create', compact('usuarios'));
    }

    public function store(StoreCrearBatchRequest $request)
    {
        $crearBatch = CrearBatch::create($request->all());

        return redirect()->route('frontend.crear-batches.index');
    }

    public function edit(CrearBatch $crearBatch)
    {
        abort_if(Gate::denies('crear_batch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $usuarios = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $crearBatch->load('usuario');

        return view('frontend.crearBatches.edit', compact('crearBatch', 'usuarios'));
    }

    public function update(UpdateCrearBatchRequest $request, CrearBatch $crearBatch)
    {
        $crearBatch->update($request->all());

        return redirect()->route('frontend.crear-batches.index');
    }

    public function show(CrearBatch $crearBatch)
    {
        abort_if(Gate::denies('crear_batch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $crearBatch->load('usuario');

        return view('frontend.crearBatches.show', compact('crearBatch'));
    }

    public function destroy(CrearBatch $crearBatch)
    {
        abort_if(Gate::denies('crear_batch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $crearBatch->delete();

        return back();
    }

    public function massDestroy(MassDestroyCrearBatchRequest $request)
    {
        $crearBatches = CrearBatch::find(request('ids'));

        foreach ($crearBatches as $crearBatch) {
            $crearBatch->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
