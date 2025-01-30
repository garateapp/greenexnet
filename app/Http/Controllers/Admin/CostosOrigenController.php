<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CostosOrigen;
use App\Models\CostoOrigenAereo;
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
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Exports\AsistenciaExport;
use App\Models\CostosOrigenAereo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Maatwebsite\Excel\Facades\Excel;

class CostosOrigenController extends Controller
{
    public function costosorigen(Request $request)
    {
        abort_if(Gate::denies('costos_origen_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $costos = CostosOrigen::whereRaw('deleted_at is null')->select(
            DB::raw('SUM(consolidacion_safe_cargo) as consolidacion_safe_cargo'),
            DB::raw('SUM(citacion_falso) as citacion_falso'),
            DB::raw('SUM(materiales_consolidacion) as materiales_consolidacion'),
            DB::raw('SUM(flete_terrestre_underlung) as flete_terrestre_underlung'),
            DB::raw('SUM(falso_flete) as falso_flete'),
            DB::raw('SUM(interplanta) as interplanta'),
            DB::raw('SUM(sobreestadia) as sobreestadia'),
            DB::raw('SUM(porteo) as porteo'),
            DB::raw('SUM(almacenaje) as almacenaje'),
            DB::raw('SUM(retiro_cruzado) as retiro_cruzado'),
            DB::raw('SUM(otros_costos_carga) as otros_costos_carga'),
            DB::raw('SUM(agenciamiento) as agenciamiento'),
            DB::raw('SUM(honorarios_aga) as honorarios_aga'),
            DB::raw('SUM(certificado_origen) as certificado_origen'),
            DB::raw('SUM(diferencias_co) as diferencias_co'),
            DB::raw('SUM(seguridad_portuaria) as seguridad_portuaria'),
            DB::raw('SUM(gate_out) as gate_out'),
            DB::raw('SUM(servicio_retiro_express) as servicio_retiro_express'),
            DB::raw('SUM(gate_in)  as gate_in'),
            DB::raw('SUM(gate_set) as gate_set'),
            DB::raw('SUM(late_arrival) as late_arrival'),
            DB::raw('SUM(early_arrival) as early_arrival'),
            DB::raw('SUM(emision_destino) as emision_destino'),
            DB::raw('SUM(servicio_detention) as servicio_detention'),
            DB::raw('SUM(doc_fee) as doc_fee'),
            DB::raw('SUM(control_sello) as control_sello'),
            DB::raw('SUM(almacenamiento) as almacenamiento'),
            DB::raw('SUM(pago_tardio) as pago_tardio'),
            DB::raw('SUM(otros_costos_embarque) as otros_costos_embarque'),
            DB::raw('SUM(1_matriz_fuera_plazo) as matriz_fuera_plazo'),
            DB::raw('SUM(correccion_matriz) as correccion_matriz'),
            DB::raw('SUM(correccion_matriz_2) as correccion_matriz_2'),
            DB::raw('SUM(correccion_bl_1) as correccion_bl_1'),
            DB::raw('SUM(correccion_bl_2) as correccion_bl_2'),
            DB::raw('SUM(reemision_c_o) as reemision_c_o'),
            DB::raw('SUM(reemision_fitosanitario) as reemision_fitosanitario'),
            DB::raw('SUM(otros_documental) as otros_documental')
        )->get();
        $costosAereos=CostosOrigenAereo::whereRaw('deleted_at is null')->select(
            DB::raw('SUM(termografo_usd) as termografo_usd'),
            DB::raw('SUM(mantas_termicas_usd) as mantas_termicas_usd'),
            DB::raw('SUM(flete_aeropuerto_clp) as flete_aeropuerto_usd'),
            DB::raw('SUM(awb_usd) as awb_usd'),
            DB::raw('SUM(awb_clp) as awb_clp'),
            DB::raw('SUM(honorarios_clp) as honorarios_clp'),
            DB::raw('SUM(cert_origen_clp) as cert_origen_clp'),
            DB::raw('SUM(gastos_bodega_clp) as gastos_bodega_clp'),
            DB::raw('SUM(reemison_clp) as reemison_clp'),
            DB::raw('SUM(reemision_fito_clp) as reemision_fito_clp'),
            DB::raw('SUM(sag_sps_clp) as sag_sps_clp'),
            DB::raw('SUM(sag_otros_costos_clp) as sag_otros_costos_clp'),
            DB::raw('SUM(flete_aeropuerto_clp) as flete_aeropuerto_clp'),
            DB::raw('SUM(handling_clp) as handling_clp')
        )->get();






        return view('admin.comex.costosorigen', compact('costosAereos','costos'));
    }
    public function guardacostosorigen(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls',

            ]);
            $archivo = $request->file('file');
            $data = Excel::toArray(new ExcelImport, $archivo);
            $data = collect($data[0]);
            if ($request->tipoSel == 1) {
                CostosOrigen::truncate();
                // dD($data);
                // Formatear la data

                for ($i = 1; $i < count($data); $i++) {
                    $formattedData = [
                        'n_embarque' => $data[$i]['informacion_de_embarque'],
                        'cliente' => $data[$i][1],
                        'embarcadora' => $data[$i][2],
                        'agencia_aduana' => $data[$i][3],
                        'puerto_embarque' => $data[$i][4],
                        'planta_carga' => $data[$i][5],
                        'empresa_transportista' => $data[$i][6],
                        'especies' => $data[$i][7],
                        'cajas' => $data[$i][8] == ' ' ? 0 : $data[$i][8],
                        'naviera' => $data[$i][9],
                        'nave' => $data[$i][10],
                        'booking' => $data[$i][11],
                        'n_contenedor' => $data[$i][12],
                        'n_bill_of_lading' => $data[$i][13],
                        'tipo_flete' => $data[$i][14],
                        'puerto_destino' => $data[$i][15],
                        'flete_collect' => $data[$i]['tarifa_segun_bl'],
                        'gastos_chinos_bl' => $data[$i][17],
                        'usd_pagado_greenex' => $data[$i]['costos_de_bl'],
                        'motivo_pago_usd' => $data[$i][19],
                        'n_factura_consolidacion_safe_cargo' => $data[$i]['costos_de_carga'],
                        'consolidacion_safe_cargo' => $data[$i][21],
                        'n_factura_citacion_falso' => $data[$i][22],
                        'citacion_falso' => $data[$i][23],
                        'n_factura_materiales_consolidacion' => $data[$i][24],
                        'materiales_consolidacion' => $data[$i][25],
                        'n_factura_flete_terrestre_underlung' => $data[$i][26],
                        'flete_terrestre_underlung' => $data[$i][27],
                        'n_factura_falso_flete' => $data[$i][28],
                        'falso_flete' => $data[$i][29],
                        'n_factura_interplanta' => $data[$i][30],
                        'interplanta' => $data[$i][31],
                        'n_factura_sobreestadia' => $data[$i][32],
                        'sobreestadia' => $data[$i][33],
                        'n_factura_porteo' => $data[$i][34],
                        'porteo' => $data[$i][35],
                        'n_factura_almacenaje' => $data[$i][36],
                        'almacenaje' => $data[$i][37],
                        'n_factura_retiro_cruzado' => $data[$i][38],
                        'retiro_cruzado' => $data[$i][39],
                        'n_factura_otros_costos_carga' => $data[$i][40],
                        'otros_costos_carga' => $data[$i][41],
                        'n_factura_agenciamiento' => $data[$i]['costos_de_embarques_en_origen'],
                        'agenciamiento' => $data[$i][43],
                        'n_factura_honorarios_aga' => $data[$i][44],
                        'honorarios_aga' => $data[$i][45],
                        'n_factura_certificado_origen' => $data[$i][46],
                        'certificado_origen' => $data[$i][47],
                        'n_factura_diferencias_co' => $data[$i][48],
                        'diferencias_co' => $data[$i][49],
                        'n_factura_seguridad_portuaria' => $data[$i][50],
                        'seguridad_portuaria' => $data[$i][51],
                        'n_factura_gate_out' => $data[$i][52],
                        'gate_out' => $data[$i][53],
                        'n_factura_servicio_retiro_express' => $data[$i][54],
                        'servicio_retiro_express' => $data[$i][55],
                        'n_factura_gate_in' => $data[$i][56],
                        'gate_in' => $data[$i][57],
                        'n_factura_gate_set' => $data[$i][58],
                        'gate_set' => $data[$i][59],
                        'n_factura_late_arrival' => $data[$i][60],
                        'late_arrival' => $data[$i][61],
                        'n_factura_early_arrival' => $data[$i][62],
                        'early_arrival' => $data[$i][63],
                        'n_factura_emision_destino' => $data[$i][64],
                        'emision_destino' => $data[$i][65],
                        'n_factura_servicio_detention' => $data[$i][66],
                        'servicio_detention' => $data[$i][67],
                        'n_factura_doc_fee' => $data[$i][68],
                        'doc_fee' => $data[$i][69],
                        'n_factura_control_sello' => $data[$i][70],
                        'control_sello' => $data[$i][71],
                        'n_factura_almacenamiento' => $data[$i][72],
                        'almacenamiento' => $data[$i][73],
                        'n_factura_pago_tardio' => $data[$i][74],
                        'pago_tardio' => $data[$i][75],
                        'n_factura_otros_costos_embarque' => $data[$i][76],
                        'otros_costos_embarque' => $data[$i][77],
                        'n_factura_1_matriz_fuera_plazo' => $data[$i]['costos_de_reemision_documental'],
                        '1_matriz_fuera_plazo' => $data[$i][79],
                        'n_factura_2_correccion_matriz' => $data[$i][80],
                        'correccion_matriz' => $data[$i][81],
                        'n_factura_3_correccion_matriz' => $data[$i][82],
                        'correccion_matriz_2' => $data[$i][83],
                        'n_factura_4_correccion_bl' => $data[$i][84],
                        'correccion_bl_1' => $data[$i][85],
                        'n_factura_5_correccion_bl' => $data[$i][86],
                        'correccion_bl_2' => $data[$i][87],
                        'n_factura_reemision_c_o' => $data[$i][88],
                        'reemision_c_o' => $data[$i][89],
                        'n_factura_reemision_fitosanitario' => $data[$i][90],
                        'reemision_fitosanitario' => $data[$i][91],
                        'n_factura_otros_documental' => $data[$i][92],
                        'otros_documental' => $data[$i][93],
                        'costos_usd_pagado_greenex' => $data[$i]['totales'],
                        'costos_carga' => $data[$i][95],
                        'costos_embarque' => $data[$i][96],
                        'costo_reemision_doc' => $data[$i][97],
                        'total_general' => $data[$i][98]
                    ];


                    CostosOrigen::create($formattedData);
                }
            }
            elseif($request->selTipo==2){
                CostosOrigenAereo::truncate();

                for ($i = 2; $i < count($data); $i++) {
                    $formattedData = [
                        'n_embarque'=>$data[$i][0],
                        'cliente'=>$data[$i][1],
                        'freightforwarded'=>$data[$i][2], //aéreo es Freight Forwarded
                        'especie'=>$data[$i][3],
                        'cajas'=>$data[$i][4],
                        'n_pallets'=>$data[$i][5],
                        'cantidad_camiones'=>$data[$i][6],
                        'empresa_transportista'=>$data[$i][7],
                        'aerop_destino'=>$data[$i][8],
                        'aerolinea'=>$data[$i][9],
                        'awb'=>$data[$i][10],
                        'bodega'=>$data[$i][11],
                        'tipo_flete'=>$data[$i][12],
                        'tipo_vuelo'=>$data[$i][13],
                        'n_factura_termografo'=>$data[$i][14],
                        'termografo_usd'=>$data[$i][15],
                        'n_factura_mantas_termicas'=>$data[$i][16],
                        'mantas_termicas_usd'=>$data[$i][17],
                        'n_factura_flete_aeropuerto_clp'=>$data[$i][18],
                        'flete_aeropuerto_clp'=>$data[$i][19],
                        'n_factura_awb_usd'=>$data[$i][20],
                        'awb_usd'=>$data[$i][21],
                        'n_factura_awb_clp'=>$data[$i][22],
                        'awb_clp'=>$data[$i][23],
                        'n_factura_honorarios_clp'=>$data[$i]['costos_embarque'],
                        'honorarios_clp'=>$data[$i][25],
                        'n_factura_agenciamiento_clp'=>$data[$i][26],
                        'agenciamiento_clp'=>$data[$i][27],
                        'n_factura_cert_origen_clp'=>$data[$i][28],
                        'cert_origen_clp'=>$data[$i][29],
                        'n_factura_handling_clp'=>$data[$i][30],
                        'handling_clp'=>$data[$i][31],
                        'n_factura_gastos_bodega_clp'=>$data[$i][32],
                        'gastos_bodega_clp'=>$data[$i][33],
                        'n_factura_otros_costos_clp'=>$data[$i][34],
                        'otros_costos_clp'=>$data[$i][35],
                        'n_factura_reemison_clp'=>$data[$i]['costo_documental'],
                        'reemison_clp'=>$data[$i][37],
                        'n_factura_reemision_fito_clp'=>$data[$i][38],
                        'reemision_fito_clp'=>$data[$i][39],
                        'n_factura_sag_sps_clp'=>$data[$i]['costos_sag']?$data[$i]['costos_sag']:0,
                        'sag_sps_clp'=>$data[$i][41],
                        'n_factura_sag_otros_costos_clp'=>$data[$i][42],
                        'sag_otros_costos_clp'=>$data[$i][43],
                    ];

                    CostosOrigenAereo::create($formattedData);
                }
            }
            $cont = CostosOrigen::all()->count();
            return redirect()->back()->with('message', 'archivo actualizado correctamente con un total de: ' . $cont . ' Registros');
        } catch (\Throwable $th) {

            return redirect()->back()->with('error', 'Error al subir el archivo: ' . $th->getMessage());
        }
    }
}
