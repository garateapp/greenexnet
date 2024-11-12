<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPersonalRequest;
use App\Http\Requests\StorePersonalRequest;
use App\Http\Requests\UpdatePersonalRequest;
use App\Models\Cargo;
use App\Models\Entidad;
use App\Models\Estado;
use App\Models\Personal;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class PersonalController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('personal_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        dd(Storage::disk('public'));
        if ($request->ajax()) {
            $query = Personal::with(['cargo', 'estado', 'entidad'])->select(sprintf('%s.*', (new Personal)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'personal_show';
                $editGate      = 'personal_edit';
                $deleteGate    = 'personal_delete';
                $crudRoutePart = 'personals';

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
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });
            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });
            $table->editColumn('rut', function ($row) {
                return $row->rut ? $row->rut : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('telefono', function ($row) {
                return $row->telefono ? $row->telefono : '';
            });
            $table->addColumn('cargo_nombre', function ($row) {
                return $row->cargo ? $row->cargo->nombre : '';
            });

            $table->addColumn('estado_nombre', function ($row) {
                return $row->estado ? $row->estado->nombre : '';
            });

            $table->addColumn('entidad_nombre', function ($row) {
                return $row->entidad ? $row->entidad->nombre : '';
            });
            $table->addColumn('foto', function ($row) {
                return $row->foto ? $row->foto : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'cargo', 'estado', 'entidad']);

            return $table->make(true);
        }

        return view('admin.personals.index');
    }

    public function create()
    {
        abort_if(Gate::denies('personal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cargos = Cargo::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = Estado::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $entidads = Entidad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.personals.create', compact('cargos', 'entidads', 'estados'));
    }

    public function store(StorePersonalRequest $request)
    {
        $personal = new Personal();
        $personal->nombre = $request->nombre;
        $personal->codigo = $request->codigo;
        $personal->rut = $request->rut;
        $personal->email = $request->email;
        $personal->telefono = $request->telefono;
        $personal->cargo_id = $request->cargo_id;
        $personal->estado_id = $request->estado_id;
        $personal->entidad_id = $request->entidad_id;
        $base64Image = $request->input('foto');
        $fileData = explode(',', $base64Image);
        $imageData = base64_decode($fileData[1]);
        // Generar un nombre único para el archivo
        $fileName = $request->rut . '.jpg'; // Puedes cambiar la extensión según el tipo de imagen

        // Guardar la imagen en el storage
        $filePath = 'images/' . $fileName;
        Storage::disk('public')->put($filePath, $imageData);

        $personal->foto = $filePath;

        $personal->save();

        // $personal = Personal::create($request->all());

        return redirect()->route('admin.personals.index');
    }

    public function edit(Personal $personal)
    {
        abort_if(Gate::denies('personal_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cargos = Cargo::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = Estado::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $entidads = Entidad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $personal->load('cargo', 'estado', 'entidad');

        return view('admin.personals.edit', compact('cargos', 'entidads', 'estados', 'personal'));
    }

    public function update(UpdatePersonalRequest $request, Personal $personal)
    {

        $nPersonal = Personal::find($personal->id);
        $nPersonal->nombre = $request->nombre;
        $nPersonal->codigo = $request->codigo;
        $nPersonal->rut = $request->rut;
        $nPersonal->email = $request->email;
        $nPersonal->telefono = $request->telefono;
        $nPersonal->cargo_id = $request->cargo_id;
        $nPersonal->estado_id = $request->estado_id;
        $nPersonal->entidad_id = $request->entidad_id;
        //dd($request->foto);
        if ($request->foto != null) {
            $base64Image = $request->input('foto');
            $fileData = explode(',', $base64Image);
            $imageData = base64_decode($fileData[1]);
            // Generar un nombre único para el archivo
            $fileName = $request->rut . '.jpg'; // Puedes cambiar la extensión según el tipo de imagen

            // Guardar la imagen en el storage
            $filePath = 'images/' . $fileName;
            Storage::disk('public')->put($filePath, $imageData);
            $nPersonal->foto = $filePath;
        }


        $nPersonal->save();
        //$personal->update($request->all());

        return redirect()->route('admin.personals.index');
    }

    public function show(Personal $personal)
    {
        abort_if(Gate::denies('personal_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $personal->load('cargo', 'estado', 'entidad');

        return view('admin.personals.show', compact('personal'));
    }

    public function destroy(Personal $personal)
    {
        abort_if(Gate::denies('personal_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $personal->delete();

        return back();
    }

    public function massDestroy(MassDestroyPersonalRequest $request)
    {
        $personals = Personal::find(request('ids'));

        foreach ($personals as $personal) {
            $personal->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
