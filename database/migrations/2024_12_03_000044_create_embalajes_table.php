<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmbalajesTable extends Migration
{
    public function up()
    {
        Schema::create('embalajes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('c_embalaje');
            $table->integer('kgxcaja');
            $table->string('cajaxpallet');
            $table->float('altura_pallet', 15, 2)->nullable();
            $table->string('tipo_embarque')->nullable();
            $table->string('caja');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
