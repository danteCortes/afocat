<?php

namespace Afocat;

use Illuminate\Database\Eloquent\Model;

class AccidentadoGasto extends Model
{
  protected $table = 'afoc_accidentado_gasto';

  public $timestamps = false;

  public function getFechaLimiteAttribute($value){
    if (isset($value)) {
      $value = date('d/m/Y', strtotime($value));
    }
    return $value;
  }

  public function accidentado(){
    return $this->belongsTo('\Afocat\Accidentado', 'accidentado_codigo', 'codigo');
  }

  public function gasto(){
    return $this->belongsTo('\Afocat\Gasto', 'gasto_id', 'id');
  }
}
