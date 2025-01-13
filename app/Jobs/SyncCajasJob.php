<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use DB;
class SyncCajasJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $cajas = DB::connection("sqlsrv")->table('PKG_Stock_Cajas AS SC')
            ->select([
                'SC.ncaja',
                'SCH.id_pkg_stock_det',
                DB::raw('ISNULL(AEN.CSG, \'\') AS csg_productor'),
                DB::raw('ISNULL(AEN.nombre, \'\') AS n_productor'),
                DB::raw('ISNULL(AEN.nombre_sucursal, \'\') AS ns_productor'),
                DB::raw('ISNULL(AEN.codigo_sag, \'\') AS codigo_sag_productor'),
                DB::raw('ISNULL(AEN.CP1, \'\') AS cp1_productor'),
                DB::raw('ISNULL(PES.nombre, \'Sin Especie\') AS n_especie'),
                DB::raw('ISNULL(PVA.nombre, \'Sin Variedad\') AS n_variedad'),
            DB::raw('ISNULL(ACC.nombre, \'\') AS n_centrocosto'),
            'COMU.nombre AS n_comuna',
            'CIU.nombre AS n_ciudad',
            'PROV.nombre AS n_provincia',
            'PPN.nombre as nave',
            'ETI.nombre as n_etiqueta',
            'PGD.contenedor',
            'SD.cantidad',
            'SD.peso_neto',
            'sd.fecha_cosecha',
            'sd.fecha_produccion',
            'pemb.fecha_despacho',
            'pemb.etd',
            'pemb.transporte',
            DB::raw('CASE WHEN ISNULL(SD.peso_final, 0) = 0 THEN isnull(SD.peso_neto, 0) + ISNULL(AIT.tara_std, 0)
            ELSE isnull(SD.peso_final, 0) + ISNULL(AIT.tara_std, 0) END AS peso_bruto'),
            DB::raw('ISNULL(APE4.nombre_sucursal, \'\') AS ns_productor_rotulacion'),
            DB::raw('ISNULL(dbo.ADM_Entidades_N_Comuna(APE4.id), \'\') AS comuna_productor_rotulacion'),
            DB::raw('ISNULL(dbo.ADM_Entidades_N_Provincia(APE4.id), \'\') AS provincia_productor_rotulacion'),
            DB::raw('ISNULL(dbo.ADM_Entidades_C_Region(APE4.id), \'\') AS C_Region_productor_rotulacion'),
            DB::raw('ISNULL(dbo.ADM_Entidades_Direccion(APE4.id), \'\') AS Direccion_Productor_Rotulado'),
            DB::raw('ISNULL(dbo.ADM_Entidades_N_Region(APE4.id), \'\') AS N_Region_Productor_Rotulado'),
            DB::raw('ISNULL(APE4.nombre, \'\') AS n_productor_rotulacion'),
            DB::raw('ISNULL(PPE.nombre, \'\') AS n_especie_rotulacion'),
            DB::raw('ISNULL(PPE.Tipo_Produccion, \'\') as tipo_produccion'),
            DB::raw('ISNULL(PPV.nombre, \'\') AS n_variedad_rotulacion'),
            DB::raw('ISNULL(dbo.ADM_Entidades_nombre(APE5.id), \'\') AS n_empresa'),
            DB::raw('(SELECT APE5.nombre from ADM_P_Entidades APE5 where APE5.id=pemb.id_adm_p_entidades_exportadora) as n_exportadora'),
            DB::raw('(SELECT APE5.nombre from ADM_P_Entidades APE5 where APE5.id=pemb.id_adm_p_entidades_destinatario) as n_destinatario'),
            ])
            ->join('PKG_Stock_Cajas_Historial as SCH', 'SCH.id_pkg_stock_cajas', '=', 'SC.id')
            ->join('PKG_Stock_Det as SD', 'SD.id', '=', 'SCH.id_pkg_stock_det')
            ->leftJoin('PKG_G_Despacho as PGD', function ($join) {
                $join->on('SD.destruccion_tipo', '=', 'PGD.tipo')
                    ->on('SD.destruccion_id', '=', 'PGD.id');
            })
            ->leftJoin('PKG_Embarques as pemb', 'pemb.id', '=', 'PGD.id_pkg_embarques')
            ->leftJoin('PRO_P_Naves as PPN', 'PPN.id', '=', 'pemb.id_pro_p_naves')
            ->leftJoin('ADM_P_CentrosCosto as ACC', 'SD.id_adm_p_centroscosto', '=', 'ACC.id')
            ->leftJoin('ADM_P_Entidades as AEN', 'ACC.id_adm_p_entidades', '=', 'AEN.id')
            ->leftJoin('PRO_P_Variedades as PVA', 'ACC.id_pro_p_variedades', '=', 'PVA.id')
            ->leftJoin('PRO_P_Variedades as PVAR', 'PVAR.id', '=', 'PVA.Id_Pro_P_Variedades_Comercial')
            ->leftJoin('PRO_P_Especies as PES', 'PVA.id_pro_p_especies', '=', 'PES.id')
            ->leftJoin('PRO_P_Etiquetas as ETI', 'SD.id_pro_p_etiquetas', '=', 'ETI.id')
            ->leftJoin('ADM_P_Entidades as APE', 'AEN.id_matriz', '=', 'APE.id')
            ->leftJoin('ADM_P_Comunas as COMU', 'COMU.id', '=', 'AEN.id_adm_p_comunas')
            ->leftJoin('ADM_P_Ciudades as CIU', 'CIU.id', '=', 'COMU.id_adm_p_ciudades')
            ->leftJoin('ADM_P_Provincias as PROV', 'PROV.id', '=', 'CIU.id_adm_p_provincias')
            ->leftJoin('PKG_Stock as ST', 'SD.id_pkg_stock', '=', 'ST.id')
            ->leftJoin('ADM_P_Items as AIT', 'ST.id_adm_p_items_contenedor', '=', 'AIT.id')
            ->leftJoin('ADM_P_Entidades as APE1', 'PGD.id_adm_p_entidades_destinatario', '=', 'APE1.id')
            ->leftJoin('ADM_P_Entidades as APE2', 'PGD.id_adm_p_entidades_transportista', '=', 'APE2.id')
            ->leftJoin('ADM_P_Entidades as APE3', 'APE3.id', '=', 'SD.id_adm_p_entidades_exportadora')
            ->leftJoin('ADM_P_Entidades as APE4', 'SD.id_adm_p_entidades_productor_rotulacion', '=', 'APE4.id')
            ->leftJoin('ADM_P_Entidades as APE5', 'PGD.id_adm_p_entidades_empresa', '=', 'APE5.id')
            ->leftJoin('PRO_P_Variedades as PPV', 'SD.id_pro_p_variedades_rotulacion', '=', 'PPV.id')
            ->rightJoin('PRO_P_Especies as PPE', 'PPV.id_pro_p_especies', '=', 'PPE.id')
            ->leftJoin('PRO_P_Familias as PPF', 'PPE.id_pro_p_familias', '=', 'PPF.id')
            ->whereIn('SD.destruccion_tipo', ['GDP', 'GDI', 'GDT'])
            ->where('PPE.id', '=', 7)
            ->where('SD.inventario', 1)
            //->where('SC.ncaja', '>', $request->min)
            ->whereIn(DB::raw('DATEPART(WEEK, pemb.etd)'), [3])
            ->where('SD.destruccion_id', '>=', 0)
            ->orderBy('SC.ncaja')->get();
                //$jsonData = json_encode($cajasChunk);
                // Log::info("registross".count($cajasChunk));
                // Storage::disk('public')->append('cajas.json.gz', gzcompress($jsonData, 9));
                Storage::disk('public')->put('cajas2.json', json_encode($cajas));
            
            
                    
                   // Storage::disk('public')->put('cajas.json', json_encode($cajas));
                
        } catch (\Exception $e) {
            Log::error('Error al sincronizar datos de cajas: ' . $e->getMessage());
        }
    }
}
