<?php

namespace Afocat;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Accidente extends Model
{
  protected $table = 'afoc_accidentes';

  public $timestamps = false;

  public function setOcurrenciaAttribute($ocurrencia){
    if ($ocurrencia != "") {

      $this->attributes['ocurrencia'] = Carbon::createFromFormat('d/m/Y', $ocurrencia)->format('Y-m-d');
    }else{

      $this->attributes['ocurrencia'] = null;
    }
  }

  public function getOcurrenciaAttribute($value){
    if (isset($value)) {
      $value = date('d/m/Y', strtotime($value));
    }
    return $value;
  }

  public function getNotificacionAttribute($value){
    if (isset($value)) {
      $value = date('d/m/Y', strtotime($value));
    }
    return $value;
  }

  public function vehiculo(){
    return $this->belongsTo('\Afocat\Vehiculo', 'vehiculo_placa', 'placa');
  }

  public function accidentados(){
    return $this->hasMany('\Afocat\Accidentado', 'accidente_id', 'id');
  }

  public function distrito(){
    return $this->belongsTo('\Afocat\Distrito');
  }
}
