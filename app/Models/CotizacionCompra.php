<?php

namespace App\Models;

use DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotizacionCompra extends Model
{
    use HasFactory;

    public $table = 'cotizacion_compras';

    protected $dates = [
        'fecha_recepcion',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'solicitud_compra_id',
        'proveedor',
        'monto',
        'moneda_id',
        'archivo_path',
        'fecha_recepcion',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function solicitudCompra()
    {
        return $this->belongsTo(SolicitudCompra::class, 'solicitud_compra_id');
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function getFechaRecepcionAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFechaRecepcionAttribute($value)
    {
        $this->attributes['fecha_recepcion'] = $value
            ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d')
            : null;
    }
}
