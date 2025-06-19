<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseContacto extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'base_contactos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const TIPO_TRANSPORTE_SELECT = [
        'M' => 'Marítimo',
        'A' => 'Aéreo',
        'T' => 'Terrestre',
    ];

    protected $fillable = [
        'cliente_id',
        'tipo_transporte',
        'rut_recibidor',
        'tipoydestino',
        'direccion',
        'contacto',
        'telefono',
        'fax',
        'email',
        'notify',
        'consignee',
        'codigo',
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
        return $this->belongsTo(BaseRecibidor::class, 'cliente_id');
    }
}
