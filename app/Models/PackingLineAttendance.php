<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingLineAttendance extends Model
{
    use HasFactory;

    protected $table = 'packing_line_attendances';

    protected $fillable = [
        'personal_id',
        'location_id',
        'fecha_hora_salida',
        'fecha_hora_entrada',
        'minutos',
    ];

    protected $casts = [
        'fecha_hora_salida' => 'datetime',
        'fecha_hora_entrada' => 'datetime',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function setMinutesDiff(): void
    {
        if ($this->fecha_hora_salida && $this->fecha_hora_entrada) {
            $this->minutos = $this->fecha_hora_salida->diffInMinutes($this->fecha_hora_entrada);
        }
    }
}
