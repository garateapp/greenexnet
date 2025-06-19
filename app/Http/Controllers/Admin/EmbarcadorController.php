<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEmbarcadorRequest;
use App\Http\Requests\StoreEmbarcadorRequest;
use App\Http\Requests\UpdateEmbarcadorRequest;
use App\Models\Embarcador;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EmbarcadorController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('embarcador_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Embarcador::query()->select(sprintf('%s.*', (new Embarcador)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'embarcador_show';
                $editGate      = 'embarcador_edit';
                $deleteGate    = 'embarcador_delete';
                $crudRoutePart = 'embarcadors';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });
            $table->editColumn('via', function ($row) {
                return $row->via ? Embarcador::VIA_SELECT[$row->via] : '';
            });
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });
            $table->editColumn('rut', function ($row) {
                return $row->rut ? $row->rut : '';
            });
            $table->editColumn('attn', function ($row) {
                return $row->attn ? $row->attn : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('telefono', function ($row) {
                return $row->telefono ? $row->telefono : '';
            });
            $table->editColumn('cc', function ($row) {
                return $row->cc ? $row->cc : '';
            });
            $table->editColumn('p_sag_dir', function ($row) {
                return $row->p_sag_dir ? $row->p_sag_dir : '';
            });
            $table->editColumn('g_dir_a', function ($row) {
                return $row->g_dir_a ? $row->g_dir_a : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.embarcadors.index');
    }

    public function create()
    {
        abort_if(Gate::denies('embarcador_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.embarcadors.create');
    }

    public function store(StoreEmbarcadorRequest $request)
    {
        $embarcador = Embarcador::create($request->all());

        return redirect()->route('admin.embarcadors.index');
    }

    public function edit(Embarcador $embarcador)
    {
        abort_if(Gate::denies('embarcador_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.embarcadors.edit', compact('embarcador'));
    }

    public function update(UpdateEmbarcadorRequest $request, Embarcador $embarcador)
    {
        $embarcador->update($request->all());

        return redirect()->route('admin.embarcadors.index');
    }

    public function show(Embarcador $embarcador)
    {
        abort_if(Gate::denies('embarcador_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embarcador->load('embarcadorInstructivoEmbarques');

        return view('admin.embarcadors.show', compact('embarcador'));
    }

    public function destroy(Embarcador $embarcador)
    {
        abort_if(Gate::denies('embarcador_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embarcador->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmbarcadorRequest $request)
    {
        $embarcadors = Embarcador::find(request('ids'));

        foreach ($embarcadors as $embarcador) {
            $embarcador->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
