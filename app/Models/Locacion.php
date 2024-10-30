<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locacion extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'locacions';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nombre',
        'area_id',
        'cantidad_personal',
        'estado_id',
        'locacion_padre_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function locacionTurnosFrecuencia()
    {
        return $this->hasMany(TurnosFrecuencium::class, 'locacion_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function locacion_padre()
    {
        return $this->belongsTo(self::class, 'locacion_padre_id');
    }
}
