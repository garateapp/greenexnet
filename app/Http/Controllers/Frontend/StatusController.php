<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyStatusRequest;
use App\Http\Requests\StoreStatusRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Models\Status;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StatusController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = Status::all();

        return view('frontend.statuses.index', compact('statuses'));
    }

    public function create()
    {
        abort_if(Gate::denies('status_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.statuses.create');
    }

    public function store(StoreStatusRequest $request)
    {
        $status = Status::create($request->all());

        return redirect()->route('frontend.statuses.index');
    }

    public function edit(Status $status)
    {
        abort_if(Gate::denies('status_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.statuses.edit', compact('status'));
    }

    public function update(UpdateStatusRequest $request, Status $status)
    {
        $status->update($request->all());

        return redirect()->route('frontend.statuses.index');
    }

    public function show(Status $status)
    {
        abort_if(Gate::denies('status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.statuses.show', compact('status'));
    }

    public function destroy(Status $status)
    {
        abort_if(Gate::denies('status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $status->delete();

        return back();
    }

    public function massDestroy(MassDestroyStatusRequest $request)
    {
        $statuses = Status::find(request('ids'));

        foreach ($statuses as $status) {
            $status->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
