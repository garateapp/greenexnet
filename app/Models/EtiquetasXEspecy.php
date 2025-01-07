<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EtiquetasXEspecy extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'etiquetas_x_especies';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'especie_id',
        'etiqueta_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function especie()
    {
        return $this->belongsTo(Especy::class, 'especie_id');
    }

    public function etiqueta()
    {
        return $this->belongsTo(Etiquetum::class, 'etiqueta_id');
    }
}
