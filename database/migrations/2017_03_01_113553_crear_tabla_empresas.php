<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaEmpresas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afoc_empresas', function (Blueprint $table) {
            $table->string('ruc', 11);
            $table->primary('ruc');
            $table->string('nombre');
            $table->string('direccion');
            $table->string('provincia');
            $table->string('departamento');
            $table->string('representante')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('nacimiento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('afoc_empresas');
    }
}
