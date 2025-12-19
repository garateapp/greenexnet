<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCapturadorEstructuraRequest;
use App\Http\Requests\StoreCapturadorEstructuraRequest;
use App\Http\Requests\UpdateCapturadorEstructuraRequest;
use App\Models\Capturador;
use App\Models\CapturadorEstructura;
use App\Models\TiposSeccionConversor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Costo;

class CapturadorEstructuraController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('capturador_estructura_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CapturadorEstructura::with(['capturador'])->select(sprintf('%s.*', (new CapturadorEstructura)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'capturador_estructura_show';
                $editGate      = 'capturador_estructura_edit';
                $deleteGate    = 'capturador_estructura_delete';
                $crudRoutePart = 'capturador-estructuras';

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
            $table->addColumn('capturador_nombre', function ($row) {
                return $row->capturador ? $row->capturador->nombre : '';
            });

            $table->editColumn('propiedad', function ($row) {
                return $row->propiedad ? $row->propiedad : '';
            });
            $table->editColumn('coordenada', function ($row) {
                return $row->coordenada ? $row->coordenada : '';
            });
            $table->editColumn('orden', function ($row) {
                return $row->orden ? $row->orden : '';
            });
            $table->editColumn('visible', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->visible ? 'checked' : null) . '>';
            });
            $table->editColumn('formula', function ($row) {
                return $row->formula ? $row->formula : '';
            });
            $table->editColumn('tipos_seccion_conversors_id', function ($row) {
                return $row->tipos_seccion_conversors_id ? $row->tipos_seccion_conversors_id : '';
            });
            

            $table->rawColumns(['actions', 'placeholder', 'capturador', 'visible']);

            return $table->make(true);
        }

        $capturadors = Capturador::get();
        $tipos_seccion_conversors = TiposSeccionConversor::get();

        return view('admin.capturadorEstructuras.index', compact('capturadors', 'tipos_seccion_conversors'));
    }

    public function create()
    {
        abort_if(Gate::denies('capturador_estructura_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $capturadors = Capturador::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        $tipos_seccion_conversors = TiposSeccionConversor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        $costos=Costo::pluck('nombre', 'nombre')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.capturadorEstructuras.create', compact('capturadors', 'tipos_seccion_conversors', 'costos'));
    }

    public function store(StoreCapturadorEstructuraRequest $request)
    {
        $capturadorEstructura = CapturadorEstructura::create($request->all());

        return redirect()->route('admin.capturador-estructuras.index');
    }

    public function edit(CapturadorEstructura $capturadorEstructura)
    {
        abort_if(Gate::denies('capturador_estructura_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $capturadors = Capturador::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        $tipos_seccion_conversors = TiposSeccionConversor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        $costos=Costo::pluck('nombre', 'nombre')->prepend(trans('global.pleaseSelect'), '');

        $capturadorEstructura->load('capturador');

        return view('admin.capturadorEstructuras.edit', compact('capturadorEstructura', 'capturadors', 'tipos_seccion_conversors','costos'));
    }

    public function update(UpdateCapturadorEstructuraRequest $request, CapturadorEstructura $capturadorEstructura)
    {
        $capturadorEstructura->update($request->all());

        return redirect()->route('admin.capturador-estructuras.index');
    }

    public function inlineUpdate(Request $request, CapturadorEstructura $capturadorEstructura)
    {
        abort_if(Gate::denies('capturador_estructura_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [
            'field' => ['required', 'in:propiedad,coordenada,orden'],
            'value' => ['nullable'],
        ];

        if ($request->input('field') === 'orden') {
            $rules['value'] = ['nullable', 'integer', 'min:-2147483648', 'max:2147483647'];
        }

        if (in_array($request->input('field'), ['propiedad', 'coordenada'], true)) {
            $rules['value'] = ['required', 'string'];
        }

        $data = $request->validate($rules);

        $value = $data['value'];
        if ($data['field'] === 'orden' && $value === '') {
            $value = null;
        }

        $capturadorEstructura->{$data['field']} = $value;
        $capturadorEstructura->save();

        return response()->json([
            'status' => 'ok',
            'field' => $data['field'],
            'value' => $capturadorEstructura->{$data['field']},
        ]);
    }

    public function show(CapturadorEstructura $capturadorEstructura)
    {
        abort_if(Gate::denies('capturador_estructura_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $capturadorEstructura->load('capturador');

        return view('admin.capturadorEstructuras.show', compact('capturadorEstructura'));
    }

    public function destroy(CapturadorEstructura $capturadorEstructura)
    {
        abort_if(Gate::denies('capturador_estructura_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $capturadorEstructura->delete();

        return back();
    }

    public function massDestroy(MassDestroyCapturadorEstructuraRequest $request)
    {
        $capturadorEstructuras = CapturadorEstructura::find(request('ids'));

        foreach ($capturadorEstructuras as $capturadorEstructura) {
            $capturadorEstructura->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
