<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asistencium extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'asistencia';

    protected $dates = [
        'fecha_hora',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'locacion_id',
        'turno_id',
        'personal_id',
        'fecha_hora',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function locacion()
    {
        return $this->belongsTo(Locacion::class, 'locacion_id');
    }

    public function turno()
    {
        return $this->belongsTo(FrecuenciaTurno::class, 'turno_id');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function getFechaHoraAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setFechaHoraAttribute($value)
    {
        $this->attributes['fecha_hora'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }
}
