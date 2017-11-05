<?php

namespace Afocat;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
  public $primaryKey = 'placa';

  protected $casts = [
      'placa' => 'string',
  ];

  protected $table = 'afoc_vehiculos';

  public $timestamps = false;

  public function persona(){
  	return $this->belongsTo('\Afocat\Persona', 'persona_dni', 'dni');
  }

  public function empresa(){
  	return $this->belongsTo('\Afocat\Empresa', 'empresa_ruc', 'ruc');
  }

  public function cats(){
  	return $this->hasMany('\Afocat\Afocat', 'vehiculo_placa', 'placa');
  }

  public function getVehiculoAttribute()
  {
      return $this->clase." ".$this->marca." ".$this->modelo." ".$this->color;
  }
}
