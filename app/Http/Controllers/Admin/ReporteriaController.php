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
use DB;

class ReporteriaController extends Controller
{


    public function getSabana()
    {
        $mesEnCurso = date('m');
        $asistencias = Asistencium::whereMonth('fecha_hora', $mesEnCurso)->with('personal', 'locacion', 'turno')->get();

        return response()->json($asistencias);
    }

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
        $asistenciasPieChart = Asistencium::selectRaw('DAYNAME(fecha_hora) as dia, COUNT(*) as total,fecha_hora')
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
    public function obtenerDatosReporte(Request $request)
    {
        return view('admin.reporteria.stockinventario');
    }

    public function obtieneRecepcionDatosRecepcion(Request $request)
    {

        $datosSinProcesar = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                DB::RAW("COUNT(DISTINCT lote_recepcion) AS cantidad"),
                DB::RAW("SUM(cantidad) AS cantidad_total"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'destruccion_tipo',

            )
            ->where('destruccion_tipo', '=', '')
            ->groupBy('destruccion_tipo')
            ->get();
        $datosProcesados = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                DB::RAW("COUNT(DISTINCT lote_recepcion) AS cantidad"),
                DB::RAW("SUM(cantidad) AS cantidad_total"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'destruccion_tipo',

            )
            ->where('destruccion_tipo', '=', 'PRN')
            ->groupBy('destruccion_tipo')
            ->get();
        $maximaEsperaHoras = DB::connection("sqlsrv")->table('V_PKG_Recepcion_FG')
            ->selectRaw("DATEDIFF(HOUR, fecha_g_recepcion, GETDATE()) AS horas_en_espera")
            ->addSelect('destruccion_tipo', 'lote_recepcion', 'fecha_g_recepcion')
            ->where('destruccion_tipo', '=', '')
            ->groupBy('lote_recepcion', 'destruccion_tipo', 'fecha_g_recepcion')
            ->orderBy('horas_en_espera', 'DESC')
            ->first();
        $nota_calidad = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                DB::RAW("SUM(cantidad) AS cantidad"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'nota_calidad',
                'destruccion_tipo',
            )
            ->where('destruccion_tipo', '=', 'PRN')
            ->groupBy('nota_calidad', 'destruccion_tipo')
            ->get();

        $pesoxFecha = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                DB::RAW("SUM(cantidad) AS cantidad"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'fecha_g_recepcion_sh',

            )
            ->where('destruccion_tipo', '=', 'PRN')
            ->groupBy('fecha_g_recepcion_sh', 'destruccion_tipo')
            ->get();
        // Consulta principal


        return response()->json([
            "datosSinProcesar" => $datosSinProcesar,
            "datosProcesados" => $datosProcesados,
            "maximaEsperaHoras" => $maximaEsperaHoras,
            "nota_calidad" => $nota_calidad,
            "pesoxFecha" => $pesoxFecha
        ], 200);
    }

    public function obtieneDatosStockInventario(Request $request)
    {

        $datos = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                'id_empresa',
                DB::RAW("SUM(cantidad) AS cantidad"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'lote_recepcion',
                'n_empresa',
                'c_empresa',
                'codigo_sag_empresa',
                'CSG',
                'id_g_recepcion',
                'tipo_g_recepcion',
                'numero_g_recepcion',
                'fecha_g_recepcion_sh',
                'Hora_g_Recepcion',
                'id_emisor',
                'r_emisor',
                'c_emisor',
                'n_emisor',
                'Codigo_Sag_emisor',
                'id_exportadora',
                'r_exportadora',
                'c_exportadora',
                'n_exportadora',
                'tipo_documento_recepcion',
                'numero_documento_recepcion',
                'fecha_cosecha_sf',
                'id_grupo',
                'r_grupo',
                'n_grupo',
                'id_productor',
                'r_productor',
                'c_productor',
                'NS_Productor',
                'n_productor',
                'id_predio',
                'c_predio',
                'n_predio',
                'id_centrocosto',
                'c_centrocosto',
                'n_centrocosto',
                'id_familia',
                'c_familia',
                'n_familia',
                'id_especie',
                'c_especie',
                'n_especie',
                'id_variedad',
                'c_variedad',
                'n_variedad',
                'id_categoria',
                'c_categoria',
                'n_categoria',
                't_categoria',
                'id_categoria_st',
                'n_categoria_st',
                'id_calibre',
                'c_calibre',
                'n_calibre',
                'id_serie',
                'c_serie',
                'n_serie',
                'liquidada',
                'control_calidad',
                'id_bodega',
                'c_bodega',
                'n_bodega',
                'destruccion_tipo',
                'destruccion_id',
                'creacion_tipo',
                'creacion_id',
                'numero',
                'c_turno',
                'n_turno',
                'nota_calidad',
                'n_estado',
                'id_tratamiento',
                'C_Tratamiento',
                'N_tratamiento',
                'fecha_hora_destruccion',
                'Estadia',
                'cuenta_pallets',
                'Id_productor_rotulado',
                'n_productor_rotulado',
                'csg_productor_rotulado'
            )
            ->groupBy(
                'lote_recepcion',
                'id_empresa',
                'n_empresa',
                'c_empresa',
                'codigo_sag_empresa',
                'CSG',
                'id_g_recepcion',
                'tipo_g_recepcion',
                'numero_g_recepcion',
                'fecha_g_recepcion_sh',
                'Hora_g_Recepcion',
                'id_emisor',
                'r_emisor',
                'c_emisor',
                'n_emisor',
                'Codigo_Sag_emisor',
                'id_exportadora',
                'r_exportadora',
                'c_exportadora',
                'n_exportadora',
                'tipo_documento_recepcion',
                'numero_documento_recepcion',
                'fecha_cosecha_sf',
                'id_grupo',
                'r_grupo',
                'n_grupo',
                'id_productor',
                'r_productor',
                'c_productor',
                'NS_Productor',
                'n_productor',
                'id_predio',
                'c_predio',
                'n_predio',
                'id_centrocosto',
                'c_centrocosto',
                'n_centrocosto',
                'id_familia',
                'c_familia',
                'n_familia',
                'id_especie',
                'c_especie',
                'n_especie',
                'id_variedad',
                'c_variedad',
                'n_variedad',
                'id_categoria',
                'c_categoria',
                'n_categoria',
                't_categoria',
                'id_categoria_st',
                'n_categoria_st',
                'id_calibre',
                'c_calibre',
                'n_calibre',
                'id_serie',
                'c_serie',
                'n_serie',
                'liquidada',
                'control_calidad',
                'id_bodega',
                'c_bodega',
                'n_bodega',
                'destruccion_tipo',
                'destruccion_id',
                'creacion_tipo',
                'creacion_id',
                'numero',
                'c_turno',
                'n_turno',
                'nota_calidad',
                'n_estado',
                'id_tratamiento',
                'C_Tratamiento',
                'N_tratamiento',
                'fecha_hora_destruccion',
                'Estadia',
                'cuenta_pallets',
                'Id_productor_rotulado',
                'n_productor_rotulado',
                'csg_productor_rotulado'
            )
            ->orderByDesc('fecha_g_recepcion_sh')
            ->get(); //DatosCaja::whereBetween('FechaProduccion', ['2023-11-11', '2023-11-12'])->get(); //dd($request->fecha_inicio)


        return response()->json($datos, 200);
    }
}
