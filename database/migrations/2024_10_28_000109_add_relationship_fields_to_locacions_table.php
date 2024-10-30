<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToLocacionsTable extends Migration
{
    public function up()
    {
        Schema::table('locacions', function (Blueprint $table) {
            $table->unsignedBigInteger('area_id')->nullable();
            $table->foreign('area_id', 'area_fk_10224774')->references('id')->on('areas');
            $table->unsignedBigInteger('estado_id')->nullable();
            $table->foreign('estado_id', 'estado_fk_10224776')->references('id')->on('estados');
            $table->unsignedBigInteger('locacion_padre_id')->nullable();
            $table->foreign('locacion_padre_id', 'locacion_padre_fk_10224780')->references('id')->on('locacions');
        });
    }
}
