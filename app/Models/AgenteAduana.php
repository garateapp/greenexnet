<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgenteAduana extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'agente_aduanas';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nombre',
        'rut',
        'codigo',
        'direccion',
        'email',
        'telefono',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function agenteAduanaInstructivoEmbarques()
    {
        return $this->hasMany(InstructivoEmbarque::class, 'agente_aduana_id', 'id');
    }
}
