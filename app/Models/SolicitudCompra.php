<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudCompra extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'solicitud_compras';

    protected $dates = [
        'fecha_requerida',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'solicitante_id',
        'responsable_id',
        'adquisicion_estado_id',
        'centro_costo_id',
        'moneda_id',
        'titulo',
        'descripcion',
        'monto_estimado',
        'cotizaciones_requeridas',
        'cotizaciones_por_adquisiciones',
        'fecha_requerida',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'cotizaciones_por_adquisiciones' => 'boolean',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function estado()
    {
        return $this->belongsTo(AdquisicionEstado::class, 'adquisicion_estado_id');
    }

    public function centroCosto()
    {
        return $this->belongsTo(CentroCosto::class, 'centro_costo_id');
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function cotizaciones()
    {
        return $this->hasMany(CotizacionCompra::class, 'solicitud_compra_id');
    }

    public function getFechaRequeridaAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFechaRequeridaAttribute($value)
    {
        $this->attributes['fecha_requerida'] = $value
            ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d')
            : null;
    }
}
