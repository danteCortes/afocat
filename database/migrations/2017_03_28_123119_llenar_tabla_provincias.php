<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LlenarTablaProvincias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $provincia = new \Afocat\Provincia;
      $provincia->nombre = mb_strtoupper('HUÁNUCO');
      $provincia->departamento_id = 1;
      $provincia->save();

      $provincia = new \Afocat\Provincia;
      $provincia->nombre = mb_strtoupper('AMBO');
      $provincia->departamento_id = 1;
      $provincia->save();

      $provincia = new \Afocat\Provincia;
      $provincia->nombre = mb_strtoupper('DOS DE MAYO');
      $provincia->departamento_id = 1;
      $provincia->save();

      $provincia = new \Afocat\Provincia;
      $provincia->nombre = mb_strtoupper('HUACAYBAMBA');
      $provincia->departamento_id = 1;
      $provincia->save();

      $provincia = new \Afocat\Provincia;
      $provincia->nombre = mb_strtoupper('HUAMALÍES');
      $provincia->departamento_id = 1;
      $provincia->save();

      $provincia = new \Afocat\Provincia;
      $provincia->nombre = mb_strtoupper('LEONCIO PRADO');
      $provincia->departamento_id = 1;
      $provincia->save();

      $provincia = new \Afocat\Provincia;
      $provincia->nombre = mb_strtoupper('MARAÑÓN');
      $provincia->departamento_id = 1;
      $provincia->save();

      $provincia = new \Afocat\Provincia;
      $provincia->nombre = mb_strtoupper('PACHITEA');
      $provincia->departamento_id = 1;
      $provincia->save();

      $provincia = new \Afocat\Provincia;
      $provincia->nombre = mb_strtoupper('PUERTO INCA');
      $provincia->departamento_id = 1;
      $provincia->save();

      $provincia = new \Afocat\Provincia;
      $provincia->nombre = mb_strtoupper('LAURICOCHA');
      $provincia->departamento_id = 1;
      $provincia->save();

      $provincia = new \Afocat\Provincia;
      $provincia->nombre = mb_strtoupper('YAROWILCA');
      $provincia->departamento_id = 1;
      $provincia->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
