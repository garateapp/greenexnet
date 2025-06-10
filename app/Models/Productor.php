<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Productor extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'productors';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'rut',
        'nombre',
        'grupo_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function productorValorFletes()
    {
        return $this->hasMany(ValorFlete::class, 'productor_id', 'id');
    }

    public function productorValorEnvases()
    {
        return $this->hasMany(ValorEnvase::class, 'productor_id', 'id');
    }

    public function productorAnticipos()
    {
        return $this->hasMany(Anticipo::class, 'productor_id', 'id');
    }

    public function productorRecepcions()
    {
        return $this->hasMany(Recepcion::class, 'productor_id', 'id');
    }

    public function productorProcesos()
    {
        return $this->hasMany(Proceso::class, 'productor_id', 'id');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }
}
