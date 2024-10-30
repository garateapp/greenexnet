<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FrecuenciaTurno extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'frecuencia_turnos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'dia',
        'turno_id',
        'nombre',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const DIA_SELECT = [
        '1' => 'Lunes',
        '2' => 'Martes',
        '3' => 'Miércoles',
        '4' => 'Jueves',
        '5' => 'Viernes',
        '6' => 'Sábado',
        '7' => 'Domingo',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function frecuenciaTurnosFrecuencia()
    {
        return $this->hasMany(TurnosFrecuencium::class, 'frecuencia_id', 'id');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id');
    }
}
