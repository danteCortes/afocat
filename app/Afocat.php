<?php

namespace Afocat;

use Illuminate\Database\Eloquent\Model;

class Afocat extends Model
{
    protected $table = 'afoc_afocats';

    public $primaryKey = 'numero';

    protected $casts = [
  		'numero' => 'string',
  	];

    public $timestamps = false;

    protected $dates = [
      'inicio_contrato',
      'fin_contrato',
      'inicio_certificado',
      'fin_certificado',
      'registro'
    ];

    public function getInicioContratoAttribute($value){
      if (isset($value)) {
        $value = date('d/m/Y', strtotime($value));
      }
      return $value;
    }

    public function getFinContratoAttribute($value){
      if (isset($value)) {
        $value = date('d/m/Y', strtotime($value));
      }
      return $value;
    }

    public function getInicioCertificadoAttribute($value){
      if (isset($value)) {
        $value = date('d/m/Y', strtotime($value));
      }
      return $value;
    }

    public function getFinCertificadoAttribute($value){
      if (isset($value)) {
        $value = date('d/m/Y', strtotime($value));
      }
      return $value;
    }

    public function vehiculo(){
    	return $this->belongsTo('\Afocat\Vehiculo', 'vehiculo_placa', 'placa');
    }

    public function persona(){
    	return $this->belongsTo('\Afocat\Persona', 'persona_dni', 'dni');
    }

    public function empresa(){
    	return $this->belongsTo('\Afocat\Empresa', 'empresa_ruc', 'ruc');
    }

    public function duplicado(){
      return $this->hasOne('\Afocat\Duplicado', 'afocat_numero', 'numero');
    }
}
