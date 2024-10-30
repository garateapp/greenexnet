<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntidadsTable extends Migration
{
    public function up()
    {
        Schema::create('entidads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('rut');
            $table->string('direccion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
