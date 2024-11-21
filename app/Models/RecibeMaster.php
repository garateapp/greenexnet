<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecibeMaster extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'recibe_masters';

    public static $searchable = [
        'especie',
    ];

    public const ESTIBA_CAMION_SELECT = [
        '1' => 'CUMPLE',
        '0' => 'NO CUMPLE',
    ];

    public const ESTADO_SELECT = [
        'NUEVO'     => 'NUEVO',
        'REEMPLAZO' => 'REEMPLAZO',
    ];

    public const ESPONJAS_CLORADAS_SELECT = [
        '0' => 'NO CUMPLE',
        '1' => 'CUMPLE',
    ];

    protected $dates = [
        'fecha_recepcion',
        'fecha_cosecha',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'especie',
        'exportador',
        'partida',
        'estado',
        'cod_central',
        'cod_productor',
        'nro_guia_despacho',
        'fecha_recepcion',
        'fecha_cosecha',
        'cod_variedad',
        'estiba_camion',
        'esponjas_cloradas',
        'nro_bandeja',
        'hora_llegada',
        'kilo_muestra',
        'kilo_neto',
        'temp_ingreso',
        'temp_salida',
        'lote',
        'huerto',
        'hidro',
        'fecha_envio',
        'respuesta_envio',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    // public function getFechaRecepcionAttribute($value)
    // {
    //     return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    // }

    // public function setFechaRecepcionAttribute($value)
    // {
    //     //$this->attributes['fecha_recepcion'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    // }
}
