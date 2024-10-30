<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DatosCaja extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'datos_cajas';

    public static $searchable = [
        'proceso',
        'fecha_produccion',
        'turno',
    ];

    protected $dates = [
        'fecha_produccion',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'proceso',
        'fecha_produccion',
        'turno',
        'cod_linea',
        'cat',
        'variedad_real',
        'variedad_timbrada',
        'salida',
        'marca',
        'productor_real',
        'especie',
        'cod_caja',
        'cod_confeccion',
        'calibre_timbrado',
        'peso_timbrado',
        'lote',
        'nuevo_lote',
        'codigo_qr',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getFechaProduccionAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFechaProduccionAttribute($value)
    {
        $this->attributes['fecha_produccion'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
}
