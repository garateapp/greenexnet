<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocacionsTable extends Migration
{
    public function up()
    {
        Schema::create('locacions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre')->nullable();
            $table->integer('cantidad_personal');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
