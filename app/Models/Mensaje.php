<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $embarque;
    const ENVIADO =1;
    const LEIDO =2;
}
