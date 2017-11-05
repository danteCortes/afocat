<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afoc_vehiculos', function (Blueprint $table) {
            $table->string('placa', 10);
            $table->primary('placa');
            $table->string('persona_dni', 8)->nullable();
            $table->foreign('persona_dni')->references('dni')->on('afoc_personas')->onUpdate('cascade')->onDelete('cascade');
            $table->string('empresa_ruc', 11)->nullable();
            $table->foreign('empresa_ruc')->references('ruc')->on('afoc_empresas')->onUpdate('cascade')->onDelete('cascade');
            $table->string('marca');
            $table->string('modelo');
            $table->string('color')->nullable();
            $table->string('clase');
            $table->string('categoria')->nullable();
            $table->integer('asientos')->nullable();
            $table->integer('anio');
            $table->string('uso');
            $table->string('serie')->nullable();
            $table->string('motor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehiculos');
    }
}
