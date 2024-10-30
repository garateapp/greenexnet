<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPersonalsTable extends Migration
{
    public function up()
    {
        Schema::table('personals', function (Blueprint $table) {
            $table->unsignedBigInteger('cargo_id')->nullable();
            $table->foreign('cargo_id', 'cargo_fk_10224808')->references('id')->on('cargos');
            $table->unsignedBigInteger('estado_id')->nullable();
            $table->foreign('estado_id', 'estado_fk_10224809')->references('id')->on('estados');
            $table->unsignedBigInteger('entidad_id')->nullable();
            $table->foreign('entidad_id', 'entidad_fk_10224810')->references('id')->on('entidads');
        });
    }
}
