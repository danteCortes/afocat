<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LlenarTablaDepartamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $departamento = new \Afocat\Departamento;
        $departamento->nombre = mb_strtoupper('HUÁNUCO');
        $departamento->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('afoc_departamentos', function (Blueprint $table) {
            //
        });
    }
}
