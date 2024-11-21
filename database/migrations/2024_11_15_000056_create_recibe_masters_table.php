<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecibeMastersTable extends Migration
{
    public function up()
    {
        Schema::create('recibe_masters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('especie');
            $table->integer('exportador');
            $table->integer('partida');
            $table->string('estado');
            $table->integer('cod_central');
            $table->string('cod_productor');
            $table->string('nro_guia_despacho');
            $table->date('fecha_recepcion');
            $table->date('fecha_cosecha');
            $table->integer('cod_variedad');
            $table->string('estiba_camion');
            $table->string('esponjas_cloradas');
            $table->integer('nro_bandeja');
            $table->time('hora_llegada');
            $table->float('kilo_muestra', 15, 2);
            $table->float('kilo_neto', 15, 2);
            $table->float('temp_ingreso', 15, 2);
            $table->float('temp_salida', 15, 2);
            $table->string('lote');
            $table->string('huerto');
            $table->string('hidro');
            $table->string('fecha_envio')->nullable();
            $table->string('respuesta_envio')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
