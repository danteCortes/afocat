<?php

namespace Afocat;

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
  protected $table = 'afoc_distritos';

  public $timestamps = false;

  public function provincia(){
    return $this->belongsTo('\Afocat\Provincia', 'provincia_id');
  }
}
