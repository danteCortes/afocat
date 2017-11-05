<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaAccidentadoGasto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afoc_accidentado_gasto', function (Blueprint $table) {
            $table->increments('id');
            $table->string('accidentado_codigo', 10);
            $table->foreign('accidentado_codigo')->references('codigo')->on('afoc_accidentados')->onUpdate('cascade')->onDelte('cascade');
            $table->integer('gasto_id')->unsigned();
            $table->foreign('gasto_id')->references('id')->on('afoc_gastos')->onUpdate('cascade')->onDelete('cascade');
            $table->float('pagado')->nullable();
            $table->float('pendiente')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('afoc_accidentado_gasto');
    }
}
