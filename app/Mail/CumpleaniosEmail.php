<?php

namespace Afocat\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CumpleaniosEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $persona;

    public function __construct(\Afocat\Persona $persona)
    {
        $this->persona = $persona;
    }


    public function build()
    {
        return $this->markdown('email.cumpleanios', ['persona'=>$this->persona])
          ->from('atencioncliente@afocatregionalleondehuanuco.org')
          ->subject('FELIZ CUMPLEAÑOS '.$this->persona->nombre);
    }
}
