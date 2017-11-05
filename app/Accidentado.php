<?php

namespace Afocat;

use Illuminate\Database\Eloquent\Model;

class Accidentado extends Model
{
    protected $table = 'afoc_accidentados';

    public $primaryKey = 'codigo';

    public $timestamps = false;

    protected $casts = [
        'codigo' => 'string',
    ];

    public function accidente(){
      return $this->belongsTo('\Afocat\Accidente', 'accidente_id');
    }

    public function pagos(){
      return $this->hasMany('\Afocat\AccidentadoGasto', 'accidentado_codigo', 'codigo');
    }
}
