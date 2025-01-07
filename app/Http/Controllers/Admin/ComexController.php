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
use App\Exports\ComparativaExport;
use App\Models\Diccionario;

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
            }
            $estrItems = $estructuras->where('tipos_seccion_conversors_id', 2);
            foreach ($estrItems as $estructura) {


                $filaInicial = $estructura->coordenada; // Por ejemplo, A5
                preg_match('/(\D+)(\d+)/', $filaInicial, $matches);
                $columna = $matches[1]; // A
                $fila = (int)$matches[2]; // 5
                //$filaCostos=$fila;

                while (true) {
                    $valorCelda = $hoja->getCell("{$columna}{$fila}")->getValue();
                    if ($filaCostos < $fila) {
                        $filaCostos = $fila;
                    }
                    if ($hoja->getCell("A{$fila}")->getValue() == 'TOTAL SALES') {
                        break; // Encontramos el inicio de la sección de costos
                    }
                    $item = [];
                    if ($valorCelda = "") {
                        Log::info('Fila vacía ' . $hoja->getCell("I{$fila}")->getValue() . " en la celda {$columna}{$fila}");

                        break; // Fila vacía, fin de los ítems
                    }
                    preg_match('/(\D+)/', $estructura->coordenada, $colMatch);
                    $col = $colMatch[1];
                    if ($col == 'I') {
                        Log::info('Columna I' . $hoja->getCell("{$col}{$fila}")->getValue());
                    }
                    $item[] = [
                        'coordenada' => "{$col}{$fila}",
                        'propiedad' => $estructura->propiedad,
                        'valor' => $this->normalizarTexto($hoja->getCell("{$col}{$fila}")->getValue()),
                    ];




                    $items[] = $item;
                    $fila++;
                }
            }

            $estrItems = $estructuras->where('tipos_seccion_conversors_id', 3)->sortBy(['coordenada', 'asc']);

            $limit = $filaCostos + 10;
            Log::info('Fila Costo:' . $filaCostos);
            for ($i = $filaCostos; $i < $limit; $i++) {
                $valorCelda = $hoja->getCell("A{$filaCostos}")->getValue();
                Log::info('Valor Celda:' . $valorCelda);
                if ($valorCelda == "TOTAL SALES") {
                    $filaCostos = $filaCostos + 1;
                    break;
                } else {
                    $filaCostos = $filaCostos + 1;
                }
            }

            foreach ($estrItems as $estructura) {


                $filaInicialCostos = $estructura->coordenada;
                preg_match('/(\D+)(\d+)/', $filaInicialCostos, $matchesCostos);
                $columnaCostos = $matchesCostos[1]; // A


                $valor = $hoja->getCell("{$columnaCostos}{$filaCostos}")->getValue();

                $costos[] = [
                    'coordenada' => "{$columnaCostos}{$filaCostos}",
                    'propiedad' => $estructura->propiedad,
                    'valor' => $this->normalizarTexto($valor),
                ];
                $filaCostos++;
            }
            $datosExcel = ExcelDato::where('instructivo', $instructivo)->get();

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
                    'datos' => json_encode([
                        'cabecera' => $datos['cabecera'] ?? [],
                        'items' => $items,
                        'costos' => $costos,
                    ], JSON_UNESCAPED_UNICODE),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al procesar el archivo Excel: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ocurrió un error al procesar el archivo.',
                'error' => $e->getMessage(),
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
            foreach ($items as &$item) {

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
            ]);
        } catch (\Exception $e) {
            Log::error('Error al mostrar los datos procesados: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ocurrió un error al mostrar los datos.',
                'error' => $e->getMessage(),
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
            //nave
            $nave_id = $clltCabecera->first(function ($nave) {
                return $nave['propiedad'] === 'Nave';
            });
            $LiqCabecera->nave_id = $nave_id['valor'];
            $etaFA = $clltCabecera->first(function ($eta) {
                return $eta['propiedad'] === 'Fecha de Arribo';
            });
            $LiqCabecera->eta = $this->formatDate2($etaFA['valor']);
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

            // Mostrar el resultado
            /*
            'contenedor',
            'eta',
            'variedad_id',
            'pallet',
            'etiqueta_id',
            'calibre',
            'embalaje_id',
            'cantidad',
            'fecha_venta',
            'ventas',
            'precio_unitario',
            'monto_rmb',
            'observaciones',
            'liqcabecera_id',
            */
            foreach ($registros as $fila) {
                $contenedor = isset($fila['Contenedor']) ? $fila['Contenedor'] : '';
                $eta = $LiqCabecera->eta;
                $variedad_id = $fila['Variedad'];
                $pallet = isset($fila['Pallet']) ? $fila['Pallet'] : '';
                $etiqueta_id = isset($fila['Etiqueta']) ? $fila['Etiqueta'] : '';
                $calibre = isset($fila['Calibre']) ? $fila['Calibre'] : '';
                $embalaje_id = isset($fila['Embalaje']) ? $fila['Embalaje'] : '';
                $cantidad = isset($fila['Cantidad']) ? $fila['Cantidad'] : 0;
                $fecha_venta = isset($fila['Fecha de Venta']) ? $this->formatDate2($fila['Fecha de Venta']) : '';
                $ventas = isset($fila['Ventas']) ? $fila['Ventas'] : 0;
                $precio_unitario = isset($fila['Precio Unitario']) ? $fila['Precio Unitario'] : 0;
                $monto_rmb = isset($fila['Monto RMB']) ? $fila['Monto RMB'] : 0;
                $observaciones = isset($fila['Observaciones']) ? $fila['Observaciones'] : '';
                $liqcabecera_id = $LiqCabecera->id;
                LiquidacionesCx::create([
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
                    'liqcabecera_id' => $liqcabecera_id
                ]);
            }
            foreach ($costos as $costo) {
                $propiedad = $costo['propiedad'];
                //$Costo::where('nombre', $propiedad)->first();
                $valor = $costo['valor'];
                $c = new Costo();
                LiqCosto::create([
                    'nombre_costo' => $propiedad,
                    'valor' => $valor,
                    'liq_cabecera_id' => $liqcabecera_id
                ]);
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
            $LiqCabecera->total_costo = $total_costos;
            $LiqCabecera->total_bruto = $total_bruto;
            $LiqCabecera->total_neto = $total_neto;

            $LiqCabecera->update();
            //procesamos la cabecera
            $clientes_comexes = ClientesComex::get();
            $naves            = Nafe::get();
            $message = 'Datos procesados y guardados correctamente.';
            $status = 'success';
            return view('admin.liqCxCabeceras.index', compact('message', 'status', 'clientes_comexes', 'naves'));
        } catch (\Exception $e) {
            $clientes_comexes = ClientesComex::get();
            $naves            = Nafe::get();
            Log::error('Error al procesar los datos: ' . $e->getMessage());
            $message = 'Error al procesar los datos: ' . $e->getMessage();
            $status = 'error';
            return view('admin.liqCxCabeceras.index', compact('message', 'status', 'clientes_comexes', 'naves'));
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
            return $timestamp->format('d-m-Y H:i');
        } catch (\Exception $e) {
            Log::error("Error al formatear la fecha: " . $e->getMessage());
            return $date; // Si falla, devuelve la fecha original
        }
    }

    //generar excel comparativo
    public function generacomparativa(Request $request)
    {   
        $ids =explode(',', $request->input('ids'));
        
        
        $liqCxCabeceras = LiqCxCabecera::whereIn('id', $ids)->get(); // LiqCxCabecera::find(request('ids'));
        $dataComparativa = collect();
       
        
        foreach ($liqCxCabeceras as $liqCxCabecera) {
            $detalle = LiquidacionesCx::where('liqcabecera_id', $liqCxCabecera->id)->get();
            $nombre_costo = Costo::pluck('nombre'); // Extraer solo los nombres de costos

            foreach ($detalle as $item) {
                $costos = LiqCosto::where('liq_cabecera_id', $liqCxCabecera->id)->get();

                // Inicializar los costos procesados con valores por defecto (0)
                $costo_procesado = $nombre_costo->mapWithKeys(function ($nombre) {
                    return [$nombre => 0];
                })->toArray();

                // Procesar los costos reales
                foreach ($costos as $costo) {
                    if (array_key_exists($costo->nombre_costo, $costo_procesado) ) {
                        $costo_procesado[$costo->nombre_costo] = $costo->valor;
                    }
                    else {
                        // Si el costo no existe en la lista de costos procesados, agregarlo con valor 0
                        $costo_procesado[$costo->nombre_costo] = 0;
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
                    "Tipo de embalaje" => $this->traducedatos($item->embalaje_id,'Embalaje'),
                ];
                // Agregar los datos principales y los costos procesados al array
                $dataComparativa->push(array_merge(
                    [
                        'cliente' => $liqCxCabecera->cliente->nombre_fantasia,
                        'nave' => $liqCxCabecera->nave->nombre,
                        'eta' => $liqCxCabecera->eta,
                        'variedad' => $item->variedad,
                        'etiqueta' => $item->etiqueta_id,
                        'calibre' => $this->traducedatos($item->calibre,'Calibre'),
                        'embalaje' =>$this->traducedatos($item->embalaje_id,'Embalaje'),
                        'cantidad' => $item->cantidad,
                        'fecha_venta' => $item->fecha_venta,
                        'ventas' => $item->ventas,
                        'precio_unitario' => isset($item->precio_unitario)?$item->precio_unitario:0,
                        'monto_rmb' => $item->monto_rmb,
                        'observaciones' => $item->observaciones,
                        'tasa_cambio' => $liqCxCabecera->tasa_cambio
                    ],
                    $costo_procesado,$calculos
                    
                        // Incorporar los costos como columnas adicionales
                ));
            }
        }
        return Excel::download(new ComparativaExport($dataComparativa), 'comparativa-liquidaciones'.date('Y-m-d H:i:s').'.xlsx');
    }
    function traducedatos($texto,$tipo)
    {
        $dato=Diccionario::where("tipo",$tipo)->where("variable",$texto)->first();
        return $dato->valor;
    }
}
