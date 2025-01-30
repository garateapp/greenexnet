<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostosOrigenAereo extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'costos_origen_aereo';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'n_embarque',  //aéreo, Maritimo
        'cliente',    //aéreo, Maritimo
        'freightforwarded', //aéreo es Freight Forwarded
        'especie',
        'cajas',
        'n_pallets',
        'cantidad_camiones',
        'empresa_transportista',
        'aerop_destino',
        'aerolinea',
        'awb',
        'bodega',
        'tipo_flete',
        'tipo_vuelo',
        'n_factura_termografo',
        'termografo_usd',
        'n_factura_mantas_termicas',
        'mantas_termicas_usd',
        'n_factura_flete_aeropuerto_clp',
        'flete_aeropuerto_clp',
        'n_factura_awb_usd',
        'awb_usd',
        'n_factura_awb_clp',
        'awb_clp',
        'n_factura_honorarios_clp',
        'honorarios_clp',
        'n_factura_agenciamiento_clp',
        'agenciamiento_clp',
        'n_factura_cert_origen_clp',
        'cert_origen_clp',
        'n_factura_handling_clp',
        'handling_clp',
        'n_factura_gastos_bodega_clp',
        'gastos_bodega_clp',
        'n_factura_otros_costos_clp',
        'otros_costos_clp',
        'n_factura_reemison_clp',
        'reemison_clp',
        'n_factura_reemision_fito_clp',
        'reemision_fito_clp',
        'n_factura_sag_sps_clp',
        'sag_sps_clp',
        'n_factura_sag_otros_costos_clp',
        'sag_otros_costos_clp',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
