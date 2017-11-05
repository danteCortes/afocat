<?php

namespace Afocat;

use Illuminate\Database\Eloquent\Model;

class Anulado extends Model
{
    protected $table = 'afoc_anulados';

    public $timestamps = false;

    public function getFechaAttribute($value){
    if (isset($value)) {
      $value = date('d/m/Y', strtotime($value));
    }
    return $value;
  }
}
