<?php

namespace Afocat\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Afocat\Vehiculo;
use Afocat\Persona;
use Afocat\Empresa;

class VehiculoController extends Controller
{

  public function index(){
    return view('vehiculo.todos');
  }

  public function create(){
    return view('vehiculo.nuevo');
  }

  public function store(Request $request){
    if ($vehiculo = Vehiculo::find($request->placa)) {

      return $this->actualizarVehiculo($request, $vehiculo);
    }else{

      return $this->guardarVehiculo($request);
    }

  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id){
    $vehiculo = \Afocat\Vehiculo::find($id);
    return view('vehiculo.mostrar')->with('vehiculo', $vehiculo);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id){
    $vehiculo = \Afocat\Vehiculo::find($id);
    return view('vehiculo.editar')->with('vehiculo', $vehiculo);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id){
    $vehiculo = \Afocat\Vehiculo::find($id);
    $vehiculo->placa = mb_strtoupper($request['placa']);
    if (strlen($request['buscar_dni']) == 8) {
      $vehiculo->persona_dni = $request['buscar_dni'];
      $vehiculo->empresa_ruc = null;
    }elseif (strlen($request['buscar_dni']) == 11) {
      $vehiculo->persona_dni = null;
      $vehiculo->empresa_ruc = $request['buscar_dni'];
    }else{
      $mensaje = "EL DOCUMENTO INGRESADO NO TIENE EL FORMATO CORRECTO, POR FAVOR VUELVA A INTENTARLO.";
      $resultado = "error";
      return redirect('vehiculo/'.$vehiculo->placa.'/edit')->with($resultado, $mensaje);
    }
    $vehiculo->marca = mb_strtoupper($request['marca']);
    $vehiculo->modelo = mb_strtoupper($request['modelo']);
    $vehiculo->color = mb_strtoupper($request['color']);
    $vehiculo->clase = mb_strtoupper($request['clase']);
    $vehiculo->categoria = mb_strtoupper($request['categoria']);
    $vehiculo->asientos = $request['asientos'];
    $vehiculo->anio = $request['anio'];
    $vehiculo->uso = 'PÚBLICO';
    $vehiculo->motor = mb_strtoupper($request['motor']);
    $vehiculo->save();

    $mensaje = "SE ACTUALIZARON LOS DATOS DEL VEHÍCULO CON PLACA ".mb_strtoupper($request['placa']).".";
    $resultado = "correcto";
    return redirect('vehiculo')->with($resultado, $mensaje);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id){
     $vehiculo = \Afocat\Vehiculo::find($id);
     $vehiculo->delete();
     return 1;
     return redirect('vehiculo')->with('advertencia', 'EL VEHÍCULO '.$vehiculo->placa.' FUE BORRADO.');
  }

  public function otro(Request $request){

    if (strlen($request['dni']) == 8) {
      $cliente = \Afocat\Persona::find($request['dni']);
      if (isset($cliente)) {
          return $cliente->nombre." ".$cliente->paterno." ".$cliente->materno;
      }else{
          return "NO EXISTE ESTE CLIENTE, POR FAVOR INGRÉSELO A LA BASE DE DATOS.";
      }
    }elseif (strlen($request['dni']) == 11) {
      $cliente = \Afocat\Empresa::find($request['dni']);
      if (isset($cliente)) {
          return $cliente->nombre;
      }else{
          return "NO EXISTE ESTE CLIENTE, POR FAVOR INGRÉSELO A LA BASE DE DATOS.";
      }
    }else{
      return "EL DOCUMENTO INGRESADO ES INCORRECTO.";
    }
  }

  //Metodo para buscar un auto y a su dueño
  public function buscarAuto(Request $request){
    $vehiculo = Vehiculo::find($request['placa']);
    if (isset($vehiculo)) {
      $duenio = $vehiculo->persona;
      if (isset($duenio)) {
        return ['vehiculo'=>$vehiculo->clase.' '.$vehiculo->marca.' '.$vehiculo->modelo.' '.$vehiculo->color.' '.$vehiculo->categoria,
          'duenio'=>$duenio->nombre.' '.$duenio->paterno.' '.$duenio->materno];
      } else {
        $duenio = $vehiculo->empresa;
        return ['vehiculo'=>$vehiculo->clase.' '.$vehiculo->marca.' '.$vehiculo->modelo.' '.$vehiculo->color.' '.$vehiculo->categoria,
          'duenio'=>$duenio->nombre];
      }
    } else {
      return ['vehiculo'=>'NO EXISTE ESTE VEHÍCULO, INGRÉSELO A LA BASE DE DATOS.',
        'duenio'=>'NO SE ENCONTRARON DATOS DEL DUEÑO'];
    }

  }

  public function buscarVehiculo(Request $request){
      $line_quantity = intVal($request->current);
      $line_number = intVal($request->rowCount);
      $where = $request->searchPhrase;
      $sort = $request->sort;

      if (isset($sort['placa'])) {
          $order_by = 'placa';
          $order_name = $sort['placa'];
      }
      if (isset($sort['vehiculo'])) {
          $order_by = 'clase';
          $order_name = $sort['vehiculo'];
      }

      $skip = 0;
      $take = $line_number;

      if ($line_quantity > 1) {
          //DESDE QUE REGISTRO SE INICIA
          $skip = $line_number * ($line_quantity - 1);
          //CANTIDAD DE RANGO
          $take = $line_number;
      }
      //Grupo de datos que enviaremos al modelo para filtrar
      if ($request->rowCount < 0) {
          if (empty($where)) {
              $data_list = Vehiculo::orderBy($order_by, $order_name)
                  ->get();
          } else {
              $data_list = Vehiculo::where('placa', 'like', '%' . $where . '%')
                  ->orWhere('marca', 'like', '%' . $where . '%')
                  ->orderBy($order_by, $order_name)
                  ->get();
          }

          if (empty($where)) {

              $total = Vehiculo::count('placa');
          } else {

              $total = Vehiculo::where('placa', 'like', '%' . $where . '%')
                  ->orWhere('marca', 'like', '%' . $where . '%')
                  ->count('placa');
          }
      } else {
          if (empty($where)) {
              $data_list = Vehiculo::offset($skip)
                  ->limit($take)
                  ->orderBy($order_by, $order_name)
                  ->get();
          } else {
              $data_list = Vehiculo::offset($skip)
                  ->limit($take)
                  ->where('placa', 'like', '%' . $where . '%')
                  ->orWhere('marca', 'like', '%' . $where . '%')
                  ->orderBy($order_by, $order_name)
                  ->get();
          }

          if (empty($where)) {

              $total = Vehiculo::count('placa');
          } else {

              $total = Vehiculo::where('placa', 'like', '%' . $where . '%')
                  ->orWhere('marca', 'like', '%' . $where . '%')
                  ->count('placa');
          }
      }

      foreach ($data_list as $data_table):

          $data = array_merge(
              array
              (
                  "placa" => $data_table['placa'],
                  "vehiculo" => $data_table->vehiculo
              )
          );
          //Asignamos un grupo de datos al array datas
          $datas[] = $data;
      endforeach;

      return response()->json(
          array(
              'current' => $line_quantity,
              'rowCount' => $line_number,
              'rows' => $datas,
              'total' => $total,
              'skip' => $skip,
              'take' => $take
          )
      );


  }

  private function guardarVehiculo(Request $request){

    $vehiculo = new Vehiculo;
    $vehiculo->placa = mb_strtoupper($request['placa']);
    if (strlen($request['buscar_dni']) == 8) {

      if ($persona = Persona::find($request->buscar_dni)) {

        $vehiculo->persona_dni = $persona->dni;
      }else{

        $mensaje = "NO EXISTE UN CLIENTE CON ESE DOCUMENTO, POR FAVOR VUELVA A INTENTARLO.";
        $resultado = "error";
        return redirect('vehiculo/create')->with($resultado, $mensaje);
      }
    }elseif (strlen($request['buscar_dni']) == 11) {

      if ($empresa = Empresa::find($request->buscar_dni)) {

        $vehiculo->empresa_ruc = $empresa->ruc;
      }else{

        $mensaje = "NO EXISTE UN CLIENTE CON ESE DOCUMENTO, POR FAVOR VUELVA A INTENTARLO.";
        $resultado = "error";
        return redirect('vehiculo/create')->with($resultado, $mensaje);
      }
    }else{
        $mensaje = "EL DOCUMENTO INGRESADO NO TIENE EL FORMATO CORRECTO, POR FAVOR VUELVA A INTENTARLO.";
        $resultado = "error";
        return redirect('vehiculo/create')->with($resultado, $mensaje);
    }
    $vehiculo->marca = mb_strtoupper($request['marca']);
    $vehiculo->modelo = mb_strtoupper($request['modelo']);
    $vehiculo->color = mb_strtoupper($request['color']);
    $vehiculo->clase = mb_strtoupper($request['clase']);
    $vehiculo->categoria = mb_strtoupper($request['categoria']);
    $vehiculo->asientos = $request['asientos'];
    $vehiculo->anio = $request['anio'];
    $vehiculo->uso = 'PÚBLICO';
    $vehiculo->motor = mb_strtoupper($request['motor']);
    $vehiculo->save();

    $mensaje = "EL VEHÍCULO CON PLACA ".mb_strtoupper($request['placa'])." FUE GUARDADO CON EXITO, PUEDE PROCEDER A GUARDAR LOS DATOS DEL CAT.";
    $resultado = "correcto";
    return redirect('afocat/create')->with($resultado, $mensaje);
  }

  private function actualizarVehiculo(Request $request, Vehiculo $vehiculo){

    if (strlen($request['buscar_dni']) == 8) {

      if ($persona = Persona::find($request->buscar_dni)) {

        $vehiculo->persona_dni = $persona->dni;
      }else{

        $mensaje = "NO EXISTE UN CLIENTE CON ESE DOCUMENTO, POR FAVOR VUELVA A INTENTARLO.";
        $resultado = "error";
        return redirect('vehiculo/create')->with($resultado, $mensaje);
      }
    }elseif (strlen($request['buscar_dni']) == 11) {

      if ($empresa = Empresa::find($request->buscar_dni)) {

        $vehiculo->empresa_ruc = $empresa->ruc;
      }else{

        $mensaje = "NO EXISTE UN CLIENTE CON ESE DOCUMENTO, POR FAVOR VUELVA A INTENTARLO.";
        $resultado = "error";
        return redirect('vehiculo/create')->with($resultado, $mensaje);
      }
    }else{
        $mensaje = "EL DOCUMENTO INGRESADO NO TIENE EL FORMATO CORRECTO, POR FAVOR VUELVA A INTENTARLO.";
        $resultado = "error";
        return redirect('vehiculo/create')->with($resultado, $mensaje);
    }
    $vehiculo->marca = mb_strtoupper($request['marca']);
    $vehiculo->modelo = mb_strtoupper($request['modelo']);
    $vehiculo->color = mb_strtoupper($request['color']);
    $vehiculo->clase = mb_strtoupper($request['clase']);
    $vehiculo->categoria = mb_strtoupper($request['categoria']);
    $vehiculo->asientos = $request['asientos'];
    $vehiculo->anio = $request['anio'];
    $vehiculo->uso = 'PÚBLICO';
    $vehiculo->motor = mb_strtoupper($request['motor']);
    $vehiculo->save();

    $mensaje = "EL VEHÍCULO CON PLACA ".mb_strtoupper($request['placa'])." FUE ACTUALIZADO CON EXITO, PUEDE PROCEDER A GUARDAR LOS DATOS DEL CAT.";
    $resultado = "correcto";
    return redirect('afocat/create')->with($resultado, $mensaje);
  }

}
