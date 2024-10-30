<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrecuenciaTurnosTable extends Migration
{
    public function up()
    {
        Schema::create('frecuencia_turnos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dia');
            $table->string('nombre')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
