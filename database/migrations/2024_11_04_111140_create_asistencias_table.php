<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsistenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistencia', function (Blueprint $table) {
            // 'turnofrecuencia_id',
            // 'personal_id',
            // 'created_at',
            // 'updated_at',
            // 'deleted_at',
            // 'locacion_id',
            // 'fecha_hora',
            $table->id();
            $table->datetime('fecha_hora');
            $table->foreignId('turnofrecuencia_id')->constrained('turnos_frecuencia');
            $table->foreignId('personal_id')->constrained('personal');
            $table->foreignId('locacion_id')->constrained('locacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asistencia');
    }
}
