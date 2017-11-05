<?php

namespace Afocat;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
  public $primaryKey = 'dni';

  protected $casts = [
		'dni' => 'string',
	];

  protected $table = 'afoc_personas';

  public $timestamps = false;

  protected $fillable = ['dni', 'nombre', 'telefono', 'email'];

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

  public function vehiculos(){
    return $this->hasMany('\Afocat\Vehiculo', 'persona_dni', 'dni');
  }

  public function getAfiliadoAttribute()
  {
      return $this->paterno." ".$this->materno." ".$this->nombre;
  }
}
