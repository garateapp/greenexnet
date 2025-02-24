<?php

namespace App\Libs;

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
use App\Libs\Funciones_Globales;

class Liquidaciones
{

    public function Liquidaciones() {}
    public function obtenerDatosLiquidaciones()
    {
        $datos = DB::table('greenexnet.liquidaciones_cxes as lc')
            ->join('greenexnet.liq_cx_cabeceras as lcc', 'lc.liqcabecera_id', '=', 'lcc.id')
            ->join('greenexnet.excel_datos as ed', 'lcc.instructivo', '=', 'ed.instructivo')
            ->join('greenexnet.clientes_comexes as cc', 'lcc.cliente_id', '=', 'cc.id')
            ->whereNull('lc.deleted_at')
            ->groupBy(
                'ed.instructivo',
                'cc.nombre_fantasia',
                'lc.variedad_id',
                'lc.etiqueta_id',
                'lc.embalaje_id',
                'lc.calibre',
                'lc.pallet',
                'ed.tasa',
                'lcc.id',
                'lc.precio_unitario',
                'lcc.factor_imp_destino',
                'lc.cantidad',
                'fecha_arribo',
                'fecha_venta',
                'nave_id'
            )
            ->select([
                DB::raw('"" as placeholder'),
                'cc.nombre_fantasia',
                'ed.instructivo',
                'lc.variedad_id',
                'lc.etiqueta_id',
                'lc.embalaje_id',
                'lc.calibre',
                'lc.pallet',
                'ed.tasa',
                'lcc.id',
                DB::raw("(SELECT nombre from greenexnet.naves where id=lcc.nave_id) as nave_id"),
                DB::raw('SUM(lc.cantidad)*embalaje_id as kilos_item'),
                DB::raw('SUM(lc.cantidad) as cantidad_total'),
                'lcc.factor_imp_destino as factor',
                DB::raw('SUM(lc.precio_unitario * lc.cantidad) AS MONTO_RMB'),
                DB::raw('SUM((lc.precio_unitario * lc.cantidad) / ed.tasa) AS MONTO_USD'),
                DB::raw('WEEK(ed.fecha_arribo) as fecha_arribo'),
                DB::raw('WEEK(ed.fecha_venta) as fecha_venta')
            ])
            ->get();


        return $datos;
    }
    public function ConsolidadoLiquidaciones()
    {
        $fg = new Funciones_Globales();
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
            $detalle = LiquidacionesCx::where('liqcabecera_id', $liqCxCabecera->id)->whereNotNull('folio_fx')->whereNotNull('c_embalaje')->where('folio_fx','NOT LIKE', '%,%')->get();
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
                $Pallet = $item->pallet; //O
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
    public function ConsolidadoLiquidacionesUnitario(int $id)
    {
        $fg = new Funciones_Globales();
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
                $Pallet = $item->pallet; //O
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
    /**
     * Agrupa las liquidaciones por cliente, nave, semana de arribo, variedad, embalaje, etiqueta y calibre.
     * Calcula el FOB_total en USD y el total de kilos.
     * Calcula el promedio de FOB por caja.
     * Regresa un objeto con las claves:
     *      cliente
     *      nave
     *      ETA_Week
     *      etiqueta
     *      variedad
     *      calibre
     *      embalaje
     *      FOB_TO_USD
     *      Cantidad
     *      PromedioFOBxCaja
     *
     * @return object
     */
    public function Liquidacionesagrupadas()
    {

        $datos = $this->ConsolidadoLiquidaciones();

        // $grouped = collect($datos)->groupBy(function ($item) {
        //     return
        //             $item['nave'] .'|'.
        //             $item['ETA_Week'] . '|' .
        //             $item['cliente'] . '|' .
        //             $item['c_embalaje'] . '|' .
        //             $item['etiqueta'] . '|' .
        //             $item['variedad'] . '|' .
        //             $item['calibre'];
        // })->map(function ($grupo) {
        //     $totalFobUsd = round($grupo->sum('FOB_TO_USD'),2);
        //     $totalKilos = round($grupo->sum('Kilos_total'),2);
        //     $cantidad=$grupo->sum('Cajas');
        //     // if($grupo->first()['nave']){
        //     // $nave=Nafe::find($grupo->first()['nave'])->first();
        //     // $naveNombre=$nave->nombre;
        //     // }
        //     // else{
        //     //     $naveNombre="Aéreo";
        //     // }
        //     // Log::info("Instructivos: " . implode(', ', $grupo->pluck('Liquidacion')->toArray()));
        //     // Log::info("Naves: " . implode(', ', $grupo->pluck('nave')->toArray()));

        //     // Calcular FOB_kg basado en el total de FOB_TO_USD y Kilos_total
        //     $XFOBCaja=round($totalFobUsd>0?$totalFobUsd/$cantidad:0,2);
        //     return[

        //         "cliente" => $grupo->first()['cliente'],
        //         "nave"=>$grupo->first()['nave'],
        //         "ETA_Week" => $grupo->first()['ETA_Week'],
        //         "etiqueta" => $grupo->first()['etiqueta'],
        //         "variedad" => $grupo->first()['variedad'],
        //         "calibre" => $grupo->first()['calibre'],
        //         "embalaje" => $grupo->first()['c_embalaje'],
        //         "kilos_total"=>$totalKilos,
        //         "FOB_TO_USD"=>$totalFobUsd,
        //         "Cantidad"=>$cantidad,
        //         "FOB_USD"=> round($grupo->sum('FOB_USD'),4),
        //         "PromedioFOBxCaja"=>$grupo->avg('FOB_USD')

        //     ];
        // });
        // Log::info($grouped->values()->where('cliente','=','YUHUA'));
        return $datos;
    }

}
