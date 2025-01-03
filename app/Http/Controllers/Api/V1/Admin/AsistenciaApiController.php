<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAsistenciumRequest;
use App\Http\Requests\UpdateAsistenciumRequest;
use App\Http\Resources\Admin\AsistenciumResource;
use App\Models\Asistencium;
use App\Models\Personal;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;


class AsistenciaApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('asistencium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AsistenciumResource(Asistencium::with(['locacion', 'turno', 'personal'])->get());
    }
    public function guardarAsistencia(Request $request)
    {
        //         puesto: "23"
        // run: "9160225-3"
        // ubicacion: "23"
        $personal = Personal::where('rut', $request->run)->first();
        $puesto = $request->puesto;
        $turno = $request->turno;
        $fecha_hora = date('d/m/Y H:i:s');
        $fecha = date('d/m/Y');
        $asistencium = new Asistencium();
        $asistencium->locacion_id = $puesto;
        $asistencium->turno_id = $turno;
        $asistencium->personal_id = $personal->id;
        $asistencium->fecha_hora = $fecha_hora;
        $asistencia = Asistencium::whereBetween('fecha_hora', [$fecha . " 00:00:00", $fecha . " 23:59:59"])
            ->with(['locacion', 'turno', 'personal'])->first();
        if ($asistencia != null) {
            return response()->json(['message' => 'Ya se ha registrado la asistencia del personal'], 400);
        } else {
            $asistencium->save();

            return response()->json(['message' => 'Asistencia guardada correctamente'], 200);
        }
    }
    public function obtieneAsistenciaActual(Request $request)
    {
        //abort_if(Gate::denies('asistencium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        $fechaDeseada = Carbon::parse(date('Y-m-d'))->format('Y-m-d');
        $asistencia = Asistencium::where("locacion_id", $request->ubicacion)->where("turno_id", $request->turno)
            ->whereBetween('fecha_hora', [$fechaDeseada . " 00:00:00", $fechaDeseada . " 23:59:59"])
            ->with(['locacion', 'turno', 'personal'])->get();
        return response()->json($asistencia, 200);
    }
    public function store(StoreAsistenciumRequest $request)
    {
        $asistencium = Asistencium::create($request->all());

        return (new AsistenciumResource($asistencium))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Asistencium $asistencium)
    {
        abort_if(Gate::denies('asistencium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AsistenciumResource($asistencium->load(['locacion', 'turno', 'personal']));
    }

    public function update(UpdateAsistenciumRequest $request, Asistencium $asistencium)
    {
        $asistencium->update($request->all());

        return (new AsistenciumResource($asistencium))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Asistencium $asistencium)
    {
        abort_if(Gate::denies('asistencium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $asistencium->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function getAttendanceData()
    {
        $filePath = storage_path('attendance_data.csv');
        $data = [];
        dd($filePath);
        if (Storage::exists($filePath)) {
            $fileContent = Storage::get($filePath);
            $lines = explode("\n", $fileContent);

            foreach ($lines as $line) {
                $columns = str_getcsv($line);
                if (count($columns) === 4) {
                    $data[] = [
                        'fecha_hora' => Carbon::parse($columns[0])->format('Y-m-d H:i:s'),
                        'locacion_id' => $columns[1],
                        'personal_id' => $columns[2],
                        'turno_id' => $columns[3],
                    ];
                }
            }
        }

        return response()->json($data);
    }
}
