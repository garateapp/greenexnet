<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencium;
use App\Models\Personal;
use App\Models\Locacion;
use App\Models\Area;
use App\Models\ClientesComex;
use App\Models\Embalaje;
use App\Models\Turno;
use App\Models\FrecuenciaTurno;
use App\Models\Embarque;
use App\Models\LiqCxCabecera;
use App\Models\LiquidacionesCx;
use App\Models\LiqCosto;
use App\Models\Costo;
use App\Models\ExcelDato;
use App\Models\Diccionario;
use App\Models\Nafe;
use Log;
use Carbon\Carbon;
use App\Models\MetasClienteComex;
use App\Imports\ExcelImport;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use DateTime;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SyncCajasJob;
use Illuminate\Support\Facades\Log as FacadesLog;

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
            ->where('id_empresa', '=', '1')
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
            ->where('id_empresa', '=', '1')
            ->groupBy('destruccion_tipo')
            ->get();
        $maximaEsperaHoras = DB::connection("sqlsrv")->table('V_PKG_Recepcion_FG')
            ->selectRaw("DATEDIFF(HOUR, fecha_g_recepcion, GETDATE()) AS horas_en_espera")
            ->addSelect('destruccion_tipo', 'lote_recepcion', 'fecha_g_recepcion')
            ->where('destruccion_tipo', '=', '')
            ->where('id_empresa', '=', '1')
            ->groupBy('lote_recepcion', 'destruccion_tipo', 'fecha_g_recepcion')
            ->orderBy('horas_en_espera', 'DESC')
            ->first();
        $nota_calidad = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                DB::RAW("SUM(cantidad) AS cantidad"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'nota_calidad',
                'id_especie',
            )
            ->where('destruccion_tipo', '=', '')
            ->where('id_empresa', '=', '1')
            ->where('id_especie', '=', '7')->groupBy('nota_calidad', 'id_especie')
            ->get();

        $pesoxFecha = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                DB::RAW("SUM(cantidad) AS cantidad"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'destruccion_tipo',
            )
            ->where('destruccion_tipo', '=', 'PRN')
            ->where('id_empresa', '=', '1')
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
            ->where('id_empresa', '=', '1')
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
            ->where('id_empresa', '=', '1')
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
            ->where('id_empresa', '=', '1')
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
            ->where('fecha_g_recepcion_sh', '>=', DB::RAW("DATEADD(DAY, -8, GETDATE())"))
            ->where('id_empresa', '=', '1')

            ->groupBy(
                'fecha_g_recepcion_sh',
                'n_empresa',
                'n_exportadora',
                'n_especie',
                'n_variedad',


            )
            ->get();
        $datosTotal = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'n_exportadora'



            )
            ->where('n_especie', '=', 'Cherries')
            ->groupBy(


                'n_exportadora'




            )
            ->get();
        $stockSemanal = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                DB::RAW('DATEPART(WEEK,  [fecha_g_recepcion_sh]) as numero_semana'),
                DB::RAW('SUM(peso_neto) as peso_neto'),
                'n_exportadora'
            )
            ->where('n_especie', '=', 'Cherries')
            ->groupByRaw('DATEPART(WEEK, fecha_g_recepcion_sh), n_exportadora')
            // ->orderByDesc('numero_semana')
            ->get();
        //...

        return response()->json(["data" => $datos, "totales" => $datosTotal, "stockSemanal" => $stockSemanal], 200);
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
    public function Transito()
    {
        return view('admin.reporteria.transito');
    }
    public function obtieneTransito()
    {
        $transito = DB::connection("sqlsrv")->table('dbo.V_PKG_Stock_Inventario')
            ->select(
                'n_variedad_original as n_variedad',
                'c_embalaje',
                'n_calibre',
                'n_etiqueta',
                DB::RAW("SUM(cantidad) as cantidad"),
                DB::RAW("SUM(peso_neto) as peso_neto"),


            )
            //->where('c_altura', '=', '240')
            ->where('n_categoria', '=', 'Cat 1')
            ->where('n_exportadora', '=', 'Greenex Spa')
            ->where('id_empresa', '=', '1')
            ->where('n_categoria', '!=', 'muestra')
            ->where('fecha_produccion', '<', DB::RAW("DATEADD(DAY, -2, GETDATE())"))
            ->groupBy(
                'n_variedad_original',
                'c_embalaje',
                'n_categoria',
                'n_calibre',
                'n_etiqueta',

            )
            ->get();
        $embalajes_detalle = Embalaje::all();
        // Paso 1: Convertir $embalajes_detalle a un Map con c_embalaje como clave
        $embalajesMap = Embalaje::all()->keyBy('c_embalaje');

        // Paso 2: Iterar sobre $transito y agregar las columnas de embalajes
        $transitoConDetalles = $transito->map(function ($item) use ($embalajesMap) {
            $c_embalaje = $item->c_embalaje; // Clave de coincidencia
            if ($embalajesMap->has($c_embalaje)) {
                // Combinar las propiedades de embalajes en el registro actual de tránsito
                $detalleEmbalaje = $embalajesMap->get($c_embalaje);

                // Agregar las propiedades del embalaje al registro de tránsito
                $item->caja = $detalleEmbalaje->caja;
                $item->kgxcaja = $detalleEmbalaje->kgxcaja;
                $item->cajaxpallet = $detalleEmbalaje->cajaxpallet;
                $item->altura_pallet = $detalleEmbalaje->altura_pallet;
                $item->tipo_embarque = $detalleEmbalaje->tipo_embarque;
            } else {
                // Si no hay coincidencia, puedes definir valores por defecto o nulos
                $item->caja = null;
                $item->kgxcaja = null;
                $item->cajaxpallet = null;
                $item->altura_pallet = null;
                $item->tipo_embarque = null;
            }
            return $item;
        });
        $antiguedad = DB::connection("sqlsrv")->table('dbo.V_PKG_Stock_Inventario')
            ->select(
                DB::RAW("SUM(cantidad) AS cantidad"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                "fecha_produccion as fecha_minima"

            )
            //->where('destruccion_tipo', '=', '')
            ->where('id_especie', '=', '7')
            //->where('id_altura', '=', '8')
            ->where('id_empresa', '=', '1')
            ->where(DB::raw("DATEPART(YEAR,fecha_produccion)"), '>', '2023')
            ->where('n_categoria', '!=', 'muestra')
            ->groupBy('fecha_produccion')

            ->first();

        $n_variedades = collect($transito)->pluck('n_variedad')->unique()->values();
        $c_embalajes = collect($transito)->pluck('c_embalaje')->unique()->values();
        $n_calibres = collect($transito)->pluck('n_calibre')->unique()->values();
        $n_bodegas = collect($transito)->pluck('n_bodega')->unique()->values();
        $n_etiquetas = collect($transito)->pluck('n_etiqueta')->unique()->values();

        return response()->json([
            "data" => $transitoConDetalles,
            "n_variedades" => $n_variedades,
            "c_embalajes" => $c_embalajes,
            "n_calibres" => $n_calibres,
            "n_bodegas" => $n_bodegas,
            "embalajes_detalle" => $embalajes_detalle,
            "n_etiquetas" => $n_etiquetas,
            "antiguedad" => $antiguedad
        ], 200);
    }
    public function obtieneDetallesTransito(Request $request)
    {
        $transito = DB::connection("sqlsrv")->table('dbo.V_PKG_Stock_Inventario')
            ->select(
                'n_variedad',
                'fecha_produccion',
                'c_embalaje',
                'peso_std_embalaje',
                'n_exportadora',
                'n_envase',
                'n_calibre',
                'n_etiqueta',
                'folio',
                'texto_libre_hs',
                DB::RAW("SUM(cantidad) as cantidad"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'n_especie',
                'n_bodega',
                'fila',
                'columna',
                'altura',
                'id_altura',
                'e_inspeccion',
                'n_variedad_original',
                'n_categoria',
                'tipo_embalaje',
                'cuenta_pallets',
                'Tratamiento'
            )
            //->where('id_altura', '=', 8)
            ->where('n_categoria', '=', 'Cat 1')
            ->where('n_exportadora', '=', 'Greenex Spa')
            ->where('c_embalaje', '=', $request->n_embalaje)
            ->where('n_variedad_original', '=', $request->n_variedad)
            ->where('n_etiqueta', '=', $request->n_etiqueta)
            ->where('n_categoria', '!=', 'muestra')
            ->where('id_empresa', '=', '1')
            ->where('fecha_produccion', '<', DB::RAW("DATEADD(DAY, -2, GETDATE())"))

            ->groupBy(
                'n_variedad',
                'fecha_produccion',
                'c_embalaje',
                'peso_std_embalaje',
                'n_bodega',
                'n_exportadora',
                'n_envase',
                'id_contenedor',
                'Cant_Contenedor',
                'n_categoria',
                'n_calibre',
                'n_etiqueta',
                'folio',
                'texto_libre_hs',
                'n_especie',
                'fila',
                'columna',
                'altura',
                'id_altura',
                'e_inspeccion',
                'n_variedad_original',
                'tipo_embalaje',
                'cuenta_pallets',
                'Tratamiento',
                'antiguedad'
            )
            ->orderBy('folio', 'asc')
            ->get();
        return response()->json($transito, 200);
    }
    public function obtieneDetallesTransitoCalibre(Request $request)
    {
        $transito = DB::connection("sqlsrv")->table('dbo.V_PKG_Stock_Inventario')
            ->select(
                'n_variedad',
                'fecha_produccion',
                'c_embalaje',
                'peso_std_embalaje',
                'n_exportadora',
                'n_envase',
                'n_calibre',
                'n_etiqueta',
                'folio',
                'texto_libre_hs',
                DB::RAW("SUM(cantidad) as cantidad"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                'n_especie',
                'n_bodega',
                'fila',
                'columna',
                'altura',
                'id_altura',
                'e_inspeccion',
                'n_variedad_original',
                'n_categoria',
                'tipo_embalaje',
                'cuenta_pallets',
                'Tratamiento'
            )
            //->where('id_altura', '=', 8)
            ->where('n_categoria', '=', 'Cat 1')
            ->where('n_exportadora', '=', 'Greenex Spa')
            ->where('c_embalaje', '=', $request->c_embalaje)
            ->where('n_variedad_original', '=', $request->n_variedad)
            ->where('n_etiqueta', '=', $request->n_etiqueta)
            ->where('n_calibre', '=', $request->n_calibre)
            ->where('id_empresa', '=', '1')
            ->where('fecha_produccion', '<', DB::RAW("DATEADD(DAY, -2, GETDATE())"))
            ->groupBy(
                'n_variedad',
                'fecha_produccion',
                'c_embalaje',
                'peso_std_embalaje',
                'n_bodega',
                'n_exportadora',
                'n_envase',
                'id_contenedor',
                'Cant_Contenedor',
                'n_categoria',
                'n_calibre',
                'n_etiqueta',
                'folio',
                'texto_libre_hs',
                'n_especie',
                'fila',
                'columna',
                'altura',
                'id_altura',
                'e_inspeccion',
                'n_variedad_original',
                'tipo_embalaje',
                'cuenta_pallets',
                'Tratamiento',
                'antiguedad'
            )
            ->orderBy('folio', 'asc')
            ->get();
        return response()->json(['data' => $transito], 200);
    }

    public function StockInventarioxSemana()
    {
        $stockSemanal = DB::connection("sqlsrv")->table('dbo.V_PKG_Stock_Inventario')
            ->select(
                DB::RAW("DATEPART(WEEK, 'fecha_g_recepcion_sh') AS numero_semana"),
                DB::RAW("SUM(peso_neto) AS peso_neto"),
                'n_exportadora',
                'n_especie',
                'n_variedad'
            )
            ->groupBy(
                DB::RAW("DATEPART(WEEK, 'fecha_g_recepcion_sh')"),
                'n_empresa',
                'n_exportadora',
                'n_especie',
                'n_variedad'
            )
            ->orderBy('numero_semana')
            ->get();
        return response()->json(['stockSemanal' => $stockSemanal], 200);
    }
    public function embarques()
    {
        return view('admin.reporteria.embarque');
    }
    public function obtieneEmbarques()
    {
        $embarques = DB::connection("sqlsrv")->table('dbo.V_PKG_Embarques')
            ->select(
                'n_embarque',
                DB::RAW('DATEPART(WEEK, etd) as Semana'),
                DB::RAW('SUM(Cantidad) as Cantidad'),
                DB::RAW('SUM(peso_neto) as Peso_neto'),
                DB::RAW('SUM(peso_bruto) as peso_bruto'),
                'fecha_embarque',
                'transporte',
                'n_packing_origen',
                'n_pais_destino',
                'numero_g_despacho',
                'c_destinatario',
                'n_empresa',
                'n_exportadora',
                'n_altura',
                'ns_productor',
                'N_Especie',
                'N_Variedad',
                'n_embalaje',
                't_embalaje',
                'n_contenedor',
                'n_etiqueta',
                'n_puerto_destino',
                'n_puerto_origen',
                'numero_referencia',
                'contenedor',
                'n_nave'
            )
            ->where('id_especie', '=', '7')
            ->where(DB::RAW('DATEPART(WEEK, etd)'), '>', '48')
            ->groupBy(
                'n_embarque',
                DB::RAW('DATEPART(WEEK, etd)'),
                'fecha_embarque',
                'transporte',
                'n_packing_origen',
                'n_pais_destino',
                'numero_g_despacho',
                'c_destinatario',
                'n_empresa',
                'n_exportadora',
                'n_altura',
                'ns_productor',
                'N_Especie',
                'N_Variedad',
                'n_embalaje',
                't_embalaje',
                'n_contenedor',
                'n_etiqueta',
                'n_puerto_destino',
                'n_puerto_origen',
                'numero_referencia',
                'contenedor',
                'n_nave'
            )->orderBy('Semana', 'desc')
            ->get();
        $total = 0;
        $totalPeso = 0;
        $cant_contenedores = 0;

        foreach ($embarques as $embarque) {
            if ($embarque->c_destinatario != null) {
                try {
                    if (ClientesComex::where('codigo_cliente', explode("-", $embarque->c_destinatario)[0])->exists()) {
                        $CxComex = ClientesComex::where('codigo_cliente', explode("-", $embarque->c_destinatario)[0])->first();
                        $embarque->c_destinatario = ClientesComex::where('codigo_cliente', explode("-", $embarque->c_destinatario)[0])->first()->nombre_fantasia;
                    } else {
                    }
                    $embarque->c_destinatario = ClientesComex::where('codigo_cliente', $embarque->c_destinatario)->first()->nombre_fantasia;
                } catch (\Throwable $th) {
                }
            }
            $total += $embarque->Cantidad;
            $totalPeso += $embarque->Peso_neto;
        }
        $n_variedades = collect($embarques)->pluck('N_Variedad')->unique()->values();
        $n_etiqueta = collect($embarques)->pluck('n_etiqueta')->unique()->values();
        $cliente = collect($embarques)->pluck('c_destinatario')->unique()->values();
        $n_exportadora = collect($embarques)->pluck('n_exportadora')->unique()->values();
        $transporte = collect($embarques)->pluck('transporte')->unique()->values();
        $semana = collect($embarques)->pluck('Semana')->unique()->values();
        Storage::disk('public')->put('embarques.json', json_encode($embarques));

        return response()->json([
            'data' => $embarques,
            'n_variedades' => $n_variedades,
            'n_etiqueta' => $n_etiqueta,
            'cliente' => $cliente,
            'n_exportadora' => $n_exportadora,
            'total' => $total,
            'totalPeso' => $totalPeso,
            'transporte' => $transporte,
            'semana' => $semana,
        ], 200);
    }
    public function ObjetivosEnvios()
    {
        $dataMetas = DB::connection("sqlsrv")->table(function ($query) {
            $query->from('dbo.V_PKG_Embarques')
                ->selectRaw("
        DATEPART(WEEK, etd) as semana,
        c_destinatario,
        SUM(cantidad) / CP2_Embalaje / 20 as Contenedores, SUM(cantidad) as cantidad,CP2_Embalaje")

                ->where('id_especie', 7)
                ->where('transporte', 'MARITIMO')
                ->where('n_exportadora', 'Greenex Spa')
                ->groupByRaw('DATEPART(WEEK, etd), c_destinatario, CP2_Embalaje');
        }, 's')
            ->select('semana', 'c_destinatario', DB::raw('SUM(Contenedores) as contenedores'), DB::raw('SUM(cantidad) as Cajas'), 'CP2_Embalaje')
            ->groupBy('semana', 'c_destinatario', 'CP2_Embalaje')
            ->orderBy('c_destinatario', 'asc')
            ->orderBy('semana', 'desc')
            ->get();

        foreach ($dataMetas as $chart) {
            if ($chart->c_destinatario != null) {

                try {

                    if (ClientesComex::where('codigo_cliente', explode("-", $chart->c_destinatario)[0])->exists()) {

                        $CxComex = ClientesComex::where('codigo_cliente', explode("-", $chart->c_destinatario)[0])->first();
                        $chart->c_destinatario = $CxComex->nombre_fantasia;

                        $MetaCx = MetasClienteComex::where('clientecomex_id', '=', $CxComex->id)->first();
                        if ($MetaCx != null) {
                            $chart->alsu = $MetaCx->observaciones;
                            $chart->meta = ($MetaCx->cantidad * $chart->CP2_Embalaje) * 20;
                            $chart->metacont = $MetaCx->cantidad;
                        } else {
                            $chart->alsu = '';
                            $chart->meta = 0;
                            $chart->metacont = 0;
                        }
                    } else {
                        $chart->alsu = '';
                        $chart->meta = 0;
                    }
                } catch (\Throwable $th) {
                }
            }
        }
        return response()->json(['data' => $dataMetas], 200);
    }
    public function ObjetivosEnviosAereos()
    {
        ini_set('memory_limit', '512M');
        $dataMetas = DB::connection("sqlsrv")->table(function ($query) {
            $query->from('dbo.V_PKG_Embarques')
                ->selectRaw("
        DATEPART(WEEK, etd) as semana,
        c_destinatario,
        SUM(cantidad)/CP2_Embalaje/20 as Contenedores,
        ROUND(SUM(cantidad) / CP2_Embalaje,2) as Pallets, SUM(cantidad) as Cajas")
                ->where('id_especie', 7)
                ->where('transporte', 'AEREO')
                ->where('n_exportadora', 'Greenex Spa')
                ->groupByRaw('DATEPART(WEEK, etd), c_destinatario, CP2_Embalaje');
        }, 's')
            ->select('semana', 'c_destinatario', DB::raw('SUM(Pallets) as Pallets,SUM(Cajas) as Cajas '), DB::raw('SUM(Contenedores) as contenedores'))
            ->groupBy('semana', 'c_destinatario')
            ->orderBy('semana', 'desc')
            ->get();

        foreach ($dataMetas as $chart) {
            if ($chart->c_destinatario != null) {

                try {

                    if (ClientesComex::where('codigo_cliente', explode("-", $chart->c_destinatario)[0])->exists()) {

                        $CxComex = ClientesComex::where('codigo_cliente', explode("-", $chart->c_destinatario)[0])->first();
                        $chart->c_destinatario = $CxComex->nombre_fantasia;

                        $MetaCx = MetasClienteComex::where('clientecomex_id', '=', $CxComex->id)->first();
                        if ($MetaCx != null) {
                            $chart->alsu = $MetaCx->observaciones;
                            $chart->meta = $MetaCx->cantidad;
                            $chart->metacont = 0;
                            //  $chart->metacont = $MetaCx->contenedores;
                        } else {
                            $chart->alsu = '';
                            $chart->meta = 0;
                            $chart->metacont = 0;
                        }
                    } else {
                        $chart->alsu = '';
                        $chart->meta = 0;
                    }
                } catch (\Throwable $th) {
                }
            }
        }
        return response()->json(['data' => $dataMetas], 200);
    }
    public function ObjetivosEnviosTerrestre()
    {
        $dataMetas = DB::connection("sqlsrv")->table(function ($query) {
            $query->from('dbo.V_PKG_Embarques')
                ->selectRaw("
        DATEPART(WEEK, etd) as semana,
        c_destinatario,
         SUM(cantidad) / CP2_Embalaje / 20 as Contenedores,
        ROUND(SUM(cantidad) / CP2_Embalaje,2) as Pallets, SUM(cantidad) as Cajas")
                ->where('id_especie', 7)
                ->where('transporte', 'CAMION FRIGORIFICO')
                ->where('n_exportadora', 'Greenex Spa')
                ->groupByRaw('DATEPART(WEEK, etd), c_destinatario, CP2_Embalaje');
        }, 's')
            ->select('semana', 'c_destinatario', DB::raw('SUM(Pallets) as Pallets,SUM(Cajas) as Cajas '))
            ->groupBy('semana', 'c_destinatario')
            ->orderBy('semana', 'desc')
            ->get();

        foreach ($dataMetas as $chart) {
            if ($chart->c_destinatario != null) {

                try {

                    if (ClientesComex::where('codigo_cliente', explode("-", $chart->c_destinatario)[0])->exists()) {

                        $CxComex = ClientesComex::where('codigo_cliente', explode("-", $chart->c_destinatario)[0])->first();
                        $chart->c_destinatario = $CxComex->nombre_fantasia;

                        $MetaCx = MetasClienteComex::where('clientecomex_id', '=', $CxComex->id)->first();
                        if ($MetaCx != null) {
                            $chart->alsu = $MetaCx->observaciones;
                            $chart->meta = $MetaCx->cantidad;
                        } else {
                            $chart->alsu = '';
                            $chart->meta = 0;
                        }
                    } else {
                        $chart->alsu = '';
                        $chart->meta = 0;
                    }
                } catch (\Throwable $th) {
                }
            }
        }
        return response()->json(['data' => $dataMetas], 200);
    }
    /*************  ✨ Codeium Command ⭐  *************/
    /**
     * Retorna la cantidad de embarques que se encuentran en la vista dbo.V_PKG_Embarques,
     * que cumplen con los siguientes filtros:
     * - id_especie = 7
     * - Semana de embarque mayor o igual a 1
     * - id_exportadora = 22
     * - id_destinatario y n_destinatario no nulos
     * - Transporte en ['MARITIMO', 'AEREO']
     *
     * @return \Illuminate\Http\Response
     */
    /******  7627ef3b-343b-436c-8433-d4abf1cc0599  *******/
    public function getCantRegistros()
    {
        $cantEmbarques = DB::connection("sqlsrv")->table('dbo.V_PKG_Embarques')
            ->select(DB::raw('COUNT(n_embarque) as cant'))
            ->where('id_especie', 7)
            ->where(DB::raw('DATEPART(WEEK, etd)'), '>', 43)
            //->where('n_embarque', '>', $cargados->num_embarque)
            ->where('id_exportadora', '=', '22')
            ->whereNotNull('id_destinatario')
            ->whereNotNull('n_destinatario')
            ->whereIn('transporte', ['MARITIMO', 'AEREO'])
            ->get();

        return response()->json(['cantEmbarques' => $cantEmbarques], 200);
    }
    public function ObtieneEmbarquesyPackingList()
    {

        $cargados = Embarque::orderBy('num_embarque', 'desc')->first();

        $embarques = DB::connection("sqlsrv")->table('dbo.V_PKG_Embarques')
            ->select(
                DB::raw('ROW_NUMBER() OVER (ORDER BY [etd] ASC) AS id'),
                'n_embarque',
                DB::raw("DATEPART(WEEK, eta) as semana"),
                'id_destinatario',
                'n_destinatario',
                'c_destinatario',
                'fecha_embarque',
                'n_packing_origen',
                'n_naviera',
                'n_nave',
                'contenedor',
                'N_Especie',
                'N_Variedad',
                'n_embalaje',
                't_embalaje',
                'n_etiqueta',
                'cantidad',
                'peso_neto',
                'n_puerto_origen',
                'n_pais_destino',
                'n_puerto_destino',
                'transporte',
                'n_packing_origen',
                'etd',
                'eta',
                'numero_reserva_agente_naviero',
                'total_pallets',
                'numero_referencia',
                'nave',
                'folio',
                'peso_std_embalaje',
                'n_variedad_rotulacion',
                'n_categoria',
                'fecha_produccion',
                'n_productor_rotulacion',
                'codigo_sag_productor',
                'n_calibre',
            )
            ->where(DB::raw('DATEPART(YEAR, eta)'), '=', 2025)
            ->where(DB::raw('DATEPART(MONTH, eta)'), '>=', 1)
            //->where('n_embarque', '>', $cargados->num_embarque)
            ->where('id_exportadora', '=', '22')
            ->whereNotNull('id_destinatario')
            ->whereNotNull('n_destinatario')
            ->get();
        $lstEmbarque = collect();
        foreach ($embarques as $embarque) {
            if (ClientesComex::where('codigo_cliente', explode("-", $embarque->c_destinatario)[0])->exists()) {

                $CxComex = ClientesComex::where('codigo_cliente', explode("-", $embarque->c_destinatario)[0])->first();
                $embarque->n_destinatario = $CxComex->nombre_fantasia;
            } else {
            }
        }



        $n_variedades = collect($embarques)->pluck('N_Variedad')->unique()->values();
        $n_etiqueta = collect($embarques)->pluck('n_etiqueta')->unique()->values();
        $cliente = collect($embarques)->pluck('n_destinatario')->unique()->values();
        $n_exportadora = collect($embarques)->pluck('n_exportadora')->unique()->values();
        $transporte = collect($embarques)->pluck('transporte')->unique()->values();
        $semana = collect($embarques)->pluck('Semana')->unique()->values();
        $nave = collect($embarques)->pluck('nave')->unique()->values();
        $embalajes = collect($embarques)->pluck('n_etiqueta')->unique()->values();



        Storage::disk('public')->put('datos.json', json_encode($embarques));

        //dd($lstEmbarque);

        return response()->json([
            'objEmbarque' => $embarques
        ], 200);
    }
    public function getPackingList()
    {

        $packingList = DB::connection("sqlsrv")->table('dbo.V_PKG_Despachos_Embarques')
            ->select('*')
            ->where('id_exportadora', '=', 22)->get();
        return response()->json([
            'packingList' => $packingList
        ], 200);
    }
    public function getClientesComex()
    {
        $CxComex = ClientesComex::all();
        return response()->json(['CxComex' => $CxComex], 200);
    }
    public function detalleembarque()
    {
        return view('admin.reporteria.detalleembarque');
    }
    function SyncDatosCajas()
    {
        $compressedData = Storage::disk('public')->get('cajas.json.gz');
        $jsonData = gzuncompress($compressedData);
        Storage::disk('public')->put('datosCajas.json', $jsonData);
        return response($jsonData)
            ->header('Content-Type', 'application/json');
    }
    function SyncDatosCajas2()
    {
        try {
            // Despachar el Job en segundo plano
            SyncCajasJob::dispatch();

            return response()->json([
                'message' => 'Sincronización iniciada correctamente. Los datos se guardarán en segundo plano.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al iniciar la sincronización',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieves the minimum and maximum values of the 'ncaja' field from the PKG_Stock_Cajas table.
     *
     * This method connects to the 'sqlsrv' database and queries the PKG_Stock_Cajas table
     * to find the minimum and maximum 'ncaja' values. It returns a JSON response containing
     * these values.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response with the minimum and maximum 'ncaja' values.
     */

    public function getMinMaxCajas()
    {

        $NumCajas = DB::connection("sqlsrv")->table('PKG_Stock_Cajas as SC')
            ->select(
                DB::raw('MIN(SC.ncaja) as minimo'),
                DB::raw('MAX(SC.ncaja) as maximo')
            )->get();

        $min = $NumCajas[0]->minimo;
        $max = $NumCajas[0]->maximo;

        return response()->json(['min' => $min, 'max' => $max], 200);
    }
    public function detallecajas()
    {
        return view('admin.reporteria.detallecajas');
    }

    public function getLiquidaciones()
    {
        $datos = DB::table('greenexnet.liquidaciones_cxes as lc')
        ->join('greenexnet.liq_cx_cabeceras as lcc', 'lc.liqcabecera_id', '=', 'lcc.id')
        ->join('greenexnet.liq_costos as lc2', 'lc2.liq_cabecera_id', '=', 'lcc.id')
        ->join('greenexnet.excel_datos as ed', 'lcc.instructivo', '=', 'ed.instructivo')
        ->join('greenexnet.clientes_comexes as cc', 'lcc.cliente_id', '=', 'cc.id')
        ->whereNull('lc.deleted_at')
        ->select([
            DB::raw('"" as `placeholder`'),
            'lcc.id',
            'ed.instructivo',
            'ed.tasa',
            DB::raw('SUM(lc.precio_unitario * lc.cantidad) AS `MONTO_RMB`'), // Nueva columna
            DB::raw('SUM(lc.precio_unitario * lc.cantidad)/ed.tasa AS `MONTO_USD`')
        ])
        ->groupBy('ed.instructivo', 'lcc.id','ed.tasa') // Agrupación
        ->get();
            foreach($datos as $dato){
                $costos=DB::table('greenexnet.liq_costos as lc')->select(DB::raw("SUM(valor) as costos"))->where("liq_cabecera_id",$dato->id)->first();
                $costo_usd=$costos->costos/$dato->tasa;
                $dato->costos=$costo_usd;
                $dato->FOB_USD=$dato->MONTO_USD-$costo_usd;
            }


        return $datos;
    }
    public function liquidacionesventa()
    {
        return view("admin.reporteria.liquidacionesventa");
    }
    public function getDetallesInstructivo(Request $request)
    {
        $instructivo = $request->input('instructivo');
        $variedad = $request->input('variedad');
        $calibre = $request->input('calibre');

        $query = "
            SELECT
                (SUM(peso_neto) / (SELECT SUM(peso_neto)
                                   FROM [FX6_Packing_Garate_Operaciones].[dbo].[V_PKG_Embarques]
                                   WHERE numero_referencia = ?)) * 100 as porcentaje,
                [C_Embalaje],
                n_variedad,
                n_calibre,
                n_productor,
                [CSG_Productor],
                cantidad,
                folio
            FROM [FX6_Packing_Garate_Operaciones].[dbo].[V_PKG_Embarques]
            WHERE numero_referencia = ? and n_variedad = ? and n_calibre = ?
            GROUP BY C_Embalaje, n_variedad, n_calibre, CSG_Productor, n_productor, cantidad, folio
            ORDER BY folio
        ";

        $resultados = DB::connection('sqlsrv')->select($query, [$instructivo, $instructivo, $variedad, $calibre]);

        return response()->json($resultados);
    }
    public function compartivoliquidacionescx()
    {
        return view("admin.reporteria.compartivoliquidacionescx");
    }
    public function DataLiquidaciones()
    {

        $liqCxCabeceras = LiqCxCabecera::whereNull('deleted_at')->get(); // LiqCxCabecera::find(request('ids'));

        $dataComparativa = collect();
        $C_Logisticos = Costo::where('categoria', 'Costo Logístico')->get();
        $C_Mercado = Costo::where('categoria', 'Costos Mercado')->get();
        $C_Impuestos = Costo::where('categoria', 'Impuestos')->get();
        $C_FleteInternacional = Costo::where('categoria', 'Flete Internacional')->get();
        $C_FleteDomestico = Costo::where('categoria', 'Flete Doméstico')->get();
        $C_Comision = Costo::where('categoria', 'Comisión')->get();
        //Inicio los costos agrupados por categoria
        $costosLogisticos = 0;
        $costosMercado = 0;
        $costosImpuestos = 0;
        $costosFleteInternacional = 0;
        $costosFleteDomestico = 0;
        $comision = 0;
        $entradamercado = 0;
        $otroscostosdestino = 0;
        $ajusteimpuesto = 0;
        $otrosimpuestos = 0;
        $otrosingresos = 0;
        $i = 2;

        foreach ($liqCxCabeceras as $liqCxCabecera) {
            $flete_exportadora = $liqCxCabecera->flete_exportadora;
            $tipo_transporte = $liqCxCabecera->tipo_transporte;
            $factor_imp_destino = $liqCxCabecera->factor_imp_destino;
            $detalle = LiquidacionesCx::where('liqcabecera_id', $liqCxCabecera->id)->get();
            $excelDato = ExcelDato::where('instructivo', $liqCxCabecera->instructivo)->first();
            Log::info("Instructivo: " . $liqCxCabecera->instructivo);
            $nombre_costo = Costo::pluck('nombre'); // Extraer solo los nombres de costos
            $total_kilos = 0;
            $total_ventas = 0;
            foreach ($detalle as $item) {
                $total_kilos = $total_kilos + (float)(str_replace(',', '.', $this->traducedatos($item->embalaje_id, 'Embalaje'))) * (float)(str_replace(',', '.', $item->cantidad));

                $total_ventas = $total_ventas + $item->cantidad * (float)(str_replace(',', '.', $item->precio_unitario));
                // Log::info("Total Venta: " . $total_ventas);
            }
            $porcComision = '0,06';
            foreach ($detalle as $item) {

                $costos = LiqCosto::where('liq_cabecera_id', $liqCxCabecera->id)->get();


                // Procesar los costos reales

                foreach ($costos as $costo) {

                    switch ($costo->nombre_costo) {
                        case 'Costo Logístico':
                            $costosLogisticos = $costo->valor;
                            break;
                        case 'Costo Mercado':
                            $costosMercado = $costo->valor;
                            break;
                        case 'Impuestos':
                            $costosImpuestos = $costo->valor;
                            break;
                        case 'Flete Internacional':
                            $costosFleteInternacional = $costo->valor;
                            break;
                        case 'Flete Doméstico':
                            $costosFleteDomestico = $costo->valor;
                            break;
                        case 'Comisión':
                            $comision = $costo->valor;
                            $porcComision = $comision / $total_ventas;
                            Log::info("Porcentaje Comision: " . $porcComision);
                            break;
                        case 'Entrada Mercado':
                            $entradamercado = $costo->valor;
                            break;
                        case 'Otros Costos Destino':
                            $otroscostosdestino = $costo->valor;
                            break;
                        case 'Ajuste Impuesto':
                            //Caso particular FruitLink el ajuste de impuesto esta en dolares

                            $ajusteimpuesto = $costo->valor;
                            // if($liqCxCabecera->cliente_id == 5){
                            //     $ajusteimpuesto = $ajusteimpuesto * $excelDato->tasa;
                            // }
                            break;
                        case 'Otros Impuestos':
                            $otrosimpuestos = $costo->valor;
                            break;
                        case 'Otros Ingresos':
                            $otrosingresos = $costo->valor;
                            break;
                        default:


                            break;
                    }
                }


                //  dd($liqCxCabecera);
                // Agregar los datos principales y los costos procesados al array
                $peso_neto = ((float)$this->traducedatos($item->embalaje_id, 'Embalaje'))?0:(float)$this->traducedatos($item->embalaje_id, 'Embalaje');
                Log::info(" Factor Imp Destino: " . $factor_imp_destino . " Instructivo " . $liqCxCabecera->instructivo . " PRecio " . $item->precio_unitario . " Cantidad " . $item->cantidad . "Peso Neto: " . $peso_neto);

                $costoCajasRMB = ((float)$factor_imp_destino * (isset($item->precio_unitario) ? (float)$item->precio_unitario : 0));
                    + (((float)$costosLogisticos == 0 ? 0 : (float)$costosLogisticos) / (float)$total_kilos) * $peso_neto +
                    (((float)$entradamercado == 0 ? 0 : (float)$entradamercado) / (float)$total_kilos) * $peso_neto + (((float)$costosMercado == 0 ? 0 : (float)$costosMercado) / (float)$total_kilos) * $peso_neto +
                    (((float)$otroscostosdestino == 0 ? 0 : (float)$otroscostosdestino) / $total_kilos) * $peso_neto + ($porcComision * isset($item->precio_unitario) ? $item->precio_unitario : 0) +
                    (((float)$costosFleteInternacional == 0 ? 0 : (float)$costosFleteInternacional) / $total_kilos) * $peso_neto + (((float)$ajusteimpuesto == 0 ? 0 : ((float)$ajusteimpuesto) / $excelDato->tasa) / $total_kilos) * $peso_neto
                    + (((float)$otrosimpuestos == 0 ? 0 : ((float)$otrosimpuestos / $excelDato->tasa)) / $total_kilos) * $peso_neto;
                $resultadoCajaRMB =  ($item->cantidad * $item->precio_unitario)- $costoCajasRMB;
                $nave = Nafe::where('id', $liqCxCabecera->nave_id)->first();
                $nombre_nave = "";
                if ($nave) {
                    $nombre_nave = $nave->nombre;
                } else {
                    $nombre_nave = 'Sin Información';
                }
                $RMB_Caja=isset($item->precio_unitario) ? $item->precio_unitario : 0;
        $RMB_Venta=$item->cantidad * $item->precio_unitario;
        $comision_caja=$porcComision*$RMB_Caja;
        $peso_neto=$this->traducedatos($item->embalaje_id, 'Embalaje');
        $kilos_total=$peso_neto?0:$peso_neto*$item->cantidad;
        $calibre= $this->traducedatos($item->calibre, 'Calibre');
        $costos_logisticos_caja_RMB= (($costosLogisticos == 0 ? 0 : $costosLogisticos)/$total_kilos)*$peso_neto?0:$peso_neto;
        $costos_logisticos_caja_RMB_TO=(($costosLogisticos == 0 ? 0 : $costosLogisticos)/$total_kilos)*$peso_neto?0:$peso_neto*$item->cantidad;
        $entrada_mercado_caja_RMB=($entradamercado == 0 ? 0 : $entradamercado/$total_kilos)*$peso_neto?0:$peso_neto;
        $entrada_mercado_caja_RMB_TO=($entradamercado == 0 ? 0 : $entradamercado/$total_kilos)*$peso_neto?0:$peso_neto*$item->cantidad;
        $costo_mercado_caja_RMB=($costosMercado == 0 ? 0 : $costosMercado/$total_kilos)*$peso_neto?0:$peso_neto;
        $costo_mercado_caja_RMB_TO=$costo_mercado_caja_RMB*$item->cantidad;
        $otros_costos_destino_caja_RMB=($otroscostosdestino == 0 ? 0 : $otroscostosdestino/$total_kilos)*$peso_neto?0:$peso_neto;
        $otros_costos_destino_caja_RMB_TO=$otros_costos_destino_caja_RMB*$item->cantidad;
        $costosFleteInternacional_caja_RMB=($costosFleteInternacional == 0 ? 0 : $costosFleteInternacional/$total_kilos)*$peso_neto?0:$peso_neto;
        $costosFleteInternacional_caja_RMB_TO=$costosFleteInternacional_caja_RMB*$item->cantidad;
        $ajuste_impuesto_USD=(($ajusteimpuesto == 0 ? 0 : $ajusteimpuesto/$excelDato->tasa)/$total_kilos)*$peso_neto?0:$peso_neto;
        $ajuste_impuesto_USD_TO=$ajuste_impuesto_USD*$item->cantidad;
        $flete_aereo_USD=($flete_exportadora/$total_kilos)*$peso_neto;
        $flete_aereo_USD_TO=$flete_aereo_USD*$item->cantidad;
        $otros_impuestos_jwm=(($otrosimpuestos == 0 ? 0 : ($otrosimpuestos / $excelDato->tasa))/$total_kilos)*$peso_neto?0:$peso_neto;
        $otros_impuestos_jwm_TO=$otros_impuestos_jwm*$item->cantidad;
        $costos_caja_RMB=($factor_imp_destino*$item->cantidad*$RMB_Caja)+$costos_logisticos_caja_RMB+$entrada_mercado_caja_RMB+$costo_mercado_caja_RMB+$otros_costos_destino_caja_RMB+$comision_caja+$costosFleteInternacional_caja_RMB+$ajuste_impuesto_USD+$otros_impuestos_jwm;
        $costos_caja_RMB_TO=$costos_caja_RMB*$item->cantidad;

        $dataComparativa->push(array_merge(
            [
                'Embarque' => '',  //A
                'cliente' => $liqCxCabecera->cliente->nombre_fantasia, //B
                'nave' => $nombre_nave, //C
                'Puerto Destino' => '', //D
                'AWB' => '', //E
                'Contenedor' => '', //F
                'Liquidación' => $liqCxCabecera->instructivo, //G
                'ETD' => '', //H
                'ETD Week' => '', //I
                'ETA' => $excelDato->fecha_arribo, //J
                'ETA Week' => ($excelDato->fecha_arribo ? Carbon::parse($excelDato->fecha_arribo)->weekOfYear : 0), //K
                'Fecha Venta' => $item->fecha_venta ? Carbon::parse($item->fecha_venta) : 0, //L
                'Fecha Venta Week' => ($excelDato->fecha_venta ? Carbon::parse($excelDato->fecha_venta)->weekOfYear : 0), //M
                'Fecha Liquidación' => $excelDato->fecha_liquidacion, //N
                'Pallet' => $item->pallet, //O
                'Peso neto' =>  $peso_neto, //P
                'Kilos total' => $total_kilos, //Q
                'embalaje' => $peso_neto, //R
                'etiqueta' => $item->etiqueta_id, //S
                'variedad' => $item->variedad_id, //T
                'Calibre Estandar'   => '', //U
                'calibre' => $calibre, //V
                'color' => '', //W
                'Observaciones' => $item->observaciones, //X
                'Cajas' => $item->cantidad, //y
                'RMB Caja' => $RMB_Caja, //z
                'RMB Venta' => $RMB_Venta, //AA
                'Comision Caja' => $comision_caja, //AB
                '% Comisión' => $porcComision, //AC
                'RMB Comisión' => $comision_caja*$item->cantidad, //AD
                'Factor Imp destino' => $factor_imp_destino, //AE  Esto no esta definido como para poder calcularlo
                'Imp destino caja RMB' => $factor_imp_destino*$item->cantidad*$RMB_Caja, //AF
                'RMB Imp destino TO' => $factor_imp_destino*$item->cantidad*$RMB_Caja*$item->cantidad, //AG
                'Costo log. Caja RMB' => $costos_logisticos_caja_RMB, //AH
                'RMB Costo log. TO' => $costos_logisticos_caja_RMB_TO, //AI
                'Ent. Al mercado Caja RMB' => $entrada_mercado_caja_RMB, //AJ Preguntar a Haydelin
                'RMB Ent. Al mercado TO' => $entrada_mercado_caja_RMB_TO, //AK
                'Costo mercado caja RMB' => $costo_mercado_caja_RMB, //AL
                'RMB Costos mercado TO' => $costo_mercado_caja_RMB_TO, //AM
                'Otros  costos dest. Caja RMB' => $otros_costos_destino_caja_RMB ,  //AN  debemos configurar costos en categoría otros
                'RMB otros costos TO' => $otros_costos_destino_caja_RMB_TO, //AO
                'Flete marit. Caja RMB' =>$costosFleteInternacional_caja_RMB , //AP
                'RMB Flete Marit. TO' => $costosFleteInternacional_caja_RMB_TO, //AQ
                'Costos cajas RMB' => $costos_caja_RMB, //AR
                'RMB Costos TO' => $costos_caja_RMB_TO, //AS
                'Resultados caja RMB' => $RMB_Caja-$costos_caja_RMB,  //AT  Verificar con Haydelin
                'RMB result. TO' => $RMB_Caja-$costos_caja_RMB*$item->cantidad, //AU  Verificar con Haydelin
                'TC'    => $excelDato->tasa, //AV
                'Venta USD' => $RMB_Caja/$excelDato->tasa, //AW
                'Ventas TO USD' => ($RMB_Caja/$excelDato->tasa)*$item->cantidad, //AX
                'Com USD' => $comision_caja/$excelDato->tasa, //AY
                'Com TO USD' => ($comision_caja/$excelDato->tasa)*$item->cantidad, //AZ
                'Imp destino USD' => ($factor_imp_destino*$item->cantidad*$RMB_Caja/$excelDato->tasa), //BA
                'Imp destino USD TO' => ($factor_imp_destino*$item->cantidad*$RMB_Caja/$excelDato->tasa)*$item->cantidad, //BB
                'Costo log. USD' => $costos_logisticos_caja_RMB/$excelDato->tasa, //BC
                'Costo log. USD TO' => ($costos_logisticos_caja_RMB/$excelDato->tasa)*$item->cantidad, //BD
                'Ent. Al mercado USD' => ($entrada_mercado_caja_RMB/$excelDato->tasa), //BE
                'Ent. Al mercado USD TO' => ($entrada_mercado_caja_RMB/$excelDato->tasa)*$item->cantidad, //BF
                'Costo mercado USD' =>($costo_mercado_caja_RMB/$excelDato->tasa), //BG
                'Costos mercado USD TO' => ($costo_mercado_caja_RMB/$excelDato->tasa)*$item->cantidad, //BH
                'Otros  costos dest. USD' => ($otros_costos_destino_caja_RMB/$excelDato->tasa), //BI
                'Otros costos USD TO' => ($otros_costos_destino_caja_RMB/$excelDato->tasa)*$item->cantidad, //BJ
                'Flete marit. USD'    => ($costosFleteInternacional_caja_RMB/$excelDato->tasa), //BK
                'Flete Marit. USD TO' => ($costosFleteInternacional_caja_RMB/$excelDato->tasa)*$item->cantidad, //BL
                'Costos cajas USD' =>($costos_caja_RMB/$excelDato->tasa) , //BM
                'Costos USD TO' => ($costos_caja_RMB/$excelDato->tasa)*$item->cantidad, //BN
                'Ajuste impuesto USD' => ($ajuste_impuesto_USD), //BO
                'Ajuste TO USD' => $ajuste_impuesto_USD_TO, //BP
                'Flete Aereo' => $flete_aereo_USD, //BQ
                'Flete Aereo TO' => $flete_aereo_USD_TO, //BR
                'FOB USD' =>($RMB_Caja-$costos_caja_RMB/$excelDato->tasa)-($ajuste_impuesto_USD)-($flete_aereo_USD), //BS
                'FOB TO USD' => '=+BS' . $i . '*Y' . $i, //BT
                'FOB kg' => '=+BT' . $i . '/Q' . $i, //BU
                'FOB Equivalente' => '=+BU' . $i . '*5', //BV
                'Flete Cliente' => $flete_exportadora > 0 ? 'NO' : 'SI', //BW
                'Transporte' => $tipo_transporte == "A" ? 'AEREO' : 'MARITIMO', //BX
                'CNY' => 'PRE', //BY
                'Pais' => 'CHINA', //BZ
                'Otros Impuestos (JWM) Impuestos' => (($otrosimpuestos == 0 ? 0 : ($otrosimpuestos / $excelDato->tasa))/$total_kilos)*$peso_neto?0:$peso_neto, //CA
                'Otros Impuestos (JWM) TO USD' => '=+CA' . $i . '*Y' . $i, //CB
                'Otros Ingresos (abonos)' => '=+(' . ($otrosingresos == 0 ? 0 : ($otrosingresos / $excelDato->tasa)) . '/' . $total_kilos . ')*P' . $i, //CC
                'Otros Ingresos (abonos) TO USD' => '=+CC' . $i . '*Y' . $i, //CD
                    //$costo_procesado,
                    // $calculos

                    // Incorporar los costos como columnas adicionales
            ]));
                $i++;
                $costosLogisticos = 0;
                $costosMercado = 0;
                $costosImpuestos = 0;
                $costosFleteInternacional = 0;
                $costosFleteDomestico = 0;
                $comision = 0;
                $entradamercado = 0;
                $otroscostosdestino = 0;
            }
        }
        // Semana ETA
        // Nave
        // Clasificación
        // Variedad
        // Calibre
        // Cantidad
        // Total FOB USD
        // Promedio x Caja

        //dd($dataComparativa);
        $groupedData = $dataComparativa->groupBy(function ($item) {
            return  $item['nave'] . '|' . $item['ETA_Week'] . '|' . $item['variedad'] . '|' . $item['calibre'] . '|' .$item['etiqueta'] . '|' . $item["Embalaje_real"];
        })->map(function ($group) {
            return [

                'Embarque' => '',  // No cambia
                'nave' => $group->first()['nave'],
                'ETA_Week' => $group->first()['ETA_Week'],
                'Peso_neto' => $group->first()['Embalaje_real'],
                'Kilos_total' => $group->sum('Kilos_total'),
                'etiqueta' => $group->first()['etiqueta'],
                'variedad' => $group->first()['variedad'],
                'calibre' => $group->first()['calibre'],
                'Cajas' => $group->sum('Cajas'),
                'Venta_USD' => $group->sum('Venta_USD'),
                'Ventas_TO_USD' => $group->sum('Ventas_TO_USD'),
                'FOB_USD' => $group->sum('FOB_USD'),
                'FOB_TO_USD' => $group->sum('FOB_TO_USD'),
                'FOB_kg' => $group->avg('Costos_cajas_USD'),
                'FOB_Equivalente' => $group->avg('FOB_Equivalente'),
            ];
        })->values(); // Para resetear índices



        return response()->json(["dataComparativa" => $dataComparativa, "agrupacionComparativa" => $groupedData]);
    }

    function traducedatos($texto, $tipo)
    {
        try {
            if ($texto == null || $texto == '') {
                return $texto;
            }
            Log::info("Traduciendo datos: " . $texto . "----" . $tipo);
            $dato = Diccionario::where("tipo", $tipo)->where("variable", $texto)->first();
            if ($dato == null) {
                return $texto;
            }
            return $dato->valor;
        } catch (\Exception $e) {
            Log::error("Error al traducir datos: " . $e->getMessage() . "----" . $texto . "----" . $tipo);

            return $texto;
        }
    }


}
