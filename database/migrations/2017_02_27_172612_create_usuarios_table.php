<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afoc_usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('persona_dni', 8);
            $table->foreign('persona_dni')->references('dni')->on('afoc_personas')->onUpdate('cascade')->onDelete('cascade');
            $table->string('password');
            $table->rememberToken();
            $table->tinyInteger('area');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
