<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelDato extends Model
{
    use HasFactory;

    protected $table = 'excel_datos';

    protected $fillable = [
        'archivo_id',
        'identificador_interno',
        'master_id',
        'modulo',
        'cliente',
        'nombre_archivo',
        'instructivo',
        'tasa',
        'datos',
    ];

    protected $casts = [
        'datos' => 'array',
    ];
}
