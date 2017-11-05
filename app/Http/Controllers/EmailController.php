<?php

namespace Afocat\Http\Controllers;

use Afocat\Email;
use Illuminate\Http\Request;
use Afocat\Persona;
use Afocat\Afocat;
use DB;
use Mail;
use Afocat\Mail\CumpleaniosEmail;
use Afocat\Mail\RecordatorioMail;
use Afocat\Mail\BienvenidaEmail;
use Afocat\Mail\NotificacionMail;

class EmailController extends Controller
{

  /**
   * Muestra una lista de cumpleanieros
   *
   * @return \Illuminate\Http\Response
   */
  public function mostrarCumpleanieros(){

    $cumpleanieros = Persona::whereDay('nacimiento', date('d'))->whereMonth('nacimiento', date('m'))->get();
    return view('email.cumpleanieros')->with('cumpleanieros', $cumpleanieros);
  }

  /**
   * Envia un email a una persona para felicitar por su cumpleaños.
   *
   * @param String dni
   * @return \Illuminate\Http\Response
   */
  public function felicitarCumpleanios($dni){
    $persona = Persona::find($dni);
    if ($persona->email) {
      Mail::to($persona->email)->send(new CumpleaniosEmail($persona));
    }
    return redirect('cumpleanieros')->with('correcto', 'EL EMAIL DE FELICITACIÓN SE ENVIÓ CORRECTAMENTE A '.$persona->nombre);
  }

  /**
   * Envia un email a todas las personas que cumplen años en el día para
   * felicitarlo por su cumpleaños.
   *
   * @return \Illuminate\Http\Response
   */
  public function felicitarTodos(){
    $personas = Persona::whereDay('nacimiento', date('d'))->whereMonth('nacimiento', date('m'))->get();
    foreach ($personas as $persona) {
      if ($persona->email) {
        // Enviar email a cada persona.
        Mail::to($persona->email)->send(new CumpleaniosEmail($persona));
      }
    }
    return redirect('administrador')->with('correcto', 'SE ENVIARON TODOS LOS
      EMAIL DE FELICITACIONES A LOS AFILIADOS QUE CUMPLEN AÑOS EL DÍA DE HOY');
  }

  /**
   * Envia un email a todas las personas que tienen CAT´s por vencerse en esta semana.
   *
   * @return \Illuminate\Http\Response
   */
  public function recordarTodos(){
    $porvencer = Afocat::whereDate('fin_certificado', '<=', date('Y-m-d', strtotime('+1 week')))
      ->whereDate('fin_certificado', '>', date('Y-m-d'))->get();

    foreach ($porvencer as $vencer) {
      if ($persona = $vencer->vehiculo->persona) {
        if($persona->email){
          Mail::to($persona->email)->send(new RecordatorioMail($vencer));
        }
      }
    }
    return redirect('administrador')->with('correcto', 'SE ENVIARON TODOS LOS
      EMAIL RECORDANDO A LOS AFILIADOS QUE SE LES VENCE UN CAT EN ESTA SEMANA');
  }

  /**
   * Envia un email a una personas que tiene CAT´s por vencerse en esta semana.
   *
   * @param String numero
   * @return \Illuminate\Http\Response
   */
  public function recordar($numero){
    $cat = Afocat::find($numero);

    if($persona = $cat->vehiculo->persona){
      if ($persona->email) {

        Mail::to($persona->email)->send(new RecordatorioMail($cat));
      }
    }

    return redirect('recordar')->with('correcto', 'SE ENVIÓ UN EMAIL RECORDATORIO AL SR(A) '.$persona->nombre);
  }

  /**
   * Envia un email a todas las personas que tienen CAT´s vencidos.
   *
   * @return \Illuminate\Http\Response
   */
  public function recordarVencidos(){
    $vencidos = Afocat::whereDate('fin_certificado', '<=', date('Y-m-d'))
      ->whereDate('fin_certificado', '>', date('Y-m-d', strtotime('-1 month')))->get();

    foreach ($vencidos as $vencido) {
      if ($persona = $vencido->vehiculo->persona) {
        if($persona->email){
          Mail::to($persona->email)->send(new NotificacionMail($vencido));
        }
      }
    }
    return redirect('administrador')->with('correcto', 'SE ENVIARON TODOS LOS
      EMAIL NOTIFICANDO A LAS PERSONAS QUE SE LES VENCIERON SU CATS');
  }

  /**
   * Envia un email a una personas que tiene CAT´s por vencerse en esta semana.
   *
   * @param String numero
   * @return \Illuminate\Http\Response
   */
  public function vencidos($numero){
    $cat = Afocat::find($numero);
    if ($persona = $cat->vehiculo->persona) {
      if ($persona->email) {
        Mail::to($persona->email)->send(new NotificacionMail($cat));
      }
    }

    return redirect('recordar')->with('correcto', 'SE ENVIÓ UN EMAIL DE NOTIFICACIÓN AL SR(A) '.$persona->nombre);
  }
}
