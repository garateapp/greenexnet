<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tipo extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'tipos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'sigla',
        'nombre',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /*************  ✨ Codeium Command ⭐  *************/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * @description Relacion con Entidad. Un Tipo puede tener varias Entidades asociadas.
     */
    /******  d13c1c91-ab42-4230-a347-006b31f728ed  *******/
    public function tipoEntidads()
    {
        return $this->hasMany(Entidad::class, 'tipo_id', 'id');
    }
}
