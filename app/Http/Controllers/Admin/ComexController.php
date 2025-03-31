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
use App\Models\Fob;
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
use App\Models\Variedad;
use App\Models\Especy;
use Exception;
use Psy\Readline\Hoa\Console;
use Symfony\Component\Console\Logger\ConsoleLogger;

class ComexController extends Controller
{
    use CsvImportTrait;
    public function capturador()
    {
        $Capturador = Capturador::all();
        $clientes=ClientesComex::all();
        return view('admin.comex.capturador', compact('Capturador','clientes'));
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
            if($capturador->id==16){
                if($request->input('cliente')==null){
                return response()->json(['message' => 'Debe seleccionar un cliente.'], 400);
                }
                else{
                    $cliente=$request->input('cliente');
                }
            }
            else{
                $cliente = $capturador->cliente_id;
            }
            

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
                    Log::info("Error" . $e->getMessage() . "----" . $estructura->coordenada . "----" . $estructura->propiedad . "----" . $e->getTraceAsString());
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
                    //Log::info('celdaItems1(' . $columna . $fila . ') :' . $hoja->getCell("{$columna}{$fila}")->getValue());

                    //Solo por temas de RVG debo depurar aca la fila
                    if ($hoja->getCell("{$columna}{$fila}")->getValue() == '') {

                        break;
                    }



                    if ($fila == $fila_costos && ($capturador->id != 7 && $capturador->id != 15)) {


                        break;
                    }
                    if (($capturador->id == 7 || $capturador->id == 15) && $valorCelda == '') {
                        break;
                    }
                    $item = [];
                    try{

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
                    }catch(\Exception $e){
                        Log::info("Error" . $e->getMessage() . "----" . $estructura->coordenada . "----" . $estructura->propiedad . "----" . $e->getTraceAsString());
                        $fila++;
                    }
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

            // 🧮 **Calcular Totales en Items**3
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
            
            $itemsLiquidacion=collect();
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
                if ($variedad_id != '') {
                    $especie = Variedad::where('nombre', $variedad_id)->first();
                    if ($especie) {
                        $especie_id = $especie->especie_id;
                        $LiqCabecera->especie_id = $especie_id;
                    }
                }
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
               
                $LiqCabecera->save();
                //dd($LiqCabecera);
                //DB::statement('SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED');
                if($pallet!=''){
                $resultados = DB::connection('sqlsrv')
                    ->table('dbo.V_PKG_Embarques')
                    ->selectRaw('
                                    n_variedad_rotulacion,
                                    C_Embalaje,
                                    c_calibre,
                                    n_etiqueta,
                                    SUM(Cantidad) as total_cantidad
                                ')
                    ->where('numero_referencia', $instructivo)
                    ->where('n_variedad_rotulacion', $variedad_id)
                    ->where('n_etiqueta', $etiqueta_id)
                    ->where('c_calibre', (string)$calibre)
                    ->where('folio','like','%'.$pallet)
                    ->groupBy('n_variedad_rotulacion', 'C_Embalaje', 'c_calibre', 'n_etiqueta')
                    ->get();
                }
                else{
                    $resultados = DB::connection('sqlsrv')
                    ->table('dbo.V_PKG_Embarques')
                    ->selectRaw('
                                    n_variedad_rotulacion,
                                    C_Embalaje,
                                    c_calibre,
                                    n_etiqueta,
                                    SUM(Cantidad) as total_cantidad
                                ')
                    ->where('numero_referencia', $instructivo)
                    ->where('n_variedad_rotulacion', $variedad_id)
                    ->where('n_etiqueta', $etiqueta_id)
                    ->where('c_calibre', (string)$calibre)
                    ->groupBy('n_variedad_rotulacion', 'C_Embalaje', 'c_calibre', 'n_etiqueta')
                    ->get();
                }
                    
                $c_embalaje = '';

                $folio_fx = '';
                if (count($resultados) == 1) {

                    //$liq=LiquidacionesCx::where('liqcabecera_id', $dato->id)->where('variedad_id', $item->variedad_id)->where('etiqueta_id', $item->etiqueta_id)->where('calibre', $item->calibre)->first();
                    $c_embalaje = $resultados[0]->C_Embalaje;
                }
                elseif(count($resultados)>1){
                    foreach($resultados as $result){
                        if($result->total_cantidad==$cantidad){
                            $c_embalaje=$result->C_Embalaje;
                        }
                    }
                }
                else{
                    $c_embalaje='';
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
                    ->where('n_variedad_rotulacion', $variedad_id)
                    //  ->where('n_etiqueta','like', $item->etiqueta_id.'%')
                    //  ->where('c_calibre','like',$item->calibre.'%')
                    ->where('folio', 'like', '%' . $pallet)
                    //->where('n_variedad', $variedad_id)
                    ->where('n_etiqueta', $etiqueta_id)
                    ->where('c_calibre', $calibre)
                    ->where('c_embalaje', $c_embalaje)
                    ->orderBy('folio')
                    ->get();
                if (count($resultados) == 1) {
                    foreach ($resultados as $res) {
                        $folio_fx = $res->folio;
                    }
                } elseif (count($resultados) > 1) {
                    $original = '';

                    $i = 0;

                    foreach ($resultados as $res) {
                        if ($i == 0) {
                            $folio_fx = $res->folio;
                        } else {
                            $folio_fx = $folio_fx . "," . $res->folio;
                        }
                        $i++;
                    }

                    $array = explode(",", $folio_fx);
                    $arrayUnicos = array_unique($array);

                    // Convertir el array de vuelta a una cadena
                    $cadenaUnica = implode(',', $arrayUnicos);
                    //Log::info("instructivo: " . $dato->instructivo . " Folio: " . $item->pallet . " Folios: " . $item->folio_fx." Cadena entrada ".$item->folio_fx." Cadena salida ".$cadenaUnica);
                    $folio_fx = $cadenaUnica;
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
                        'c_embalaje' => $c_embalaje,
                        'folio_fx' => $folio_fx

                    ]);
                    $itemsLiquidacion->push($result);
                    Log::info('Datos guardados correctamente' . "----" . $result);
                } catch (\Exception $e) {
                    Log::error('Error al guardar los datos: ' . $e->getMessage() . "----" . $e->getLine()."----".$e->getTraceAsString());
                }
            }
            
            $grupos = $itemsLiquidacion->groupBy(function ($item) {
                return $item->folio_fx . '|' . $item->calibre . '|' . $item->variedad_id . '|' . $item->c_embalaje . '|' . $item->etiqueta_id;
            })->filter(function ($grupo) {
                return $grupo->count() > 1; // Solo grupos con más de una línea, si quieres todos quita este filter
            });


           
            foreach ($grupos as $clave => $items) {
                // Extraer los valores de la clave compuesta
                [$folio_fx, $calibre, $variedad_id, $c_embalaje, $etiqueta_id] = explode('|', $clave);
            
                // Convertir folio_fx en formato para IN
                $arrayFolios = explode(',', $folio_fx);
                $folioTransformado = "'" . implode("','", $arrayFolios) . "'";
            
                // Consulta a la base de datos para obtener la suma de cantidades
                $sumaCantidadDB = DB::connection('sqlsrv')
                    ->table('dbo.V_PKG_Despachos')
                    ->selectRaw('SUM(cantidad) as cantidad_total')
                    ->whereRaw("folio IN ($folioTransformado)")
                    ->where('c_calibre', $calibre)
                    ->where('n_variedad_rotulacion', $variedad_id)
                    ->where('C_Embalaje', $c_embalaje)
                    ->where('n_etiqueta', $etiqueta_id)
                    ->first();
            
                // Calcular suma de precio * cantidad desde los ítems en $itemsLiquidacion
                $sumaPrecioCantidad = $items->sum(function ($item) {
                    return $item->precio_unitario * $item->cantidad;
                });
            
                if ($sumaCantidadDB && $sumaCantidadDB->cantidad_total > 0) {
                    $precioPonderado = $sumaPrecioCantidad / $sumaCantidadDB->cantidad_total;
            
                    // Actualizar todos los ítems en este grupo
                    foreach ($items as $item) {
                        $item->precio_unitario = $precioPonderado;
                        $item->monto_rmb = $precioPonderado * $item->cantidad; // Recalcular monto_rmb
                        $item->save(); // Guardar en la base de datos
                    }
            
                    Log::info("Precio ponderado calculado para folio(s) {$folio_fx} (calibre: {$calibre}, variedad: {$variedad_id}, embalaje: {$c_embalaje}, etiqueta: {$etiqueta_id}): {$precioPonderado}");
                } else {
                    Log::warning("No se pudo calcular precio ponderado para folio(s) {$folio_fx} (calibre: {$calibre}, variedad: {$variedad_id}, embalaje: {$c_embalaje}, etiqueta: {$etiqueta_id}): cantidad total es 0 o no hay datos");
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
            Log::error('Error al procesar los datos: ' . $e->getMessage() . "----" . $e->getLine()."----".$e->getTraceAsString());
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
                        'ETA' => ($excelDato->fecha_arribo ? Carbon::parse($excelDato->fecha_arribo)->format('Y-m-d') : ''), //J
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
                        'Costos cajas RMB' => '=+AF' . $i . '+AH' . $i . '+AJ' . $i . '+AL' . $i . '+AN' . $i . '+AB' . $i . '+AP' . $i . '+(BO' . $i . ')+(BQ' . $i . '*AV' . $i . ')', //AR -(CC' . $i . '*AV' . $i . ') +(CA' . $i . '*AV' . $i . ') Se elimina por que no incide
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
                        'Ajuste impuesto USD' => '=+(' . ($ajusteimpuesto == 0 ? 0 : ($ajusteimpuesto)) . '/' . $total_kilos . ')*P' . $i, //BO
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
                        'Otros Impuestos (JWM) Impuestos' => '=+(' . ($otrosimpuestos == 0 ? 0 : ($otrosimpuestos )) . '/' . $total_kilos . ')*P' . $i, //CA
                        'Otros Impuestos (JWM) TO USD' => '=+CA' . $i . '*Y' . $i, //CB
                        'Otros Ingresos (abonos)' => '=+(' . ($otrosingresos == 0 ? 0 : ($otrosingresos )) . '/' . $total_kilos . ')*P' . $i, //CC
                        'Otros Ingresos (abonos) TO USD' => '=+CC' . $i . '*Y' . $i, //CD
                        'RMB Flete Domestico. Caja' => '=+(' . ($costosFleteDomestico == 0 ? 0 : $costosFleteDomestico) . '/' . $total_kilos . ')*P' . $i, //CE
                        'RMB Flete Domestico. TO' => '=+CE' . $i . '*Y' . $i, //CF
                        'USD Flete Domestico. '    => '=+CE' . $i . '/AV' . $i, //CG
                        'USD Flete Domestico. TO' => '=+CG' . $i . '*Y' . $i, //CH
                        'embalaje_dato_origen' => $item->c_embalaje, //CI

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
            Log::info("Instructivo Comparativa: " . $liqCxCabecera->instructivo);
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
                        'ETA' => ($excelDato->fecha_arribo ? Carbon::parse($excelDato->fecha_arribo)->format('Y-m-d') : ''), //J
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
                        'Costos cajas RMB' => '=+AF' . $i . '+AH' . $i . '+AJ' . $i . '+AL' . $i . '+AN' . $i . '+AB' . $i . '+AP' . $i . '+(BO' . $i . ')+(BQ' . $i . '*AV' . $i . ')', //AR -(CC' . $i . '*AV' . $i . ') +(CA' . $i . '*AV' . $i . ')
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
                        'Ajuste impuesto USD' => '=+(' . ($ajusteimpuesto == 0 ? 0 : ($ajusteimpuesto) ) . '/' . $total_kilos . ')*P' . $i, //BO
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
                        'Otros Impuestos (JWM) Impuestos' => '=+(' . ($otrosimpuestos == 0 ? 0 : ($otrosimpuestos )) . '/' . $total_kilos . ')*P' . $i, //CA
                        'Otros Impuestos (JWM) TO USD' => '=+CA' . $i . '*Y' . $i, //CB
                        'Otros Ingresos (abonos)' => '=+(' . ($otrosingresos == 0 ? 0 : ($otrosingresos )) . '/' . $total_kilos . ')*P' . $i, //CC
                        'Otros Ingresos (abonos) TO USD' => '=+CC' . $i . '*Y' . $i, //CD
                        'RMB Flete Domestico. Caja ' => '=+(' . ($costosFleteDomestico == 0 ? 0 : $costosFleteDomestico) . '/' . $total_kilos . ')*P' . $i, //CE
                        'RMB Flete Domestico. TO' => '=+CE' . $i . '*Y' . $i, //CF
                        'USD Flete Domestico. '    => '=+CF' . $i . '/AV' . $i, //CG
                        'USD Flete Domestico. TO' => '=+CG' . $i . '*Y' . $i, //CH
                        'embalaje_dato_origen' => $item->c_embalaje, //CI

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
    public function actualizarValorGD_en_fx()
    {




        $affectedRows = 0;
        $resEjec = collect();


        //Obtener la sesión correctamente
        if (session()->has('liqs')) {
            $liqs = session('liqs');
        } else {
        $liqs = $this->ConsolidadoLiquidaciones();

        session(['liqs' => $liqs]);
        }
        //dd($liqs[0]);
        // Obtener cabeceras
        //$liqCxCabeceras = LiqCxCabecera::whereNull('deleted_at')->get();

        
        foreach ($liqs as $liq) {
    
            $variedad=Variedad::where('nombre',$liq["variedad"])->first();
          //  dd($variedad);
            $especie=Especy::where('id',$variedad->especie_id)->first();
            //dd($especie);
           // Uncomment this to debug the structure of $liq if needed
           Fob::create([
                
                'cliente' => $liq['cliente'] ?? null,  
                'nave' => $liq['nave'],              
                'Liquidacion' => $liq['Liquidacion'] ?? null,
                'ETA' => $liq['ETA'],
                'ETA_Week' => $liq['ETA_Week'],
                'Fecha_Venta' => $liq['Fecha_Venta'] ?? null,
                'Fecha_Venta_Week' => $liq['Fecha_Venta_Week'] ?? null,
                'Pallet' => $liq['Pallet'] ?? null,
                'Peso_neto' => $liq['Peso_neto'] ?? null,
                'Kilos_total' => $liq['Kilos_total'] ?? null,
                'embalaje' => $liq['embalaje'] ?? null,
                'etiqueta' => $liq['etiqueta'] ?? null,
                'variedad' => $liq['variedad'] ?? null,
                'calibre' => $liq['calibre'] ?? null,
                'Cajas' => $liq['Cajas'] ?? null,
                'TC' => $liq['TC'] ?? null,
                'Ventas_TO_USD' => $liq['Ventas_TO_USD'] ?? null,
                'Venta_USD' => $liq['Venta_USD'] ?? null,
                'Com_USD' => $liq['Com_USD'] ?? null,
                'Com_TO_USD' => $liq['Com_TO_USD'] ?? null,
                'Imp_destino_USD' => $liq['Imp_destino_USD'] ?? null,
                'Imp_destino_USD_TO' => $liq['Imp_destino_USD_TO'] ?? null,
                'Costo_log_USD' => $liq['Costo_log_USD'] ?? null,
                'Costo_log_USD_TO' => $liq['Costo_log_USD_TO'] ?? null,
                'Ent_Al_mercado_USD' => $liq['Ent_Al_mercado_USD'] ?? null,
                'Ent_Al_mercado_USD_TO' => $liq['Ent_Al_mercado_USD_TO'] ?? null,
                'Costo_mercado_USD' => $liq['Costo_mercado_USD'] ?? null,
                'Costos_mercado_USD_TO' => $liq['Costos_mercado_USD_TO'] ?? null,
                'Otros_costos_dest_USD' => $liq['Otros_costos_dest_USD'] ?? null,
                'Otros_costos_USD_TO' => $liq['Otros_costos_USD_TO'] ?? null,
                'Flete_marit_USD' => $liq['Flete_marit_USD'] ?? null,
                'Flete_Marit_USD_TO' => $liq['Flete_Marit_USD_TO'] ?? null,
                'Costos_USD_TO' => $liq['Costos_USD_TO'] ?? null,
                'Ajuste_TO_USD' => $liq['Ajuste_TO_USD'] ?? null,
                'FOB_USD' => $liq['FOB_USD'] ?? null,
                'FOB_TO_USD' => $liq['FOB_TO_USD'] ?? null,
                'FOB_kg' => $liq['FOB_kg'] ?? null,
                'FOB_Equivalente' => $liq['FOB_Equivalente'] ?? null,
                'Flete_Cliente' => $liq['Flete_Cliente'] ?? null,
                'Transporte' => $liq['Transporte'] ?? null,
                'c_embalaje' => $liq['c_embalaje'] ?? null,
                'folio_fx' => $liq['folio_fx'] ?? null,
                'especie' => $especie->nombre ?? null,
                'Costos_cajas_USD' => $liq['Costos_cajas_USD'] ?? null,
            ]);
          
            $affectedRows++;
        }

        

        return response()->json(["message" => "Se modificaron $affectedRows registros", "data" => $affectedRows], 200);
    }


    public function ConsolidadoLiquidaciones()
    {



        $fg = $this;
        $liqCxCabeceras = LiqCxCabecera::whereNull('deleted_at')->where('id', '>', 470)->get(); // LiqCxCabecera::find(request('ids'));


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
            Log::info("inst -> " . $liqCxCabecera->instructivo);
            $flete_exportadora = $liqCxCabecera->flete_exportadora;
            $tipo_transporte = $liqCxCabecera->tipo_transporte;
            $factor_imp_destino = $liqCxCabecera->factor_imp_destino;
            $detalle = LiquidacionesCx::where('liqcabecera_id', $liqCxCabecera->id)->whereNull('deleted_at')->where('folio_fx', 'not like', '%,%')->whereNotNull('folio_fx')->get();

            $excelDato = ExcelDato::where('instructivo', $liqCxCabecera->instructivo)->first();
            //Log::info("Instructivo: " . $liqCxCabecera->instructivo);
            $nombre_costo = Costo::pluck('nombre'); // Extraer solo los nombres de costos
            $total_kilos = 0;
            $total_ventas = 0;
            foreach ($detalle as $item) {
                $total_kilos = $total_kilos + (float)(str_replace(',', '.', $fg->traducedatos($item->embalaje_id, 'Embalaje') ? $fg->traducedatos($item->embalaje_id, 'Embalaje') : $item->embalaje)) * (float)(str_replace(',', '.', $item->cantidad));

                $total_ventas = $total_ventas + $item->cantidad * (float)(str_replace(',', '.', $item->precio_unitario));
                // Log::info("Total Venta: " . $total_ventas);
            }
            $porcComision = '0,06';
            foreach ($detalle as $item) {

                $costos = LiqCosto::where('liq_cabecera_id', $liqCxCabecera->id)->get();


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
                            //   Log::info("Porcentaje Comision: " . $porcComision);
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
                //Variables
                $nave = $liqCxCabecera->nave_id ? Nafe::find($liqCxCabecera->nave_id)->nombre : "";

                $Embarque = "";
                $cliente = $liqCxCabecera->cliente->nombre_fantasia; //B
                $nave = $nave; //C
                $PuertoDestino = ''; //D
                $AWB = ''; //E
                $Contenedor = ''; //F
                $Liquidacion = $liqCxCabecera->instructivo; //G
                $ETD = ''; //H
                $ETD_Week = ''; //I
                $ETA = ($excelDato->fecha_arribo ? Carbon::parse($excelDato->fecha_arribo)->format('Y-m-d') : ''); //J
                $ETA_Week = ($excelDato->fecha_arribo ? Carbon::parse($excelDato->fecha_arribo)->weekOfYear : 0); //K
                $Fecha_Venta = $item->fecha_venta ? Carbon::parse($item->fecha_venta) : 0; //L
                $Fecha_Venta_Week = ($excelDato->fecha_venta ? Carbon::parse($excelDato->fecha_venta)->weekOfYear : 0); //M
                $Fecha_Liquidación = $excelDato->fecha_liquidacion; //N
                $Pallet = $item->folio_fx; //O
                $Peso_neto = (float)(str_replace(',', '.', $fg->traducedatos($item->embalaje_id, 'Embalaje')));  //P
                $Kilos_total = $Peso_neto * $item->cantidad; //Q
                $embalaje = $fg->traducedatos($item->embalaje_id, 'Embalaje'); //R
                $etiqueta = $item->etiqueta_id; //S
                $variedad = $item->variedad_id; //T
                $Calibre_Estandar   = ''; //U
                $calibre = $fg->traducedatos($item->calibre, 'Calibre'); //V
                $color = ''; //W
                $Observaciones = $item->observaciones; //X
                $Cajas = $item->cantidad; //y
                $TC    = $excelDato->tasa; //AV
                $RMB_Caja = isset($item->precio_unitario) ? $item->precio_unitario : 0; //z
                $RMB_Venta = $Cajas * $RMB_Caja; //AA
                $Comision_Caja = $porcComision * $RMB_Caja; //AB
                $porcComision = $porcComision; //AC
                $RMB_Comision = $Comision_Caja * $Cajas; //AD
                $Factor_Imp_destino = $factor_imp_destino; //AE  Esto no esta definido como para poder calcularlo
                $Imp_destino_caja_RMB = $Factor_Imp_destino * $RMB_Caja; //AF
                $RMB_Imp_destino_TO = $Imp_destino_caja_RMB * $Cajas; //AG
                $Costo_log_Caja_RMB = (($costosLogisticos == 0 ? 0 : $costosLogisticos) / $total_kilos) * $Peso_neto; //AH
                $RMB_Costo_log_TO = $Costo_log_Caja_RMB *  $Cajas; //AI
                $Ent_Al_mercado_Caja_RMB = (($entradamercado == 0 ? 0 : $entradamercado) / $total_kilos) * $Peso_neto; //AJ Preguntar a Haydelin
                $RMB_Ent_Al_mercado_TO = $Ent_Al_mercado_Caja_RMB * $Cajas; //AK
                $Costo_mercado_caja_RMB = (($costosMercado == 0 ? 0 : $costosMercado) / $total_kilos) * $Peso_neto; //AL
                $RMB_Costos_mercado_TO = $Costo_mercado_caja_RMB * $Cajas; //AM
                $Otros_costos_dest_Caja_RMB = (($otroscostosdestino == 0 ? 0 : $otroscostosdestino) / $total_kilos) * $Peso_neto;  //AN  debemos configurar costos en categoría otros
                $RMB_otros_costos_TO = $Otros_costos_dest_Caja_RMB * $Cajas; //AO
                $Flete_marit_Caja_RMB =  (($costosFleteInternacional == 0 ? 0 : $costosFleteInternacional) / $total_kilos) * $Peso_neto; //AP
                $RMB_Flete_Marit_TO = $Flete_marit_Caja_RMB * $Cajas; //AQ
                $Otros_Impuestos_JWM_Impuestos = (($otrosimpuestos == 0 ? 0 : ($otrosimpuestos )) / $total_kilos) * $Peso_neto; //CA
                $Otros_Impuestos_JWM_TO_USD = $Otros_Impuestos_JWM_Impuestos * $Cajas; //CB
                $Otros_Ingresos_abonos = (($otrosingresos == 0 ? 0 : ($otrosingresos )) / $total_kilos) * $Peso_neto; //CC
                $Otros_Ingresos_abonos_TO_USD = $Otros_Ingresos_abonos * $Cajas; //CD
                $RMB_Flete_Domestico_Caja = (($costosFleteDomestico == 0 ? 0 : $costosFleteDomestico) / $total_kilos) * $Peso_neto; //CE
                $RMB_Flete_Domestico_TO = $RMB_Flete_Domestico_Caja * $Cajas; //CF
                $USD_Flete_Domestico    = $RMB_Flete_Domestico_TO / $TC; //CG
                $USD_Flete_Domestico_TO = $USD_Flete_Domestico * $Cajas; //CH
                $Ajuste_impuesto_USD = (($ajusteimpuesto == 0 ? 0 : ($ajusteimpuesto) / $excelDato->tasa) / $total_kilos) * $Peso_neto; //BO
                $Flete_Aereo = ($flete_exportadora / $total_kilos) * $Peso_neto; //BQ
                $Flete_Aereo_TO = $Flete_Aereo * $Cajas; //BR
                $Costos_cajas_RMB = $Imp_destino_caja_RMB + $Costo_log_Caja_RMB + $Ent_Al_mercado_Caja_RMB + $Costo_mercado_caja_RMB + $Otros_costos_dest_Caja_RMB +
                    $Comision_Caja + $Flete_marit_Caja_RMB + ($Ajuste_impuesto_USD)  + ($Flete_Aereo * $TC); //AR - ($Otros_Ingresos_abonos * $TC)  ($Otros_Impuestos_JWM_Impuestos ) +
                $RMB_Costos_TO = $Costos_cajas_RMB * $Cajas; //AS
                $Resultados_caja_RMB =  $RMB_Caja - $Costos_cajas_RMB;  //AT  Verificar con Haydelin
                $RMB_result_TO = $Resultados_caja_RMB * $Cajas; //AU  Verificar con Haydelin
                $Venta_USD = $RMB_Caja / $TC; //AW
                $Ventas_TO_USD = $Venta_USD * $Cajas; //AX
                $Com_USD = $Comision_Caja / $TC; //AY
                $Com_TO_USD = $Com_USD * $Cajas; //AZ
                $Imp_destino_USD = $Imp_destino_caja_RMB / $TC; //BA
                $Imp_destino_USD_TO = $Imp_destino_USD * $Cajas; //BB
                $Costo_log_USD = $Costo_log_Caja_RMB / $TC; //BC
                $Costo_log_USD_TO = $Costo_log_USD * $Cajas; //BD
                $Ent_Al_mercado_USD = $Ent_Al_mercado_Caja_RMB / $TC; //BE
                $Ent_Al_mercado_USD_TO = $Ent_Al_mercado_USD * $Cajas; //BF
                $Costo_mercado_USD = $Costo_mercado_caja_RMB / $TC; //BG
                $Costos_mercado_USD_TO = $Costo_mercado_USD * $Cajas; //BH
                $Otros_costos_dest_USD = $Otros_costos_dest_Caja_RMB / $TC; //BI
                $Otros_costos_USD_TO = $Otros_costos_dest_USD * $Cajas; //BJ
                $Flete_marit_USD    = $Flete_marit_Caja_RMB / $TC; //BK
                $Flete_Marit_USD_TO = $Flete_marit_USD * $Cajas; //BL
                $Costos_cajas_USD = $Costos_cajas_RMB / $TC; //BM
                $Costos_USD_TO = $Costos_cajas_USD * $Cajas; //BN
                $Ajuste_TO_USD = $Costos_USD_TO * $Cajas; //BP
                $FOB_USD = ($Resultados_caja_RMB / $TC); //BS
                $FOB_TO_USD = $FOB_USD * $Cajas; //BT
                $FOB_kg = $FOB_TO_USD / $Kilos_total; //BU
                $FOB_Equivalente = $FOB_kg * 5; //BV
                $Flete_Cliente = $flete_exportadora > 0 ? 'NO' : 'SI'; //BW
                $Transporte = $tipo_transporte == "A" ? 'AEREO' : 'MARITIMO'; //BX
                $CNY = 'PRE'; //BY
                $Pais = 'CHINA'; //BZ
                $c_embalaje = $item->c_embalaje;
                $folio_fx = $item->folio_fx;

                //$embalaje_dato_origen'=>$item->embalaje_id, //CI

                //Fin Variables

                $dataComparativa->push(array_merge(
                    [
                        'Embarque=',  //A
                        'cliente' => $cliente,
                        'nave' => $nave, //C
                        'Puerto Destino' => $PuertoDestino, //D
                        'AWB' => $AWB, //E
                        'Contenedor' => $Contenedor, //F
                        'Liquidacion' => $Liquidacion, //G
                        'ETD' => $ETD, //H
                        'ETD_Week' => $ETD_Week, //I
                        'ETA' => $ETA, //J
                        'ETA_Week' => $ETA_Week, //K
                        'Fecha_Venta' => $Fecha_Venta, //L
                        'Fecha_Venta_Week' => $Fecha_Venta_Week, //M
                        'Fecha_Liquidación' => $Fecha_Liquidación, //N
                        'Pallet' => $Pallet, //O
                        'Peso_neto' =>  $Peso_neto, //P
                        'Kilos_total' => $Kilos_total, //Q
                        'xembalaje' => $embalaje, //R
                        'etiqueta' => $etiqueta, //S
                        'variedad' => $variedad, //T
                        'Calibre_Estandar'   => '', //U
                        'calibre' => $calibre, //V
                        'color=', //W
                        'Observaciones' => $Observaciones, //X
                        'Cajas' => $Cajas, //y
                        'RMB_Caja' => $RMB_Caja, //z
                        'RMB_Venta' => $RMB_Venta, //AA
                        'Comision_Caja' => $Comision_Caja, //AB
                        '%_Comisión' => $porcComision, //AC
                        'RMB_Comisión' => $RMB_Comision, //AD
                        'Factor_Imp_destino' => $factor_imp_destino, //AE  Esto no esta definido como para poder calcularlo
                        'Imp_destino_caja_RMB' => $Imp_destino_caja_RMB, //AF
                        'RMB_Imp_destino_TO' => $RMB_Imp_destino_TO, //AG
                        'Costo_log_Caja_RMB' => $Costo_log_Caja_RMB, //AH
                        'RMB_Costo_log_TO' => $RMB_Costo_log_TO, //AI
                        'Ent_Al_mercado_Caja_RMB' => $Ent_Al_mercado_Caja_RMB, //AJ Preguntar a Haydelin
                        'RMB_Ent_Al_mercado_TO' => $RMB_Ent_Al_mercado_TO, //AK
                        'Costo_mercado_caja_RMB' => $Costo_mercado_caja_RMB, //AL
                        'RMB_Costos_mercado_TO' => $RMB_Costos_mercado_TO, //AM
                        'Otros_costos_dest_Caja' => $Otros_costos_dest_Caja_RMB,  //AN  debemos configurar costos en categoría otros
                        'RMB_otros_costos_TO' => $RMB_otros_costos_TO, //AO
                        'Flete_marit_Caja_RMB' => $Flete_marit_Caja_RMB, //AP
                        'RMB_Flete_Marit_TO' => $RMB_Flete_Marit_TO, //AQ
                        'Costos_cajas_RMB' => $Costos_cajas_RMB, //AR
                        'RMB_Costos_TO' => $RMB_Costos_TO, //AS
                        'Resultados_caja' => $Resultados_caja_RMB,  //AT  Verificar con Haydelin
                        'RMB_result_TO' => $RMB_result_TO, //AU  Verificar con Haydelin
                        'TC'    => $TC, //AV
                        'Venta_USD' => $Venta_USD, //AW
                        'Ventas_TO_USD' => $Ventas_TO_USD, //AX
                        'Com_USD' => $Com_USD, //AY
                        'Com_TO_USD' => $Com_TO_USD, //AZ
                        'Imp_destino_USD' => $Imp_destino_USD, //BA
                        'Imp_destino_USD_TO' => $Imp_destino_USD_TO, //BB
                        'Costo_log_USD' => $Costo_log_USD, //BC
                        'Costo_log_USD_TO' => $Costo_log_USD_TO, //BD
                        'Ent_Al_mercado_USD' => $Ent_Al_mercado_USD, //BE
                        'Ent_Al_mercado_USD_TO' => $Ent_Al_mercado_USD_TO, //BF
                        'Costo_mercado_USD' => $Costo_mercado_USD, //BG
                        'Costos_mercado_USD_TO' => $Costos_mercado_USD_TO, //BH
                        'Otros_costos_dest_USD' => $Otros_costos_dest_USD, //BI
                        'Otros_costos_USD_TO' => $Otros_costos_USD_TO, //BJ
                        'Flete marit. USD'    => $Flete_marit_USD, //BK
                        'Flete_Marit_USD_TO' => $Flete_Marit_USD_TO, //BL
                        'Costos_cajas_USD' => $Costos_cajas_USD, //BM
                        'Costos_USD_TO' => $Costos_USD_TO, //BN
                        'Ajuste_impuesto_USD' => $Ajuste_impuesto_USD, //BO
                        'Ajuste_TO_USD' => $Ajuste_TO_USD, //BP
                        'Flete_Aereo' => $Flete_Aereo, //BQ
                        'Flete_Aereo_TO' => $Flete_Aereo_TO, //BR
                        'FOB_USD' => $FOB_USD, //BS
                        'FOB_TO_USD' => $FOB_TO_USD, //BT
                        'FOB_kg' => $FOB_kg, //BU
                        'FOB_Equivalente' => $FOB_Equivalente, //BV
                        'Flete_Cliente' => $Flete_Cliente, //BW
                        'Transporte' => $Transporte, //BX
                        'CNY=PRE', //BY
                        'Pais=CHINA', //BZ
                        'Otros_Impuestos_JWM_Impuestos' => $Otros_Impuestos_JWM_Impuestos, //CA
                        'Otros_Impuestos_JWM_TO_USD' => $Otros_Impuestos_JWM_TO_USD, //CB
                        'Otros_Ingresos_abonos' => $Otros_Ingresos_abonos, //CC
                        'Otros_Ingresos_abonos_TO_USD' => $Otros_Ingresos_abonos_TO_USD, //CD
                        'RMB_Flete_Domestico_Caja' => $RMB_Flete_Domestico_Caja, //CE
                        'RMB_Flete_Domestico_TO' => $RMB_Flete_Domestico_TO, //CF
                        'USD_Flete_Domestico'    => $USD_Flete_Domestico, //CG
                        'USD_Flete_Domestico_TO' => $USD_Flete_Domestico_TO, //CH
                        'embalaje' => $c_embalaje, //agregado para obtener datos
                        'folio_fx' => $folio_fx,



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
        return $dataComparativa;
    }
}
