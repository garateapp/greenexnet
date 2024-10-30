<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTurnosFrecuenciaTable extends Migration
{
    public function up()
    {
        Schema::table('turnos_frecuencia', function (Blueprint $table) {
            $table->unsignedBigInteger('frecuencia_id')->nullable();
            $table->foreign('frecuencia_id', 'frecuencia_fk_10224815')->references('id')->on('frecuencia_turnos');
            $table->unsignedBigInteger('locacion_id')->nullable();
            $table->foreign('locacion_id', 'locacion_fk_10224816')->references('id')->on('locacions');
        });
    }
}
