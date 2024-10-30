<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAsistenciaTable extends Migration
{
    public function up()
    {
        Schema::table('asistencia', function (Blueprint $table) {
            $table->unsignedBigInteger('turno_id')->nullable();
            $table->foreign('turno_id', 'turno_fk_10224822')->references('id')->on('turnos_frecuencia');
            $table->unsignedBigInteger('personal_id')->nullable();
            $table->foreign('personal_id', 'personal_fk_10224823')->references('id')->on('personals');
        });
    }
}
