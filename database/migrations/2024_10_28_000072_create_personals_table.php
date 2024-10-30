<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalsTable extends Migration
{
    public function up()
    {
        Schema::create('personals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('codigo')->nullable();
            $table->string('rut');
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
