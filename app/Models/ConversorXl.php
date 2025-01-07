<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConversorXl extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'conversor_xls';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'cliente_id',
        'modulo_id',
        'tipo_id',
        'propiedad',
        'coordenada',
        'orden',
        'visible',
        'formula',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function cliente()
    {
        return $this->belongsTo(ClientesComex::class, 'cliente_id');
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    public function tipo()
    {
        return $this->belongsTo(TiposSeccionConversor::class, 'tipo_id');
    }
}
