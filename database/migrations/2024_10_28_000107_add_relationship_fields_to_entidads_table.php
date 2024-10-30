<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToEntidadsTable extends Migration
{
    public function up()
    {
        Schema::table('entidads', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_id')->nullable();
            $table->foreign('tipo_id', 'tipo_fk_10224759')->references('id')->on('tipos');
        });
    }
}
