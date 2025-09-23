<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Locacion;
use App\Models\Turno;
use App\Models\PlanificacionPersonal;
use Illuminate\Http\Request;

class PlanificadorController extends Controller
{
    public function index()
    {
        $locaciones = Locacion::all();
        $turnos = Turno::all();
        return view('admin.planificadorpersonal.index', compact('locaciones', 'turnos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'planificacion' => 'required|array',
            'planificacion.*.locacion_id' => 'required|exists:locacions,id',
            'planificacion.*.turno_id' => 'required|exists:turnos,id',
            'planificacion.*.cantidad_personal_planificada' => 'required|integer|min:0',
        ]);

        $fecha = $request->input('fecha');

        foreach ($request->input('planificacion') as $planData) {
            PlanificacionPersonal::updateOrCreate(
                [
                    'fecha' => $fecha,
                    'locacion_id' => $planData['locacion_id'],
                    'turno_id' => $planData['turno_id'],
                ],
                [
                    'cantidad_personal_planificada' => $planData['cantidad_personal_planificada'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Planificación guardada exitosamente.');
    }

    public function getPlanificacionData(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
        ]);

        $fecha = $request->input('fecha');

        $planificacionData = PlanificacionPersonal::where('fecha', $fecha)->get();

        if ($planificacionData->isEmpty()) {
            $locaciones = Locacion::all();
            $turnos = Turno::all();
            $defaultPlanificacion = [];

            foreach ($locaciones as $locacion) {
                foreach ($turnos as $turno) {
                    $defaultPlanificacion[] = [
                        'locacion_id' => $locacion->id,
                        'turno_id' => $turno->id,
                        'cantidad_personal_planificada' => $locacion->cantidad_personal ?? 0, // Use cantidad_personal from Locacion, default to 0 if null
                    ];
                }
            }
            return response()->json($defaultPlanificacion);
        }

        return response()->json($planificacionData);
    }
}