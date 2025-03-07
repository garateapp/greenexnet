<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HandPack extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'hand_packs';

    protected $dates = [
        'fecha',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'rut',
        'fecha',
        'embalaje',
        'guuid',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const EMBALAJE_SELECT = [
        '9'   => '9',
        '7'   => '7',
        '6'   => '6',
        '5'   => '5',
        '4.4' => '4,4',
        '4'   => '4',


    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getFechaAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }


}
