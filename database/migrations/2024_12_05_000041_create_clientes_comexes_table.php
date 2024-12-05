<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesComexesTable extends Migration
{
    public function up()
    {
        Schema::create('clientes_comexes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo_cliente');
            $table->string('nombre_empresa');
            $table->string('nombre_fantasia');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
