<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorreoalsoAir extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'correoalso_airs';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const TRANSPORTE_SELECT = [
        'Air'  => 'Air',
        'Sea'  => 'Sea',
        'Land' => 'Land',
    ];

    protected $fillable = [
        'cliente_id',
        'puerto_requerido',
        'correos',
        'also_notify',
        'codigo',
        'transporte',
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
