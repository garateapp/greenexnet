<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAirlineRequest;
use App\Http\Requests\StoreAirlineRequest;
use App\Http\Requests\UpdateAirlineRequest;
use App\Models\Airline;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AirlineController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('airline_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $airlines = Airline::all();

        return view('frontend.airlines.index', compact('airlines'));
    }

    public function create()
    {
        abort_if(Gate::denies('airline_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.airlines.create');
    }

    public function store(StoreAirlineRequest $request)
    {
        $airline = Airline::create($request->all());

        return redirect()->route('frontend.airlines.index');
    }

    public function edit(Airline $airline)
    {
        abort_if(Gate::denies('airline_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.airlines.edit', compact('airline'));
    }

    public function update(UpdateAirlineRequest $request, Airline $airline)
    {
        $airline->update($request->all());

        return redirect()->route('frontend.airlines.index');
    }

    public function show(Airline $airline)
    {
        abort_if(Gate::denies('airline_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.airlines.show', compact('airline'));
    }

    public function destroy(Airline $airline)
    {
        abort_if(Gate::denies('airline_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $airline->delete();

        return back();
    }

    public function massDestroy(MassDestroyAirlineRequest $request)
    {
        $airlines = Airline::find(request('ids'));

        foreach ($airlines as $airline) {
            $airline->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
