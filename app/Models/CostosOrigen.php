<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostosOrigen extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'costos_origen';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'n_embarque',  //aéreo, Maritimo
        'cliente',    //aéreo, Maritimo
        'embarcadora', //aéreo es Freight Forwarded
        'agencia_aduana',
        'puerto_embarque',
        'planta_carga',
        'empresa_transportista',
        'especies',
        'cajas',
        'naviera',
        'nave',
        'booking',
        'n_contenedor',
        'n_bill_of_lading',
        'tipo_flete',
        'puerto_destino',
        'flete_collect',
        'gastos_chinos_bl',
        'usd_pagado_greenex',
        'motivo_pago_usd',
        'n_factura_consolidacion_safe_cargo',
        'consolidacion_safe_cargo',
        'n_factura_citacion_falso',
        'citacion_falso',
        'n_factura_materiales_consolidacion',
        'materiales_consolidacion',
        'n_factura_flete_terrestre_underlung',
        'flete_terrestre_underlung',
        'n_factura_falso_flete',
        'falso_flete',
        'n_factura_interplanta',
        'interplanta',
        'n_factura_sobreestadia',
        'sobreestadia',
        'n_factura_porteo',
        'porteo',
        'n_factura_almacenaje',
        'almacenaje',
        'n_factura_retiro_cruzado',
        'retiro_cruzado',
        'n_factura_otros_costos_carga',
        'otros_costos_carga',
        'n_factura_agenciamiento',
        'agenciamiento',
        'n_factura_honorarios_aga',
        'honorarios_aga',
        'n_factura_certificado_origen',
        'certificado_origen',
        'n_factura_diferencias_co',
        'diferencias_co',
        'n_factura_seguridad_portuaria',
        'seguridad_portuaria',
        'n_factura_gate_out',
        'gate_out',
        'n_factura_servicio_retiro_express',
        'servicio_retiro_express',
        'n_factura_gate_in',
        'gate_in',
        'n_factura_gate_set',
        'gate_set',
        'n_factura_late_arrival',
        'late_arrival',
        'n_factura_early_arrival',
        'early_arrival',
        'n_factura_emision_destino',
        'emision_destino',
        'n_factura_servicio_detention',
        'servicio_detention',
        'n_factura_doc_fee',
        'doc_fee',
        'n_factura_control_sello',
        'control_sello',
        'n_factura_almacenamiento',
        'almacenamiento',
        'n_factura_pago_tardio',
        'pago_tardio',
        'n_factura_otros_costos_embarque',
        'otros_costos_embarque',
        'n_factura_1_matriz_fuera_plazo',
        '1_matriz_fuera_plazo',
        'n_factura_2_correccion_matriz',
        '2_correccion_matriz',
        'n_factura_3_correccion_matriz',
        '3_correccion_matriz',
        'n_factura_4_correccion_bl',
        '4_correccion_bl',
        'n_factura_5_correccion_bl',
        '5_correccion_bl',
        'n_factura_reemision_c_o',
        'reemision_c_o',
        'n_factura_reemision_fitosanitario',
        'reemision_fitosanitario',
        'n_factura_otros_documental',
        'otros_documental',
        'costos_usd_pagado_greenex',
        'costos_carga',
        'costos_embarque',
        'costo_reemision_doc',
        'total_general',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
