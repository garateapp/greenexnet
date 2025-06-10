<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValorDolar extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'valor_dolars';

    protected $dates = [
        'fecha_cambio',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'fecha_cambio',
        'valor',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function valorDolarValorFletes()
    {
        return $this->hasMany(ValorFlete::class, 'valor_dolar_id', 'id');
    }

    public function tipoCambioAnticipos()
    {
        return $this->hasMany(Anticipo::class, 'tipo_cambio_id', 'id');
    }

    public function getFechaCambioAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFechaCambioAttribute($value)
    {
        $this->attributes['fecha_cambio'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
}
