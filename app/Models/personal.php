<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Personal extends Model
{

    use SoftDeletes, Auditable, HasFactory;

    public $table = 'personals';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',

    ];

    protected $fillable = [
        'nombre',
        'codigo',
        'rut',
        'email',
        'telefono',
        'cargo_id',
        'estado_id',
        'entidad_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'foto',
    ];

    /*************  ✨ Codeium Command ⭐  *************/
    /**
     * Serialize the date into a string format.
     *
     * @param DateTimeInterface $date The date to serialize.
     * @return string The formatted date string.
     */
    /******  8e2522cf-52cf-4e1b-91f9-de82a8fbacd9  *******/
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function entidad()
    {
        return $this->belongsTo(Entidad::class, 'entidad_id');
    }
}
