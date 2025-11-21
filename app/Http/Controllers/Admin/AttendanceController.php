<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\personal;
use App\Models\Attendance;
use App\Models\Locacion;
use App\Models\PackingLineAttendance;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function showConfirmation(Request $request)
    {
        $personId = $request->query('person_id');
        $person = null;
        $supervisorLocation = null;
        $supervisorLocationId = null;
        $error = null;
        $source = $request->query('source', 'automatico');
        $packingMode = 'default';
        $nextAction = 'salida';

        if ($personId) {
            $person = personal::where('rut', $personId)->first();
            if (!$person) {
                $error = 'No se encontró personal con el RUT proporcionado.';
            }
        }

        $packingLineIds = collect(config('packing.line_locations', []))
            ->map(fn ($value) => (int) $value)
            ->filter()
            ->values();

        $loggedInUser = Auth::user()->load('personal.assignedLocation');
        if ($loggedInUser && $loggedInUser->personal) {
            $supervisorLocation = $loggedInUser->personal->assignedLocation->nombre ?? 'Ubicación no asignada';
            $supervisorLocationId = $loggedInUser->personal->assignedLocation->id ?? null;

            if ($supervisorLocationId && $packingLineIds->contains((int) $supervisorLocationId)) {
                $packingMode = 'packing';
            }
        }

        if ($packingMode === 'packing' && $person) {
            $openLog = PackingLineAttendance::where('personal_id', $person->id)
                ->whereNull('fecha_hora_entrada')
                ->latest('fecha_hora_salida')
                ->first();

            if ($openLog) {
                $nextAction = 'entrada';
            } else {
                $nextAction = 'salida';
            }
        }

        return view('admin.attendance.confirm', compact(
            'person',
            'supervisorLocation',
            'supervisorLocationId',
            'error',
            'source',
            'packingMode',
            'nextAction'
        ));
    }

    public function findPerson(Request $request)
    {
        $request->validate(['rut' => 'required|string']);
        $rut = $request->input('rut');
        return redirect()->route('admin.attendance.confirm', ['person_id' => $rut, 'source' => 'manual']);
    }

    public function storeAttendance(Request $request)
    {
        $request->validate([
            'person_id' => 'required|exists:personals,id',
            'entry_type' => 'required|string|in:manual,automatico',
        ]);

        // Get the logged-in user's associated personal record to get the location
        $loggedInUser = Auth::user();
        $location = null;
        if ($loggedInUser && $loggedInUser->personal && $loggedInUser->personal->assignedLocation) {
            $location = $loggedInUser->personal->assignedLocation->id;
        } else {
            return response()->json(['success' => false, 'message' => 'Ubicación del supervisor no encontrada.'], 400);
        }

        $packingLineIds = collect(config('packing.line_locations', []))
            ->map(fn ($value) => (int) $value)
            ->filter()
            ->values();

        Log::info('Packing line IDs: ' . json_encode($packingLineIds->toArray()));

        if ($packingLineIds->contains((int) $location)) {
            return $this->handlePackingLineAttendance((int) $request->input('person_id'), (int) $location);
        }

        try {
            Attendance::create([
                'personal_id' => $request->input('person_id'),
                'location' => $location,
                'timestamp' => now(),
                'entry_type' => $request->input('entry_type'),
            ]);

            return response()->json(['success' => true, 'message' => 'Asistencia confirmada con éxito.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al guardar la asistencia: ' . $e->getMessage()], 500);
        }
    }

    public function offline()
    {
        return view('admin.attendance.offline_confirm');
    }

    public function offlineData()
    {
        return response()->json([
            'personals' => personal::select('id', 'nombre', 'rut')->get(),
            'locaciones' => Locacion::select('id', 'nombre', 'locacion_padre_id')->get(),
        ]);
    }

    public function syncOffline(Request $request)
    {
        $data = $request->validate([
            'entries' => 'required|array',
            'entries.*.local_id' => 'required|string',
            'entries.*.rut' => 'required|string',
            'entries.*.location_id' => 'required|integer|exists:locacions,id',
            'entries.*.timestamp' => 'nullable|date',
        ]);

        $synced = 0;
        $errors = [];
        $syncedIds = [];
        Log::info('Syncing offline data', $data);
        foreach ($data['entries'] as $entry) {
            $normalizedRut = $entry['rut'];
            $person = personal::where("rut", strtolower($normalizedRut))->first();

            if (!$person) {
                $errors[] = "No se encontró personal para el RUT {$entry['rut']}.";
                continue;
            }

            $location = Locacion::where('id',$entry['location_id'])->first();
            if (!$location) {
                $errors[] = "La ubicación {$entry['location_id']} no existe.";
                continue;
            }

            try {
                Attendance::create([
                    'personal_id' => $person->id,
                    'location' => $location->id,
                    'timestamp' => isset($entry['timestamp']) ? Carbon::parse($entry['timestamp']) : now(),
                    'entry_type' => 'offline',
                ]);
                $synced++;
                $syncedIds[] = $entry['local_id'];
            } catch (\Throwable $exception) {
                $errors[] = "No se pudo sincronizar el registro de {$entry['rut']}: {$exception->getMessage()}";
                Log::error("Error syncing offline data: {$exception->getMessage()}", $entry);
            }
        }

        return response()->json([
            'success' => count($errors) === 0,
            'synced' => $synced,
            'synced_ids' => $syncedIds,
            'errors' => $errors,
        ], count($errors) === 0 ? 200 : 207);
    }

    private function normalizeRut(string $rut): string
    {
        return strtolower(preg_replace('/[^0-9kK]/', '', $rut));
    }

    public function reportIndex()
    {
        $locations = Locacion::pluck('nombre', 'id');
        return view('admin.attendance.report', compact('locations'));
    }

    public function generateReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validate and parse dates, provide defaults if empty
        try {
            $startDate = $startDate ? Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay() : Carbon::now()->subDays(7)->startOfDay();
        } catch (\Exception $e) {
            try {
                $startDate = $startDate ? Carbon::createFromFormat('d-m-Y', $startDate)->startOfDay() : Carbon::now()->subDays(7)->startOfDay();
            } catch (\Exception $e) {
                return response()->json(['message' => 'Formato de fecha de inicio inválido.', 'error' => $e->getMessage()], 400);
            }
        }

        try {
            $endDate = $endDate ? Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay() : Carbon::now()->endOfDay();
        } catch (\Exception $e) {
            try {
                $endDate = $endDate ? Carbon::createFromFormat('d-m-Y', $endDate)->endOfDay() : Carbon::now()->endOfDay();
            } catch (\Exception $e) {
                return response()->json(['message' => 'Formato de fecha de fin inválido.', 'error' => $e->getMessage()], 400);
            }
        }

        $locationFilter = $request->input('location_filter');

        $query = Attendance::with('personal')
            ->whereBetween('timestamp', [$startDate, $endDate]);

        if ($locationFilter) {
            $query->where('location', Locacion::find($locationFilter)->nombre); // Filter by location name
        }

        $attendanceRecords = $query->get();

        $tableData = [];
        $chartData = []; // For attendance by date
        $locationChartData = []; // For attendance by location
        $locationDateChartData = []; // For attendance by location and date

        foreach ($attendanceRecords as $record) {
            $tableData[] = [
                'date' => Carbon::parse($record->timestamp)->format('d-m-Y'),
                'personal_name' => $record->personal->nombre ?? 'N/A',
                'personal_rut' => $record->personal->rut ?? 'N/A',
                'location_name' => $record->location,
                'time' => Carbon::parse($record->timestamp)->format('H:i:s'),
            ];

            // Chart Data: Attendance by Date
            $dateKey = Carbon::parse($record->timestamp)->format('Y-m-d');
            if (!isset($chartData[$dateKey])) {
                $chartData[$dateKey] = 0;
            }
            $chartData[$dateKey]++;

            // Chart Data: Attendance by Location
            $locationName = $record->location;
            if (!isset($locationChartData[$locationName])) {
                $locationChartData[$locationName] = 0;
            }
            $locationChartData[$locationName]++;

            // Chart Data: Attendance by Location and Date
            if (!isset($locationDateChartData[$dateKey])) {
                $locationDateChartData[$dateKey] = [];
            }
            if (!isset($locationDateChartData[$dateKey][$locationName])) {
                $locationDateChartData[$dateKey][$locationName] = 0;
            }
            $locationDateChartData[$dateKey][$locationName]++;
        }

        return response()->json([
            'tableData' => $tableData,
            'chartData' => $chartData,
            'locationChartData' => $locationChartData,
            'locationDateChartData' => $locationDateChartData,
        ]);
    }

    protected function handlePackingLineAttendance(int $personalId, int $locationId)
    {
        $now = Carbon::now();
        $person = personal::find($personalId);
        $personData = [
            'id' => $personalId,
            'nombre' => $person->nombre ?? null,
            'rut' => $person->rut ?? null,
        ];

        $openLog = PackingLineAttendance::where('personal_id', $personalId)
            ->whereNull('fecha_hora_entrada')
            ->latest('fecha_hora_salida')
            ->first();

        if ($openLog) {
            $openLog->fecha_hora_entrada = $now;
            if ($openLog->fecha_hora_salida) {
                $openLog->minutos = $openLog->fecha_hora_salida->diffInMinutes($now);
            }
            $openLog->save();

            return response()->json([
                'success' => true,
                'message' => 'Entrada registrada correctamente.',
                'action' => 'entrada',
                'person' => $personData,
            ], 200);
        }

        PackingLineAttendance::create([
            'personal_id' => $personalId,
            'location_id' => $locationId,
            'fecha_hora_salida' => $now,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Salida registrada correctamente.',
            'action' => 'salida',
            'person' => $personData,
        ], 200);
    }
}
