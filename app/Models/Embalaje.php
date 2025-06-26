<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Embalaje extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'embalajes';

    public static $searchable = [
       'c_embalaje',
        'kgxcaja',
        'cajaxpallet',
        'altura_pallet',
        'tipo_embarque',
        'caja',
        'cajasxlinea',
        'lineasxpallet',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const TIPO_EMBARQUE_SELECT = [
        'MARITIMO' => 'MARíTIMO',
        'AEREO'    => 'AÉREO',
        'TERRESTRE' => 'TERRESTRE',
    ];

    protected $fillable = [
        'c_embalaje',
        'kgxcaja',
        'cajaxpallet',
        'altura_pallet',
        'tipo_embarque',
        'caja',
        'cajasxlinea',
        'lineasxpallet',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function embalajeMaterialProductos()
    {
        return $this->hasMany(MaterialProducto::class, 'embalaje_id', 'id');
    }
}
