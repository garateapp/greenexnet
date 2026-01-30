<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlAccessPresence extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_id',
        'nombre',
        'departamento',
        'last_entry_at',
        'last_exit_at',
        'last_event_id_pair',
        'pin',
    ];

    protected $casts = [
        'last_entry_at' => 'datetime',
        'last_exit_at' => 'datetime',
    ];
}
