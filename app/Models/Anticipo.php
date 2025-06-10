<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anticipo extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'anticipos';

    protected $dates = [
        'fecha_documento',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'productor_id',
        'valor',
        'num_docto',
        'fecha_documento',
        'tipo_cambio_id',
        'especie_id',
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

    public function getFechaDocumentoAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFechaDocumentoAttribute($value)
    {
        $this->attributes['fecha_documento'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }


    public function especie()
    {
        return $this->belongsTo(Especy::class, 'especie_id');
    }
}
