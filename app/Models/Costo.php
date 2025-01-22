<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Costo extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'costos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nombre',
        'valor_x_defecto',
        'categoria',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const CATEGORIA_SELECT = [
        'Comisión'             => 'Comisión',
        'Entrada Mercado'      => 'Entrada Mercado',
        'Costo Mercado'       => 'Costo Mercado',
        'Otros Costos Destino' => 'Otros Costos Destino',
        'Flete'                => 'Flete',
        'Costo Logístico'      => 'Costo Logístico',
        'Flete Internacional'  => 'Flete Internacional',
        'Flete Domestico'      => 'Flete Domestico',
        'Impuestos'            => 'Impuestos',
        'Ajuste Impuesto'     => 'Ajuste Impuesto',
        'Otros Impuestos'     => 'Otros Impuestos',
        'Otros Ingresos' => 'Otros Ingresos',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
