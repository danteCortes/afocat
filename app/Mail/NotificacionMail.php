<?php

namespace Afocat\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cat;

    public function __construct(\Afocat\Afocat $cat)
    {
        $this->cat = $cat;
    }

    public function build()
    {
        return $this->markdown('email.notificacion', ['cat'=>$this->cat])->from('atencioncliente@afocatregionalleondehuanuco.org')
          ->subject('SU CAT ESTA VENCIDO');
    }
}
