<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEmbarqueRequest;
use App\Http\Requests\StoreEmbarqueRequest;
use App\Http\Requests\UpdateEmbarqueRequest;
use App\Models\Embarque;
use App\Imports\ExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\Mensaje;
use App\Mail\MiMailable;
use Illuminate\Support\Facades\Mail;
use App\Mail\MensajeGenericoMailable;
use App\Models\ClientesComex;
use App\Models\Capturador;
use App\Models\CapturadorEstructura;
use App\Imports\ExcelConversor;
use App\Models\ExcelDato;
use Illuminate\Support\Str;
use App\Models\LiqCxCabecera;
use App\Models\LiquidacionesCx;
use App\Models\LiqCosto;
use App\Models\Costo;
use App\Models\Nafe;
use App\Libs\Liquidaciones;
use App\Exports\ComparativaExport;
use App\Models\Diccionario;
use Exception;
use Psy\Readline\Hoa\Console;
use Symfony\Component\Console\Logger\ConsoleLogger;

class ComexController extends Controller
{
    use CsvImportTrait;
    public function capturador()
    {
        $Capturador = Capturador::all();
        return view('admin.comex.capturador', compact('Capturador'));
    }
    public function capturadorexcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
            'plantilla' => 'required|string',
        ]);
        $instructivo = $request->input('instructivo');
        $tasa = $request->input('tasa');

        try {
            $archivo = $request->file('file');
            $capturadorId = $request->input('plantilla');
            $capturador = Capturador::find($capturadorId);
            $fa = explode('/', $request->input('fecha_arribo'));
            $fv = explode('/', $request->input('fecha_venta'));
            $fl = explode('/', $request->input('fecha_liquidacion'));
            $fecha_arribo = $fa[2] . '-' . $fa[1] . '-' . $fa[0];
            $fecha_venta = $fv[2] . '-' . $fv[1] . '-' . $fv[0];
            $fecha_liquidacion = $fl[2] . '-' . $fl[1] . '-' . $fl[0];
            $fila_costos = $request->input('fila_costos');

            if (!$capturador) {
                return response()->json(['message' => 'Capturador no encontrado.'], 404);
            }

            $modulo = $capturador->modulo_id;
            $cliente = $capturador->cliente_id;

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivo->getPathname());
            $hoja = $spreadsheet->getActiveSheet();

            $estructuras = CapturadorEstructura::where('capturador_id', $capturadorId)
                ->where('visible', true)
                ->orderBy('tipos_seccion_conversors_id')
                ->get();

            if ($estructuras->isEmpty()) {
                return response()->json(['message' => 'No hay estructuras configuradas para este capturador.'], 400);
            }

            $datos = [];
            $items = [];
            $costos = [];
            $fila = 0;
            $f = $estructuras->where('tipos_seccion_conversors_id', 2)->first();

            preg_match('/(\D+)(\d+)/', $f->coordenada, $matches);
            $col = $matches[1]; // A
            $fil = (int)$matches[2]; // 5
            $filaCostos = 0;
            $condicion = true;

            foreach ($estructuras as $estructura) {
                $tipoSeccion = $estructura->tipos_seccion_conversors_id; // Define el tipo de sección

                if ($tipoSeccion == 1) {

                    $valor = $hoja->getCell($estructura->coordenada)->getValue();
                    $datos['cabecera'][] = [
                        'coordenada' => $estructura->coordenada,
                        'propiedad' => $estructura->propiedad,
                        'valor' => $this->normalizarTexto($valor),
                    ];
                }
                Log::info("Cabecera" . $estructura->coordenada . "----" . $estructura->propiedad . "----" . $estructura->propiedad);
            }

            $estrItems = $estructuras->where('tipos_seccion_conversors_id', 2)->sortBy(['coordenada', 'asc']);

            foreach ($estrItems as $estructura) {


                $filaInicial = $estructura->coordenada; // Por ejemplo, A5
                preg_match('/(\D+)(\d+)/', $filaInicial, $matches);

                try {
                    $columna = $matches[1]; // A
                    $fila = (int)$matches[2]; // 5
                } catch (\Exception $e) {
                    Log::info("Error" . $e->getMessage() . "----" . $estructura->coordenada."----" . $estructura->propiedad."----" . $e->getTraceAsString());
                    return response()->json([
                        'message' => 'Ocurrió un error al procesar el archivo.',
                        'error' => $e->getMessage() . "----" . $estructura->coordenada,
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
                //$filaCostos=$fila;

                while (true) {
                    $valorCelda = $hoja->getCell("{$columna}{$fila}")->getValue();
                    Log::info('celdaItems1(' . $columna . $fila . ') :' . $hoja->getCell("{$columna}{$fila}")->getValue());
                    if ($fila == $fila_costos && $capturador->id != 7) {


                        break;
                    }
                    if ($capturador->id == 7 && $valorCelda == '') {
                        break;
                    }
                    $item = [];

                    preg_match('/(\D+)/', $estructura->coordenada, $colMatch);
                    Log::info('celdaItems2(' . $colMatch[1] . $fila . ') :' . $hoja->getCell("{$colMatch[1]}$fila")->getValue());
                    $col = $colMatch[1];
                    Log::info('celdaItems(' . $col . $fila . ') :' . $hoja->getCell("{$col}{$fila}")->getValue());
                    if ($estructura->propiedad == "Calibre") {
                        $item[] = [
                            'coordenada' => "{$col}{$fila}",
                            'propiedad' => $estructura->propiedad,
                            'valor' => $this->traducedatos($hoja->getCell("{$col}{$fila}")->getValue(), 'Calibre'),
                            'orden' => $estructura->orden,
                        ];
                    } elseif ($estructura->propiedad == "Embalaje") {
                        $item[] = [
                            'coordenada' => "{$col}{$fila}",
                            'propiedad' => $estructura->propiedad,
                            'valor' => $this->traducedatos($hoja->getCell("{$col}{$fila}")->getValue(), 'Embalaje'),
                            'orden' => $estructura->orden,
                        ];
                    } else {
                        $item[] = [
                            'coordenada' => "{$col}{$fila}",
                            'propiedad' => $estructura->propiedad,
                            'valor' => $this->normalizarTexto($hoja->getCell("{$col}{$fila}")->getValue()),
                            'orden' => $estructura->orden,
                        ];
                    }
                    //Log::info('Celda('.$col.$fila.') :' . $hoja->getCell("{$col}{$fila}")->getValue());

                    Log::info('Item: ' . json_encode($item));

                    $items[] = $item;
                    $fila++;
                }
            }


            $estrItems = $estructuras->where('tipos_seccion_conversors_id', 3)->sortBy(['orden', 'asc']);



            foreach ($estrItems as $estructura) {

                $filaInicialCostos = $estructura->coordenada;
                preg_match('/(\D+)(\d+)/', $filaInicialCostos, $matchesCostos);
                $columnaCostos = $matchesCostos[1]; // A


                $valor = $hoja->getCell("{$columnaCostos}{$fila_costos}")->getValue();

                $costos[] = [
                    'coordenada' => "{$columnaCostos}{$fila_costos}",
                    'propiedad' => $estructura->propiedad,
                    'valor' => $this->normalizarTexto($valor),
                ];
                $fila_costos++;
            }

            foreach ($costos as &$costo) {
                if ($costo["propiedad"] == "Ajuste Impuesto") {
                    $costo["valor"] = (float)$costo["valor"] * (float)$tasa;
                }
            }

            $datosExcel = ExcelDato::where('instructivo', $instructivo)->get();
            //    dd($fecha_arribo,$fecha_venta,$fecha_liquidacion,$fila_costos);
            // Guardar en la base de datos
            if (count($datosExcel) == 0 || $datosExcel == null) {
                ExcelDato::create([
                    'archivo_id' => uniqid(),
                    'master_id' => $capturadorId,
                    'modulo' => $modulo,
                    'cliente' => $cliente,
                    'nombre_archivo' => $this->normalizarTexto($archivo->getClientOriginalName()),
                    'instructivo' => $instructivo,
                    'tasa' => $tasa,
                    'fecha_arribo' => Carbon::parse($fecha_arribo)->format('Y-m-d'), // Formatear la fecha $fecha_arribo,
                    'fecha_venta' => Carbon::parse($fecha_venta)->format('Y-m-d'), // Formatear la fecha $fecha_venta,
                    'fecha_liquidacion' => Carbon::parse($fecha_liquidacion)->format('Y-m-d'), // Formatear la fecha $fecha_liquidacion,
                    'fila_costos' => $fila_costos,
                    'datos' => json_encode([
                        'cabecera' => $datos['cabecera'] ?? [],
                        'items' => $items,
                        'costos' => $costos,
                    ], JSON_UNESCAPED_UNICODE),

                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al procesar el archivo Excel: ' . $e->getMessage() . "----" . $e->getLine() . "----" . $e->getTraceAsString());
            return response()->json([
                'message' => 'Ocurrió un error al procesar el archivo.',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
        try {
            // 📥 Obtener datos desde la base de datos
            $datosExcel = ExcelDato::where('instructivo', $instructivo)->firstOrFail();
            $datos = json_decode($datosExcel->datos, true);

            // 🛠️ Inicializar variables
            $cabecera = $datos['cabecera'] ?? [];
            $items = $datos['items'] ?? [];
            $costos = $datos['costos'] ?? [];



            $totalItems = 0;
            $totalCostos = 0;

            // 🧮 **Calcular Totales en Items**
            foreach ($items as $item) {

                $cantidad = $item[0]['propiedad'] == "Cantidad" ? floatval($item[0]['valor']) : 0;
                $precioUnitario = $item[0]['propiedad'] == "Precio Unitario" ? floatval($item[0]['valor']) : 0;
                if ($cantidad != 0 && $precioUnitario != 0) {
                    $cantidad = 1;
                    $item['TotalLinea'] = $cantidad * $precioUnitario;

                    $totalItems += $item['TotalLinea'];
                }
            }

            // 🧮 **Calcular Totales en Costos**
            foreach ($costos as $costo) {
                $monto = isset($costo['Monto']) ? floatval($costo['Monto']) : 0;
                $totalCostos += $monto;
            }

            // 🧮 **Calcular Total General**
            $totalGeneral = $totalItems - $totalCostos;

            // 📤 Enviar datos a la vista
            return view('admin.comex.capturaliquidaciones', [
                'cabecera' => $datos['cabecera'],
                'items' => $datos['items'],
                'costos' => $datos['costos'],
                'instructivo' => $instructivo,
                'tasa' => $tasa,
                'datosExcel' => $datosExcel,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al mostrar los datos procesados: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ocurrió un error al mostrar los datos.',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }
    public function mostrarDatosProcesados($archivoId) {}
    private function filaCompletaVacia($hoja, $fila)
    {
        $ultimaColumna = $hoja->getHighestColumn(); // Obtiene la última columna con datos
        foreach (range('A', $ultimaColumna) as $columna) {
            $valor = $hoja->getCell("$columna$fila")->getValue();
            if (!is_null($valor) && trim($valor) !== '') {
                return false; // Si alguna celda tiene datos, la fila no está vacía
            }
        }
        return true; // Todas las celdas están vacías
    }
    /**
     * 📌 **Función Auxiliar para Buscar Texto en una Celda**
     */
    private function contieneTexto($texto, $busqueda)
    {
        return stripos($texto, $busqueda) !== false; // No distingue mayúsculas/minúsculas
    }

    private function normalizarTexto($texto)
    {
        return preg_replace('/[^\x20-\x7E]/', '', $texto); // Elimina cualquier carácter no ASCII
    }
    /**
     * Convierte una coordenada (ej. A1) a columna y fila.
     */
    private function parsearCoordenada($coordenada)
    {
        preg_match('/([A-Z]+)(\d+)/', strtoupper($coordenada), $matches);

        $columna = $matches[1] ?? null;
        $fila = $matches[2] ?? null;

        return [$columna, $fila];
    }

    /**
     * Convierte la letra de una columna (ej. A) a un índice numérico.
     */
    private function columnaALetra($letra)
    {
        $letra = strtoupper($letra);
        $longitud = strlen($letra);
        $numero = 0;

        for ($i = 0; $i < $longitud; $i++) {
            $numero = $numero * 26 + (ord($letra[$i]) - ord('A') + 1);
        }

        return $numero;
    }
    public function guardaliquidacion(Request $request)
    {
        try {
            $instructivo = $request->input('instructivo');
            $tasa = $request->input('tasa');

            $datosLiq = ExcelDato::where('instructivo', $instructivo)->firstOrFail();
            $datos = json_decode($datosLiq->datos, true);

            // 🛠️ Inicializar variables
            $cabecera = $datos['cabecera'] ?? [];
            $items = $datos['items'] ?? [];
            $costos = $datos['costos'] ?? [];
            $clltCabecera = collect($cabecera);
            $clltItems = collect($items);
            $clltCostos = collect($costos);

            $cliente = $datosLiq->cliente;
            $master = $datosLiq->master_id;

            $LiqCabecera = new LiqCxCabecera();
            // dd($cabecera);
            /*
         'instructivo',
        'cliente_id',
        'nave_id',
        'eta',
        'tasa_intercambio',
        'total_costo',
        'total_bruto',
        'total_neto',
         */

            $LiqCabecera->cliente_id = $cliente;
            $LiqCabecera->instructivo = $instructivo;
            $LiqCabecera->tasa_intercambio = $tasa;
            $LiqCabecera->flete_exportadora = $request->input('flete_exportadora');
            $LiqCabecera->factor_imp_destino = 0;
            //nave

            $nave_id = $clltCabecera->first(function ($nave) {
                return $nave['propiedad'] === 'Nave';
            });
            if (isset($nave_id['valor'])) {
                $LiqCabecera->nave_id = $nave_id['valor'];
            }

            $etaFA = $clltCabecera->first(function ($eta) {
                return $eta['propiedad'] === 'Fecha de Arribo';
            });

            $LiqCabecera->eta = $request->input('fecha_arribo');
            //dd($LiqCabecera->eta);
            $LiqCabecera->total_costo = 0;
            $LiqCabecera->total_bruto = 0;
            $LiqCabecera->total_neto = 0;
            $costos = $clltCostos->map(function ($costo) {
                return [
                    'propiedad' => $costo['propiedad'],
                    'valor' => $costo['valor'],
                ];
            });

            $LiqCabecera->save();
            $registros = [];

            // Iterar sobre cada elemento y agrupar por fila (número en la coordenada)
            // Transformar la colección
            $registros = $clltItems
                ->groupBy(function ($item) {

                    return preg_replace('/\D/', '', $item[0]['coordenada']); // Extraer solo el número de la coordenada
                })
                ->map(function ($fila) {
                    return $fila->mapWithKeys(function ($item) {
                        return [$item[0]['propiedad'] => $item[0]['valor']];
                    });
                })
                ->values();

            foreach ($registros as $fila) {

                $contenedor = isset($fila['Contenedor']) ? $fila['Contenedor'] : '';
                $eta = $LiqCabecera->eta;
                // if ($master == 8) {
                //     $texto = $fila['Embalaje'];
                //     if (preg_match('/智利车厘子(.*?)5KG/', $texto, $matches)) {
                //         $resultado = trim($matches[1]); // Remueve espacios extra

                //     } else {
                //         $resultado = "Sin Variedad.";
                //     }
                //     $variedad_id =  $resultado;
                // } else {

                $variedad_id = $fila['Variedad'];
                //}
                $pallet = isset($fila['Pallet']) ? $fila['Pallet'] : '';
                $etiqueta_id = isset($fila['Etiqueta']) ? $fila['Etiqueta'] : '';
                $calibre = isset($fila['Calibre']) ? $fila['Calibre'] : '';
                $embalaje_id = isset($fila['Embalaje']) ? $fila['Embalaje'] : '';
                $cantidad = isset($fila['Cantidad']) ? $fila['Cantidad'] : 0;
                $fecha_venta = $request->input('fecha_venta');
                $ventas = isset($fila['Ventas']) ? $fila['Ventas'] : 0;
                $precio_unitario = isset($fila['Precio Unitario']) ? $fila['Precio Unitario'] : 0;
                $monto_rmb = isset($fila['Monto RMB']) ? $fila['Monto RMB'] : 0;
                $observaciones = isset($fila['Observaciones']) ? $fila['Observaciones'] : '';
                $liqcabecera_id = $LiqCabecera->id;
                $resultados = DB::connection('sqlsrv')
                    ->table('dbo.V_PKG_Embarques')
                    ->selectRaw('
                                    n_variedad,
                                    C_Embalaje,
                                    c_calibre,
                                    n_etiqueta,
                                    SUM(Cantidad) as total_cantidad
                                ')
                        ->where('numero_referencia', $instructivo)
                        ->where('n_variedad', $variedad_id)
                        ->where('n_etiqueta', $etiqueta_id)
                        ->where('c_calibre', $calibre)
                        ->groupBy('n_variedad', 'C_Embalaje', 'c_calibre', 'n_etiqueta')
                        ->get();
                $c_embalaje='';
                $folio_fx='';
                        if(count($resultados)>0){

                            //$liq=LiquidacionesCx::where('liqcabecera_id', $dato->id)->where('variedad_id', $item->variedad_id)->where('etiqueta_id', $item->etiqueta_id)->where('calibre', $item->calibre)->first();
                            $c_embalaje=$resultados[0]->C_Embalaje;
                        }
                        DB::statement('SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED');
                $resultados = DB::connection('sqlsrv')
                    ->table('dbo.V_PKG_Despachos')
                    ->selectRaw('folio,
                                n_variedad_rotulacion,
                                c_calibre,
                                n_etiqueta
                            ')
                            ->where('numero_embarque', str_replace('i', '', str_replace("I", "", $instructivo)))
                    //  ->where('n_variedad_rotulacion', $item->variedad_id)
                    //  ->where('n_etiqueta','like', $item->etiqueta_id.'%')
                    //  ->where('c_calibre','like',$item->calibre.'%')
                    ->where('folio', 'like', '%' . $pallet)
                    ->where('n_variedad', $variedad_id)
                    ->where('n_etiqueta', $etiqueta_id)
                    ->where('c_calibre', $calibre)
                    ->orderBy('folio')
                    ->get();
                    if (count($resultados) == 1) {
                        foreach ($resultados as $res) {
                            $folio_fx = $res->folio;

                        }
                    } elseif (count($resultados) > 1) {
                           $original='';

                           $i = 0;

                            foreach ($resultados as $res) {

                                if ($i == 0) {

                                    $folio_fx = $res->folio;
                                } else {


                                        $folio_fx = $folio_fx . "," . $res->folio;

                                }
                                $i++;
                            }

                            $array=explode(",",$folio_fx);
                            $arrayUnicos = array_unique($array);

    // Convertir el array de vuelta a una cadena
                            $cadenaUnica = implode(',', $arrayUnicos);
                            //Log::info("instructivo: " . $dato->instructivo . " Folio: " . $item->pallet . " Folios: " . $item->folio_fx." Cadena entrada ".$item->folio_fx." Cadena salida ".$cadenaUnica);
                            $folio_fx=$cadenaUnica;


                        }
                try {
                    $result = LiquidacionesCx::create([
                        'contenedor' => $contenedor,
                        'eta' => $eta,
                        'variedad_id' => $variedad_id,
                        'pallet' => $pallet,
                        'etiqueta_id' => $etiqueta_id,
                        'calibre' => $calibre,
                        'embalaje_id' => $embalaje_id,
                        'cantidad' => $cantidad,
                        'fecha_venta' => $fecha_venta,
                        'ventas' => $ventas,
                        'precio_unitario' => $precio_unitario,
                        'monto_rmb' => $monto_rmb,
                        'observaciones' => $observaciones,
                        'liqcabecera_id' => $liqcabecera_id,
                        'c_embalaje'=>$c_embalaje,
                        'folio_fx'=>$folio_fx
                    ]);
                    Log::info('Datos guardados correctamente' . "----" . $result);
                } catch (\Exception $e) {
                    Log::error('Error al guardar los datos: ' . $e->getMessage() . "----" . $e->getLine());
                }
            }
            foreach ($costos as $costo) {
                $propiedad = $costo['propiedad'];
                //$Costo::where('nombre', $propiedad)->first();

                    $valor = $costo['valor'] == "" ? 0 : $costo['valor'];


                $c = new Costo();
                try {
                    LiqCosto::create([
                        'nombre_costo' => $propiedad,
                        'valor' => $valor,
                        'liq_cabecera_id' => $liqcabecera_id
                    ]);
                    Log::info('Costos guardados correctamente');
                } catch (\Exception $e) {
                    Log::error('Error al guardar los costos: ' . $e->getMessage() . "----" . $e->getLine());
                }
            }

            // Convertir los registros en un array de filas para la base de datos
            //$final = array_values($registros);
            $LiqCabecera::find($liqcabecera_id)->first();
            $totcostos = LiqCosto::where('liq_cabecera_id', $LiqCabecera->id)
                ->select(DB::raw('SUM(valor) as total_costos'))->get();

            $total_items = LiquidacionesCx::where('liqcabecera_id', $LiqCabecera->id)
                ->select(DB::raw('SUM(cantidad*precio_unitario) as total_items'))->get();
            $total_bruto = $total_items[0]->total_items;
            $total_neto = $total_bruto - $totcostos[0]->total_costos;
            $total_costos = $totcostos[0]->total_costos;
            Log::info('Total Costos: ' . $total_costos);
            Log::info('Total Bruto: ' . $total_bruto);
            Log::info('Total Neto: ' . $total_neto);
            $LiqCabecera->total_costo = $total_costos;
            $LiqCabecera->total_bruto = $total_bruto;
            $LiqCabecera->total_neto = $total_neto;

            $LiqCabecera->update();
            //procesamos la cabecera
            $clientes_comexes = ClientesComex::get();
            $naves            = Nafe::get();
            $message = 'Datos procesados y guardados correctamente.';
            $status = 'success';
            return redirect()->route('admin.liq-cx-cabeceras.edit', $liqcabecera_id)->with('message', $message); //view('admin.liqCxCabeceras.index', compact('message', 'status', 'clientes_comexes', 'naves'));
        } catch (\Exception $e) {
            $clientes_comexes = ClientesComex::get();
            $naves            = Nafe::get();
            Log::error('Error al procesar los datos: ' . $e->getMessage() . "----" . $e->getLine());
            $message = 'Error al procesar los datos: ' . $e->getMessage() . "----" . $e->getLine();
            $status = 'error';
            return redirect()->route('admin.liq-cx-cabeceras.index')->with('error', 'Error al guardar los datos.');
        }
    }
    function formatDate2($date)
    {
        try {
            // Excel almacena las fechas como un número de días desde 1900-01-01
            $excelDate = (float)$date;  // Convertir a float para asegurar que el cálculo sea preciso

            // Ajustar el desfase desde 1900-01-01 (día base en Excel)
            $timestamp = Carbon::createFromTimestampUTC((int)(($excelDate - 25569) * 86400));

            // Establecer la zona horaria requerida
            //$timestamp->setTimezone('America/Santiago');

            // Devolver el formato esperado
            return $timestamp->format('Y-m-d');
        } catch (\Exception $e) {
            Log::error("Error al formatear la fecha: " . $e->getMessage());
            return $date; // Si falla, devuelve la fecha original
        }
    }

    //generar excel comparativo
    public function generacomparativa(Request $request)
    {
        $ids = json_decode($request->ids, true);

        $liqCxCabeceras = LiqCxCabecera::whereIn('id', $ids)->get(); // LiqCxCabecera::find(request('ids'));

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

            $nombre_costo = Costo::pluck('nombre'); // Extraer solo los nombres de costos
            $total_kilos = 0;
            $total_ventas = 0;
            foreach ($detalle as $item) {
                $total_kilos = $total_kilos + (float)(str_replace(',', '.', $this->traducedatos($item->embalaje_id, 'Embalaje'))) * (float)(str_replace(',', '.', $item->cantidad));

                $total_ventas = $total_ventas + $item->cantidad * (float)(str_replace(',', '.', $item->precio_unitario));

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
                            $comision += $costo->valor;
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

                $calculos = [
                    "SANITIZING FEE" => '',
                    "DOMESTIC FREIGHT" => '',
                    "Handling" => 0,
                    "Airport handling" => '',
                    "Trucking" => '',
                    "Forklift" => '',
                    "Labor charge" => '',
                    "VAT 9％" => $item->monto_rmb * 0.09,
                    "MARKET ENTRY FEE" => 0,
                    "OCEAN FREIGHT" => 0,
                    "AIR FREIGHT" => 0,
                    "CUSTOMS DECLARATION" => 0,
                    "CHARGES" => 0,
                    "JIANGNAN MARKET OPERATION FEE" => 0,
                    "Total Charges" => 0,
                    "Resultado Bruto Total" => 0,
                    "Resultado Bruto Unitario Caja" => 0,
                    "Kilos" => 0,
                    "Resultado Bruto Unitario Kilo" => 0,
                    "USD Resultado Bruto Total" => 0,
                    "USD Resultado Bruto Unitario Caja" => 0,
                    "USD Resultado Bruto Unitario Kilo" => 0,
                    "Venta Kilo RMB" => 0,
                    "Tipo de embalaje" => $this->traducedatos($item->embalaje_id, 'Embalaje'),
                ];
                //  dd($liqCxCabecera);
                // Agregar los datos principales y los costos procesados al array

                $dataComparativa->push(array_merge(
                    [
                        'Embarque' => '',  //A
                        'cliente' => $liqCxCabecera->cliente->nombre_fantasia, //B
                        'nave' => $liqCxCabecera->nave_id, //C
                        'Puerto Destino' => '', //D
                        'AWB' => '', //E
                        'Contenedor' => '', //F
                        'Liquidación' => $liqCxCabecera->instructivo, //G
                        'ETD' => '', //H
                        'ETD Week' => '', //I
                        'ETA' => ($excelDato->fecha_arribo ? Carbon::parse($excelDato->fecha_arribo)->format('Y-m-d'):''), //J
                        'ETA Week' => ($excelDato->fecha_arribo ? Carbon::parse($excelDato->fecha_arribo)->weekOfYear : 0), //K
                        'Fecha Venta' => $item->fecha_venta ? Carbon::parse($item->fecha_venta) : 0, //L
                        'Fecha Venta Week' => ($excelDato->fecha_venta ? Carbon::parse($excelDato->fecha_venta)->weekOfYear : 0), //M
                        'Fecha Liquidación' => $excelDato->fecha_liquidacion, //N
                        'Pallet' => $item->pallet, //O
                        'Peso neto' =>  $this->traducedatos($item->embalaje_id, 'Embalaje'), //P
                        'Kilos total' => '=+P' . $i . '*Y' . $i, //Q
                        'embalaje' => $this->traducedatos($item->embalaje_id, 'Embalaje'), //R
                        'etiqueta' => $item->etiqueta_id, //S
                        'variedad' => $item->variedad_id, //T
                        'Calibre Estandar'   => '', //U
                        'calibre' => $this->traducedatos($item->calibre, 'Calibre'), //V
                        'color' => '', //W
                        'Observaciones' => $item->observaciones, //X
                        'Cajas' => $item->cantidad, //y
                        'RMB Caja' => isset($item->precio_unitario) ? $item->precio_unitario : 0, //z
                        'RMB Venta' => $item->cantidad * $item->precio_unitario, //AA
                        'Comision Caja' => '=+AC' . $i . '*Z' . $i, //AB
                        '% Comisión' => $porcComision, //AC
                        'RMB Comisión' => '=+AB' . $i . '*Y' . $i, //AD
                        'Factor Imp destino' => $factor_imp_destino, //AE  Esto no esta definido como para poder calcularlo
                        'Imp destino caja RMB' => '=+(AE' . $i . '*Z' . $i . ')', //AF
                        'RMB Imp destino TO' => '=+AF' . $i . '*Y' . $i, //AG
                        'Costo log. Caja RMB' => '=+(' . ($costosLogisticos == 0 ? 0 : $costosLogisticos) . '/' . $total_kilos . ')*P' . $i, //AH
                        'RMB Costo log. TO' => '=+AH' . $i . '*Y' . $i, //AI
                        'Ent. Al mercado Caja RMB' => '=+(' . ($entradamercado == 0 ? 0 : $entradamercado) . '/' . $total_kilos . ')*P' . $i, //AJ Preguntar a Haydelin
                        'RMB Ent. Al mercado TO' => '=+AJ' . $i . '*Y' . $i, //AK
                        'Costo mercado caja RMB' => '=+(' . ($costosMercado == 0 ? 0 : $costosMercado) . '/' . $total_kilos . ')*P' . $i, //AL
                        'RMB Costos mercado TO' => '=+AL' . $i . '*Y' . $i, //AM
                        'Otros  costos dest. Caja RMB' => '=+(' . ($otroscostosdestino == 0 ? 0 : $otroscostosdestino) . '/' . $total_kilos . ')*P' . $i,  //AN  debemos configurar costos en categoría otros
                        'RMB otros costos TO' => '=+AN' . $i . '*Y' . $i, //AO
                        'Flete marit. Caja RMB' => '=+(' . ($costosFleteInternacional == 0 ? 0 : $costosFleteInternacional) . '/' . $total_kilos . ')*P' . $i, //AP
                        'RMB Flete Marit. TO' => '=+AP' . $i . '*Y' . $i, //AQ
                        'Costos cajas RMB' => '=+AF' . $i . '+AH' . $i . '+AJ' . $i . '+AL' . $i . '+AN' . $i . '+AB' . $i . '+AP' . $i . '+(CA' . $i . '*AV'.$i. ')+(BO' . $i . '*AV'.$i.')-(CC' . $i . '*AV'.$i.')+(BQ'.$i.'*AV'.$i.')', //AR
                        'RMB Costos TO' => '=+AR' . $i . '*Y' . $i, //AS
                        'Resultados caja RMB' => '=+Z' . $i . '-AR' . $i,  //AT  Verificar con Haydelin
                        'RMB result. TO' => '=+AT' . $i . '*Y' . $i, //AU  Verificar con Haydelin
                        'TC'    => $excelDato->tasa, //AV
                        'Venta USD' => '=+Z' . $i . '/AV' . $i, //AW
                        'Ventas TO USD' => '=+AW' . $i . '*Y' . $i, //AX
                        'Com USD' => '=+AB' . $i . '/AV' . $i, //AY
                        'Com TO USD' => '=+AY' . $i . '*Y' . $i, //AZ
                        'Imp destino USD' => '=+AF' . $i . '/AV' . $i, //BA
                        'Imp destino USD TO' => '=+BA' . $i . '*Y' . $i, //BB
                        'Costo log. USD' => '=+AH' . $i . '/AV' . $i, //BC
                        'Costo log. USD TO' => '=+BC' . $i . '*Y' . $i, //BD
                        'Ent. Al mercado USD' => '=+AJ' . $i . '/AV' . $i, //BE
                        'Ent. Al mercado USD TO' => '=+BE' . $i . '*Y' . $i, //BF
                        'Costo mercado USD' => '=+AL' . $i . '/AV' . $i, //BG
                        'Costos mercado USD TO' => '=+BG' . $i . '*Y' . $i, //BH
                        'Otros  costos dest. USD' => '=+AN' . $i . '/AV' . $i, //BI
                        'Otros costos USD TO' => '=+BI' . $i . '*Y' . $i, //BJ
                        'Flete marit. USD'    => '=+AP' . $i . '/AV' . $i, //BK
                        'Flete Marit. USD TO' => '=+BK' . $i . '*Y' . $i, //BL
                        'Costos cajas USD' => '=+AR' . $i . '/AV' . $i, //BM
                        'Costos USD TO' => '=+BM' . $i . '*Y' . $i, //BN
                        'Ajuste impuesto USD' => '=+(' . ($ajusteimpuesto == 0 ? 0 : ($ajusteimpuesto)/$excelDato->tasa) . '/' . $total_kilos . ')*P' . $i, //BO
                        'Ajuste TO USD' => '=+BO' . $i . '*Y' . $i, //BP
                        'Flete Aereo' => '=+(' . $flete_exportadora . '/' . $total_kilos . ')*P' . $i, //BQ
                        'Flete Aereo TO' => '=+BQ' . $i . '*Y' . $i, //BR
                        'FOB USD' => '=+(AT' . $i . '/AV' . $i . ')', //BS
                        'FOB TO USD' => '=+BS' . $i . '*Y' . $i, //BT
                        'FOB kg' => '=+BT' . $i . '/Q' . $i, //BU
                        'FOB Equivalente' => '=+BU' . $i . '*5', //BV
                        'Flete Cliente' => $flete_exportadora > 0 ? 'NO' : 'SI', //BW
                        'Transporte' => $tipo_transporte == "A" ? 'AEREO' : 'MARITIMO', //BX
                        'CNY' => 'PRE', //BY
                        'Pais' => 'CHINA', //BZ
                        'Otros Impuestos (JWM) Impuestos' => '=+(' . ($otrosimpuestos == 0 ? 0 : ($otrosimpuestos / $excelDato->tasa)) . '/' . $total_kilos . ')*P' . $i, //CA
                        'Otros Impuestos (JWM) TO USD' => '=+CA' . $i . '*Y' . $i, //CB
                        'Otros Ingresos (abonos)' => '=+(' . ($otrosingresos == 0 ? 0 : ($otrosingresos / $excelDato->tasa)) . '/' . $total_kilos . ')*P' . $i, //CC
                        'Otros Ingresos (abonos) TO USD' => '=+CC' . $i . '*Y' . $i, //CD
                        'RMB Flete Domestico. Caja' => '=+(' . ($costosFleteDomestico == 0 ? 0 : $costosFleteDomestico) . '/' . $total_kilos . ')*P' . $i, //CE
                        'RMB Flete Domestico. TO' => '=+CE' . $i . '*Y' . $i, //CF
                        'USD Flete Domestico. '    => '=+CE' . $i . '/AV' . $i, //CG
                        'USD Flete Domestico. TO' => '=+CG' . $i . '*Y' . $i, //CH
                        'embalaje_dato_origen'=>$item->c_embalaje, //CI

                    ],
                    //$costo_procesado,
                    // $calculos

                    // Incorporar los costos como columnas adicionales
                ));
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
        return Excel::download(new ComparativaExport($dataComparativa), 'comparativa-liquidaciones' . date('Y-m-d H:i:s') . '.xlsx');
    }
    public function generacomparativaglobal(Request $request)
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
                Log::info("Total Venta: " . $total_ventas);
            }
            $porcComision = '0,06';
            foreach ($detalle as $item) {

                $costos = LiqCosto::where('liq_cabecera_id', $liqCxCabecera->id)->get();

                // // Inicializar los costos procesados con valores por defecto (0)
                // $costo_procesado = $nombre_costo->mapWithKeys(function ($nombre) {
                //     return [$nombre => 0];
                // })->toArray();

                // Procesar los costos reales

                foreach ($costos as $costo) {
                    // if (array_key_exists($costo->nombre_costo, $costo_procesado)) {
                    //     $costo_procesado[$costo->nombre_costo] = $costo->valor;
                    // } else {
                    //     // Si el costo no existe en la lista de costos procesados, agregarlo con valor 0
                    //     $costo_procesado[$costo->nombre_costo] = 0;
                    // }
                    // Log::info('Nombre Costo:' . "----" . $costo->nombre_costo);
                    // $CatCosto = Costo::where('nombre', $costo->nombre_costo)->first();
                    // // Log::info('Categoria Costo:' . "----" . $CatCosto->categoria."----".$costo->valor);

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
                            $comision += $costo->valor;
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

                $calculos = [
                    "SANITIZING FEE" => '',
                    "DOMESTIC FREIGHT" => '',
                    "Handling" => 0,
                    "Airport handling" => '',
                    "Trucking" => '',
                    "Forklift" => '',
                    "Labor charge" => '',
                    "VAT 9％" => $item->monto_rmb * 0.09,
                    "MARKET ENTRY FEE" => 0,
                    "OCEAN FREIGHT" => 0,
                    "AIR FREIGHT" => 0,
                    "CUSTOMS DECLARATION" => 0,
                    "CHARGES" => 0,
                    "JIANGNAN MARKET OPERATION FEE" => 0,
                    "Total Charges" => 0,
                    "Resultado Bruto Total" => 0,
                    "Resultado Bruto Unitario Caja" => 0,
                    "Kilos" => 0,
                    "Resultado Bruto Unitario Kilo" => 0,
                    "USD Resultado Bruto Total" => 0,
                    "USD Resultado Bruto Unitario Caja" => 0,
                    "USD Resultado Bruto Unitario Kilo" => 0,
                    "Venta Kilo RMB" => 0,
                    "Tipo de embalaje" => $this->traducedatos($item->embalaje_id, 'Embalaje'),
                ];
                //  dd($liqCxCabecera);
                // Agregar los datos principales y los costos procesados al array

                Log::info("Instructivo: " . $liqCxCabecera->instructivo);
                $dataComparativa->push(array_merge(
                    [
                        'Embarque' => '',  //A
                        'cliente' => $liqCxCabecera->cliente->nombre_fantasia, //B
                        'nave' => $liqCxCabecera->nave_id, //C
                        'Puerto Destino' => '', //D
                        'AWB' => '', //E
                        'Contenedor' => '', //F
                        'Liquidación' => $liqCxCabecera->instructivo, //G
                        'ETD' => '', //H
                        'ETD Week' => '', //I
                        'ETA' => ($excelDato->fecha_arribo ? Carbon::parse($excelDato->fecha_arribo)->format('Y-m-d'):''), //J
                        'ETA Week' => ($excelDato->fecha_arribo ? Carbon::parse($excelDato->fecha_arribo)->weekOfYear : 0), //K
                        'Fecha Venta' => $item->fecha_venta ? Carbon::parse($item->fecha_venta) : 0, //L
                        'Fecha Venta Week' => ($excelDato->fecha_venta ? Carbon::parse($excelDato->fecha_venta)->weekOfYear : 0), //M
                        'Fecha Liquidación' => $excelDato->fecha_liquidacion, //N
                        'Pallet' => $item->pallet, //O
                        'Peso neto' =>  $this->traducedatos($item->embalaje_id, 'Embalaje'), //P
                        'Kilos total' => '=+P' . $i . '*Y' . $i, //Q
                        'embalaje' => $this->traducedatos($item->embalaje_id, 'Embalaje'), //R
                        'etiqueta' => $item->etiqueta_id, //S
                        'variedad' => $item->variedad_id, //T
                        'Calibre Estandar'   => '', //U
                        'calibre' => $this->traducedatos($item->calibre, 'Calibre'), //V
                        'color' => '', //W
                        'Observaciones' => $item->observaciones, //X
                        'Cajas' => $item->cantidad, //y
                        'RMB Caja' => isset($item->precio_unitario) ? $item->precio_unitario : 0, //z
                        'RMB Venta' => $item->cantidad * $item->precio_unitario, //AA
                        'Comision Caja' => '=+AC' . $i . '*Z' . $i, //AB
                        '% Comisión' => $porcComision, //AC
                        'RMB Comisión' => '=+AB' . $i . '*Y' . $i, //AD
                        'Factor Imp destino' => $factor_imp_destino, //AE  Esto no esta definido como para poder calcularlo
                        'Imp destino caja RMB' => '=+(AE' . $i . '*Z' . $i . ')', //AF
                        'RMB Imp destino TO' => '=+AF' . $i . '*Y' . $i, //AG
                        'Costo log. Caja RMB' => '=+(' . ($costosLogisticos == 0 ? 0 : $costosLogisticos) . '/' . $total_kilos . ')*P' . $i, //AH
                        'RMB Costo log. TO' => '=+AH' . $i . '*Y' . $i, //AI
                        'Ent. Al mercado Caja RMB' => '=+(' . ($entradamercado == 0 ? 0 : $entradamercado) . '/' . $total_kilos . ')*P' . $i, //AJ Preguntar a Haydelin
                        'RMB Ent. Al mercado TO' => '=+AJ' . $i . '*Y' . $i, //AK
                        'Costo mercado caja RMB' => '=+(' . ($costosMercado == 0 ? 0 : $costosMercado) . '/' . $total_kilos . ')*P' . $i, //AL
                        'RMB Costos mercado TO' => '=+AL' . $i . '*Y' . $i, //AM
                        'Otros  costos dest. Caja RMB' => '=+(' . ($otroscostosdestino == 0 ? 0 : $otroscostosdestino) . '/' . $total_kilos . ')*P' . $i,  //AN  debemos configurar costos en categoría otros
                        'RMB otros costos TO' => '=+AN' . $i . '*Y' . $i, //AO
                        'Flete marit. Caja RMB' => '=+(' . ($costosFleteInternacional == 0 ? 0 : $costosFleteInternacional) . '/' . $total_kilos . ')*P' . $i, //AP
                        'RMB Flete Marit. TO' => '=+AP' . $i . '*Y' . $i, //AQ
                        'Costos cajas RMB' => '=+AF' . $i . '+AH' . $i . '+AJ' . $i . '+AL' . $i . '+AN' . $i . '+AB' . $i . '+AP' . $i . '+(CA' . $i . '*AV'.$i. ')+(BO' . $i . '*AV'.$i.')-(CC' . $i . '*AV'.$i.')+(BQ'.$i.'*AV'.$i.')', //AR
                        'RMB Costos TO' => '=+AR' . $i . '*Y' . $i, //AS
                        'Resultados caja RMB' => '=+Z' . $i . '-AR' . $i,  //AT  Verificar con Haydelin
                        'RMB result. TO' => '=+AT' . $i . '*Y' . $i, //AU  Verificar con Haydelin
                        'TC'    => $excelDato->tasa, //AV
                        'Venta USD' => '=+Z' . $i . '/AV' . $i, //AW
                        'Ventas TO USD' => '=+AW' . $i . '*Y' . $i, //AX
                        'Com USD' => '=+AB' . $i . '/AV' . $i, //AY
                        'Com TO USD' => '=+AY' . $i . '*Y' . $i, //AZ
                        'Imp destino USD' => '=+AF' . $i . '/AV' . $i, //BA
                        'Imp destino USD TO' => '=+BA' . $i . '*Y' . $i, //BB
                        'Costo log. USD' => '=+AH' . $i . '/AV' . $i, //BC
                        'Costo log. USD TO' => '=+BC' . $i . '*Y' . $i, //BD
                        'Ent. Al mercado USD' => '=+AJ' . $i . '/AV' . $i, //BE
                        'Ent. Al mercado USD TO' => '=+BE' . $i . '*Y' . $i, //BF
                        'Costo mercado USD' => '=+AL' . $i . '/AV' . $i, //BG
                        'Costos mercado USD TO' => '=+BG' . $i . '*Y' . $i, //BH
                        'Otros  costos dest. USD' => '=+AN' . $i . '/AV' . $i, //BI
                        'Otros costos USD TO' => '=+BI' . $i . '*Y' . $i, //BJ
                        'Flete marit. USD'    => '=+AP' . $i . '/AV' . $i, //BK
                        'Flete Marit. USD TO' => '=+BK' . $i . '*Y' . $i, //BL
                        'Costos cajas USD' => '=+AR' . $i . '/AV' . $i, //BM
                        'Costos USD TO' => '=+BM' . $i . '*Y' . $i, //BN
                        'Ajuste impuesto USD' => '=+(' . ($ajusteimpuesto == 0 ? 0 : ($ajusteimpuesto)/$excelDato->tasa) . '/' . $total_kilos . ')*P' . $i, //BO
                        'Ajuste TO USD' => '=+BO' . $i . '*Y' . $i, //BP
                        'Flete Aereo' => '=+(' . $flete_exportadora . '/' . $total_kilos . ')*P' . $i, //BQ
                        'Flete Aereo TO' => '=+BQ' . $i . '*Y' . $i, //BR
                        'FOB USD' => '=+(AT' . $i . '/AV' . $i . ')', //BS
                        'FOB TO USD' => '=+BS' . $i . '*Y' . $i, //BT
                        'FOB kg' => '=+BT' . $i . '/Q' . $i, //BU
                        'FOB Equivalente' => '=+BU' . $i . '*5', //BV
                        'Flete Cliente' => $flete_exportadora > 0 ? 'NO' : 'SI', //BW
                        'Transporte' => $tipo_transporte == "A" ? 'AEREO' : 'MARITIMO', //BX
                        'CNY' => 'PRE', //BY
                        'Pais' => 'CHINA', //BZ
                        'Otros Impuestos (JWM) Impuestos' => '=+(' . ($otrosimpuestos == 0 ? 0 : ($otrosimpuestos / $excelDato->tasa)) . '/' . $total_kilos . ')*P' . $i, //CA
                        'Otros Impuestos (JWM) TO USD' => '=+CA' . $i . '*Y' . $i, //CB
                        'Otros Ingresos (abonos)' => '=+(' . ($otrosingresos == 0 ? 0 : ($otrosingresos / $excelDato->tasa)) . '/' . $total_kilos . ')*P' . $i, //CC
                        'Otros Ingresos (abonos) TO USD' => '=+CC' . $i . '*Y' . $i, //CD
                        'RMB Flete Domestico. Caja ' => '=+(' . ($costosFleteDomestico == 0 ? 0 : $costosFleteDomestico) . '/' . $total_kilos . ')*P' . $i, //CE
                        'RMB Flete Domestico. TO' => '=+CE' . $i . '*Y' . $i, //CF
                        'USD Flete Domestico. '    => '=+CF' . $i . '/AV' . $i, //CG
                        'USD Flete Domestico. TO' => '=+CG' . $i . '*Y' . $i, //CH
                        'embalaje_dato_origen'=>$item->c_embalaje, //CI

                    ],
                    //$costo_procesado,
                    // $calculos

                    // Incorporar los costos como columnas adicionales
                ));
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
        return Excel::download(new ComparativaExport($dataComparativa), 'comparativa-liquidaciones' . date('Y-m-d H:i:s') . '.xlsx');
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
    public function eliminardatosExcel(Request $request)
    {
        $instructivo = $request->input('instructivo');
        ExcelDato::where('instructivo', $instructivo)->delete();
        return redirect()->route('admin.comex.capturador')->with('message', 'Datos eliminados correctamente.');
    }
    public function actualizarValorGD_en_fx(){


        $resEjec = collect();
        $liq = new Liquidaciones();

        // Obtener la sesión correctamente
        if (session()->has('liqs')) {
            $liqs = session('liqs');
        } else {
            $liqs = $liq->ConsolidadoLiquidaciones();
            session(['liqs' => $liqs]);
        }

        // Obtener cabeceras
        $liqCxCabeceras = LiqCxCabecera::whereNull('deleted_at')->where('id', 67)->get();

        foreach ($liqCxCabeceras as $liqCxCabecera) {
            try {
                // Obtener despachos
                $despachos = DB::connection('sqlsrv')->table("V_PKG_Despachos")
                    ->select('folio','n_variedad','c_embalaje','n_calibre','n_etiqueta','id_pkg_stock_det')
                    ->where('tipo_g_despacho', '=', 'GDP')
                    ->where('numero_embarque', '=', str_replace('I', '', 'I2425003'))
                    ->get();

                foreach ($despachos as $despacho) {
                    $EFOB = 0;
                    $ECCajas = 0;
                    $valor = 0;

                    $items = $liqs->where('folio_fx', $despacho->folio)
                    ->where('variedad', Str::upper($despacho->n_variedad))
                    ->where('embalaje', Str::upper($despacho->c_embalaje))
                    ->where('calibre', Str::upper($despacho->n_calibre))
                    ->where('etiqueta',Str::upper($despacho->n_etiqueta));

                    Log::info('Folio despacho: ' . ($despacho->folio ?? 'N/A'));

                    foreach ($items as $item) {
                        $EFOB += $item['FOB_TO_USD'];
                        $ECCajas += $item['Cajas'];
                    }

                    // Evitar división por cero
                    $valor = ($ECCajas > 0) ? ($EFOB / $ECCajas) : 0;

                    $resEjec->push([
                        'folio' => $despacho->folio,
                        'valor' => $valor,
                    ]);
                    try {
                     //   dd(DB::connection('sqlsrv')->getPdo());
                    } catch (\Exception $e) {
                        die("Could not connect to the database.  Please check your configuration. error:" . $e );
                    }
                    // Realizar el UPDATE en la base de datos
                    DB::connection('sqlsrv')
                        ->table('PKG_Stock_Det')
                        ->where('folio', $despacho->folio)
                    ->where('id', $despacho->id_pkg_stock_det)
                        ->where('destruccion_tipo', 'GDP')
                        ->update(['valor' => $valor]);
                }
            } catch (Exception $e) {
                Log::error("Error al actualizar valor GD en FX: " . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return response()->json($resEjec);
    }


}
