<?php

namespace Afocat;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'afoc_departamentos';

    public $timestamps = false;

    public function provincias(){
      return $this->hasMany('\Afocat\Provincias', 'departamento_id');
    }
}
