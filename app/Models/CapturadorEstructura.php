<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CapturadorEstructura extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'capturador_estructuras';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'capturador_id',
        'propiedad',
        'coordenada',
        'orden',
        'visible',
        'formula',
        'tipos_seccion_conversors_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function capturador()
    {
        return $this->belongsTo(Capturador::class, 'capturador_id');
    }
}
