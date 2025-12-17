<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Locacion;
use App\Models\Personal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class KpturaSyncController extends Controller
{
    public function syncData()
    {
        return response()->json([
            'locacions' => Locacion::select('id', 'nombre', 'locacion_padre_id')
                ->orderBy('nombre')
                ->get(),
            'personals' => Personal::select('id', 'nombre', 'codigo', 'rut')
                ->orderBy('nombre')
                ->get(),
        ]);
    }

    public function storeAttendances(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '*' => ['required', 'array'],
            '*.personal_id' => ['required', 'integer', 'exists:personals,id'],
            '*.location_id' => ['required', 'integer', 'exists:locacions,id'],
            '*.timestamp' => ['required', 'date'],
            '*.local_id' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['message' => 'Datos invalidos', 'errors' => $validator->errors()],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $inserted = 0;
        foreach ($validator->validated() as $attendance) {
            Attendance::create([
                'personal_id' => $attendance['personal_id'],
                'location' => $attendance['location_id'],
                'timestamp' => Carbon::parse($attendance['timestamp'])->setTimezone('America/Santiago'),
                'entry_type' => 'kptura',
            ]);
            $inserted++;
        }

        return response()->json(['pushed' => $inserted], Response::HTTP_CREATED);
    }
}
