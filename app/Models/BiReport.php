<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'icon_class',
    ];

    /**
     * The users that belong to the BiReport
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'bi_report_user', 'bi_report_id', 'user_id');
    }
}
