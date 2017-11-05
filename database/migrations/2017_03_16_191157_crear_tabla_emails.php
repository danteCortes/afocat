<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afoc_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('persona_dni', 8);
            $table->foreign('persona_dni')->references('dni')->on('afoc_personas')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('motivo');
            $table->date('fecha');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('afoc_emails');
    }
}
