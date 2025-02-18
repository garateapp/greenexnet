<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostosOrigenAereoTable extends Migration
{
    public function up()
    {
        Schema::create('costos_origen_aereo', function (Blueprint $table) {
            $table->id();
            $table->string('n_embarque'); // aéreo, Marítimo
            $table->string('cliente'); // aéreo, Marítimo
            $table->string('freightforwarded'); // aéreo es Freight Forwarded
            $table->string('especie');
            $table->integer('cajas');
            $table->integer('n_pallets');
            $table->integer('cantidad_camiones');
            $table->string('empresa_transportista');
            $table->string('aerop_destino');
            $table->string('aerolinea');
            $table->string('awb');
            $table->string('bodega');
            $table->string('tipo_flete');
            $table->string('tipo_vuelo');
            $table->string('n_factura_termografo');
            $table->decimal('termografo_usd', 10, 2);
            $table->string('n_factura_flete_aeropuerto_clp');
            $table->decimal('n_factura_awb_usd', 10, 2);
            $table->decimal('awb_usd', 10, 2);
            $table->string('n_factura_awb_clp');
            $table->decimal('awb_clp', 10, 2);
            $table->string('n_factura_honorarios_clp');
            $table->decimal('honorarios_clp', 10, 2);
            $table->string('n_factura_agenciamiento_clp');
            $table->decimal('agenciamiento_clp', 10, 2);
            $table->string('n_factura_cert_origen_clp');
            $table->decimal('cert_origen_clp', 10, 2);
            $table->string('n_factura_gastos_bodega_clp');
            $table->decimal('gastos_bodega_clp', 10, 2);
            $table->string('n_factura_otros_costos_clp');
            $table->decimal('otros_costos_clp', 10, 2);
            $table->string('n_factura_reemison_clp');
            $table->decimal('reemison_clp', 10, 2);
            $table->string('n_factura_reemision_fito_clp');
            $table->decimal('reemision_fito_clp', 10, 2);
            $table->string('n_factura_sag_sps_clp');
            $table->decimal('sag_sps_clp', 10, 2);
            $table->string('n_factura_sag_otros_costos_clp');
            $table->decimal('sag_otros_costos_clp', 10, 2);
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('costos_origen_aereo');
    }
}
