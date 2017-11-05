<?php

namespace Afocat;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'afoc_provincias';

    public $timestamps = false;

    public function distritos(){
      return $this->hasMany('\Afocat\Distrito', 'provincia_id');
    }

    public function departamento(){
      return $this->belongsTo('\Afocat\Departamento', 'departamento_id');
    }
}
