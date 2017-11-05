<?php

namespace Afocat\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BienvenidaEmail extends Mailable
{
  use Queueable, SerializesModels;

  public $nombre;

  public function __construct($nombre)
  {
    $this->nombre = $nombre;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->markdown('email.bienvenida', ['nombre', $this->nombre])
      ->from('soporte@afocatregionalleondehuanuco.org')
      ->subject('Bienvenido a Afocat Regional León de Huánuco');
  }
}
