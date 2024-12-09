<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetasClienteComex extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'metas_cliente_comexes';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'clientecomex_id',
        'anno',
        'semana',
        'cantidad',
        'observaciones',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function clientecomex()
    {
        return $this->belongsTo(ClientesComex::class, 'clientecomex_id');
    }
}
