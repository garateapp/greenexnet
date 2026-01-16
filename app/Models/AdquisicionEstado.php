<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class AdquisicionEstado extends Model
{
    use HasFactory;

    public $table = 'adquisicion_estados';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'nombre',
        'slug',
        'orden',
        'color',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function solicitudes()
    {
        return $this->hasMany(SolicitudCompra::class, 'adquisicion_estado_id');
    }
}
