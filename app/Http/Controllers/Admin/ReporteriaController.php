<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencium;
use App\Models\Personal;
use App\Models\Locacion;
use App\Models\Area;
use App\Models\Turno;
use App\Models\FrecuenciaTurno;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class ReporteriaController extends Controller
{

    public function getDatosgenerales()
    {
        //Manejo de fechas

        $mesEnCurso = date('m');
        $diaActual = date('d');
        $agnoActual = date('Y');

        $asistenciaMes = Asistencium::whereMonth('fecha_hora', $mesEnCurso)->whereYear('fecha_hora', $agnoActual)->count();
        $asistenciaDia = Asistencium::whereDay('fecha_hora', $diaActual)->whereMonth('fecha_hora', $mesEnCurso)->whereYear('fecha_hora', $agnoActual)->count();
        $asistenciaYear = Asistencium::whereYear('fecha_hora', $agnoActual)->count();

        $cantPersonalEsperado = Locacion::where('cantidad_personal', '>', 0)->where('locacion_padre_id', 1)->sum('cantidad_personal') * 3;

        $cantPersonalActuales = $asistenciaDia;
        $cantPersonasAusentes = $cantPersonalEsperado - $cantPersonalActuales;

        $porcentajeAusencias = $cantPersonalEsperado > 0 ? round(($cantPersonasAusentes / $cantPersonalEsperado) * 100, 2) : 0;

        $cantPersonasLunes = Asistencium::whereDay('fecha_hora', 1)->whereMonth('fecha_hora', $mesEnCurso)->whereYear('fecha_hora', $agnoActual)->count();

        $date = new DateTime();
        $date->modify('this week monday');
        $lunesSemanaActual = $date->format('Y-m-d'); // Formatear la fecha al formato 'Y-m-d'

        // Obtener el número de registros de asistencia en la fecha del lunes de la semana actual
        $cantPersonasLunes = Asistencium::whereDate('fecha_hora', $lunesSemanaActual)->count();
        $cantPersonasEsperadasLunes =
            $date->modify('this week thursday');
        $martesSemanaActual = $date->format('Y-m-d'); // Formatear la fecha al formato 'Y-m-d'
        $cantPersonasMartes = Asistencium::whereDate('fecha_hora', $martesSemanaActual)->count();
        $date->modify('this week wednesday');
        $miercolesSemanaActual = $date->format('Y-m-d'); // Formatear la fecha al formato 'Y-m-d'
        $cantPersonasMiercoles = Asistencium::whereDate('fecha_hora', $miercolesSemanaActual)->count();
        $date->modify('this week tuesday');
        $juevesSemanaActual = $date->format('Y-m-d'); // Formatear la fecha al formato 'Y-m-d'
        $cantPersonasJueves = Asistencium::whereDate('fecha_hora', $lunesSemanaActual)->count();
        $date->modify('this week friday');
        $viernesSemanaActual = $date->format('Y-m-d'); // Formatear la fecha al formato 'Y-m-d'
        $cantPersonasViernes = Asistencium::whereDate('fecha_hora', $viernesSemanaActual)->count();
        $date->modify('this week saturday');
        $sabadoSemanaActual = $date->format('Y-m-d'); // Formatear la fecha al formato 'Y-m-d'
        $cantPersonasSabado = Asistencium::whereDate('fecha_hora', $sabadoSemanaActual)->count();
        $date->modify('this week sunday');
        $domingoSemanaActual = $date->format('Y-m-d'); // Formatear la fecha al formato 'Y-m-d'
        $cantPersonasDomingo = Asistencium::whereDate('fecha_hora', $domingoSemanaActual)->count();
        $lunesSemanaActual = now()->startOfWeek()->format('Y-m-d');
        $domingoSemanaActual = now()->startOfWeek()->addDays(7)->format('Y-m-d');

        $asistenciasPorDia = Asistencium::selectRaw('DAYNAME(fecha_hora) as dia, COUNT(*) as total')
            ->whereBetween('fecha_hora', [$lunesSemanaActual, $domingoSemanaActual])
            ->orderBy('fecha_hora')
            ->groupBy('dia')
            ->get();


        $ubicacionesConCobertura = Locacion::where('locacion_padre_id', '!=', 1)->withCount(['asistencias as total_asistencias'])
            ->get()
            ->filter(function ($ubicacion) {
                return $ubicacion->total_asistencias >= $ubicacion->cantidad_personal;
            })->count();

        $totalUbicaciones = Locacion::where('locacion_padre_id', '!=', 1)->count();
        $porcentajeCobertura = $totalUbicaciones > 0 ? round(($ubicacionesConCobertura / $totalUbicaciones) * 100, 2) : 0;


        //Gráficos
        $asistenciasPieChart = Asistencium::selectRaw('DAYNAME(fecha_hora) as dia, COUNT(*) as total')
            ->whereBetween('fecha_hora', [$lunesSemanaActual, $viernesSemanaActual]) // Filtrar por fechas de la semana
            ->orderBy('fecha_hora')
            ->groupBy('dia', 'fecha_hora')
            ->get();
        $asistenciasxUbicacion = Asistencium::selectRaw('COUNT(*) as total,locacions.nombre as locacion')
            ->join('locacions', 'asistencia.locacion_id', '=', 'locacions.id')
            ->whereBetween('fecha_hora', [$lunesSemanaActual, $viernesSemanaActual]) // Filtrar por fechas de la semana
            ->groupBy('locacion')
            ->get();
        //asistencia x turno
        $asistenciasPorTurno = Asistencium::selectRaw('frecuencia_turnos.nombre as turno, COUNT(*) as total')
            ->join('frecuencia_turnos', 'asistencia.turno_id', '=', 'frecuencia_turnos.id')
            ->whereBetween('fecha_hora', [$lunesSemanaActual, $viernesSemanaActual])
            ->groupBy('turno')
            ->orderBy('turno')
            ->get();

        // Ahora obtienes la cantidad esperada para cada turno
        $turnosEsperados = [
            1 => 50,  // Turno 1: 50 personas esperadas
            2 => 40,  // Turno 2: 40 personas esperadas
            3 => 30   // Turno 3: 30 personas esperadas
        ];

        // Puedes calcular el porcentaje de cumplimiento para cada turno

        $asistenciasConCumplimiento = $asistenciasPorTurno->map(function ($asistencia) use ($turnosEsperados) {
            $totalEsperado = $turnosEsperados[$asistencia->turno] ?? 0;
            $porcentajeCumplimiento = $totalEsperado > 0 ? ($asistencia->total / 593) * 100 : 0;
            return [
                'turno' => $asistencia->turno,
                'total' => $asistencia->total,
                'cumplimiento' => $porcentajeCumplimiento
            ];
        });


        //$asistenciaMes=Asistencium::where
        return response()->json([
            'mesEnCurso' => $mesEnCurso,
            'diaActual' => $diaActual,
            'agnoActual' => $agnoActual,
            'asistenciaMes' => $asistenciaMes,
            'asistenciaDia' => $asistenciaDia,
            'asistenciaYear' => $asistenciaYear,
            'cantPersonalEsperado' => $cantPersonalEsperado,
            'cantPersonalActuales' => $cantPersonalActuales,
            'cantPersonasAusentes' => $cantPersonasAusentes,
            'porcentajeAusencias' => $porcentajeAusencias,
            'cantPersonasLunes' => $cantPersonasLunes,
            'cantPersonasMartes' => $cantPersonasMartes,
            'cantPersonasMiercoles' => $cantPersonasMiercoles,
            'cantPersonasJueves' => $cantPersonasJueves,
            'cantPersonasViernes' => $cantPersonasViernes,
            'cantPersonasSabado' => $cantPersonasSabado,
            'cantPersonasDomingo' => $cantPersonasDomingo,
            'asistenciasPorDia' => $asistenciasPorDia,
            'porcentajeCobertura' => $porcentajeCobertura,
            'totalUbicaciones' => $totalUbicaciones,
            'asistenciaPieChart' => $asistenciasPieChart,
            'asistenciasxUbicacion' => $asistenciasxUbicacion,
            'asistenciasConCumplimiento' => $asistenciasConCumplimiento,
        ], 200);
    }
}
