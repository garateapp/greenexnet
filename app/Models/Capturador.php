<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Capturador extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'capturadors';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nombre',
        'cliente_id',
        'modulo_id',
        'funcion_id',
        'activo',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function cliente()
    {
        return $this->belongsTo(ClientesComex::class, 'cliente_id');
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    public function funcion()
    {
        return $this->belongsTo(Funcione::class, 'funcion_id');
    }
    public function estructuras()
{
    return $this->hasMany(CapturadorEstructura::class, 'capturador_id');
}
}
