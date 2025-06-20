<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fob extends Model
{
    // Specify the table name (including schema if needed)
    protected $table = 'FOB';

    // Define the primary key
    protected $primaryKey = 'id';

    // Indicate that the primary key is auto-incrementing
    public $incrementing = true;

    // Specify that timestamps are not used (since they aren't in the table)
    public $timestamps = false;

    // Define the fillable fields (mass assignable)
    protected $fillable = [
        'cliente',
        'nave',
        'Liquidacion',
        'ETA',
        'ETA_Week',
        'Fecha_Venta',
        'Fecha_Venta_Week',
        'Pallet',
        'Peso_neto',
        'Kilos_total',
        'embalaje',
        'etiqueta',
        'variedad',
        'calibre',
        'Cajas',
        'TC',
        'Ventas_TO_USD',
        'Venta_USD',
        'Com_USD',
        'Com_TO_USD',
        'Imp_destino_USD',
        'Imp_destino_USD_TO',
        'Costo_log_USD',
        'Costo_log_USD_TO',
        'Ent_Al_mercado_USD',
        'Ent_Al_mercado_USD_TO',
        'Costo_mercado_USD',
        'Costos_mercado_USD_TO',
        'Otros_costos_dest_USD',
        'Otros_costos_USD_TO',
        'Flete_marit_USD',
        'Flete_Marit_USD_TO',
        'Costos_USD_TO',
        'Ajuste_TO_USD',
        'FOB_USD',
        'FOB_TO_USD',
        'FOB_kg',
        'FOB_Equivalente',
        'Flete_Cliente',
        'Transporte',
        'c_embalaje',
        'folio_fx',
        'especie',
        'Costos_cajas_USD',
        'pais'
    ];

    // Define the data types for specific columns (optional casting)
    protected $casts = [
        'eta' => 'date',
        'Fecha_Venta' => 'date',
        'Fecha_Venta_Week' => 'date',
        'Peso_neto' => 'float',
        'Kilos_Total' => 'float',
        'cajas' => 'float',
        'TC' => 'float',
        'Ventas_TO_USD' => 'float',
        'Venta_USD' => 'float',
        'Com_USD' => 'float',
        'Com_TO_USD' => 'float',
        'Imp_destino_USD' => 'float',
        'Imp_destino_USD_TO' => 'float',
        'Costo_log_USD' => 'float',
        'Costo_log_USD_TO' => 'float',
        'Ent_Al_mercado_USD' => 'float',
        'Ent_Al_mercado_USD_TO' => 'float',
        'Costo_mercado_USD' => 'float',
        'Costos_mercado_USD_TO' => 'float',
        'Otros_costos_dest_USD' => 'float',
        'Otros_costos_USD_TO' => 'float',
        'Flete_marit_USD' => 'float',
        'Flete_Marit_USD_TO' => 'float',
        'Costos_USD_TO' => 'float',
        'Ajuste_TO_USD' => 'float',
        'FOB_USD' => 'float',
        'FOB_TO_USD' => 'float',
        'FOB_kg' => 'float',
        'FOB_Equivalente' => 'float',
        'Flete_Cliente' => 'float',
        'Costos_cajas_USD' => 'float',
    ];


}
