<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PuertoCorreo extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'puerto_correos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'puerto_embarque_id',
        'emails',
        'pais_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function puerto_embarque()
    {
        return $this->belongsTo(Puerto::class, 'puerto_embarque_id');
    }

    public function pais()
    {
        return $this->belongsTo(Country::class, 'pais_id');
    }
}
