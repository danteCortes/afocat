<?php

namespace Afocat\Http\Controllers;

use Afocat\Accidentado;
use Afocat\Accidente;
use Illuminate\Http\Request;
use DB;
use Validator;

class AccidentadoController extends Controller
{
  public function index(){
    $accidentados = Accidentado::all();
    return View('accidentado.inicio')->with('accidentados', $accidentados);
  }

  public function create(){

    return view('accidentado.nuevo');
  }

  public function store(Request $request){

    Validator::make(
      $request->all(),
      [
        'accidente_id'  =>  'required|exists:afoc_accidentes,id',
        'dni'=>'required|digits:8',
        'nombre'=>'required'
      ],
      [
        'exists'  =>  'El código del accidente no existe.'
      ]
    )->validate();

    $accidente = Accidente::find($request->accidente_id);
    $codigo = $request->accidente_id.'.'.(count($accidente->accidentados)+1);
    $accidentado = new Accidentado;
    $accidentado->codigo = $codigo;
    $accidentado->accidente_id = $accidente->id;
    $accidentado->dni = $request->dni;
    $accidentado->nombre = mb_strtoupper($request->nombre);
    $accidentado->forma = mb_strtoupper($request->forma_pago);
    if ($request->a_82) {
      $accidentado->cuenta = str_replace(' ', '', $request->a_82);
    }
    $accidentado->a_cuenta = mb_strtoupper($request->cuenta_a_82);
    $accidentado->save();

    if ($request->continuar) {
      return redirect('accidentado/create')->with('correcto', 'EL ACCIDENTADO FUE INGRESADO CORRECTAMENTE, PUEDE CONTINUAR INGRESANDO A LOS DEMAS
      ACCIDENTADOS PARA EL ACCIDENTE '.$accidente->id.'.');
    } else {
      return redirect('accidente/'.$accidente->id)->with('correcto', 'TODOS LOS ACCIDENTADOS FUERON INGRESADOS CORRECTAMENTE');
    }
  }

  public function show(Accidentado $accidentado){
    return view('accidentado.mostrar')->with('accidentado', $accidentado);
  }

  public function edit(Accidentado $accidentado){
    return view('accidentado.editar')->with('accidentado', $accidentado);
  }

  public function update(Request $request, Accidentado $accidentado){

    Validator::make(
      $request->all(),
      [
        'accidente_id'  =>  'required|exists:afoc_accidentes,id',
        'dni'=>'required|digits:8',
        'nombre'=>'required'
      ],
      [
        'exists'  =>  'El código del accidente no existe.'
      ]
    )->validate();

    if ($accidentado->accidente->id != $request->accidente_id) {

      $accidente = Accidente::find($request->accidente_id);
      $codigo = $request->accidente_id.'.'.(count($accidente->accidentados)+1);

      $accidentado->codigo = $codigo;
      $accidentado->accidente_id = $accidente->id;
    }

    $accidentado->dni = $request->dni;
    $accidentado->nombre = mb_strtoupper($request->nombre);
    $accidentado->forma = mb_strtoupper($request->forma_pago);
    if ($request->a_82) {
      $accidentado->cuenta = str_replace(' ', '', $request->a_82);
    }
    $accidentado->a_cuenta = mb_strtoupper($request->cuenta_a_82);
    $accidentado->save();

    return redirect('accidentado/'.$accidentado->codigo)->with('correcto', 'TODOS LOS ACCIDENTADOS FUERON INGRESADOS CORRECTAMENTE');
  }

  public function destroy(Accidentado $accidentado){
      $accidente = $accidentado->accidente;
      $accidentado->delete();
      $i = 1;
      foreach ($accidente->accidentados as $accidentado) {
        $codigo = explode('.', $accidentado->codigo);
        $accidentado->codigo = $codigo[0].'.'.$i;
        $accidentado->save();
        $i++;
      }
      return redirect('accidentado')->with('advertencia', 'EL ACCIDENTADO FUE BORRADO DE LOS REGISTROS');
  }

  public function buscarAccidente(Request $request){
    $accidente = Accidente::find($request->id);
    if ($accidente) {
      $vehiculo = $accidente->vehiculo;
      if ($vehiculo->persona) {
        $duenio = $vehiculo->persona;
      }else{
        $duenio = $vehiculo->empresa;
      }
      $cat = DB::table('afoc_afocats')->where('vehiculo_placa', '=', $vehiculo->placa)->orderBy('inicio_certificado', 'desc')->get()->first();
      $cat = \Afocat\Afocat::find($cat->numero);

      return ['placa'=>$vehiculo->placa, 'duenio'=>$duenio->nombre, 'vencimiento'=>$cat->fin_contrato, 'numero'=>$cat->numero];
    } else {
      return ['placa'=>'NO SE ENCONTRARON DATOS DEL VEHICULO', 'duenio'=>'NO SE ENCONTRARON DATOS DEL DUEÑO', 'vencimiento'=>
        'NO SE ENCONTRARON DATOS DEL CAT', 'numero'=>'NO SE ENCONTRARON DATOS DEL CAT'];
    }

  }

}
