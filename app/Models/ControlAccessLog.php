<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlAccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'personal_id',
        'nombre',
        'departamento',
        'primera_entrada',
        'ultima_salida',
        'pin',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'primera_entrada' => 'datetime',
        'ultima_salida' => 'datetime',
    ];
}
