<?php

namespace Afocat\Http\Controllers;

use Illuminate\Http\Request;
use Afocat\Afocat;
use Afocat\Accidente;

class EstadisticaController extends Controller
{
  public function inicio(){
    return view('estadistica.inicio');
  }

  public function buscar(Request $request){

    set_time_limit(0);
    $inicio = date('Y-m-d', strtotime(str_replace('/', '-', $request->inicio)));
    $fin = date('Y-m-d', strtotime(str_replace('/', '-', $request->fin)));
    $respuesta = [];
    $datos = ['inicio'=>$inicio, 'fin'=>$fin];

    $respuesta = $this->nuevosRenovados($datos);

    $siniestros = $this->siniestros($datos);

    $nuevos = $this->tiposNuevo($datos);

    $antiguos = $this->tiposAntiguo($datos);

    $html = "<div class='col-xs-12 col-sm-6 col-md-4 col-lg-3'>
      <div class='panel panel-default'>
        <div class='panel-heading'>
          <h3 class='panel-title'>CAT's Nuevos y Renovados</h3>
        </div>
        <div class='panel-body'>
          <div class='progress'>
            <div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$respuesta['nuevos']."' aria-valuemin='0' aria-valuemax='".
              $respuesta['total_cats']."' style='width: 100%;'>
              Nuevos ".$respuesta['nuevos']." de ".$respuesta['total_cats']."
            </div>
          </div>
          <div class='progress'>
            <div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='".$respuesta['renovados']."' aria-valuemin='0' aria-valuemax='".
              $respuesta['total_cats']."' style='width: 100%;'>
              Renovados ".$respuesta['renovados']." de ".$respuesta['total_cats']."
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class='col-xs-12 col-sm-6 col-md-4 col-lg-3'>
      <div class='panel panel-default'>
        <div class='panel-heading'>
          <h3 class='panel-title'>Clase de Vehículos Nuevos</h3>
        </div>
        <div class='panel-body'>";
        foreach($nuevos[0] as $clave => $valor){
          $html .= "<div class='progress'>
            <div class='progress-bar progress-bar-warning' role='progressbar' aria-valuenow='".$valor."' aria-valuemin='0' aria-valuemax='".
              $nuevos['total']."' style='width: 100%;'>".
              $clave." ".$valor." de ".$nuevos['total']."
            </div>
          </div>";
        }
        $html .= "</div>
      </div>
    </div>
    <div class='col-xs-12 col-sm-6 col-md-4 col-lg-3'>
      <div class='panel panel-default'>
        <div class='panel-heading'>
          <h3 class='panel-title'>Clase de Vehículos Antiguos</h3>
        </div>
        <div class='panel-body'>";
        foreach($antiguos[0] as $clave => $valor){
          $html .= "<div class='progress'>
            <div class='progress-bar progress-bar-danger' role='progressbar' aria-valuenow='".$valor."' aria-valuemin='0' aria-valuemax='".
              $antiguos['total']."' style='width: 100%;'>".
              $clave." ".$valor." de ".$antiguos['total']."
            </div>
          </div>";
        }
        $html .= "</div>
      </div>
    </div>
    <div class='col-xs-12 col-sm-6 col-md-4 col-lg-3'>
      <div class='panel panel-default'>
        <div class='panel-heading'>
          <h3 class='panel-title'>Clase de Vehículos siniestrados</h3>
        </div>
        <div class='panel-body'>";
        foreach($siniestros[0] as $clave => $valor){
          $html .= "<div class='progress'>
            <div class='progress-bar progress-bar-primary' role='progressbar' aria-valuenow='".$valor."' aria-valuemin='0' aria-valuemax='".
              $siniestros['total']."' style='width: 100%;'>".
              $clave." ".$valor." de ".$siniestros['total']."
            </div>
          </div>";
        }
        $html .= "</div>
      </div>
    </div>";
    return $html;
  }

  private function nuevosRenovados(array $datos){
    $nuevos = 0;
    $renovados = 0;

    $cats = Afocat::whereDate('inicio_certificado', '>=', $datos['inicio'])->whereDate('inicio_certificado', '<=', $datos['fin'])->get();

    foreach ($cats as $cat) {
      $antiguo = Afocat::whereDate('inicio_certificado', '<', $datos['inicio'])->where('vehiculo_placa', '=', $cat->vehiculo_placa)
        ->whereColumn('inicio_certificado', 'inicio_contrato')->first();

      if ($antiguo) {
        $renovados++;
      }else{
        $nuevos++;
      }
    }

    return ['nuevos'=>$nuevos, 'renovados'=>$renovados, 'total_cats'=>count($cats)];
  }

  private function tiposNuevo(array $datos){
    $cats = Afocat::whereDate('inicio_certificado', '>=', $datos['inicio'])->whereDate('inicio_certificado', '<=', $datos['fin'])->get();
    $clases_vehiculo = [];
    $clases_vehiculo_nuevo = [];
    $total = 0;

    foreach ($cats as $cat) {
      $antiguo = Afocat::whereDate('inicio_certificado', '<', $datos['inicio'])->where('vehiculo_placa', '=', $cat->vehiculo_placa)
        ->whereColumn('inicio_certificado', 'inicio_contrato')->first();

      if (!$antiguo) {
        if(!in_array($cat->vehiculo->clase, $clases_vehiculo)){
          array_push($clases_vehiculo, $cat->vehiculo->clase);
          $clases_vehiculo_nuevo[$cat->vehiculo->clase] = 0;
        }else{
        }
        $clases_vehiculo_nuevo[$cat->vehiculo->clase]++;
        $total++;
      }
    }
    return ['total'=>$total, $clases_vehiculo_nuevo];
  }

  private function tiposAntiguo(array $datos){
    $cats = Afocat::whereDate('inicio_certificado', '>=', $datos['inicio'])->whereDate('inicio_certificado', '<=', $datos['fin'])->get();
    $clases_vehiculo = [];
    $clases_vehiculo_nuevo = [];
    $total = 0;

    foreach ($cats as $cat) {
      $antiguo = Afocat::whereDate('inicio_certificado', '<', $datos['inicio'])->where('vehiculo_placa', '=', $cat->vehiculo_placa)
        ->whereColumn('inicio_certificado', 'inicio_contrato')->first();

      if ($antiguo) {
        if(!in_array($cat->vehiculo->clase, $clases_vehiculo)){
          array_push($clases_vehiculo, $cat->vehiculo->clase);
          $clases_vehiculo_nuevo[$cat->vehiculo->clase] = 0;
        }else{
        }
        $clases_vehiculo_nuevo[$cat->vehiculo->clase]++;
        $total++;
      }
    }
    return ['total'=>$total, $clases_vehiculo_nuevo];
  }

  private function siniestros(array $datos){

    $accidentes = Accidente::whereDate('ocurrencia', '>=', $datos['inicio'])->whereDate('ocurrencia', '<=', $datos['fin'])->get();
    $clases_vehiculo = [];
    $clases_vehiculo_accidente = [];
    $total = 0;

    foreach ($accidentes as $accidente) {
      $vehiculo = $accidente->vehiculo;
      if(!array_key_exists($vehiculo->clase, $clases_vehiculo)){
        array_push($clases_vehiculo, $vehiculo->clase);
        $clases_vehiculo_accidente[$vehiculo->clase] = 1;
      }else{
        $clases_vehiculo_accidente[$vehiculo->clase]++;
      }
      $total++;
    }
    return ['total'=>$total, $clases_vehiculo_accidente];
  }
}
