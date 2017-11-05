<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDistritos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afoc_distritos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('provincia_id')->unsigned();
            $table->foreign('provincia_id')->references('id')->on('afoc_provincias')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nombre');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('afoc_distritos');
    }
}
