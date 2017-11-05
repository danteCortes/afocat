<?php

namespace Afocat;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
  public $primaryKey = 'ruc';

  protected $table = 'afoc_empresas';

  protected $casts = [
		'ruc' => 'string',
	];

  public $timestamps = false;

  public function setNacimientoAttribute($nacimiento){

    if ($nacimiento != "") {

      $this->attributes['nacimiento'] = date('Y-m-d', strtotime(str_replace('/', '-', $nacimiento)));
    }else{

      $this->attributes['nacimiento'] = null;
    }
  }

  public function getNacimientoAttribute($value){
    
  	if (isset($value)) {
      $value = date('d/m/Y', strtotime($value));
    }
    return $value;
  }
}
