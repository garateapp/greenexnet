<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proceso extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'procesos';

    protected $dates = [
        'fecha_proceso',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'productor_id',
        'fecha_proceso',
        'variedad',
        'categoria',
        'etiqueta',
        'calibre',
        'color',
        'total_kilos',
        'etd_week',
        'eta_week',
        'resultado_kilo',
        'resultado_total',
        'precio_comercial',
        'total_comercial',
        'costo_comercial',
        'created_at',
        'updated_at',
        'deleted_at',
        'norma',
        'temporada',
        'especie_id',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function productor()
    {
        return $this->belongsTo(Productor::class, 'productor_id');
    }
public function especie()
    {
        return $this->belongsTo(Especy::class, 'especie_id');
    }
    public function getFechaProcesoAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFechaProcesoAttribute($value)
    {
        $this->attributes['fecha_proceso'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
}
