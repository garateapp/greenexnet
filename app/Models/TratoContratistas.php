<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Personal;

class TratoContratistas extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'trato_contratistas';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'personal_id',
        'fecha',
        'cantidad',
        'monto_a_pagar',
        'cant_x_factor',
        'factor_a_pagar',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }
}
