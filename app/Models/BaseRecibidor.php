<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseRecibidor extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'base_recibidors';

    public const ESTADO_RADIO = [
        'Activo' => 'Activo',
        'Inactivo' => 'Inactivo',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'cliente_id',
        'codigo',
        'rut_sistema',
        'estado',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function clienteCorreoalsoAirs()
    {
        return $this->hasMany(CorreoalsoAir::class, 'cliente_id', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo(ClientesComex::class, 'cliente_id');
    }
}
