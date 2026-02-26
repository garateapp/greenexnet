<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialProducto extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'material_productos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'embalaje_id',
        'material_id',
        'unidadxcaja',
        'unidadxpallet',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function embalaje()
    {
        return $this->belongsTo(Embalaje::class, 'embalaje_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
