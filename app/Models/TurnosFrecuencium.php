<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TurnosFrecuencium extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'turnos_frecuencia';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'frecuencia_id',
        'locacion_id',
        'nombre',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function frecuencia()
    {
        return $this->belongsTo(FrecuenciaTurno::class, 'frecuencia_id');
    }

    public function locacion()
    {
        return $this->belongsTo(Locacion::class, 'locacion_id');
    }
}
