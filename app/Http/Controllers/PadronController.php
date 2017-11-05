<?php

namespace Afocat\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use DB;
use \Afocat\Vehiculo;
use \Afocat\Persona;
use \Afocat\Afocat;
use \Afocat\Anulado;
use \Afocat\Duplicado;
use \Afocat\Distrito;
use \Afocat\Accidente;
use \Afocat\Accidentado;
use \Afocat\AccidentadoGasto;

class PadronController extends Controller
{
  /*
   * Sube un padron de afiliados desde un archivo excel subir.xls
   */
  public function subirPadron($nombre){

    // limite de tiempo infinito.
    set_time_limit(0);

    /*
     * Lee un archivo excel 'subir.xls' para guardar los datos en la
     * base de datos
     */
    Excel::load(storage_path('app/public/afiliaciones/'.$nombre), function($archivo){

      /*
       * Guardamos las filas leidas del archivo excel en la variable $filas
       */
      $filas = $archivo->get();
      $count = 0;

      /*
       * Lee cada fila una por una para guardar sus datos en la base de datos.
       */
      foreach ($filas as $key => $value) {
        $count++;
        /*
         * Evaluamos el nro del documento, si es 0 es un CAT anulado, si no lo es
         * guardamos a una persona o una empresa.
         */
        if ($value->nro_doc == '0') {

          /*
           * es un cat anulado y lo guardamos en la tabla anulados.
           */
          $this->guardarAnulado($value->nrocat, $value->fecha_emision);

        }else{
          if (strlen($value->nro_doc) == 11) {

            /*
             * guardamos los datos de la empresa.
             * Primero verificamos que la empresa no existe en la base de datos,
             * si no existe, esta funcion lo guarda.
             */
            $empresa = $this->guardarEmpresa($value->nro_doc, $value->razonsocial, $value->direccion,
              $value->provincia, $value->departamento, $value->telefono);

            /**
             * Guardamos los datos del auto. Para esto, primero verificamos que el
             * auto no está en la base de datos, caso contrario, es por que ya tenía
             * un CAT lo que significa que puede ser un duplicado o un CAT nuevo.
             */
            $vehiculo = $this->guardarVehiculo($value->placa, null, $value->nro_doc, $value->marcavehiculo,
              $value->modelo, $value->color, $value->clase, $value->categoria, $value->nro_asiento,
              $value->ano, $value->uso_veh, $value->serie, $value->nro_motor);

          }elseif(strlen($value->nro_doc) != null) {

            /*
             * el documento es un dni y guardamos los datos de la persona.
             * Primero verificamos que tengan 8 digitos.
             */
            $dni = $this->formatdni($value->nro_doc);

            $datosPersona = [
              'dni' => $dni,
              'nombre' => $value->nombres,
              'paterno'=> $value->apellido_paterno,
              'materno' => $value->apellido_materno,
              'direccion' => $value->direccion,
              'provincia' => $value->provincia,
              'departamento' => $value->departamento,
              'telefono' => $value->telefono
            ];

            $persona = $this->guardarPersona($datosPersona);

            /**
             * Guardamos los datos del auto. Para esto, primero verificamos que el
             * auto no está en la base de datos, caso contrario, es por que ya tenía
             * un CAT lo que significa que puede ser un duplicado o un CAT nuevo.
             */
            $vehiculo = $this->guardarVehiculo($value->placa, $dni, null, $value->marcavehiculo,
              $value->modelo, $value->color, $value->clase, $value->categoria, $value->nro_asiento,
              $value->ano, $value->uso_veh, $value->serie, $value->nro_motor);

          }else{
            break;
          }

          /**
           * Ahora guardamos los datos del CAT, verificamos las fechas, si la fecha de emision
           * es igual a la fecha de inicio es una compra,caso contrario es un duplicado.
           * La fecha de fin siempre será un año despues de la fecha de inicio.
           */
           if ($value->nrocat != null) {

             $datosCertificado = [
               'numero' => $value->nrocat,
               'vehiculo_placa' => $value->placa,
               'inicio_contrato' => $value->fecha_inicio,
               'fin_contrato' => $value->fecha_fin,
               'inicio_certificado' => $value->fecha_emision,
               'fin_certificado' => $value->fecha_fin,
               'hora' => '12:00:00',
               'monto' => $value->costo
             ];

             $this->guardarCertificado($datosCertificado);
           }
        }
        echo $count."<br>";
      }
      echo "listo";
    })->get();
  }

  public function subirSiniestros($nombre){

    // limite de tiempo infinito.
    set_time_limit(0);

    /*
     * Lee un archivo excel 'subir.xls' para guardar los datos en la
     * base de datos
     */
    Excel::load(storage_path('app/public/siniestros/'.$nombre), function($archivo){

      /*
       * Guardamos las filas leidas del archivo excel en la variable $filas
       */
      $filas = $archivo->get();

      $count = 0;

      /*
       * Lee cada fila una por una para guardar sus datos en la base de datos.
       */
      foreach ($filas as $key => $value) {
        $count++;
        /*
         * Evaluamos el nro del CAT, si no es nulo procedemos a darle formato.
         * el formato necesario es ####-##
         */
        if ($value->n0_de_cat) {

          $numero_cat = $this->formatearCat($value->n0_de_cat);

          // Verificamos si el cat existe en la base de datos.
          if($cat = Afocat::find($numero_cat)){

            if (!($distrito = Distrito::where('nombre', $value->zonageografica)->first())) {
              $distrito = $this->guardarDistrito($value->zonageografica);
            }

            $datosAccidente = [
              'id' => $value->codigodelaccidente,
              'vehiculo_placa' => $cat->vehiculo_placa,
              'afocat_numero' => $numero_cat,
              'distrito_id' => $distrito->id,
              'ocurrencia' => $value->fechadeocurrencia,
              'notificacion' => $value->fecha_de_notificacion_a_la_afocat
            ];

            // Si el CAT existe en la BD guardamos los datos del accidente.
            if($accidente = $this->guardarAccidente($datosAccidente)){

              $datosAccidentado = [
                'codigo' => $value->codigodelsiniestro,
                'accidente_id' => $accidente->id,
                'nombre' => $value->nombres_de_los_accidentados
              ];

              if ($accidentado = $this->guardarAccidentado($datosAccidentado)) {

                $datosPago = [
                  'accidentado_codigo' => $accidentado->codigo,
                  'gastos_medicos_pagado' => $value->gastos_medicos_pagado,
                  'gastos_medicos_pendiente' => $value->gastos_medicos_pendiente,
                  'incapacidad_temporal_pagado' => $value->incapacidad_temporal_pagado,
                  'incapacidad_temporal_pendiente' => $value->incapacidad_temporal_pendiente,
                  'invalidez_permanente_pagado' => $value->invalidez_permanente_pagado,
                  'invalidez_permanente_pendiente' => $value->invalidez_permanente_pendiente,
                  'siniestro_de_sepelio_pagado' => $value->siniestro_de_sepelio_pagado,
                  'siniestro_de_sepelio_pendiente' => $value->siniestro_de_sepelio_pendiente,
                  'indemnizacion_por_muerte_pagado' => $value->indemnizacion_por_muerte_pagado,
                  'indemnizacion_por_muerte_pendiente' => $value->indemnizacion_por_muerte_pendiente,
                ];

                // guardamos los pagos al accidentado.
                $this->guardarAccidentadoGasto($datosPago);
              }
            }
            echo $count." ".$numero_cat." ".$datosAccidente['distrito_id']."<br>";
          }
        }


      }
      echo "listo";
    })->get();
  }

  public function actualizar(){

    set_time_limit(0);

    Excel::load(storage_path('app/public/actualizar.xlsx'), function($archivo){

      /*
       * Guardamos las filas leidas del archivo excel en la variable $filas
       */
      $filas = $archivo->get();
      $count = 0;

      /*
       * Lee cada fila una por una para guardar sus datos en la base de datos.
       */
      foreach ($filas as $key => $value) {
        $count++;

        if ($value->doc == '0') {

          $anulado = Anulado::where('numero', $value->cat)->first();
          if ($anulado) {
            if($value->estado == 1){

              $anulado->denuncia = 1;
              $anulado->save();
            }
          }
        }else{

          $vehiculo = Vehiculo::find($value->placa);
          if ($vehiculo) {
            $vehiculo->categoria = $value->categoria;
            $vehiculo->save();
          }

          $afocat = Afocat::find($value->cat);
          if ($afocat) {
            if ($afocat->inicio_contrato == $afocat->inicio_certificado) {
              $afocat->extraordinario = $value->venta-$value->monto;
              $afocat->vendedor = $value->vendedor;
              $afocat->save();
            }else{
              $afocat->monto = 10;
              $afocat->extraordinario = $value->venta-10;
              if ($value->estado == 1) {
                $afocat->actualizacion = 1;
              }
              $afocat->save();
            }
          }
        }

        echo $count." ".$value->placa."<br>";
      }
      echo "listo";
    })->get();
  }

  /**
   * Guarda los gastos de los accidentados.
   *
   * @param array datos
   * @return void
   */
  private function guardarAccidentadoGasto(array $datos){

    // verificamos que no exista el pago por gastos medicos.
    if ($accidentadoGasto = AccidentadoGasto::where('accidentado_codigo', $datos['accidentado_codigo'])->where('gasto_id', 1)->first()) {

      $accidentadoGasto = AccidentadoGasto::find($accidentadoGasto->id);
      $accidentadoGasto->pagado = $datos['gastos_medicos_pagado'];
      $accidentadoGasto->pendiente = $datos['gastos_medicos_pendiente'];
      $accidentadoGasto->estado = 0;
      $accidentadoGasto->save();
    }else{

      $accidentadoGasto = new AccidentadoGasto;
      $accidentadoGasto->accidentado_codigo = $datos['accidentado_codigo'];
      $accidentadoGasto->gasto_id = 1;
      if ($datos['gastos_medicos_pagado']) {

        $accidentadoGasto->pagado = $datos['gastos_medicos_pagado'];
      }
      if ($datos['gastos_medicos_pendiente']) {

        $accidentadoGasto->pendiente = $datos['gastos_medicos_pendiente'];
      }
      $accidentadoGasto->estado = 0;
      $accidentadoGasto->save();
    }

    if ($accidentadoGasto = AccidentadoGasto::where('accidentado_codigo', $datos['accidentado_codigo'])->where('gasto_id', 2)->first()) {

      $accidentadoGasto = AccidentadoGasto::find($accidentadoGasto->id);
      $accidentadoGasto->pagado = $datos['incapacidad_temporal_pagado'];
      $accidentadoGasto->pendiente = $datos['incapacidad_temporal_pendiente'];
      $accidentadoGasto->estado = 0;
      $accidentadoGasto->save();
    }else{

      $accidentadoGasto = new AccidentadoGasto;
      $accidentadoGasto->accidentado_codigo = $datos['accidentado_codigo'];
      $accidentadoGasto->gasto_id = 2;
      if ($datos['incapacidad_temporal_pagado']) {

        $accidentadoGasto->pagado = $datos['incapacidad_temporal_pagado'];
      }
      if ($datos['incapacidad_temporal_pendiente']) {

        $accidentadoGasto->pendiente = $datos['incapacidad_temporal_pendiente'];
      }
      $accidentadoGasto->estado = 0;
      $accidentadoGasto->save();
    }

    if ($accidentadoGasto = AccidentadoGasto::where('accidentado_codigo', $datos['accidentado_codigo'])->where('gasto_id', 3)->first()) {

      $accidentadoGasto = AccidentadoGasto::find($accidentadoGasto->id);
      $accidentadoGasto->pagado = $datos['invalidez_permanente_pagado'];
      $accidentadoGasto->pendiente = $datos['invalidez_permanente_pendiente'];
      $accidentadoGasto->estado = 0;
      $accidentadoGasto->save();
    }else{

      $accidentadoGasto = new AccidentadoGasto;
      $accidentadoGasto->accidentado_codigo = $datos['accidentado_codigo'];
      $accidentadoGasto->gasto_id = 3;
      if ($datos['invalidez_permanente_pagado']) {

        $accidentadoGasto->pagado = $datos['invalidez_permanente_pagado'];
      }
      if ($datos['invalidez_permanente_pendiente']) {

        $accidentadoGasto->pendiente = $datos['invalidez_permanente_pendiente'];
      }
      $accidentadoGasto->estado = 0;
      $accidentadoGasto->save();
    }

    if ($accidentadoGasto = AccidentadoGasto::where('accidentado_codigo', $datos['accidentado_codigo'])->where('gasto_id', 4)->first()) {

      $accidentadoGasto = AccidentadoGasto::find($accidentadoGasto->id);
      $accidentadoGasto->pagado = $datos['siniestro_de_sepelio_pagado'];
      $accidentadoGasto->pendiente = $datos['siniestro_de_sepelio_pendiente'];
      $accidentadoGasto->estado = 0;
      $accidentadoGasto->save();
    }else{

      $accidentadoGasto = new AccidentadoGasto;
      $accidentadoGasto->accidentado_codigo = $datos['accidentado_codigo'];
      $accidentadoGasto->gasto_id = 4;
      if ($datos['siniestro_de_sepelio_pagado']) {

        $accidentadoGasto->pagado = $datos['siniestro_de_sepelio_pagado'];
      }
      if ($datos['siniestro_de_sepelio_pendiente']) {

        $accidentadoGasto->pendiente = $datos['siniestro_de_sepelio_pendiente'];
      }
      $accidentadoGasto->estado = 0;
      $accidentadoGasto->save();
    }

    if ($accidentadoGasto = AccidentadoGasto::where('accidentado_codigo', $datos['accidentado_codigo'])->where('gasto_id', 5)->first()) {

      $accidentadoGasto = AccidentadoGasto::find($accidentadoGasto->id);
      $accidentadoGasto->pagado = $datos['indemnizacion_por_muerte_pagado'];
      $accidentadoGasto->pendiente = $datos['indemnizacion_por_muerte_pendiente'];
      $accidentadoGasto->estado = 0;
      $accidentadoGasto->save();
    }else{

      $accidentadoGasto = new AccidentadoGasto;
      $accidentadoGasto->accidentado_codigo = $datos['accidentado_codigo'];
      $accidentadoGasto->gasto_id = 5;
      if ($datos['indemnizacion_por_muerte_pagado']) {

        $accidentadoGasto->pagado = $datos['indemnizacion_por_muerte_pagado'];
      }
      if ($datos['indemnizacion_por_muerte_pendiente']) {

        $accidentadoGasto->pendiente = $datos['indemnizacion_por_muerte_pendiente'];
      }
      $accidentadoGasto->estado = 0;
      $accidentadoGasto->save();
    }
  }

  /**
   * Guarda los datos de un distrito en la BD
   *
   * @param string nombre
   * @return Distrito
   */
  private function guardarDistrito($nombre){

    $distrito = new Distrito;
    $distrito->provincia_id = 1;
    $distrito->nombre = $nombre;
    $distrito->save();

    return $distrito;
  }


  /**
   * Guarda los datos del accidentado en la BD.
   *
   * @param array datos
   * @return Accidentado
   */
  private function guardarAccidentado(array $datos){

    // verificamos si el accidentado existe.
    if (!($accidentado = Accidentado::find($datos['codigo']))) {

      // Si no existe verificamos que el accidentado no se halla registrado con el mismo accidente.
      if (!($accidentado = Accidentado::where('nombre', $datos['nombre'])->where('accidente_id', $datos['accidente_id'])->first())) {

        // Si no esta registrado, registramos los datos del accidentado en la BD.
        $accidentado = new Accidentado;
        $accidentado->codigo = $datos['codigo'];
        $accidentado->accidente_id = $datos['accidente_id'];
        $accidentado->nombre = $datos['nombre'];
        $accidentado->save();
      }
    }

    return $accidentado;
  }

  /**
   * Guarda los datos del accidente en la BD.
   * @param array datos
   * @return Accidente
   */
  private function guardarAccidente(array $datos){

    // verificamos que el accidente no este en la BD.
    if ($accidente = Accidente::find($datos['id'])) {

      return $accidente;
    }else{

      if(!($accidente = Accidente::where('vehiculo_placa', $datos['vehiculo_placa'])->where('afocat_numero', $datos['afocat_numero'])
      ->where('distrito_id', $datos['distrito_id'])->where('ocurrencia', $datos['ocurrencia'])
      ->where('notificacion', $datos['notificacion'])->first())){

        $accidente = new Accidente;
        $accidente->id = $datos['id'];
        $accidente->vehiculo_placa = $datos['vehiculo_placa'];
        $accidente->afocat_numero = $datos['afocat_numero'];
        $accidente->distrito_id = $datos['distrito_id'];
        $accidente->ocurrencia = $datos['ocurrencia'];
        $accidente->notificacion = $datos['notificacion'];
        $accidente->save();

        return $accidente;
      }
    }
  }

  /**
   * Da formato al numero del CAT si no lo tiene.
   */
  private function formatearCat($numero){

    $numero = explode('-', $numero);
    if(strlen($numero[1]) != 2){
      $numero = $numero[0]."-".substr($numero[1], 2, 2);
    }
    return $numero;
  }

  /**
   * Guardar los datos de un certificado.
   *
   * @param array datos
   * @return void
   */
  private function guardarCertificado(array $datos){

    // Verificamos si el Certificado no existe, si existe, salimos del método.
    if(!$certificado = $this->buscarCertificado($datos['numero'])){

      // Si no existe el certificado, verificamos si es uno nuevo o un duplicado.
      if($this->certificadoDuplicado($datos)){

        $this->guardarDuplicado($datos);
      }

      $this->guardarNuevo($datos);
    }
  }

  private function guardarDuplicado(array $datos){

    // Buscamos el certificado original.
    $original = DB::table('afoc_afocats')->where('vehiculo_placa', $datos['vehiculo_placa'])->latest('inicio_contrato')->first();
    if(isset($original)){

      $duplicado = new Duplicado;
      $duplicado->numero = $datos['numero'];
      $duplicado->afocat_numero = $original->numero;
      $duplicado->emision = $datos['inicio_contrato'];
      $duplicado->hora = $datos['hora'];
      $duplicado->monto = $datos['monto'];
      $duplicado->save();

    }
  }

  /**
   * Guarda un Certificado nuevo.
   */
  private function guardarNuevo(array $datos){

    $cat = new Afocat;
    $cat->numero = $datos['numero'];
    $cat->vehiculo_placa = $datos['vehiculo_placa'];
    $cat->inicio_contrato = $datos['inicio_contrato'];
    $cat->fin_contrato = $datos['fin_contrato'];
    $cat->inicio_certificado = $datos['inicio_certificado'];
    $cat->fin_certificado = $datos['fin_certificado'];
    $cat->hora = $datos['hora'];
    $cat->monto = $datos['monto'];
    $cat->save();
  }

  /**
   * verificar si el certificado es un duplicado.
   *
   * @param array datos
   * @return boolean
   */
  private function certificadoDuplicado(array $datos){

    // Comparamos las fechas de inicio y emision.
    if($datos['inicio_contrato'] != $datos['inicio_certificado']){
      return true;
    }

    return false;
  }

  /**
   * Busca un certificado, si no lo encuentra devuelve false.
   *
   * @param String numero
   * @return Afocat
   */
  private function buscarCertificado($numero){

    if ($certificado = Afocat::find($numero)) {

      return $certificado;
    }

    return false;
  }

  /**
   * Guardamos los datos de la persona sino existe. Si existe, actualizamos sus datos.
   *
   * @param array $datos
   * @return Persona
   */
  private function guardarPersona (array $datos){

    if (!$persona = $this->buscarPersona($datos['dni'])) {

      $persona = new Persona;
      $persona->dni = $datos['dni'];
      $persona->nombre = $datos['nombre'];
      $persona->paterno = $datos['paterno'];
      $persona->materno = $datos['materno'];
      $persona->direccion = $datos['direccion'];
      $persona->provincia = $datos['provincia'];
      $persona->departamento = $datos['departamento'];
      $persona->telefono = $datos['telefono'];
      $persona->save();

    }else{

      $persona = $this->actualizarPersona($datos);
    }

    return $persona;

  }

  /**
   * Actualizamos los datos de una persona.
   *
   * @param Array datos
   * @return Persona
   */
  private function actualizarPersona(array $datos){

    $persona = Persona::find($datos['dni']);
    $persona->dni = $datos['dni'];
    $persona->nombre = $datos['nombre'];
    $persona->direccion = $datos['direccion'];
    $persona->provincia = $datos['provincia'];
    $persona->departamento = $datos['departamento'];
    $persona->telefono = $datos['telefono'];
    $persona->save();

    return $persona;
  }

  /**
   * Buscamos a una persona, si no existe retorna falso, si existe retorna persona*
   * @param string dni
   * @return Persona
   */
  private function buscarPersona($dni){

    if($persona = Persona::find($dni)){

      return $persona;
    }

    return false;
  }

  /**
   * Guardamos el vehiculo si no esta en la base de datos.
   */
  private function guardarVehiculo($placa, $persona_dni, $empresa_ruc, $marca, $modelo,
    $color, $clase, $categoria, $asientos, $anio, $uso, $serie, $motor){

    /**
     * Verificamos si este vehiculo no existe para guardar los datos del vehiculo.
     * Si existe no guardamos nada.
     */
     if (!$vehiculo = $this->buscarVehiculo($placa)) {
       $vehiculo = new Vehiculo;
       $vehiculo->placa = $placa;
       $vehiculo->persona_dni = $persona_dni;
       $vehiculo->empresa_ruc = $empresa_ruc;
       $vehiculo->marca = $marca;
       $vehiculo->modelo = $modelo;
       $vehiculo->color = $color;
       $vehiculo->clase = $clase;
       $vehiculo->categoria = $categoria;
       $vehiculo->asientos = $asientos;
       $vehiculo->anio = $anio;
       $vehiculo->uso = $uso;
       $vehiculo->serie = $serie;
       $vehiculo->motor = $motor;
       $vehiculo->save();

     }

     return $vehiculo;
  }

  /**
   * Buscamos un vehiculo.
   *
   * @param string placa
   * @return Vehiculo
   */
  private function buscarVehiculo($placa){

    if ($vehiculo = Vehiculo::find($placa)) {

      return $vehiculo;
    }

    return false;
  }

  // Función para guardar los datos de una empresa
  private function guardarEmpresa($ruc, $razonsocial, $direccion, $provincia, $departamento, $telefono){

    /*
    * Primero verificamos si esta empresa no existe, caso contrario procedemos a guardar los datos de la empresa nueva.
    * Si la empresa ya existe saltamos esta función.
    */
    if (!$empresa = $this->buscarEmpresa($ruc)) {

      $empresa = new \Afocat\Empresa;
      $empresa->ruc = $ruc;
      $empresa->nombre = $razonsocial;
      $empresa->direccion = $direccion;
      $empresa->provincia = $provincia;
      $empresa->departamento = $departamento;
      $empresa->telefono = $telefono;
      $empresa->save();
    }

    return $empresa;
  }

  /**
   * Esta función busca una empresa, si la encuentra devuelve un objeto Empresa,
   * de lo contrario, devuelve false;
   */
  private function buscarEmpresa($ruc){

    $empresa = \Afocat\Empresa::find($ruc);
    if (isset($empresa)) {
      return $empresa;
    }

    return false;
  }

  /**
   * Guarda un CAT anulado.
   * Primero verificamos que el CAT no exista.
   *
   * @param String nrocat
   * @return void
   */
  private function guardarAnulado($nrocat, $fecha){

    // Verificamos si el afocat no existe.
    if(!$anulado = $this->buscarAnulado($nrocat)){

      $anulado = new \Afocat\Anulado;
      $anulado->numero = $nrocat;
      $anulado->fecha = $fecha;
      $anulado->save();
    }

  }

  /**
   * Busca un CAT, si existe devuelve el CAT, caso contrario, devuelde false.
   *
   * @param String nrocat
   * @return Afocat
   */
  private function buscarAnulado($nrocat){

    if ($cat = Anulado::where('numero', $nrocat)->first()) {
      return $cat;
    }

    return false;
  }

  /**
   * Damos formato al numero de dni, tiene menos de 8 digtos lo llenamos de ceros a la izquierda.
   *
   * @param string dni
   * @return string
   */
  private function formatdni($dni){
    if ($dni != 0) {
      if (strlen($dni) < 8) {
        do {
          $dni = "0".$dni;
        } while (strlen($dni) != 8);
      }
      return $dni;
    }
  }
}
