<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanificacionPersonal extends Model
{
    use HasFactory;
    public $table="planificador_personals";
    protected $fillable = [
        'fecha',
        'locacion_id',
        'turno_id',
        'cantidad_personal_planificada',
    ];

    public function locacion()
    {
        return $this->belongsTo(Locacion::class);
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }
}
