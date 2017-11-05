<?php

namespace Afocat\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Afocat\Persona;
use Afocat\Afocat;
use Afocat\Mensaje;

class SmsController extends Controller
{

  /**
   * Envia un sms a todas las personas que cumplen años en el día para
   * felicitarlo por su cumpleaños.
   *
   * @return \Illuminate\Http\Response
   */
  public function felicitarTodos(){
    $personas = DB::table('afoc_personas')->whereDay('nacimiento', date('d'))->whereMonth('nacimiento', date('m'))->get();
    $cont = 0;
    foreach ($personas as $persona) {
      if ($persona->telefono) {
        if (!Mensaje::where('tipo', 1)->whereDate('fecha', '>', date('Y-m-d', strtotime('-1 week')))->where('persona_dni', $persona->dni)->first()) {
          // Enviar email a cada persona.
          $datos = [
            'mensaje'=>$persona->nombre.' por ser tu cumpleaños te mandamos muchos abrazos y esperamos que cumplas muchos más. Te desea la familia de Afocat Regional León de Huánuco.',
            'numero'=>$persona->telefono,
          ];

          $this->enviarSms($datos);

          $mensaje = new Mensaje;
          $mensaje->persona_dni = $persona->dni;
          $mensaje->tipo = 1;
          $mensaje->fecha = date('Y-m-d');
          $mensaje->save();

          $cont++;
        }
      }
      if ($cont == 50) {
        break;
      }
    }
    return redirect('administrador')->with('correcto', 'SE ENVIARON TODOS LOS
      SMS DE FELICITACIONES A LOS AFILIADOS QUE CUMPLEN AÑOS EL DÍA DE HOY');
  }

  /**
   * Envia un sms a una persona para felicitar por su cumpleaños.
   *
   * @param String dni
   * @return \Illuminate\Http\Response
   */
  public function felicitarCumpleanios($dni){
    $persona = Persona::find($dni);
    if ($persona->telefono) {
      if (!Mensaje::where('tipo', 1)->whereDate('fecha', '>', date('Y-m-d', strtotime('-1 week')))->where('persona_dni', $persona->dni)->first()) {
        // código para enviar sms
        $datos = [
          'mensaje'=>$persona->nombre.' por ser tu cumpleaños te mandamos muchos abrazos y esperamos que cumplas muchos más. Te desea la familia de Afocat Regional León de Huánuco.',
          'numero'=>$persona->telefono,
        ];

        $this->enviarSms($datos);

        $mensaje = new Mensaje;
        $mensaje->persona_dni = $persona->dni;
        $mensaje->tipo = 1;
        $mensaje->fecha = date('Y-m-d');
        $mensaje->save();
        return redirect('cumpleanieros')->with('correcto', 'EL SMS DE FELICITACIÓN SE ENVIÓ CORRECTAMENTE A '.$persona->nombre);
      }
    }
    return redirect('cumpleanieros')->with('error', 'NO SE ENCONTRÓ UN TELÉFONO PARA '.$persona->nombre);
  }

  /**
   * Envia un sms a todas las personas que tienen CAT's por vencer en esta semana.
   *
   * @return \Illuminate\Http\Response
   */
  public function recordarTodos(){
    $porvencer = Afocat::whereDate('fin_certificado', '<=', date('Y-m-d', strtotime('+1 week')))
      ->whereDate('fin_certificado', '>', date('Y-m-d'))->get();
    $cont = 0;
    foreach ($porvencer as $vencer) {

      if ($persona = $vencer->vehiculo->persona) {
        if($persona->telefono){

          if (!Mensaje::where('tipo', 2)->whereDate('fecha', '>', date('Y-m-d', strtotime('-1 week')))->where('persona_dni', $persona->dni)->first()) {
            // Enviar sms a cada persona.
            $nombre = explode(' ', $persona->nombre)[0];
            $datos = [
              'mensaje'=>'SR(A) '.$nombre.' SU SOAT VENCERÁ EL '.$vencer->fin_certificado.' Y UD FUE ELEGIDO, CON ESTE SMS PIDA SU DSCTO EN EL JR. CONSTITUCIÓN 938 HCO. ATTE AFOCAT REG LEON DE HUÁNUCO.',
              'numero'=>$persona->telefono,
            ];

            $this->enviarSms($datos);

            $mensaje = new Mensaje;
            $mensaje->persona_dni = $persona->dni;
            $mensaje->tipo = 2;
            $mensaje->fecha = date('Y-m-d');
            $mensaje->save();

            $cont++;
          }
        }
      }
      if ($cont == 50) {
        break;
      }
    }

    return redirect('administrador')->with('correcto', 'SE ENVIARON TODOS LOS
      SMS RECORDANDO A LOS AFILIADOS QUE SE LES VENCE UN CAT EN ESTA SEMANA');
  }

  /**
   * Envia un sms a una persona para felicitar por su cumpleaños.
   *
   * @param String dni
   * @return \Illuminate\Http\Response
   */
  public function recordar($numero){
    $cat = Afocat::find($numero);
    if ($persona = $cat->vehiculo->persona) {
      if ($persona->telefono) {

        if (!Mensaje::where('tipo', 2)->whereDate('fecha', '>', date('Y-m-d', strtotime('-1 week')))->where('persona_dni', $persona->dni)->first()) {

          $nombre = explode(' ', $persona->nombre)[0];
          $datos = [
            'mensaje'=>'SR(A) '.$nombre.' SU SOAT VENCERÁ EL '.$cat->fin_certificado.' Y UD FUE ELEGIDO, CON ESTE SMS PIDA SU DSCTO EN EL JR. CONSTITUCIÓN 938 HCO. ATTE AFOCAT REG LEON DE HUÁNUCO.',
            'numero'=>$persona->telefono,
          ];

          $this->enviarSms($datos);

          $mensaje = new Mensaje;
          $mensaje->persona_dni = $persona->dni;
          $mensaje->tipo = 2;
          $mensaje->fecha = date('Y-m-d');
          $mensaje->save();
        }
        return redirect('recordar')->with('correcto', 'EL SMS RECORDATORIO SE ENVIÓ CORRECTAMENTE A '.$persona->nombre);
      }
      return redirect('recordar')->with('advertencia', 'EL SR(A) '.$persona->nombre.' NO CUENTA CON UN NÚMERO TELEFÓNICO.');
    }
  }

  /**
   * Envia un sms a todas las personas que tienen CAT's vencidos.
   *
   * @return \Illuminate\Http\Response
   */
  public function recordarVencidos(){
    $vencidos = Afocat::whereDate('fin_certificado', '<=', date('Y-m-d'))
      ->whereDate('fin_certificado', '>', date('Y-m-d', strtotime('-1 month')))->get();
    $cont = 0;

    foreach ($vencidos as $vencido) {
      if ($persona = $vencido->vehiculo->persona) {
        if($persona->telefono){
          if (!Mensaje::where('tipo', 3)->whereDate('fecha', '>', date('Y-m-d', strtotime('-1 week')))->where('persona_dni', $persona->dni)->first()) {

            // Enviar sms a cada persona.
            $nombre = explode(' ', $persona->nombre)[0];
            $datos = [
              'mensaje'=>'SR(A) '.$nombre.' SU SOAT VENCIÓ EL '.$vencido->fin_certificado.' Y UD FUE ELEGIDO, CON ESTE SMS PIDA SU DSCTO EN EL JR. CONSTITUCIÓN 938 HCO. ATTE AFOCAT REG LEON DE HUÁNUCO.',
              'numero'=>$persona->telefono,
            ];

            $this->enviarSms($datos);

            $mensaje = new Mensaje;
            $mensaje->persona_dni = $persona->dni;
            $mensaje->tipo = 3;
            $mensaje->fecha = date('Y-m-d');
            $mensaje->save();

            $cont++;
          }
        }
      }
      if($cont == 200){
        break;
      }
    }
    return redirect('administrador')->with('correcto', 'SE ENVIARON TODOS LOS
      SMS NOTIFICANDO A LAS PERSONAS QUE SE LES VENCIERON SU CATS');
  }

  /**
   * Envia un sms a una persona para notificar que tiene un CAT vencido.
   *
   * @param String dni
   * @return \Illuminate\Http\Response
   */
  public function vencidos($numero){
    $cat = Afocat::find($numero);
    if ($persona = $cat->vehiculo->persona) {
      if ($persona->telefono) {

        if (!Mensaje::where('tipo', 3)->whereDate('fecha', '>', date('Y-m-d', strtotime('-1 week')))->where('persona_dni', $persona->dni)->first()) {

          $nombre = explode(' ', $persona->nombre)[0];
          $datos = [
            'mensaje'=>'SR(A) '.$nombre.' SU SOAT VENCIÓ EL '.$cat->fin_certificado.' Y UD FUE ELEGIDO, CON ESTE SMS PIDA SU DSCTO EN EL JR. CONSTITUCIÓN 938 HCO. ATTE AFOCAT REG LEON DE HUÁNUCO.',
            'numero'=>$persona->telefono,
          ];

          $this->enviarSms($datos);

          $mensaje = new Mensaje;
          $mensaje->persona_dni = $persona->dni;
          $mensaje->tipo = 3;
          $mensaje->fecha = date('Y-m-d');
          $mensaje->save();

          return redirect('vencidos')->with('correcto', 'EL SMS DE NOTIFICACIÓN SE ENVIÓ CORRECTAMENTE A '.$persona->nombre);
        }
        return redirect('vencidos')->with('advertencia', 'NO SE ENVIÓ EL SMS A '.$persona->nombre.' YA QUE NO PASO UNA SEMANA DESDE EL ÚLTIMO SMS.');
      }
      return redirect('vencidos')->with('advertencia', 'EL SR(A) '.$persona->nombre.' NO CUENTA CON UN NÚMERO TELEFÓNICO.');
    }
  }

  private function enviarSms(array $datos){
    //CREACION DE NUEVO ENVIO DE SMS
    // <------------- API de TuLoEnvias.com ----------------->
    // <------------------- SMS V1.0 --------------------->
    $codapi	=	"	4as6hc7cr2z"; // código de afocat registrado por percy
    //$codapi	=	"	d4bjj7qzuoj"; // Código de la version de prueba
    $remitente	=	"LEON DE HCO"; //nombre o numero de quien envia el SMS
    $mensaje	=	$datos['mensaje']; //mensaje a enviar
    $destinatario	=	$datos['numero']; //numeros de celular separados por (,)
    $idgrupo	=	""; //id del grupo al que se le enviará el SMS
    $fecha_envio	=	""; //fecha de programación
    $idlote	=	""; //identificador para agrupar varios envíos (alfanumérico)
    $url	=	"http://api.tuloenvias.com/sms/";
    $url	=	str_replace(" ",'%20',$url);

    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1) ;
    curl_setopt($ch, CURLOPT_POSTFIELDS, "codapi=".$codapi."&remitente=".$remitente."&mensaje=".$mensaje."&destinatario=".$destinatario.
    "&idgrupo=".$idgrupo."&fecha_envio=".$fecha_envio."&idlote=".$idlote."");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    //Para escribir la respuesta, descomenta la siguiente linea
    return $result;
    // <------------- API de TuLoEnvias.com ----------------->
  }
}
