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

        $asistenciasPorDia = Asistencium::selectRaw('DAYNAME(fecha_hora) as dia, COUNT(*) as total')
            ->groupBy('dia')
            ->get();


        $ubicacionesConCobertura = Locacion::where('locacion_padre_id', '!=', 1)->withCount(['asistencias as total_asistencias'])
            ->get()
            ->filter(function ($ubicacion) {
                return $ubicacion->total_asistencias >= $ubicacion->cantidad_personal;
            })->count();

        $totalUbicaciones = Locacion::where('locacion_padre_id', '!=', 1)->count();
        $porcentajeCobertura = $totalUbicaciones > 0 ? ($ubicacionesConCobertura / $totalUbicaciones) * 100 : 0;

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

        ], 200);
    }
}
