<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entidad extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'entidads';

    public static $searchable = [
        'nombre',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nombre',
        'rut',
        'tipo_id',
        'direccion',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function entidadAreas()
    {
        return $this->hasMany(Area::class, 'entidad_id', 'id');
    }

    public function entidadPersonals()
    {
        return $this->hasMany(Personal::class, 'entidad_id', 'id');
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'tipo_id');
    }
}
