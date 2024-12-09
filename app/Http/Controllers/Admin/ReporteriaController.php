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
use App\Models\MetasClienteComex;
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
                'id_especie',
            )
            ->where('destruccion_tipo', '=', '')
            ->where('id_especie', '=', '7')->groupBy('nota_calidad', 'id_especie')
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
            ->where('fecha_g_recepcion_sh', '>=', DB::RAW("DATEADD(DAY, -8, GETDATE())"))

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
            ->where('id_altura', '=', 8)
            ->where('n_categoria', '=', 'Cat 1')
            ->where('n_exportadora', '=', 'Greenex Spa')
            ->where('id_empresa', '=', '1')
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
        $antiguedad = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                DB::RAW("SUM(cantidad) AS cantidad"),
                DB::RAW("SUM(peso_neto) as peso_neto"),
                DB::RAW("MIN(fecha_g_recepcion_sh) as fecha_minima"),
                'numero_g_recepcion',
            )->where('destruccion_tipo', '=', '')
            ->where('id_especie', '=', '7')
            ->where('id_empresa', '=', '1')
            ->groupBy('numero_g_recepcion')
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
            ->where('id_altura', '=', 8)
            ->where('n_categoria', '=', 'Cat 1')
            ->where('n_exportadora', '=', 'Greenex Spa')
            ->where('c_embalaje', '=', $request->n_embalaje)
            ->where('n_variedad', '=', $request->n_variedad)
            ->where('n_etiqueta', '=', $request->n_etiqueta)
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
            ->where('id_altura', '=', 8)
            ->where('n_categoria', '=', 'Cat 1')
            ->where('n_exportadora', '=', 'Greenex Spa')
            ->where('c_embalaje', '=', $request->c_embalaje)
            ->where('n_variedad', '=', $request->n_variedad)
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
                DB::RAW('DATEPART(WEEK, etd) as Semana'),
                DB::RAW('SUM(Cantidad) as Cantidad'),
                DB::RAW('SUM(peso_neto) as Peso_neto'),
                DB::RAW('SUM(peso_bruto) as peso_bruto'),
                'transporte',
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
                'n_embarque',
                'contenedor',
                'n_nave'
            )
            ->where('id_especie', '=', '7')
            ->groupBy(
                DB::RAW('DATEPART(WEEK, etd)'),
                'transporte',
                'c_destinatario',
                'n_embarque',
                'contenedor',
                'n_nave',
                'numero_g_despacho',
                'n_pais_destino',
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
                'total_pallets'
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
    public function ObjetivosEnvios(){
        $dataMetas = DB::connection("sqlsrv")->table(function ($query) {
            $query->from('dbo.V_PKG_Embarques')
                ->selectRaw("
        DATEPART(WEEK, etd) as semana,
        c_destinatario,
        SUM(cantidad) / CP2_Embalaje / 20 as Contenedores, SUM(cantidad) as cantidad")
                ->where('id_especie', 7)
                ->where('transporte', 'MARITIMO')
                ->where('n_exportadora', 'Greenex Spa')
                ->groupByRaw('DATEPART(WEEK, etd), c_destinatario, CP2_Embalaje');
        },'s')
            ->select('semana', 'c_destinatario', DB::raw('SUM(Contenedores) as contenedores'),DB::raw('SUM(cantidad) as Cajas'))
            ->groupBy('semana', 'c_destinatario')
            ->orderBy('semana', 'desc')
            ->get();

            foreach ($dataMetas as $chart) {
                if ($chart->c_destinatario != null) {

                    try {

                        if (ClientesComex::where('codigo_cliente', explode("-", $chart->c_destinatario)[0])->exists()) {

                            $CxComex = ClientesComex::where('codigo_cliente', explode("-", $chart->c_destinatario)[0])->first();
                            $chart->c_destinatario =$CxComex->nombre_fantasia;

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
    public function ObjetivosEnviosAereos(){
        $dataMetas = DB::connection("sqlsrv")->table(function ($query) {
            $query->from('dbo.V_PKG_Embarques')
                ->selectRaw("
        DATEPART(WEEK, etd) as semana,
        c_destinatario,
        ROUND(SUM(cantidad) / CP2_Embalaje,2) as Pallets, SUM(cantidad) as Cajas")
                ->where('id_especie', 7)
                ->where('transporte', 'AEREO')
                ->where('n_exportadora', 'Greenex Spa')
                ->groupByRaw('DATEPART(WEEK, etd), c_destinatario, CP2_Embalaje');
        },'s')
            ->select('semana', 'c_destinatario', DB::raw('SUM(Pallets) as Pallets,SUM(Cajas) as Cajas '))
            ->groupBy('semana', 'c_destinatario')
            ->orderBy('semana', 'desc')
            ->get();
           
            foreach ($dataMetas as $chart) {
                if ($chart->c_destinatario != null) {

                    try {

                        if (ClientesComex::where('codigo_cliente', explode("-", $chart->c_destinatario)[0])->exists()) {

                            $CxComex = ClientesComex::where('codigo_cliente', explode("-", $chart->c_destinatario)[0])->first();
                            $chart->c_destinatario =$CxComex->nombre_fantasia;

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
    public function ObjetivosEnviosTerrestre(){
        $dataMetas = DB::connection("sqlsrv")->table(function ($query) {
            $query->from('dbo.V_PKG_Embarques')
                ->selectRaw("
        DATEPART(WEEK, etd) as semana,
        c_destinatario,
        ROUND(SUM(cantidad) / CP2_Embalaje,2) as Pallets, SUM(cantidad) as Cajas")
                ->where('id_especie', 7)
                ->where('transporte', 'CAMION FRIGORIFICO')
                ->where('n_exportadora', 'Greenex Spa')
                ->groupByRaw('DATEPART(WEEK, etd), c_destinatario, CP2_Embalaje');
        },'s')
            ->select('semana', 'c_destinatario', DB::raw('SUM(Pallets) as Pallets,SUM(Cajas) as Cajas '))
            ->groupBy('semana', 'c_destinatario')
            ->orderBy('semana', 'desc')
            ->get();
           
            foreach ($dataMetas as $chart) {
                if ($chart->c_destinatario != null) {

                    try {

                        if (ClientesComex::where('codigo_cliente', explode("-", $chart->c_destinatario)[0])->exists()) {

                            $CxComex = ClientesComex::where('codigo_cliente', explode("-", $chart->c_destinatario)[0])->first();
                            $chart->c_destinatario =$CxComex->nombre_fantasia;

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
}
