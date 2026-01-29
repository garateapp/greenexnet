<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\personal;
use App\Models\Attendance;
use App\Models\ControlAccessLog;
use App\Models\Locacion;
use App\Models\PackingLineAttendance;
use App\Models\Entidad;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
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
                $error = 'No se encontro personal con el RUT proporcionado.';
            }
        }

        $packingLineIds = collect(config('packing.line_locations', []))
            ->map(fn ($value) => (int) $value)
            ->filter()
            ->values();

        $loggedInUser = Auth::user()->load('personal.assignedLocation');
        if ($loggedInUser && $loggedInUser->personal) {
            $supervisorLocation = $loggedInUser->personal->assignedLocation->nombre ?? 'Ubicacion no asignada';
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
            return response()->json(['success' => false, 'message' => 'Ubicacion del supervisor no encontrada.'], 400);
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
                'timestamp' => $request->input('fecha'),
                'entry_type' => $request->input('entry_type'),
            ]);

            return response()->json(['success' => true, 'message' => 'Asistencia confirmada con exito.'], 200);
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
                $errors[] = "No se encontro personal para el RUT {$entry['rut']}.";
                continue;
            }

            $location = Locacion::where('id', $entry['location_id'])->first();
            if (!$location) {
                $errors[] = "La ubicacion {$entry['location_id']} no existe.";
                continue;
            }

            try {
                Attendance::create([
                    'personal_id' => $person->id,
                    'location' => $location->id,
                    'timestamp' => isset($entry['timestamp'])
    ? Carbon::parse($entry['timestamp'])->setTimezone('America/Santiago')
    : now('America/Santiago'),
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
        try {
            $startDate = $this->parseDateOrDefault($request->input('start_date'), Carbon::now()->subDays(7)->startOfDay(), false);
            $endDate = $this->parseDateOrDefault($request->input('end_date'), Carbon::now()->endOfDay(), true);
        } catch (\InvalidArgumentException $e) {
            $startDate = Carbon::now()->subDays(7)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            return response()->json(['message' => $e->getMessage()], 400);
        }

        $locationFilter = $request->input('location_filter');
        $shiftFilter = $request->input('shift_filter');
        if (!$shiftFilter || $shiftFilter === 'todos') {
            $shiftFilter = $this->determineShift(Carbon::now());
        }

        $baseQuery = Attendance::with(['personal', 'personal.entidad'])
            ->whereBetween('timestamp', [$startDate, $endDate]);

        if ($locationFilter) {
            $baseQuery->where('location', (int) $locationFilter);
        }

        $allAttendanceRecords = $baseQuery->get();

        $attendanceRecords = $allAttendanceRecords->filter(function ($record) use ($shiftFilter) {
            if ($shiftFilter === 'todos' || !$shiftFilter) {
                return true;
            }

            $shift = $this->determineShift(Carbon::parse($record->timestamp));
            return $shift === $shiftFilter;
        })->values();

        $locationNames = Locacion::whereIn('id', $allAttendanceRecords->pluck('location')->filter()->unique())
            ->pluck('nombre', 'id');
        $locationsMeta = Locacion::select('id', 'nombre', 'locacion_padre_id')->get()->keyBy('id');
        $unitecParents = collect([131, 132, 133]);
        $unitecParentNames = Locacion::whereIn('id', $unitecParents)->pluck('nombre', 'id');
        $entityNames = Entidad::whereIn('id', $allAttendanceRecords->pluck('personal.entidad_id')->filter()->unique())
            ->pluck('nombre', 'id');

        $shiftComparison = [
            'dia' => [
                'label' => 'Turno Dia',
                'total' => 0,
                'unique' => 0,
            ],
            'noche' => [
                'label' => 'Turno Noche',
                'total' => 0,
                'unique' => 0,
            ],
        ];

        foreach (['dia', 'noche'] as $shiftKey) {
            $shifted = $allAttendanceRecords->filter(function ($record) use ($shiftKey) {
                return $this->determineShift(Carbon::parse($record->timestamp)) === $shiftKey;
            });

            $shiftComparison[$shiftKey]['total'] = $shifted->count();
            $shiftComparison[$shiftKey]['unique'] = $shifted->pluck('personal_id')->unique()->count();
        }

        // Attendance ruts (normalized, without verifier digit)
        $attendanceRutSet = $attendanceRecords
            ->map(function ($record) {
                return $this->normalizeRutWithoutVerifier($record->personal->rut ?? null);
            })
            ->filter()
            ->unique()
            ->flip();

        $tableData = [];
        $chartData = []; // For attendance by date
        $locationChartData = []; // For attendance by location
        $locationDateChartData = []; // For attendance by location and date
        $locationDepartmentChartData = []; // For attendance by location and department
        foreach ($attendanceRecords as $record) {
            $locationName = $locationNames[$record->location] ?? 'N/A';
            $locationMeta = $locationsMeta[$record->location] ?? null;
            $parentId = $locationMeta->locacion_padre_id ?? null;
            $parentName = $parentId ? ($locationsMeta[$parentId]->nombre ?? 'N/A') : null;
            $locationDisplay = $parentName ? "{$parentName} - {$locationName}" : $locationName;
            $entityName = $entityNames[$record->personal->entidad_id ?? null] ?? 'Sin entidad';

            $tableData[] = [
                'date' => Carbon::parse($record->timestamp)->format('d-m-Y'),
                'personal_name' => $record->personal->nombre ?? 'N/A',
                'personal_rut' => $record->personal->rut ?? 'N/A',
                'location_name' => $locationDisplay,
                'location_parent' => $parentName,
                'entity_name' => $entityName,
                'time' => Carbon::parse($record->timestamp)->format('H:i:s'),
            ];

            // Chart Data: Attendance by Date
            $dateKey = Carbon::parse($record->timestamp)->format('Y-m-d');
            if (!isset($chartData[$dateKey])) {
                $chartData[$dateKey] = 0;
            }
            $chartData[$dateKey]++;

            // Chart Data: Attendance by Location
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

            // Chart Data: Attendance by Location and Department (Entidad)
            if (!isset($locationDepartmentChartData[$locationName])) {
                $locationDepartmentChartData[$locationName] = [];
            }
            if (!isset($locationDepartmentChartData[$locationName][$entityName])) {
                $locationDepartmentChartData[$locationName][$entityName] = 0;
            }
            $locationDepartmentChartData[$locationName][$entityName]++;
        }

        // Rebuild line/parent charts using all attendance records (to always show ambos turnos)
        $locationParentDateChartData = []; // For attendance by parent location (UNITEC lines) and date
        $locationParentDateShiftChartData = []; // For attendance by parent location, date and shift
        $locationParentChildrenTotals = []; // Totals per child location under each parent
        $locationParentChildrenShiftTotals = []; // Totals per child location under each parent, split by shift
        foreach ($allAttendanceRecords as $record) {
            $locationName = $locationNames[$record->location] ?? 'N/A';
            $locationMeta = $locationsMeta[$record->location] ?? null;
            $parentId = $locationMeta->locacion_padre_id ?? null;
            $parentKey = $unitecParents->contains($parentId)
                ? ($unitecParentNames[$parentId] ?? "UNITEC {$parentId}")
                : 'Otras Ubicaciones';
            $shift = $this->determineShift(Carbon::parse($record->timestamp));

            // Chart Data: Attendance by parent location (UNITEC lines) and date
            $dateKey = Carbon::parse($record->timestamp)->format('Y-m-d');
            if (!isset($locationParentDateChartData[$dateKey])) {
                $locationParentDateChartData[$dateKey] = [];
            }
            if (!isset($locationParentDateChartData[$dateKey][$parentKey])) {
                $locationParentDateChartData[$dateKey][$parentKey] = 0;
            }
            $locationParentDateChartData[$dateKey][$parentKey]++;

            // Chart Data: Attendance by parent location, date and shift
            if (!isset($locationParentDateShiftChartData[$dateKey])) {
                $locationParentDateShiftChartData[$dateKey] = [];
            }
            if (!isset($locationParentDateShiftChartData[$dateKey][$parentKey])) {
                $locationParentDateShiftChartData[$dateKey][$parentKey] = [
                    'dia' => 0,
                    'noche' => 0,
                ];
            }
            $locationParentDateShiftChartData[$dateKey][$parentKey][$shift]++;

            // Totals per child location under each parent
            if (!isset($locationParentChildrenTotals[$parentKey])) {
                $locationParentChildrenTotals[$parentKey] = [];
            }
            if (!isset($locationParentChildrenTotals[$parentKey][$locationName])) {
                $locationParentChildrenTotals[$parentKey][$locationName] = 0;
            }
            $locationParentChildrenTotals[$parentKey][$locationName]++;

            // Totals per child location split by shift
            if (!isset($locationParentChildrenShiftTotals[$parentKey])) {
                $locationParentChildrenShiftTotals[$parentKey] = [];
            }
            if (!isset($locationParentChildrenShiftTotals[$parentKey][$locationName])) {
                $locationParentChildrenShiftTotals[$parentKey][$locationName] = [
                    'dia' => 0,
                    'noche' => 0,
                ];
            }
            $locationParentChildrenShiftTotals[$parentKey][$locationName][$shift]++;
        }

        // Cross between ControlAccessLog (expected on site) and Attendance (recorded)
        $controlAccessOpen = ControlAccessLog::select('personal_id', 'nombre', 'departamento', 'primera_entrada', 'ultima_salida')
            ->whereNull('ultima_salida')
            ->whereBetween('primera_entrada', [$startDate->copy()->setTime(7, 0), $endDate])
            ->orderBy('primera_entrada')
            ->get();

        // Apply shift filter to ControlAccessLog entries
        if ($shiftFilter !== 'todos' && $shiftFilter) {
            $controlAccessOpen = $controlAccessOpen->filter(function ($log) use ($shiftFilter) {
                $ts = $log->primera_entrada ?? $log->fecha;
                if (!$ts) {
                    return false;
                }
                $shift = $this->determineShift(Carbon::parse($ts));
                return $shift === $shiftFilter;
            })->values();
        }
        // Avoid duplicates per persona
        $controlAccessOpen = $controlAccessOpen->unique('personal_id')->values();

        $departmentCrossData = [];

        // Attendance side: count by Entidad nombre
        foreach ($attendanceRecords as $record) {
            $departmentDisplay = $record->personal->entidad->nombre ?? 'Sin entidad';
            if(!$record->personal->entidad){

                Log::info("ControlAccessLogIngestController::store", [$record]);
            }
            $departmentKey = $this->normalizeDepartmentName($departmentDisplay);

            if (!isset($departmentCrossData[$departmentKey])) {
                $departmentCrossData[$departmentKey] = [
                    'department' => $departmentDisplay,
                    'expected' => 0,
                    'attendance' => 0,
                    'pass1' => 0,
                    'pass2' => 0,
                ];
            }

            $departmentCrossData[$departmentKey]['attendance']++;
            $pass = $this->determinePassWindow(Carbon::parse($record->timestamp));
            if ($pass === 'pass1') {
                $departmentCrossData[$departmentKey]['pass1']++;
            } elseif ($pass === 'pass2') {
                $departmentCrossData[$departmentKey]['pass2']++;
            }
        }

        // Control access side: count by departamento (fallback to Entidad if not present)
        foreach ($controlAccessOpen as $accessLog) {
            $departmentDisplay = trim($accessLog->departamento ?? '');
            if ($departmentDisplay === '') {
                // Try to resolve Entidad via personal
                $person = personal::where('rut', $accessLog->personal_id)->with('entidad')->first();
                $departmentDisplay = $person->entidad->nombre ?? 'Sin entidad';
            }
            $departmentKey = $this->normalizeDepartmentName($departmentDisplay);

            if (!isset($departmentCrossData[$departmentKey])) {
                $departmentCrossData[$departmentKey] = [
                    'department' => $departmentDisplay,
                    'expected' => 0,
                    'attendance' => 0,
                    'pass1' => 0,
                    'pass2' => 0,
                ];
            }

            $departmentCrossData[$departmentKey]['expected']++;
        }

        $departmentCrossData = collect($departmentCrossData)
            ->map(function ($data) {
                $expectedCount = $data['expected'] ?? 0;
                $attendanceCount = $data['attendance'] ?? 0;

                return [
                    'department' => $data['department'],
                    'expected' => $expectedCount,
                    'attendance' => $attendanceCount,
                    'pass1' => $data['pass1'] ?? 0,
                    'pass2' => $data['pass2'] ?? 0,
                    'difference' => $expectedCount - $attendanceCount,
                ];
            })
            ->sortByDesc('expected')
            ->values()
            ->all();

        $todayStart = Carbon::today();
        $todayEnd = Carbon::today()->endOfDay();

        $kpis = [
            'today_total' => Attendance::whereBetween('timestamp', [$todayStart, $todayEnd])->count(),
            'today_unique' => Attendance::whereBetween('timestamp', [$todayStart, $todayEnd])->distinct('personal_id')->count('personal_id'),
            'range_total' => $attendanceRecords->count(),
            'range_unique' => $attendanceRecords->pluck('personal_id')->unique()->count(),
            'locations_with_attendance' => $attendanceRecords->pluck('location')->filter()->unique()->count(),
        ];

        return response()->json([
            'tableData' => $tableData,
            'chartData' => $chartData,
            'locationChartData' => $locationChartData,
            'locationDateChartData' => $locationDateChartData,
            'locationParentDateChartData' => $locationParentDateChartData,
            'locationParentDateShiftChartData' => $locationParentDateShiftChartData,
            'locationParentChildrenTotals' => $locationParentChildrenTotals,
            'locationParentChildrenShiftTotals' => $locationParentChildrenShiftTotals,
            'locationDepartmentChartData' => $locationDepartmentChartData,
            'shiftComparison' => $shiftComparison,
            'kpis' => $kpis,
            'departmentCrossData' => $departmentCrossData,
        ]);
    }
    function formatearRutConDv($rut) {


       // Inicializa el acumulador en 1
    $acumulador = 1;
    // Inicializa el contador en 0
    $contador = 0;
    // Mientras el RUT no sea igual a 0, continúa el bucle
    while ($rut != 0) {
        // Calcula el dígito verificador utilizando el algoritmo específico
        $acumulador = ($acumulador + ($rut % 10) * (9 - $contador++ % 6)) % 11;
        // Reduce el RUT al siguiente dígito
        $rut = (int)($rut / 10);
    }
    // Si el acumulador es diferente de 0, calcula el dígito verificador
    // utilizando el valor del acumulador más 47 en la tabla ASCII
    // de lo contrario, establece el dígito verificador en 'K'
    $dv = $acumulador ? chr($acumulador + 47) : 'K';

    // ---- FORMATEAR RUT ----
    $rutInvertido = strrev($rut);
    $rutFormateado = '';

    for ($i = 0; $i < strlen($rutInvertido); $i++) {
        if ($i > 0 && $i % 3 === 0) {
            $rutFormateado .= '.';
        }
        $rutFormateado .= $rutInvertido[$i];
    }

    $rutFormateado = strrev($rutFormateado);

    return $rutFormateado . '-' . $dv;
}
    private function parseDateOrDefault(?string $dateString, Carbon $default, bool $endOfDay = false): Carbon
    {
        if (!$dateString) {
            return $default;
        }

        $formats = ['m/d/Y', 'd-m-Y', 'd/m/Y', 'Y-m-d'];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $dateString);
                return $endOfDay ? $date->endOfDay() : $date->startOfDay();
            } catch (\Exception $e) {
                continue;
            }
        }

        throw new \InvalidArgumentException('Formato de fecha invalido.');
    }

    private function normalizeRutWithoutVerifier(?string $rut): ?string
    {
        if ($rut === null) {
            return null;
        }

        //$cleanRut = preg_replace('/[^0-9kK]/', '', (string) $rut);
        $rutSinDv=explode('-',$rut);
        $cleanRut = preg_replace('/[^0-9]/', '', $rutSinDv[0]);
        if ($cleanRut === '') {
            return null;
        }

        return $cleanRut;
    }

    private function normalizeDepartmentName(?string $department): string
    {
        $trimmed = trim($department ?? '');
        if ($trimmed === '') {
            return 'SIN ENTIDAD';
        }

        return Str::upper($trimmed);
    }

    private function determinePassWindow(Carbon $timestamp): ?string
    {
        $time = $timestamp->format('H:i');
        if ($time >= '08:00' && $time <= '13:00') {
            return 'pass1';
        }
        if ($time >= '14:00' && $time <= '17:30') {
            return 'pass2';
        }
        return null;
    }

    private function determineShift(Carbon $timestamp): string
    {
        $time = $timestamp->format('H:i');
        // Prioritize day shift if overlapping window; everything else counts as night
        if ($time >= '07:00' && $time <= '18:30') {
            return 'dia';
        }

        return 'noche';
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

    /**
     * Regresa el listado de personas presentes en control de acceso sin asistencia registrada.
     */
    public function missingAttendance(Request $request)
    {
        try {
            $startDate = $this->parseDateOrDefault($request->input('start_date'), Carbon::today()->startOfDay(), false)
                ->startOfDay();
            $endDate = $this->parseDateOrDefault($request->input('end_date'), Carbon::today()->endOfDay(), true)
                ->endOfDay();
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        $entidades = [4, 5, 6, 7, 9];
        Log::info("fechas: $startDate - $endDate", [$startDate, $endDate]);

        $dayStartTime = '07:00:00';   // 08:00 - 60 min
        $nightStartTime = '17:00:00'; // 18:00 - 60 min

        $baseSelect = 'p.rut, p.nombre, e.nombre as departamento, c.primera_entrada as primera_marca';

        $dayQuery = ControlAccessLog::from('control_access_logs as c')
            ->join('personals as p', 'c.personal_id', '=', 'p.codigo')
            ->join('entidads as e', 'e.id', '=', 'p.entidad_id')
            ->selectRaw($baseSelect)
            ->whereBetween('c.fecha', [$startDate, $endDate])
            ->whereIn('p.entidad_id', $entidades)
            ->whereRaw('TIME(c.primera_entrada) >= ? AND TIME(c.primera_entrada) < ?', [$dayStartTime, $nightStartTime])
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('attendances as a')
                    ->whereColumn('a.personal_id', 'p.id')
                    ->whereRaw(
                        'a.timestamp BETWEEN DATE_ADD(DATE(c.fecha), INTERVAL 7 HOUR) AND DATE_ADD(DATE(c.fecha), INTERVAL 18 HOUR)'
                    );
            });

        $nightQuery = ControlAccessLog::from('control_access_logs as c')
            ->join('personals as p', 'c.personal_id', '=', 'p.codigo')
            ->join('entidads as e', 'e.id', '=', 'p.entidad_id')
            ->selectRaw($baseSelect)
            ->whereBetween('c.fecha', [$startDate, $endDate])
            ->whereIn('p.entidad_id', $entidades)
            ->whereRaw('TIME(c.primera_entrada) >= ? OR TIME(c.primera_entrada) < ?', [$nightStartTime, $dayStartTime])
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('attendances as a')
                    ->whereColumn('a.personal_id', 'p.id')
                    ->whereRaw(
                        "a.timestamp BETWEEN DATE_ADD(DATE(c.fecha), INTERVAL 17 HOUR)
                         AND DATE_ADD(DATE_ADD(DATE_ADD(DATE(c.fecha), INTERVAL 1 DAY), INTERVAL 7 HOUR), INTERVAL 45 MINUTE)"
                    );
            });

        $faltantes = DB::query()
            ->fromSub($dayQuery->unionAll($nightQuery), 'faltantes')
            ->selectRaw('rut, nombre, departamento, MIN(primera_marca) as primera_marca')
            ->groupBy('rut', 'nombre', 'departamento')
            ->orderByDesc('primera_marca')
            ->get();
        return response()->json([
            'data' => $faltantes,
            'meta' => [
                'start_date' => $startDate->toDateTimeString(),
                'end_date' => $endDate->toDateTimeString(),
                'entidades' => $entidades,
            ],
        ]);
    }
}
