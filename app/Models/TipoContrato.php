<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoContrato extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'nombre',
        'estado_id',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
