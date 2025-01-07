<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiqCosto extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'liq_costos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'liq_cabecera_id',
        'nombre_costo',
        'valor',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function liq_cabecera()
    {
        return $this->belongsTo(LiqCxCabecera::class, 'liq_cabecera_id');
    }
}
