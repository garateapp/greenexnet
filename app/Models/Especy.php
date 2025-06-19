<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Especy extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'especies';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'codigo',
        'nombre',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function especieVariedads()
    {
        return $this->hasMany(Variedad::class, 'especie_id', 'id');
    }

    public function especieEtiquetasXEspecies()
    {
        return $this->hasMany(EtiquetasXEspecy::class, 'especie_id', 'id');
    }

    public function especieAnticipos()
    {
        return $this->hasMany(Anticipo::class, 'especie_id', 'id');
    }
}
