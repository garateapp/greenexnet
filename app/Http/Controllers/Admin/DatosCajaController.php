<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyDatosCajaRequest;
use App\Http\Requests\StoreDatosCajaRequest;
use App\Http\Requests\UpdateDatosCajaRequest;
use App\Models\DatosCaja;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Especy;

class DatosCajaController extends Controller
{
    use CsvImportTrait;
    public function index(Request $request)
    {
        abort_if(Gate::denies('datos_caja_calidad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $especies = Especy::whereIn('id', [4, 7, 5, 6])->pluck('nombre', 'nombre')->prepend(trans('global.pleaseSelect'), '');
        
            return view('admin.datosCajas.index', compact('especies'));
    }
    /*************  ✨ Codeium Command ⭐  *************/
    /**
     * Busca los datos de caja para mostrar en la datatable.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    /******  52885eb4-2672-4767-a2dc-972f00fa5c29  *******/
    public function buscaDatosCaja(Request $request)
    {
        abort_if(Gate::denies('datos_caja_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        switch ($request->especie) {
            case 'Cherries':
                $datos = DB::connection("sqlsrvUnitec")->table('dbo.DatosCajas')
                    ->select(
                        'Proceso',
                        'FechaProduccion',
                        'Turno',
                        'CodLinea',
                        'CAT',
                        'VariedadReal',
                        'VariedadTimbrada',
                        'Salida',
                        'Marca',
                        'ProductorReal',
                        'Especie',
                        'CodCaja',
                        'CodConfeccion',
                        'CalibreTimbrado',
                        'PesoTimbrado',
                        'Lote'
                    )
                    ->where('codCaja', '=', $request->codCaja)
                    ->first(); //DatosCaja::whereBetween('FechaProduccion', ['2023-11-11', '2023-11-12'])->get(); //dd($request->fecha_inicio)

                break;
            case 'Nectarines':
                $datos = DB::connection("sqlsrv")->table('V_PKG_Etiquetado_Datos_Cajas as A')
                    ->select(
                        'A.C_Linea_Produccion  as CodLinea',
                        'B.nombre as ProductorReal',
                        'A.LN_Variedad_R as VariedadReal',
                        'A.N_Proceso as Proceso',
                        'A.N_Especie_R as Especie',
                        'A.N_Calibre as CalibreTimbrado',
                        'A.CP1_Embalaje as Marca',
                        'A.N_Categoria as CAT',
                        'A.C_Embalaje as CodConfeccion',
                        'A.Peso_Neto as PesoTimbrado',
                        'A.Salida as Salida',
                        'A.n_caja as CodCaja',
                        DB::raw('CONCAT(A.Dia, \'/\', A.Mes, \'/\', A.Ano_AA) as FechaProduccion'),
                        DB::raw("CASE 
                                    WHEN A.C_TURNO LIKE 'TU1' THEN 'TURNO 1'
                                    WHEN A.C_TURNO LIKE 'TU2' THEN 'TURNO 2'
                                    ELSE CONCAT('TURNO ', SUBSTRING(A.C_TURNO,2,1)) 
                                END as Turno"),
                    )
                    ->leftJoin('V_ADM_Entidades as B', 'A.CSG_Productor_R', '=', 'B.csg')
                    ->where('A.N_Categoria', '=', 'Cat 1')
                    ->where('n_caja', '=', $request->codCaja)
                    ->where('N_Especie_R', '=', $request->especie)
                    ->first();

                break;
            case 'Peaches':
                $datos = DB::connection("sqlsrv")->table('V_PKG_Etiquetado_Datos_Cajas as A')
                ->select(
                    'A.C_Linea_Produccion as CodLinea',
                    'B.nombre as ProductorReal',
                    'A.LN_Variedad_R as VariedadReal',
                    'A.N_Proceso as Proceso',
                    'A.N_Especie_R as Especie',
                    'A.N_Calibre as CalibreTimbrado',
                    'A.CP1_Embalaje as Marca',
                    'A.N_Categoria as CAT',
                    'A.C_Embalaje as CodConfeccion',
                    'A.Peso_Neto as PesoTimbrado',
                    'A.Salida as Salida',
                    'A.n_caja as CodCaja',
                    DB::raw('CONCAT(A.Dia, \'/\', A.Mes, \'/\', A.Ano_AA) as FechaProduccion'),
                    DB::raw("CASE 
                                WHEN A.C_TURNO LIKE 'TU1' THEN 'TURNO 1'
                                WHEN A.C_TURNO LIKE 'TU2' THEN 'TURNO 2'
                                ELSE CONCAT('TURNO ', SUBSTRING(A.C_TURNO,2,1)) 
                            END as Turno"),
                )
                ->leftJoin('V_ADM_Entidades as B', 'A.CSG_Productor_R', '=', 'B.csg')
                ->where('A.N_Categoria', '=', 'Cat 1')
                ->where('n_caja', '=', $request->codCaja)
                ->where('N_Especie_R', '=', $request->especie)
                ->first();
                break;
                case 'Plums':
                    $datos = DB::connection("sqlsrv")->table('V_PKG_Etiquetado_Datos_Cajas as A')
                    ->select(
                        'A.C_Linea_Produccion as CodLinea',
                        'B.nombre as ProductorReal',
                        'A.LN_Variedad_R as VariedadReal',
                        'A.N_Proceso as Proceso',
                        'A.N_Especie_R as Especie',
                        'A.N_Calibre as CalibreTimbrado',
                        'A.CP1_Embalaje as Marca',
                        'A.N_Categoria as CAT',
                        'A.C_Embalaje as CodConfeccion',
                        'A.Peso_Neto as PesoTimbrado',
                        'A.Salida as Salida',
                        'A.n_caja as CodCaja',
                        DB::raw('CONCAT(A.Dia, \'/\', A.Mes, \'/\', A.Ano_AA) as FechaProduccion'),
                        DB::raw("CASE 
                                    WHEN A.C_TURNO LIKE 'TU1' THEN 'TURNO 1'
                                    WHEN A.C_TURNO LIKE 'TU2' THEN 'TURNO 2'
                                    ELSE CONCAT('TURNO ', SUBSTRING(A.C_TURNO,2,1)) 
                                END as Turno"),
                    )
                    ->leftJoin('V_ADM_Entidades as B', 'A.CSG_Productor_R', '=', 'B.csg')
                    ->where('A.N_Categoria', '=', 'Cat 1')
                    ->where('n_caja', '=', $request->codCaja)
                    ->where('N_Especie_R', '=', $request->especie)
                    ->first();
                    break;
            default:
                $datos = DB::connection("sqlsrvUnitec")->table('dbo.DatosCajas')
                    ->select(
                        'Proceso',
                        'FechaProduccion',
                        'Turno',
                        'CodLinea',
                        'CAT',
                        'VariedadReal',
                        'VariedadTimbrada',
                        'Salida',
                        'Marca',
                        'ProductorReal',
                        'Especie',
                        'CodCaja',
                        'CodConfeccion',
                        'CalibreTimbrado',
                        'PesoTimbrado',
                        'Lote',
                         
                    )
                    ->where('codCaja', '=', $request->codCaja)
                    ->first();
        }
        //dd($request);

        if (!$datos) {
            return response()->json([], 200);
        }
        return response()->json($datos, 200);
    }

    public function create()
    {
        abort_if(Gate::denies('datos_caja_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.datosCajas.create');
    }

    public function store(StoreDatosCajaRequest $request)
    {
        $datosCaja = DatosCaja::create($request->all());

        return redirect()->route('admin.datos-cajas.index');
    }

    public function edit(DatosCaja $datosCaja)
    {
        abort_if(Gate::denies('datos_caja_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.datosCajas.edit', compact('datosCaja'));
    }

    public function update(UpdateDatosCajaRequest $request, DatosCaja $datosCaja)
    {
        $datosCaja->update($request->all());

        return redirect()->route('admin.datos-cajas.index');
    }

    public function show(DatosCaja $datosCaja)
    {
        abort_if(Gate::denies('datos_caja_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.datosCajas.show', compact('datosCaja'));
    }

    public function destroy(DatosCaja $datosCaja)
    {
        abort_if(Gate::denies('datos_caja_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $datosCaja->delete();

        return back();
    }

    public function massDestroy(MassDestroyDatosCajaRequest $request)
    {
        $datosCajas = DatosCaja::find(request('ids'));

        foreach ($datosCajas as $datosCaja) {
            $datosCaja->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function getAttendanceData()
    {
        $filename = ('public/attendance_data_october.csv');
        $data = [];


        if (Storage::exists($filename)) {

            $fileContent = Storage::get($filename);
            $lines = explode("\n", $fileContent);

            foreach ($lines as $line) {
                $columns = str_getcsv($line);
                if (count($columns) === 4) {
                    $data[] = [
                        'fecha_hora' => $columns[0],
                        'locacion_id' => $columns[1],
                        'personal_id' => $columns[2],
                        'turno_id' => $columns[3],
                    ];
                }
            }
        }

        return response()->json($data);
    }
    public function getDailyAttendanceData()
    {
        // Ruta del archivo CSV
        $filePath = Storage::path('public/attendance_data_october.csv');

        // Verifica que el archivo exista
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        // Leer el archivo y procesar los datos
        $attendanceData = [];
        if (($handle = fopen($filePath, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $date = date('Y-m-d', strtotime($data[0])); //$data[0]

                // Cuenta asistencias por día
                if (isset($attendanceData[$date])) {
                    $attendanceData[$date]++;
                } else {
                    $attendanceData[$date] = 1;
                }
            }
            fclose($handle);
        }

        // Ordena los datos por fecha
        ksort($attendanceData);

        // Prepara los datos para el gráfico
        $labels = array_keys($attendanceData); // Fechas
        $data = array_values($attendanceData); // Cantidad de asistencias por día

        return response()->json(['labels' => $labels, 'data' => $data]);
    }
    public function getAttendanceByTurn()
    {
        $filePath = Storage::path('public/attendance_data_october.csv');

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        $attendanceByTurn = [];
        if (($handle = fopen($filePath, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $turno_id = $data[3]; // Extrae el turno_id

                if (isset($attendanceByTurn[$turno_id])) {
                    $attendanceByTurn[$turno_id]++;
                } else {
                    $attendanceByTurn[$turno_id] = 1;
                }
            }
            fclose($handle);
        }

        $labels = array_keys($attendanceByTurn); // IDs de los turnos
        $data = array_values($attendanceByTurn); // Cantidad de asistencias por turno

        return response()->json(['labels' => $labels, 'data' => $data]);
    }
    public function getTurnoLocacionData()
    {
        // Leer archivo CSV
        $filePath = storage_path('app/public/attendance_data_october.csv');
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);  // Asegura que la primera fila se toma como encabezado

        // Procesar datos
        $turnoLocacionData = [];
        foreach ($csv as $record) {
            $locacionId = $record['locacion_id'];
            $turnoId = $record['turno_id'];

            // Inicializa si no existe
            if (!isset($turnoLocacionData[$locacionId])) {
                $turnoLocacionData[$locacionId] = [];
            }

            // Incrementar contador para cada turno
            if (!isset($turnoLocacionData[$locacionId][$turnoId])) {
                $turnoLocacionData[$locacionId][$turnoId] = 0;
            }
            $turnoLocacionData[$locacionId][$turnoId]++;
        }

        return $turnoLocacionData;
    }
    public function showTurnoLocacionChart()
    {
        $data = $this->getTurnoLocacionData();
        return response()->json($data);
    }
    public function getScatterPlotData()
    {
        $filePath = storage_path('app/public/attendance_data_october.csv');

        // Leer los datos del archivo CSV
        $data = array_map('str_getcsv', file($filePath));

        // Eliminar la primera fila (cabecera)
        array_shift($data);

        // Organizar los datos para el gráfico
        $scatterData = [];
        foreach ($data as $row) {
            $personal_id = $row[2]; // personal_id
            $turno_id = $row[3]; // turno_id
            $locacion_id = $row[1]; // locacion_id

            $scatterData[] = [
                'x' => $personal_id,    // Usamos personal_id en el eje X
                'y' => $locacion_id,    // Usamos locacion_id en el eje Y
                'turno' => $turno_id,   // Información de turno (podemos usarlo para color o tamaño)
            ];
        }
        return response()->json($scatterData);
    }
}
