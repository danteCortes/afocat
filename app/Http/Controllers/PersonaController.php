<?php

namespace Afocat\Http\Controllers;

use Illuminate\Http\Request;
use Afocat\Mail\BienvenidaEmail;
use Mail;
use Afocat\Persona;
use Afocat\Afocat;
use Afocat\Empresa;
use DB;
use Validator;
use Carbon\Carbon;

class PersonaController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(){

     return view('afiliado.todos');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(){
    return view('afiliado.nuevo');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request){

    if (strlen($request->dni_ruc) == 8) {

      if (!($persona = Persona::find($request->dni_ruc))) {

        $persona = $this->guardarPersona($request);

        if(isset($persona)){

          $this->enviarEmailBienvenida($persona);

          $mensaje = "EL CLIENTE CON DNI ".$request->dni_ruc." FUE GUARDADO CON EXITO, AHORA GUARDE LOS DATOS DE SU VEHICULO.";
          $resultado = "correcto";

          return redirect('vehiculo/create')->with($resultado, $mensaje);
        }else{
          $mensaje = "HUBO UN ERROR AL GUARDAR AL CLIENTE";
          $resultado = "error";
          return redirect('persona')->with($resultado, $mensaje);
        }
      }else{

        $this->actualizarPersona($request, $persona);

        $mensaje = "SE ACTUALIZARON LOS DATOS DEL CLIENTE CON DNI ".$request->dni_ruc.", PUEDE CONTINUAR INGRESANDO LOS DATOS DE SU VEHÍCULO";
        $resultado = "correcto";
        return redirect('vehiculo/create')->with($resultado, $mensaje);
      }
    }elseif (strlen($request->dni_ruc) == 11) {

      if ($empresa = Empresa::find($request->dni_ruc)) {

        $this->actualizarEmpresa($request, $empresa);

        $mensaje = "SE ACTUALIZARON LOS DATOS DE LA EMPRESA CON RUC ".$request->dni_ruc.", PUEDE CONTINUAR INGRESANDO LOS DATOS DE SU VEHÍCULO";
        $resultado = "correcto";
        return redirect('vehiculo/create')->with($resultado, $mensaje);
      }else {

        $this->guardarEmpresa($request);

        $mensaje = "LA EMPRESA CON RUC ".$request->dni_ruc." FUE GUARDADO CON EXITO, AHORA GUARDE LOS DATOS DE SU VEHICULO.";
        $resultado = "correcto";

        return redirect('vehiculo/create')->with($resultado, $mensaje);
      }
    }else{
      $mensaje = "EL DOCUMENTO INGRESADO NO TIENE UN FORMATO VÁLIDO, POR FAVOR INTENTE NUEVAMENTE.";
      $resultado = "error";
      return redirect('/')->with($resultado, $mensaje);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id){
    $persona = \Afocat\Persona::find($id);
    return view('afiliado.mostrar')->with('afiliado', $persona);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id){
    $persona = \Afocat\Persona::find($id);
    return view('afiliado.editar')->with('afiliado', $persona);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id){

    Validator::make($request->all(), [
        'email' => 'nullable|email',
        'nacimiento' => 'nullable|date_format:d/m/Y',
    ])->validate();

    $persona = \Afocat\Persona::find($id);
    $persona->dni = $request->dni;
    $persona->nombre = mb_strtoupper($request->nombre);
    $persona->paterno = mb_strtoupper($request->paterno);
    $persona->materno = mb_strtoupper($request->materno);
    $persona->direccion = mb_strtoupper($request->direccion);
    $persona->provincia = mb_strtoupper($request->provincia);
    $persona->telefono = mb_strtoupper($request->telefono);
    $persona->email = $request->email;
    if ($request->nacimiento) {
      # code...
      $persona->nacimiento = Carbon::createFromFormat('d/m/Y', $request->nacimiento)->format('Y-m-d');
    }
    $persona->save();
    return redirect('persona')->with('correcto', 'LOS DATOS DEL AFILIADO FUERON CAMBIADOS CON EXITO');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id){

    $persona = \Afocat\Persona::find($id);
    $persona->delete();
    return 1;
  }

  private function guardarPersona(Request $request){

    $persona = new Persona;
    $persona->dni = $request->dni_ruc;
    $persona->nombre = mb_strtoupper($request->nombre);
    $persona->paterno = mb_strtoupper($request->paterno);
    $persona->materno = mb_strtoupper($request->materno);
    $persona->direccion = mb_strtoupper($request->direccion);
    $persona->provincia = mb_strtoupper($request->provincia);
    $persona->departamento = 'HUÁNUCO';
    $persona->telefono = mb_strtoupper($request->telefono);
    $persona->email = $request->email;
    $persona->nacimiento = $request->nacimiento;
    $persona->save();

    return $persona;
  }

  private function actualizarPersona(Request $request, Persona $persona){

    $persona->nombre = mb_strtoupper($request->nombre);
    $persona->paterno = mb_strtoupper($request->paterno);
    $persona->materno = mb_strtoupper($request->materno);
    $persona->direccion = mb_strtoupper($request->direccion);
    $persona->provincia = mb_strtoupper($request->provincia);
    $persona->departamento = 'HUÁNUCO';
    $persona->telefono = mb_strtoupper($request->telefono);
    $persona->email = $request->email;
    $persona->nacimiento = $request->nacimiento;
    $persona->save();

    return $persona;
  }

  private function enviarEmailBienvenida(Persona $persona){

    if ($persona->email) {

      Mail::to($persona->email)->send(new BienvenidaEmail($persona->nombre));
    }
  }

  private function guardarEmpresa(Request $request){

    $empresa = new Empresa;
    $empresa->ruc = $request->dni_ruc;
    $empresa->nombre = mb_strtoupper($request->nombre);
    $empresa->direccion = mb_strtoupper($request->direccion);
    $empresa->provincia = mb_strtoupper($request->provincia);
    $empresa->departamento = 'HUÁNUCO';
    $empresa->representante = mb_strtoupper($request->representante);
    $empresa->telefono = mb_strtoupper($request->telefono);
    $empresa->email = $request->email;
    if($request->nacimiento){
      $empresa->nacimiento = Carbon::createFromFormat('d/m/Y', $request->nacimiento)->format('Y-m-d');
    }
    $empresa->save();
    return $empresa;
  }

  private function actualizarEmpresa(Request $request, Empresa $empresa){

    $empresa->nombre = mb_strtoupper($request->nombre);
    $empresa->direccion = mb_strtoupper($request->direccion);
    $empresa->provincia = mb_strtoupper($request->provincia);
    $empresa->departamento = 'HUÁNUCO';
    $empresa->representante = mb_strtoupper($request->representante);
    $empresa->telefono = mb_strtoupper($request->telefono);
    $empresa->email = $request->email;
    if($request->nacimiento){
      $empresa->nacimiento = Carbon::createFromFormat('d/m/Y', $request->nacimiento)->format('Y-m-d');
    }
    $empresa->save();
    return $empresa;
  }

}
