<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDuplicados extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('afoc_duplicados', function (Blueprint $table) {
      $table->string('numero', 10);
      $table->primary('numero');
      $table->string('afocat_numero', 10);
      $table->foreign('afocat_numero')->references('numero')->on('afoc_afocats')->onUpdate('cascade')->onDelete('cascade');
      $table->date('emision');
      $table->time('hora');
      $table->float('monto');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('duplicados');
  }
}
