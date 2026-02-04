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
use Illuminate\Support\Facades\Log;

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
            Log::error($validator->errors());
            return response()->json(
                ['message' => 'Datos invalidos', 'errors' => $validator->errors()],

                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $inserted = 0;
        $skipped = 0;
        foreach ($validator->validated() as $attendance) {
            $ts = Carbon::parse($attendance['timestamp'])->setTimezone('America/Santiago');
            $windowStart = $ts->copy()->subHours(2);
            $windowEnd = $ts->copy()->addHours(2);

            $exists = Attendance::where('personal_id', $attendance['personal_id'])
                ->whereBetween('timestamp', [$windowStart, $windowEnd])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Attendance::create([
                'personal_id' => $attendance['personal_id'],
                'location' => $attendance['location_id'],
                'timestamp' => $ts,
                'entry_type' => 'kptura',
            ]);
            $inserted++;
        }

        return response()->json(['pushed' => $inserted, 'skipped' => $skipped], Response::HTTP_CREATED);
    }
}
