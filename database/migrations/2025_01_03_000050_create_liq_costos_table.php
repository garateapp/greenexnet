<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiqCostosTable extends Migration
{
    public function up()
    {
        Schema::create('liq_costos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre_costo');
            $table->float('valor', 15, 2)->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
