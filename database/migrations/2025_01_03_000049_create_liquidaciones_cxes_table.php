<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiquidacionesCxesTable extends Migration
{
    public function up()
    {
        Schema::create('liquidaciones_cxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('contenedor')->nullable();
            $table->date('eta')->nullable();
            $table->string('pallet')->nullable();
            $table->string('calibre')->nullable();
            $table->integer('cantidad');
            $table->date('fecha_venta')->nullable();
            $table->integer('ventas')->nullable();
            $table->float('precio_unitario', 15, 2)->nullable();
            $table->float('monto_rmb', 15, 2)->nullable();
            $table->longText('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
