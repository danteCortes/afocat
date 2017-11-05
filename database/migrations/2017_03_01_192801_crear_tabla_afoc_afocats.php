<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaAfocAfocats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afoc_afocats', function (Blueprint $table) {
            $table->string('numero', 10);
            $table->primary('numero');
            $table->string('vehiculo_placa', 10);
            $table->foreign('vehiculo_placa')->references('placa')->on('afoc_vehiculos')->onUpdate('cascade')->onDelete('cascade');
            $table->date('inicio_contrato');
            $table->date('fin_contrato');
            $table->date('inicio_certificado');
            $table->date('fin_certificado');
            $table->time('hora');
            $table->float('monto');
            $table->float('extraordinario')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('afoc_afocats');
    }
}
