<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'cargos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nombre',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /*************  ✨ Codeium Command ⭐  *************/
    /**
     * Serialize the given date into a string format.
     *
     * @param DateTimeInterface $date The date to serialize.
     * @return string The formatted date string.
     */
    /******  d2beed54-b323-4cfa-a638-c6ff9a602430  *******/
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function cargoPersonals()
    {
        return $this->hasMany(Personal::class, 'cargo_id', 'id');
    }
}
