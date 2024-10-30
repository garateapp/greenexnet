<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurnosTable extends Migration
{
    public function up()
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
