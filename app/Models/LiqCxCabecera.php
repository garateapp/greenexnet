<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiqCxCabecera extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'liq_cx_cabeceras';

    protected $dates = [
        'eta',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'instructivo',
        'cliente_id',
        'nave_id',
        'eta',
        'tasa_intercambio',
        'total_costo',
        'total_bruto',
        'total_neto',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function cliente()
    {
        return $this->belongsTo(ClientesComex::class, 'cliente_id');
    }

    public function nave()
    {
        return $this->belongsTo(Nafe::class, 'nave_id');
    }

    // public function getEtaAttribute($value)
    // {
    //     return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    // }

    // public function setEtaAttribute($value)
    // {
    //     $this->attributes['eta'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    // }
}
