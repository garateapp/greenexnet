<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingLineDetention extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'line',
        'event_date',
        'activation_date',
        'duration_minutes',
        'motivo',
        'causa',
        'notas',
        'estado',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'activation_date' => 'datetime',
        'duration_minutes' => 'integer',
    ];
}
