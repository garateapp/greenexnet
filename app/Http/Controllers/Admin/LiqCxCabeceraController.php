<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLiqCxCabeceraRequest;
use App\Http\Requests\StoreLiqCxCabeceraRequest;
use App\Http\Requests\UpdateLiqCxCabeceraRequest;
use App\Models\ClientesComex;
use App\Models\LiqCosto;
use App\Models\LiqCxCabecera;
use App\Models\LiquidacionesCx;
use App\Models\Costo;
use App\Models\ExcelDato;
use App\Models\Nafe;
use App\Models\Diccionario;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Guid\Guid;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Libs\Liquidaciones;
use App\Libs\Funciones_Globales;
use DB;
use Illuminate\Support\Str;


class LiqCxCabeceraController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('liq_cx_cabecera_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = LiqCxCabecera::with(['cliente', 'nave'])->select(sprintf('%s.*', (new LiqCxCabecera)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'liq_cx_cabecera_show';
                $editGate      = 'liq_cx_cabecera_edit';
                $deleteGate    = 'liq_cx_cabecera_delete';
                $crudRoutePart = 'liq-cx-cabeceras';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('instructivo', function ($row) {
                return $row->instructivo ? $row->instructivo : '';
            });
            $table->addColumn('cliente_nombre_fantasia', function ($row) {
                return $row->cliente ? $row->cliente->nombre_fantasia : '';
            });

            $table->editColumn('cliente.codigo_cliente', function ($row) {
                return $row->cliente ? (is_string($row->cliente) ? $row->cliente : $row->cliente->codigo_cliente) : '';
            });
            $table->addColumn('nave_nombre', function ($row) {
                return $row->nave ? $row->nave->nombre : '';
            });

            $table->editColumn('nave.codigo', function ($row) {
                return $row->nave ? (is_string($row->nave) ? $row->nave : $row->nave->codigo) : '';
            });

            $table->editColumn('tasa_intercambio', function ($row) {
                return $row->tasa_intercambio ? $row->tasa_intercambio : '';
            });
            $table->editColumn('total_costo', function ($row) {
                return $row->total_costo ? $row->total_costo : '';
            });
            $table->editColumn('total_bruto', function ($row) {
                return $row->total_bruto ? $row->total_bruto : '';
            });
            $table->editColumn('total_neto', function ($row) {
                return $row->total_neto ? $row->total_neto : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'cliente', 'nave']);

            return $table->make(true);
        }

        $clientes_comexes = ClientesComex::get();
        $naves            = Nafe::get();

        return view('admin.liqCxCabeceras.index', compact('clientes_comexes', 'naves'));
    }

    public function create()
    {
        abort_if(Gate::denies('liq_cx_cabecera_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = ClientesComex::pluck('nombre_fantasia', 'id')->prepend(trans('global.pleaseSelect'), '');

        $naves = Nafe::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.liqCxCabeceras.create', compact('clientes', 'naves'));
    }

    public function store(StoreLiqCxCabeceraRequest $request)
    {

        $liqCxCabecera = new LiqCxCabecera(); //::create($request->all());
        $liqCxCabecera->instructivo = $request->instructivo;
        $liqCxCabecera->cliente_id = $request->cliente_id;
        $liqCxCabecera->nave_id = $request->nave_id;
        $liqCxCabecera->tasa_intercambio = $request->tasa_intercambio;
        $liqCxCabecera->total_costo = $request->total_costo;
        $liqCxCabecera->total_bruto = $request->total_bruto;
        $liqCxCabecera->total_neto = $request->total_neto;
        $liqCxCabecera->flete_exportadora = $request->flete_exportadora;
        $liqCxCabecera->tipo_transporte = $request->tipo_transporte;
        $liqCxCabecera->factor_imp_destino = $request->factor_imp_destino;
        $liqCxCabecera->eta = $this->convertirFormatoFecha($request->eta);
        $liqCxCabecera->save();
        $excel = new ExcelDato();
        $excel->fecha_arribo = $this->convertirFormatoFecha($request->eta);
        $excel->fecha_liquidacion = $this->convertirFormatoFecha($request->fecha_liquidacion);
        $excel->fecha_venta = $this->convertirFormatoFecha($request->fecha_venta);
        $excel->archivo_id = $this->generateSimpleGuid();
        $excel->master_id = 99;
        $excel->modulo = 1;
        $excel->cliente = $request->cliente_id; //SUNHOLA
        $excel->nombre_archivo = "Subida Manual";
        $excel->tasa = $request->tasa_intercambio;
        $excel->datos = json_encode(["sin datos"]);

        $excel->save();

        return redirect()->route('admin.liq-cx-cabeceras.index');
    }
    function convertirFormatoFecha(string $fecha): string
    {
        // Crear un objeto DateTime a partir de la fecha en formato d/m/Y
        $fechaObjeto = \DateTime::createFromFormat('d/m/Y', $fecha);

        // Verificar si la conversión fue exitosa
        if ($fechaObjeto === false) {
            throw new \Exception("Formato de fecha inválido: $fecha");
        }

        // Retornar la fecha en el formato Y-m-d
        return $fechaObjeto->format('Y-m-d');
    }
    function generateSimpleGuid(): string
    {
        $uniqid = uniqid(mt_rand(), true);
        return substr($uniqid, 0, 8) . '-' . substr($uniqid, 8, 4) . '-' .
            substr($uniqid, 12, 4) . '-' . substr($uniqid, 16, 4) . '-' .
            substr($uniqid, 20, 12);
    }
    public function edit(LiqCxCabecera $liqCxCabecera)
    {
        abort_if(Gate::denies('liq_cx_cabecera_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = ClientesComex::pluck('nombre_fantasia', 'id')->prepend(trans('global.pleaseSelect'), '');

        $naves = Nafe::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $liqCxCabecera->load('cliente', 'nave');

        return view('admin.liqCxCabeceras.edit', compact('clientes', 'liqCxCabecera', 'naves'));
    }
    public function updateInline(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:liquidaciones_cxes,id',
            'field' => 'required|string',
            'value' => 'nullable|string|max:255',
        ]);

        $liquidacion = LiquidacionesCx::find($validated['id']);
        $liquidacion->{$validated['field']} = $validated['value'];
        $liquidacion->save();
        $liq = LiquidacionesCx::where('id', '=', $validated['id'])
            ->first();

        $total_bruto = 0;

        Log::info("liq. " . $liq);
        //$total_bruto = $total_bruto + ((float)$l->cantidad * $l->precio_unitario);


        // $liqCxCabecera = LiqCxCabecera::find($liq->liqcabecera_id);
        // $liqCxCabecera->total_bruto = $total_bruto;
        // $liqCxCabecera->total_neto = $total_bruto - $liqCxCabecera->total_costo;
        // $liqCxCabecera->save();
        return response()->json(['success' => true, 'message' => 'Campo actualizado con éxito']);
    }

    public function getDatosLiqItems(Request $request)
    {

        $liquidacion = LiquidacionesCx::where('liqcabecera_id', $request->id)->get();

        return response()->json(['success' => true, 'message' => 'Campo actualizado con éxito', 'data' => $liquidacion]);
    }
    public function getDatosLiqCostos(Request $request)
    {
        $costos = LiqCosto::where('liq_cabecera_id', $request->id)->get();
        foreach ($costos as $costo) {
            $costo->categoria = (Costo::where("nombre", $costo->nombre_costo)->first() == null) ? 'Sin Categoría' : Costo::where("nombre", $costo->nombre_costo)->first()->categoria;
        }

        return response()->json(['success' => true, 'message' => 'Campo actualizado con éxito', 'data' => $costos]);
    }
    public function update(UpdateLiqCxCabeceraRequest $request, LiqCxCabecera $liqCxCabecera)
    {
        $liqCxCabecera->update($request->all());

        return redirect()->route('admin.liq-cx-cabeceras.index');
    }

    public function show(LiqCxCabecera $liqCxCabecera)
    {
        abort_if(Gate::denies('liq_cx_cabecera_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $liqCxCabecera->load('cliente', 'nave');

        return view('admin.liqCxCabeceras.show', compact('liqCxCabecera'));
    }
    public function destroyItem($id)
    {
        $liquidacion = LiquidacionesCx::find($id);

        if (!$liquidacion) {

            return response()->json(['success' => false, 'message' => 'Línea no encontrada.'], 404);
        }

        $liquidacion->delete();

        return response()->json(['success' => true, 'message' => 'Línea eliminada correctamente.']);
    }
    public function destroyCosto($id)
    {
        $costo = LiqCosto::find($id);

        if (!$costo) {
            return response()->json(['success' => false, 'message' => 'costo no encontrado.'], 404);
        }

        $costo->delete();
        $liq = Costo::find($costo->liq_cabecera_id)
            ->get();
        $total_costo = 0;
        foreach ($liq as $l) {
            $total_costo = $total_costo + $l->valor;
        }
        $liqCxCabecera = LiqCxCabecera::find($costo->liq_cabecera_id);
        $liqCxCabecera->total_costo = $total_costo;
        $liqCxCabecera->total_neto = $liqCxCabecera->total_bruto - $total_costo;
        $liqCxCabecera->save();
        return response()->json(['success' => true, 'message' => 'costo eliminada correctamente.']);
    }
    public function destroy(LiqCxCabecera $liqCxCabecera)
    {
        abort_if(Gate::denies('liq_cx_cabecera_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $liqCxCabecera->delete();
        $liqCostos = LiqCosto::where('liq_cabecera_id', '=', $liqCxCabecera->id)->get();
        foreach ($liqCostos as $item) {
            $item->delete();
        }
        $liquidacion = LiquidacionesCx::where('liqcabecera_id', '=', $liqCxCabecera->id)->get();
        foreach ($liquidacion as $liq) {
            $liq->delete();
        }
        $exceldato = ExcelDato::where('instructivo', '=', $liqCxCabecera->instructivo)->first();
        $exceldato->delete();



        return back();
    }

    public function massDestroy(MassDestroyLiqCxCabeceraRequest $request)
    {
        $liqCxCabeceras = LiqCxCabecera::find(request('ids'));

        foreach ($liqCxCabeceras as $liqCxCabecera) {
            $liqCxCabecera->delete();
            $liqCostos = LiqCosto::where('liq_cabecera_id', '=', $liqCxCabecera->id)->get();
            foreach ($liqCostos as $item) {
                $item->delete();
            }
            $liquidacion = LiquidacionesCx::where('liqcabecera_id', '=', $liqCxCabecera->id)->get();
            foreach ($liquidacion as $liq) {
                $liq->delete();
            }
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function actualizarValorGD_Unitario(Request $request)
    {

        $id = $request->id;
        $affectedRows = 0;
        $resEjec = collect();
        //$liq = new Liquidaciones();

        // Obtener la sesión correctamente
        $liqs = $this->ConsolidadoLiquidacionesUnitario($id);
       
        // Obtener cabeceras
        $liqCxCabeceras = LiqCxCabecera::whereNull('deleted_at')->where('id', $id)->get();

        foreach ($liqCxCabeceras as $liqCxCabecera) {
            try {
                // Obtener despachos
                $despachos = DB::connection('sqlsrv')->table("V_PKG_Despachos")
                    ->select('folio', 'n_variedad', 'c_embalaje', 'n_calibre', 'n_etiqueta', 'id_pkg_stock_det')
                    ->where('tipo_g_despacho', '=', 'GDP')
                    ->where('numero_embarque', '=', str_replace('i', '', str_replace('I', '', $liqCxCabecera->instructivo)))
                    ->get();

                foreach ($despachos as $despacho) {
                    $EFOB = 0;
                    $ECCajas = 0;
                    $valor = 0;

                    $items = $liqs->filter(function ($item) use ($despacho) {
                        Log::info('Comparando:', [
                            'folio_fx' => [$item['folio_fx'], $despacho->folio, $item['folio_fx'] === $despacho->folio],
                            'variedad' => [$item['variedad'], $despacho->n_variedad, strcasecmp($item['variedad'], $despacho->n_variedad) === 0],
                            'embalaje' => [$item['embalaje'], $despacho->c_embalaje, strcasecmp($item['embalaje'], $despacho->c_embalaje) === 0],
                            'calibre' => [$item['calibre'], $despacho->n_calibre, strcasecmp($item['calibre'], $despacho->n_calibre) === 0],
                            'etiqueta' => [$item['etiqueta'], $despacho->n_etiqueta, strcasecmp($item['etiqueta'], $despacho->n_etiqueta) === 0],
                        ]);
                    
                        return $item['folio_fx'] === $despacho->folio &&
                            strcasecmp($item['variedad'], $despacho->n_variedad) === 0 &&
                            strcasecmp($item['embalaje'], $despacho->c_embalaje) === 0 &&
                            strcasecmp($item['calibre'], $despacho->n_calibre) === 0 &&
                            strcasecmp($item['etiqueta'], $despacho->n_etiqueta) === 0;
                    });
                    
                    Log::info('Elementos filtrados:', $items->toArray());
                   
                
                    

                    Log::info('item: ' . json_encode($items));
                    
                    
                    foreach ($items as $item) {
                        $EFOB += $item['FOB_TO_USD'];
                        $ECCajas += $item['Cajas'];
                        Log::info('Folio '.$item['folio_fx'].' EFOB: ' . $EFOB);
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
                        die("Could not connect to the database.  Please check your configuration. error:" . $e);
                    }
                    // Realizar el UPDATE en la base de datos
                    $affectedRows = DB::connection('sqlsrv')
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

        return response()->json(["message" => "Se modificaron $affectedRows registros", "data" => $affectedRows], 200);
    }
    function traducedatos($texto, $tipo)
    {
        try {
            if ($texto == null || $texto == '') {
                return $texto;
            }
           // Log::info("Traduciendo datos: " . $texto . "----" . $tipo);
            $dato = Diccionario::where("tipo", $tipo)->where("variable", $texto)->first();
            if ($dato == null) {
                return $texto;
            }
            return $dato->valor;
        } catch (\Exception $e) {
           // Log::error("Error al traducir datos: " . $e->getMessage() . "----" . $texto . "----" . $tipo);

            return $texto;
        }
    }
    public function ConsolidadoLiquidacionesUnitario(int $id)
    {
        $fg =$this;
        $liqCxCabeceras = LiqCxCabecera::where('id',$id)->whereNull('deleted_at')->get(); // LiqCxCabecera::find(request('ids'));

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
            //Log::info("Instructivo: " . $liqCxCabecera->instructivo);
            $nombre_costo = Costo::pluck('nombre'); // Extraer solo los nombres de costos
            $total_kilos = 0;
            $total_ventas = 0;
            foreach ($detalle as $item) {
                $total_kilos = $total_kilos + (float)(str_replace(',', '.', $fg->traducedatos($item->embalaje_id, 'Embalaje'))) * (float)(str_replace(',', '.', $item->cantidad));

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
                $Otros_Impuestos_JWM_Impuestos = (($otrosimpuestos == 0 ? 0 : ($otrosimpuestos / $excelDato->tasa)) / $total_kilos) * $Peso_neto; //CA
                $Otros_Impuestos_JWM_TO_USD = $Otros_Impuestos_JWM_Impuestos * $Cajas; //CB
                $Otros_Ingresos_abonos = (($otrosingresos == 0 ? 0 : ($otrosingresos / $excelDato->tasa)) / $total_kilos) * $Peso_neto; //CC
                $Otros_Ingresos_abonos_TO_USD = $Otros_Ingresos_abonos * $Cajas; //CD
                $RMB_Flete_Domestico_Caja = (($costosFleteDomestico == 0 ? 0 : $costosFleteDomestico) / $total_kilos) * $Peso_neto; //CE
                $RMB_Flete_Domestico_TO = $RMB_Flete_Domestico_Caja * $Cajas; //CF
                $USD_Flete_Domestico    = $RMB_Flete_Domestico_TO / $TC; //CG
                $USD_Flete_Domestico_TO = $USD_Flete_Domestico * $Cajas; //CH
                $Ajuste_impuesto_USD = (($ajusteimpuesto == 0 ? 0 : ($ajusteimpuesto) / $excelDato->tasa) / $total_kilos) * $Peso_neto; //BO
                $Flete_Aereo = ($flete_exportadora / $total_kilos) * $Peso_neto; //BQ
                $Flete_Aereo_TO = $Flete_Aereo * $Cajas; //BR
                $Costos_cajas_RMB = $Imp_destino_caja_RMB + $Costo_log_Caja_RMB + $Ent_Al_mercado_Caja_RMB + $Costo_mercado_caja_RMB + $Otros_costos_dest_Caja_RMB + $Comision_Caja + ($Otros_Impuestos_JWM_Impuestos * $TC) + ($Ajuste_impuesto_USD * $TC) - ($Otros_Ingresos_abonos * $TC) + ($Flete_Aereo * $TC); //AR
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
                $folio_fx=$item->folio_fx;

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
                        'folio_fx'=>$item->folio_fx,


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
