<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiquidacionesCx extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'liquidaciones_cxes';

    protected $dates = [
        'eta',
        'fecha_venta',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
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
        'c_embalaje',
        'folio_fx',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    // public function getEtaAttribute($value)
    // {
    //     return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    // }

    // public function setEtaAttribute($value)
    // {
    //     $this->attributes['eta'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    // }

    public function variedad()
    {
        return $this->belongsTo(Variedad::class, 'variedad_id');
    }

    public function etiqueta()
    {
        return $this->belongsTo(Etiquetum::class, 'etiqueta_id');
    }

    public function embalaje()
    {
        return $this->belongsTo(ItemEmbalaje::class, 'embalaje_id');
    }

    // public function getFechaVentaAttribute($value)
    // {
    //     return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    // }

    // public function setFechaVentaAttribute($value)
    // {
    //     $this->attributes['fecha_venta'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    // }

    public function liqcabecera()
    {
        return $this->belongsTo(LiqCxCabecera::class, 'liqcabecera_id');
    }
}
