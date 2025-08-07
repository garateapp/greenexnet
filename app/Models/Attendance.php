<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_id',
        'location',
        'timestamp',
    ];

    protected $dates = [
        'timestamp',
    ];

    public function personal()
    {
        return $this->belongsTo(personal::class);
    }
}
