<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\personal; // Assuming your Personal model is named 'personal'
use App\Models\Attendance;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    public function showConfirmation(Request $request)
    {
        $personId = $request->query('person_id');
        $person = null;

        if ($personId) {
            // Assuming 'rut' is the unique identifier for personal
            $person = personal::where('rut', $personId)->first();
        }

        if (!$person) {
            // Handle case where person is not found
            abort(Response::HTTP_NOT_FOUND, 'Personal no encontrado.');
        }

        return view('admin.attendance.confirm', compact('person'));
    }

    public function storeAttendance(Request $request)
    {
        $request->validate([
            'person_id' => 'required|exists:personals,id',
            'location' => 'required|string|max:255',
        ]);

        try {
            Attendance::create([
                'personal_id' => $request->input('person_id'),
                'location' => $request->input('location'),
                'timestamp' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Asistencia confirmada con éxito.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al guardar la asistencia: ' . $e->getMessage()], 500);
        }
    }
}
