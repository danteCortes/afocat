<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaAccidentes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afoc_accidentes', function (Blueprint $table) {
          $table->increments('id');
          $table->string('vehiculo_placa', 10);
          $table->foreign('vehiculo_placa')->references('placa')->on('afoc_vehiculos')->onUpdate('cascade')->onDelete('cascade');
          $table->integer('distrito_id')->unsigned();
          $table->foreign('distrito_id')->references('id')->on('afoc_distritos')->onUpdate('cascade')->onDelete('cascade');
          $table->date('ocurrencia')->nullable();
          $table->date('notificacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('afoc_accidentes');
    }
}
