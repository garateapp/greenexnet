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
use Yajra\DataTables\Facades\DataTables;

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

        $asistenciasPorDia = Asistencium::selectRaw('DAYNAME(fecha_hora) as dia,COUNT(*) as total')
            //->whereBetween('fecha_hora', [$lunesSemanaActual, $domingoSemanaActual])
            ->orderBy('dia')
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
            //->whereBetween('fecha_hora', [$lunesSemanaActual, $viernesSemanaActual]) // Filtrar por fechas de la semana
            ->orderBy('fecha_hora')
            ->groupBy('dia', 'fecha_hora')
            ->get();
        $asistenciasxUbicacion = Asistencium::selectRaw('COUNT(*) as total,locacions.nombre as locacion')
            ->join('locacions', 'asistencia.locacion_id', '=', 'locacions.id')
            //->whereBetween('fecha_hora', [$lunesSemanaActual, $viernesSemanaActual]) // Filtrar por fechas de la semana
            ->groupBy('locacion')
            ->get();
        //asistencia x turno
        $asistenciasPorTurno = Asistencium::selectRaw('frecuencia_turnos.nombre as turno, COUNT(*) as total')
            ->join('frecuencia_turnos', 'asistencia.turno_id', '=', 'frecuencia_turnos.id')
            //->whereBetween('fecha_hora', [$lunesSemanaActual, $viernesSemanaActual])
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
                'id_especie',
            )
            ->where('destruccion_tipo', '=', '')
            ->groupBy('nota_calidad', 'destruccion_tipo', 'id_especie')
            ->get();

        $pesoxFecha = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                DB::RAW("SUM(cantidad) AS cantidad"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'destruccion_tipo',
            )
            ->where('destruccion_tipo', '=', 'PRN')
            ->groupBy('destruccion_tipo')
            ->get();
        // Consulta principal
        $variedadxCereza = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                DB::RAW("SUM(cantidad) AS cantidad"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'destruccion_tipo',
                'n_variedad',
            )
            ->where('destruccion_tipo', '=', '')
            ->where('id_especie', '=', '7')
            ->groupBy('destruccion_tipo', 'n_variedad')
            ->get();
        return response()->json([
            "datosSinProcesar" => $datosSinProcesar,
            "datosProcesados" => $datosProcesados,
            "maximaEsperaHoras" => $maximaEsperaHoras,
            "nota_calidad" => $nota_calidad,
            "pesoxFecha" => $pesoxFecha,
            'variedadxCereza' => $variedadxCereza,
        ], 200);
    }
    public function obtieneDatosRecepcionProductor(Request $request)
    {
        $datos = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                DB::RAW("SUM(cantidad) AS cantidad"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'lote_recepcion',
                'n_empresa',
                'fecha_g_recepcion_sh',
                'Hora_g_Recepcion',
                'n_exportadora',
                'fecha_cosecha_sf',
                'NS_Productor',
                'n_productor',
                'n_predio',
                'n_especie',
                'n_variedad',
                'n_categoria',
                't_categoria',
                'id_categoria_st',
                'n_categoria_st',
                'n_calibre',
                'destruccion_tipo',
                'nota_calidad'
            )
            ->where('destruccion_tipo', '=', '')
            ->where('nota_calidad', '=', str_replace("Calidad ", "", $request->nota_calidad))
            ->groupBy(
                'lote_recepcion',
                'n_empresa',
                'fecha_g_recepcion_sh',
                'Hora_g_Recepcion',
                'n_exportadora',
                'fecha_cosecha_sf',
                'NS_Productor',
                'n_productor',
                'n_predio',
                'n_especie',
                'n_variedad',
                'n_categoria',
                't_categoria',
                'id_categoria_st',
                'n_categoria_st',
                'n_calibre',
                'destruccion_tipo',
                'nota_calidad'
            )
            ->orderByDesc('fecha_g_recepcion_sh')
            ->get();
        return response()->json($datos, 200);
    }
    public function obtieneDatosStockInventario(Request $request)
    {

        $datos = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(

                // DB::RAW("SUM(cantidad) AS cantidad"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'numero_g_recepcion',
                'n_empresa',
                'n_exportadora',
                'n_productor',
                'n_especie',
                'nota_calidad',
                'n_variedad',
                DB::RAW("DATEDIFF(HOUR, fecha_g_recepcion, GETDATE()) AS horas_en_espera"),
                DB::RAW("MAX(DATEDIFF(HOUR, fecha_g_recepcion, GETDATE())) AS max_horas_en_espera")
            )
            ->where('destruccion_tipo', '=', '')

            ->groupBy(

                'n_empresa',
                'n_exportadora',
                'n_productor',
                'n_especie',
                'nota_calidad',
                'n_variedad',
                'numero_g_recepcion',
                'fecha_g_recepcion',


            )
            ->orderBy('n_variedad')
            ->orderBy('nota_calidad')
            ->get();
        $data = $datos;
        $result = [];
        $numero_g_rececpcionanterior = 0;
        $hora_espera_max = 0;
        foreach ($data as $item) {
            $nivel1Key = $item->n_variedad . '|' . $item->nota_calidad;

            // Nivel 1: Agrupación por n_variedad y nota_calidad
            if (!isset($result[$nivel1Key])) {
                $result[$nivel1Key] = [
                    'n_variedad' => $item->n_variedad,
                    'nota_calidad' => $item->nota_calidad,
                    'peso_neto' => 0,
                    'max_horas_en_espera' => $item->max_horas_en_espera,
                    'nivel_2' => [],
                ];
            }

            // Incrementar el peso total
            $result[$nivel1Key]['peso_neto'] += (float) $item->peso_neto;
            if ($item->horas_en_espera > $hora_espera_max) {
                $hora_espera_max = $item->horas_en_espera;
                $result[$nivel1Key]['horas_en_espera'] = $item->horas_en_espera;
            }

            // Nivel 2: Agregar directamente todos los registros
            $nivel2Entry = [];
            if ($numero_g_rececpcionanterior != $item->numero_g_recepcion) {
                $nivel2Entry = [
                    'n_variedad' => $item->n_variedad,
                    'nota_calidad' => $item->nota_calidad,
                    'peso_neto' => $item->peso_neto,
                    'n_empresa' => $item->n_empresa,
                    'n_exportadora' => $item->n_exportadora,
                    'n_productor' => $item->n_productor,
                    'n_especie' => $item->n_especie,
                    'horas_en_espera' => $item->horas_en_espera,
                    'numero_g_recepcion' => $item->numero_g_recepcion,
                ];
                $result[$nivel1Key]['nivel_2'][] = $nivel2Entry;
            } else {
                $nivel2Entry['peso_neto'] += (float) $item->peso_neto;
            }
        }

        // Formatear resultado para JSON
        $finalResult = [];

        foreach ($result as $key => $group) {
            // Nivel 3: Datos adicionales por cada entrada de nivel 2
            foreach ($group['nivel_2'] as &$nivel2Entry) {
                $nivel2Entry['nivel_3'] = [
                    'n_empresa' => $nivel2Entry['n_empresa'],
                    'n_exportadora' => $nivel2Entry['n_exportadora'],
                    'n_productor' => $nivel2Entry['n_productor'],
                    'n_especie' => $nivel2Entry['n_especie'],
                    'horas_en_espera' => $nivel2Entry['horas_en_espera'],
                    'numero_g_recepcion' => $nivel2Entry['numero_g_recepcion'],
                ];
            }
            $finalResult[] = $group;
        }
        //dd($finalResult);



        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search');
        $order = $request->get('order');
        $columns = $request->get('columns');

        // Aquí debes implementar la lógica para obtener los datos
        // basándote en los parámetros de búsqueda, ordenamiento y paginación

        // Aplicar filtros


        $totalRecords = $datos->count(); // Obtén el total de registros
        $filteredRecords = 0; // Obtén el total de registros filtrados
        // Opcional: Agrupar por lote en el backend (si el frontend no lo hace)
        // foreach ($datos as $item) {
        //     $item->reporte = $this->obtieneInformeCalidad($item->numero_g_recepcion);
        // }
        //$table = DataTables::of($groupedData);
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $finalResult
        ], 200);
    }
    public function obtienePesoxDia()
    {
        $datos = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'n_exportadora',
                'n_especie',
                'n_variedad',
                'fecha_g_recepcion_sh'
            )
            ->where('destruccion_tipo', '=', '')

            ->groupBy(
                'fecha_g_recepcion_sh',
                'n_empresa',
                'n_exportadora',
                'n_especie',
                'n_variedad',


            )
            ->get();

        //...

        return response()->json($datos);
    }
    public function obtieneInformeCalidad($numero_g_recepcion)
    {
        $informe = DB::connection("mysqlAppGreenex")->table('recepcions')
            ->select(
                'id'
            )
            ->where('numero_g_recepcion', '=', $numero_g_recepcion)
            ->first();
        $url = "https://appgreenex.cl/download/recepcion/" . $informe->id . "pdf";
        return $url;
    }
}
