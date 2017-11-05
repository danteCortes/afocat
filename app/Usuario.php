<?php

namespace Afocat;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'afoc_usuarios';

    public $timestamps = false;

    public function persona(){
      return $this->belongsTo('\Afocat\Persona');
    }

    protected $fillable = ['persona_dni', 'area', 'password'];

    protected $hidden = ['password', 'remember_token'];
}
