<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'materials';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const UNIDAD_SELECT = [
        'UNIDAD' => 'UNIDAD',
        'KILOS'  => 'KILOS',
        'METRO' => 'METRO',
        'LITRO' => 'LITRO',
        'CAJA' => 'CAJA',
        'PAQUETE' => 'PAQUETE',
        'BOLSA' => 'BOLSA',
        'PULGADA' => 'PULGADA',
        'YARDAS' => 'YARDAS',
        'TONELADA' => 'TONELADA',
        'GRAMO' => 'GRAMO',
        'MILILITRO' => 'MILILITRO',
        'CENTIMETRO' => 'CENTIMETRO',
        'LATA' => 'LATA',
        'BOTELLA' => 'BOTELLA',
    ];

    protected $fillable = [
        'codigo',
        'nombre',
        'unidad',
        'costo_ult_oc',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function materialMaterialProductos()
    {
        return $this->hasMany(MaterialProducto::class, 'material_id', 'id');
    }
}
