<?php

namespace Afocat;

use Illuminate\Database\Eloquent\Model;

class Duplicado extends Model
{
  protected $table = 'afoc_duplicados';

  public $primaryKey = 'numero';

  protected $casts = [
    'numero' => 'string',
  ];

  public $timestamps = false;

  public function afocat(){
    return $this->belongsTo('\Afocat\Afocat', 'afocat_numero', 'numero');
  }

  public function persona(){
    return $this->belongsTo('\Afocat\Persona', 'persona_dni', 'dni');
  }

  public function empresa(){
    return $this->belongsTo('\Afocat\Empresa', 'empresa_ruc', 'ruc');
  }

  public function getEmisionAttribute($value){
    if (isset($value)) {
      $value = date('d/m/Y', strtotime($value));
    }
    return $value;
  }
}
