<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\personal;
use App\Models\Attendance;
use App\Models\Locacion;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function showConfirmation(Request $request)
    {
        $personId = $request->query('person_id');
        $person = null;
        $supervisorLocation = null;
        $error = null;
        $source = $request->query('source', 'automatico');

        if ($personId) {
            $person = personal::where('rut', $personId)->first();
            if (!$person) {
                $error = 'No se encontró personal con el RUT proporcionado.';
            }
        }

        // Get the logged-in user's associated personal record
        $loggedInUser = Auth::user()->load('personal.assignedLocation');
        if ($loggedInUser && $loggedInUser->personal) {
            $supervisorLocation = $loggedInUser->personal->assignedLocation->nombre ?? 'Ubicación no asignada';
        }

        return view('admin.attendance.confirm', compact('person', 'supervisorLocation', 'error', 'source'));
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
}
