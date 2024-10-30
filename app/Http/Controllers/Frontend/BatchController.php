<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyBatchRequest;
use App\Http\Requests\StoreBatchRequest;
use App\Http\Requests\UpdateBatchRequest;
use App\Models\Batch;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BatchController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('batch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $batches = Batch::all();

        return view('frontend.batches.index', compact('batches'));
    }

    public function create()
    {
        abort_if(Gate::denies('batch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.batches.create');
    }

    public function store(StoreBatchRequest $request)
    {
        $batch = Batch::create($request->all());

        return redirect()->route('frontend.batches.index');
    }

    public function edit(Batch $batch)
    {
        abort_if(Gate::denies('batch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.batches.edit', compact('batch'));
    }

    public function update(UpdateBatchRequest $request, Batch $batch)
    {
        $batch->update($request->all());

        return redirect()->route('frontend.batches.index');
    }

    public function show(Batch $batch)
    {
        abort_if(Gate::denies('batch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.batches.show', compact('batch'));
    }

    public function destroy(Batch $batch)
    {
        abort_if(Gate::denies('batch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $batch->delete();

        return back();
    }

    public function massDestroy(MassDestroyBatchRequest $request)
    {
        $batches = Batch::find(request('ids'));

        foreach ($batches as $batch) {
            $batch->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
