<?php

namespace Afocat\Http\Controllers;

use Afocat\Duplicado;
use Afocat\Afocat;
use Afocat\Anulado;
use Afocat\Vehiculo;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class DuplicadoController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(){
    $duplicados = Duplicado::all();
    return view('duplicado.inicio')->with('duplicados', $duplicados);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(){
    return view('duplicado.nuevo');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request){

    if ($duplicado = Duplicado::find($request->numero)) {

      return redirect('duplicado')->with('error', 'ESTE NUMERO DE CAT '.$request->numero.' INGRESADO YA ESTÁ REGISTRADO COMO DUPLICADO,
        PUEDE BUSCARLO Y MODIFICARLO O BORRARLO E INTENTARLO NUEVAMENTE');
    }elseif ($cat = Afocat::find($request->numero)) {

      return redirect('afocat')->with('error', 'ESTE NUMERO DE CAT '.$request->numero.' INGRESADO YA ESTÁ REGISTRADO COMO VENDIDO,
        PUEDE BUSCARLO Y MODIFICARLO O BORRARLO E INTENTARLO NUEVAMENTE');
    }elseif ($anulado = Anulado::where('numero', $request->numero)->first()) {

      return redirect('anulado/create')->with('error', 'ESTE NUMERO DE CAT '.$request->numero.' INGRESADO YA ESTÁ REGISTRADO COMO ANULADO,
      PUEDE BUSCARLO Y BORRARLO E INTENTARLO NUEVAMENTE');
    }

    return $this->guardarDuplicado($request);
  }

  private function guardarDuplicado(Request $request){

    $vehiculo = Vehiculo::find($request->buscar_cat);
    if (isset($vehiculo)) {
      $cat = DB::table('afoc_afocats')->where('vehiculo_placa', $request->buscar_cat)->orderBy('inicio_certificado', 'desc')->first();
      $duplicado = new Duplicado;
      $duplicado->numero = strtoupper($request->numero);
      $duplicado->afocat_numero = $cat->numero;
      if ($persona = $vehiculo->persona) {
        $duplicado->persona_dni = $persona->dni;
      }elseif ($empresa = $vehiculo->empresa) {
        $duplicado->empresa_ruc = $empresa->ruc;
      }
      $duplicado->emision = date('Y-m-d', strtotime(str_replace('/', '-', $request->emision)));
      $duplicado->hora = date('H:i:s', strtotime($request->hora));
      $duplicado->monto = $request->monto;
      $duplicado->save();

      $this->guardarCat($request);

      return redirect('duplicado')->with('correcto', 'EL DUPLICADO FUE REGISTRADO CON EXITO.');
    } else {
      return redirect('duplicado/create')->with('error', 'EL VEHÍCULO NO EXISTE EN LOS REGISTROS.');
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \Afocat\Duplicado  $duplicado
   * @return \Illuminate\Http\Response
   */
  public function show(Duplicado $duplicado){
    return view('duplicado.mostrar')->with('duplicado', $duplicado);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \Afocat\Duplicado  $duplicado
   * @return \Illuminate\Http\Response
   */
  public function edit(Duplicado $duplicado){
    return view('duplicado.editar')->with('duplicado', $duplicado);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Afocat\Duplicado  $duplicado
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Duplicado $duplicado){


      $cat = Afocat::find($duplicado->numero);

      $duplicado->numero = strtoupper($request->numero);
      if ($persona = $cat->vehiculo->persona) {
        $duplicado->persona_dni = $persona->dni;
      }elseif ($empresa = $cat->vehiculo->empresa) {
        $duplicado->empresa_ruc = $empresa->ruc;
      }
      $duplicado->emision = date('Y-m-d', strtotime(str_replace('/', '-', $request->emision)));
      $duplicado->hora = date('H:i:s', strtotime($request->hora));
      $duplicado->monto = $request->monto;
      $duplicado->save();

      $this->actualizarCat($request, $cat);

      return redirect('duplicado')->with('correcto', 'EL DUPLICADO FUE ACTUALIZADO CON EXITO.');

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \Afocat\Duplicado  $duplicado
   * @return \Illuminate\Http\Response
   */
  public function destroy(Duplicado $duplicado){
    $duplicado->delete();
    return redirect('duplicado')->with('advertencia', 'EL DUPLICADO DEL CAT '.$duplicado->afocat_numero.' FUE BORRADO.');
  }

  public function buscarCat(Request $request){

    $cat = Afocat::where('vehiculo_placa', $request->numero)
      ->orderBy('inicio_certificado', 'desc')->first();
    if (isset($cat)) {

      if (strtotime(str_replace('/', '-', $cat->fin_certificado)) > strtotime(date('Y-m-d'))) {
        # code...
        return ['estado'=>'correcto', 'activo'=>1, 'mensaje'=>$cat->fin_certificado];
      }else{

        return ['estado'=>'correcto', 'activo'=>0, 'mensaje'=>$cat->fin_certificado];
      }
    }else{
      return ['estado'=>'error', 'mensaje'=>"NO SE ENCONTRO EL CAT"];
    }
  }

  /**
   * Guardar CAT con los valores del duplicado.
   *
   * @param Request $request
   * @return void
   */
  private function guardarCat(Request $request){
    // Identificamos al Certificado original (el que se perdió o se esta actualizando).
    $original = Afocat::where('vehiculo_placa', $request->buscar_cat)->orderBy('inicio_certificado', 'desc')->first();
    // Guardamos un certificado que es el duplicado del original, este certificado es el duplicado.
    $afocat = new \Afocat\Afocat;
    $afocat->numero = $request['numero'];
    $afocat->vehiculo_placa = strtoupper($request->buscar_cat);
    if ($persona = $original->vehiculo->persona) {
      $afocat->persona_dni = $persona->dni;
    }elseif ($empresa = $original->vehiculo->empresa) {
      $afocat->empresa_ruc = $empresa->ruc;
    }
    $afocat->inicio_contrato = date('Y-m-d', strtotime(str_replace('/', '-', $original->inicio_contrato)));
    $afocat->fin_contrato = date('Y-m-d', strtotime(str_replace('/', '-', $original->fin_contrato)));
    $afocat->inicio_certificado = date('Y-m-d', strtotime(str_replace('/', '-', $request->emision)));
    $afocat->fin_certificado = date('Y-m-d', strtotime(str_replace('/', '-', $original->fin_certificado)));
    $afocat->hora = date('H:i:s', strtotime($request['hora']));
    $afocat->monto = $request['monto'];
    $afocat->extraordinario = $request['total']-$request['monto'];
    $afocat->actualizacion = $request['actualizacion'];
    $afocat->registro = Carbon::now()->format('Y-m-d');
    $afocat->save();
  }

  private function actualizarCat(Request $request, Afocat $afocat){

    $afocat->numero = $request['numero'];
    $afocat->inicio_certificado = date('Y-m-d', strtotime(str_replace('/', '-', $request->emision)));
    $afocat->hora = date('H:i:s', strtotime($request['hora']));
    $afocat->monto = $request['monto'];
    $afocat->extraordinario = $request['total']-$request['monto'];
    $afocat->actualizacion = $request['actualizacion'];
    $afocat->save();
  }
}
