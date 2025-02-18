<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedorsTable extends Migration
{
    public function up()
    {
        Schema::create('proveedors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rut');
            $table->string('cobro');
            $table->string('nombre_simple')->nullable();
            $table->string('razon_social');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
