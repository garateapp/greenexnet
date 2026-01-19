<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroCosto extends Model
{
    use HasFactory;

    public $table = 'centro_costos';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'entidad_id',
        'id_centrocosto',
        'c_centrocosto',
        'n_centrocosto',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function entidad()
    {
        return $this->belongsTo(Entidad::class, 'entidad_id');
    }
}
