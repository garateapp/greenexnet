<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostosOrigenTable extends Migration
{
    public function up(): void
    {
        Schema::create('costos_origen', function (Blueprint $table) {
            $table->id();
            $table->string('n_embarque');
            $table->string('cliente')->nullable();
            $table->string('embarcadora')->nullable();
            $table->string('agencia_aduana')->nullable();
            $table->string('puerto_embarque')->nullable();
            $table->string('planta_carga')->nullable();
            $table->string('empresa_transportista')->nullable();
            $table->string('especies')->nullable();
            $table->integer('cajas')->nullable();
            $table->string('naviera')->nullable();
            $table->string('nave')->nullable();
            $table->string('booking')->nullable();
            $table->string('n_contenedor')->nullable();
            $table->string('n_bill_of_lading')->nullable();
            $table->string('tipo_flete')->nullable();
            $table->string('puerto_destino')->nullable();
            $table->decimal('flete_collect', 10, 2)->nullable();
            $table->decimal('gastos_chinos_bl', 10, 2)->nullable();
            $table->decimal('usd_pagado_greenex', 10, 2)->nullable();
            $table->string('motivo_pago_usd')->nullable();

            // Campos de facturas y sus campos correspondientes
            $table->string('n_factura_consolidacion_safe_cargo')->nullable();
            $table->string('consolidacion_safe_cargo')->nullable();
            $table->string('n_factura_citacion_falso')->nullable();
            $table->string('citacion_falso')->nullable();
            $table->string('n_factura_materiales_consolidacion')->nullable();
            $table->string('materiales_consolidacion')->nullable();
            $table->string('n_factura_flete_terrestre_underlung')->nullable();
            $table->string('flete_terrestre_underlung')->nullable();
            $table->string('n_factura_falso_flete')->nullable();
            $table->string('falso_flete')->nullable();
            $table->string('n_factura_interplanta')->nullable();
            $table->string('interplanta')->nullable();
            $table->string('n_factura_sobreestadia')->nullable();
            $table->string('sobreestadia')->nullable();
            $table->string('n_factura_porteo')->nullable();
            $table->string('porteo')->nullable();
            $table->string('n_factura_almacenaje')->nullable();
            $table->string('almacenaje')->nullable();
            $table->string('n_factura_retiro_cruzado')->nullable();
            $table->string('retiro_cruzado')->nullable();
            $table->string('n_factura_otros_costos_carga')->nullable();
            $table->string('otros_costos_carga')->nullable();
            $table->string('n_factura_agenciamiento')->nullable();
            $table->string('agenciamiento')->nullable();
            $table->string('n_factura_honorarios_aga')->nullable();
            $table->string('honorarios_aga')->nullable();
            $table->string('n_factura_certificado_origen')->nullable();
            $table->string('certificado_origen')->nullable();
            $table->string('n_factura_diferencias_co')->nullable();
            $table->string('diferencias_co')->nullable();
            $table->string('n_factura_seguridad_portuaria')->nullable();
            $table->string('seguridad_portuaria')->nullable();
            $table->string('n_factura_gate_out')->nullable();
            $table->string('gate_out')->nullable();
            $table->string('n_factura_servicio_retiro_express')->nullable();
            $table->string('servicio_retiro_express')->nullable();
            $table->string('n_factura_gate_in')->nullable();
            $table->string('gate_in')->nullable();
            $table->string('n_factura_gate_set')->nullable();
            $table->string('gate_set')->nullable();
            $table->string('n_factura_late_arrival')->nullable();
            $table->string('late_arrival')->nullable();
            $table->string('n_factura_early_arrival')->nullable();
            $table->string('early_arrival')->nullable();
            $table->string('n_factura_emision_destino')->nullable();
            $table->string('emision_destino')->nullable();
            $table->string('n_factura_servicio_detention')->nullable();
            $table->string('servicio_detention')->nullable();
            $table->string('n_factura_doc_fee')->nullable();
            $table->string('doc_fee')->nullable();
            $table->string('n_factura_control_sello')->nullable();
            $table->string('control_sello')->nullable();
            $table->string('n_factura_almacenamiento')->nullable();
            $table->string('almacenamiento')->nullable();
            $table->string('n_factura_pago_tardio')->nullable();
            $table->string('pago_tardio')->nullable();
            $table->string('n_factura_otros_costos_embarque')->nullable();
            $table->string('otros_costos_embarque')->nullable();
            $table->string('n_factura_1_matriz_fuera_plazo')->nullable();
            $table->string('1_matriz_fuera_plazo')->nullable();
            $table->string('n_factura_2_correccion_matriz')->nullable();
            $table->string('2_correccion_matriz')->nullable();
            $table->string('n_factura_3_correccion_matriz')->nullable();
            $table->string('3_correccion_matriz')->nullable();
            $table->string('n_factura_4_correccion_bl')->nullable();
            $table->string('4_correccion_bl')->nullable();
            $table->string('n_factura_5_correccion_bl')->nullable();
            $table->string('5_correccion_bl')->nullable();
            $table->string('n_factura_reemision_c_o')->nullable();
            $table->string('reemision_c_o')->nullable();
            $table->string('n_factura_reemision_fitosanitario')->nullable();
            $table->string('reemision_fitosanitario')->nullable();
            $table->string('n_factura_otros_documental')->nullable();
            $table->string('otros_documental')->nullable();

            $table->decimal('costos_usd_pagado_greenex', 10, 2);
            $table->decimal('costos_carga', 10, 2);
            $table->decimal('costos_embarque', 10, 2);
            $table->decimal('costo_reemision_doc', 10, 2);
            $table->decimal('total_general', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('embarques');
    }
};
