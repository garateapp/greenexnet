<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoliticaCotizacion extends Model
{
    use HasFactory;

    public $table = 'politica_cotizaciones';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'monto_min',
        'monto_max',
        'cotizaciones_requeridas',
        'activo',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function scopeForAmount($query, $amount)
    {
        return $query
            ->where('monto_min', '<=', $amount)
            ->where(function ($q) use ($amount) {
                $q->whereNull('monto_max')
                    ->orWhere('monto_max', '>=', $amount);
            })
            ->orderBy('monto_min', 'desc');
    }
}
