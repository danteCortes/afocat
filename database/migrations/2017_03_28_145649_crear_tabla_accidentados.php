<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaAccidentados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afoc_accidentados', function (Blueprint $table) {
            $table->string('codigo', 10);
            $table->primary('codigo');
            $table->integer('accidente_id')->unsigned();
            $table->foreign('accidente_id')->references('id')->on('afoc_accidentes')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('afoc_accidentados');
    }
}
