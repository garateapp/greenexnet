<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiqCxCabecerasTable extends Migration
{
    public function up()
    {
        Schema::create('liq_cx_cabeceras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('instructivo');
            $table->date('eta')->nullable();
            $table->float('tasa_intercambio', 15, 2);
            $table->float('total_costo', 15, 2);
            $table->float('total_bruto', 15, 2)->nullable();
            $table->float('total_neto', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
