<?php

namespace App\Models;

use App\Models\Personal;
use App\Models\TurnosFrecuencium;
use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models;


class Asistencia extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    public $table = 'asistencia';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $fillable = [
        'turnofrecuencia_id',
        'personal_id',
        'created_at',
        'updated_at',
        'deleted_at',

    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    protected function turno_has_personal()
    {
        return $this->belongsTo(TurnosFrecuencium::class, 'turnofrecuencia_id');
    }
    protected function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }
}
