<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAdicionaleRequest;
use App\Http\Requests\StoreAdicionaleRequest;
use App\Http\Requests\UpdateAdicionaleRequest;
use App\Models\Adicionale;
use App\Models\Hawb;
use App\Models\Manifiest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdicionalesController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('adicionale_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $adicionales = Adicionale::with(['mawb', 'hawb'])->get();

        return view('frontend.adicionales.index', compact('adicionales'));
    }

    public function create()
    {
        abort_if(Gate::denies('adicionale_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mawbs = Manifiest::pluck('mawb', 'id')->prepend(trans('global.pleaseSelect'), '');

        $hawbs = Hawb::pluck('guia_courier', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.adicionales.create', compact('hawbs', 'mawbs'));
    }

    public function store(StoreAdicionaleRequest $request)
    {
        $adicionale = Adicionale::create($request->all());

        return redirect()->route('frontend.adicionales.index');
    }

    public function edit(Adicionale $adicionale)
    {
        abort_if(Gate::denies('adicionale_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mawbs = Manifiest::pluck('mawb', 'id')->prepend(trans('global.pleaseSelect'), '');

        $hawbs = Hawb::pluck('guia_courier', 'id')->prepend(trans('global.pleaseSelect'), '');

        $adicionale->load('mawb', 'hawb');

        return view('frontend.adicionales.edit', compact('adicionale', 'hawbs', 'mawbs'));
    }

    public function update(UpdateAdicionaleRequest $request, Adicionale $adicionale)
    {
        $adicionale->update($request->all());

        return redirect()->route('frontend.adicionales.index');
    }

    public function show(Adicionale $adicionale)
    {
        abort_if(Gate::denies('adicionale_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $adicionale->load('mawb', 'hawb');

        return view('frontend.adicionales.show', compact('adicionale'));
    }

    public function destroy(Adicionale $adicionale)
    {
        abort_if(Gate::denies('adicionale_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $adicionale->delete();

        return back();
    }

    public function massDestroy(MassDestroyAdicionaleRequest $request)
    {
        $adicionales = Adicionale::find(request('ids'));

        foreach ($adicionales as $adicionale) {
            $adicionale->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
