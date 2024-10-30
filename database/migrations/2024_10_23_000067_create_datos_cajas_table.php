<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatosCajasTable extends Migration
{
    public function up()
    {
        Schema::create('datos_cajas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('proceso');
            $table->date('fecha_produccion');
            $table->string('turno');
            $table->string('cod_linea');
            $table->string('cat')->nullable();
            $table->string('variedad_real')->nullable();
            $table->string('variedad_timbrada')->nullable();
            $table->string('salida')->nullable();
            $table->string('marca')->nullable();
            $table->string('productor_real')->nullable();
            $table->string('especie')->nullable();
            $table->string('cod_caja')->nullable();
            $table->string('cod_confeccion')->nullable();
            $table->string('calibre_timbrado')->nullable();
            $table->string('peso_timbrado')->nullable();
            $table->string('lote')->nullable();
            $table->string('nuevo_lote')->nullable();
            $table->string('codigo_qr')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
