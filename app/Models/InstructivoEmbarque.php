<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstructivoEmbarque extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'instructivo_embarques';

    protected $dates = [
        'fecha',
        'stacking_ini',
        'stacking_end',
        'etd',
        'eta',
        'fecha_carga',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'instructivo',
        'fecha',
        'embarcador_id',
        'agente_aduana_id',
        'consignee_id',
        'naviera_id',
        'num_booking',
        'nave',
        'cut_off',
        'stacking_ini',
        'stacking_end',
        'etd',
        'eta',
        'puerto_embarque_id',
        'puerto_destino_id',
        'puerto_descarga_id',
        'punto_de_entrada',
        'num_contenedor',
        'ventilacion',
        'tara_contenedor',
        'quest',
        'num_sello',
        'temperatura',
        'empresa_transportista',
        'conductor_id',
        'rut_conductor',
        'ppu',
        'telefono',
        'planta_carga_id',
        'direccion',
        'fecha_carga',
        'hora_carga',
        'guia_despacho_dirigida',
        'planilla_sag_dirigida',
        'num_po',
        'emision_de_bl_id',
        'tipo_de_flete_id',
        'clausula_de_venta_id',
        'moneda_id',
        'forma_de_pago_id',
        'modalidad_de_venta_id',
        'awb',
        'linea_aerea',
        'num_vuelo',
        'tipo_vuelo',
        'pais_embarque_id',
        'pais_destino_id',
        'dus',
        'sps',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getFechaAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFechaAttribute($value)
    {
        $this->attributes['fecha'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function embarcador()
    {
        return $this->belongsTo(Embarcador::class, 'embarcador_id');
    }

    public function agente_aduana()
    {
        return $this->belongsTo(AgenteAduana::class, 'agente_aduana_id');
    }

    public function consignee()
    {
        return $this->belongsTo(BaseRecibidor::class, 'consignee_id');
    }

    public function naviera()
    {
        return $this->belongsTo(Naviera::class, 'naviera_id');
    }

    public function getStackingIniAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setStackingIniAttribute($value)
    {
        $this->attributes['stacking_ini'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getStackingEndAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setStackingEndAttribute($value)
    {
        $this->attributes['stacking_end'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getEtdAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setEtdAttribute($value)
    {
        $this->attributes['etd'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getEtaAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setEtaAttribute($value)
    {
        $this->attributes['eta'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function puerto_embarque()
    {
        return $this->belongsTo(PuertoCorreo::class, 'puerto_embarque_id');
    }

    public function puerto_destino()
    {
        return $this->belongsTo(PuertoCorreo::class, 'puerto_destino_id');
    }

    public function puerto_descarga()
    {
        return $this->belongsTo(Puerto::class, 'puerto_descarga_id');
    }

    public function conductor()
    {
        return $this->belongsTo(Chofer::class, 'conductor_id');
    }

    public function planta_carga()
    {
        return $this->belongsTo(PlantaCarga::class, 'planta_carga_id');
    }

    public function getFechaCargaAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFechaCargaAttribute($value)
    {
        $this->attributes['fecha_carga'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function emision_de_bl()
    {
        return $this->belongsTo(EmisionBl::class, 'emision_de_bl_id');
    }

    public function tipo_de_flete()
    {
        return $this->belongsTo(Tipoflete::class, 'tipo_de_flete_id');
    }

    public function clausula_de_venta()
    {
        return $this->belongsTo(ClausulaVentum::class, 'clausula_de_venta_id');
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function forma_de_pago()
    {
        return $this->belongsTo(FormaPago::class, 'forma_de_pago_id');
    }

    public function modalidad_de_venta()
    {
        return $this->belongsTo(ModVentum::class, 'modalidad_de_venta_id');
    }
}
