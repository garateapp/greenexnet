<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recepcion extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'recepcions';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'productor_id',
        'variedad',
        'total_kilos',
        'created_at',
        'updated_at',
        'deleted_at',
        'temporada',
        'especie_id',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function productor()
    {
        return $this->belongsTo(Productor::class, 'productor_id');
    }
}
