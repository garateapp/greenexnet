<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyHawbBatchRequest;
use App\Http\Requests\StoreHawbBatchRequest;
use App\Http\Requests\UpdateHawbBatchRequest;
use App\Models\HawbBatch;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HawbBatchController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('hawb_batch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hawbBatches = HawbBatch::all();

        return view('frontend.hawbBatches.index', compact('hawbBatches'));
    }

    public function create()
    {
        abort_if(Gate::denies('hawb_batch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.hawbBatches.create');
    }

    public function store(StoreHawbBatchRequest $request)
    {
        $hawbBatch = HawbBatch::create($request->all());

        return redirect()->route('frontend.hawb-batches.index');
    }

    public function edit(HawbBatch $hawbBatch)
    {
        abort_if(Gate::denies('hawb_batch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.hawbBatches.edit', compact('hawbBatch'));
    }

    public function update(UpdateHawbBatchRequest $request, HawbBatch $hawbBatch)
    {
        $hawbBatch->update($request->all());

        return redirect()->route('frontend.hawb-batches.index');
    }

    public function show(HawbBatch $hawbBatch)
    {
        abort_if(Gate::denies('hawb_batch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.hawbBatches.show', compact('hawbBatch'));
    }

    public function destroy(HawbBatch $hawbBatch)
    {
        abort_if(Gate::denies('hawb_batch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hawbBatch->delete();

        return back();
    }

    public function massDestroy(MassDestroyHawbBatchRequest $request)
    {
        $hawbBatches = HawbBatch::find(request('ids'));

        foreach ($hawbBatches as $hawbBatch) {
            $hawbBatch->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
