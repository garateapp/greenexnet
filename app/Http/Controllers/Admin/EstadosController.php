<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyEstadoRequest;
use App\Http\Requests\StoreEstadoRequest;
use App\Http\Requests\UpdateEstadoRequest;
use App\Models\Estado;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class EstadosController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('estado_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $estados = Estado::with(['media'])->get();

        return view('admin.estados.index', compact('estados'));
    }

    public function create()
    {
        abort_if(Gate::denies('estado_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.estados.create');
    }

    public function store(StoreEstadoRequest $request)
    {
        $estado = Estado::create($request->all());

        if ($request->input('icono', false)) {
            $estado->addMedia(storage_path('tmp/uploads/' . basename($request->input('icono'))))->toMediaCollection('icono');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $estado->id]);
        }

        return redirect()->route('admin.estados.index');
    }

    public function edit(Estado $estado)
    {
        abort_if(Gate::denies('estado_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.estados.edit', compact('estado'));
    }

    public function update(UpdateEstadoRequest $request, Estado $estado)
    {
        $estado->update($request->all());

        if ($request->input('icono', false)) {
            if (!$estado->icono || $request->input('icono') !== $estado->icono->file_name) {
                if ($estado->icono) {
                    $estado->icono->delete();
                }

                $estado->addMedia(storage_path('tmp/uploads/' . basename($request->input('icono'))))->toMediaCollection('icono');
            }
        } elseif ($estado->icono) {
            $estado->icono->delete();
        }

        return redirect()->route('admin.estados.index');
    }

    public function show(Estado $estado)
    {
        abort_if(Gate::denies('estado_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.estados.show', compact('estado'));
    }

    public function destroy(Estado $estado)
    {
        abort_if(Gate::denies('estado_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $estado->delete();

        return back();
    }

    public function massDestroy(MassDestroyEstadoRequest $request)
    {
        Estado::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('estado_create') && Gate::denies('estado_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Estado();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
