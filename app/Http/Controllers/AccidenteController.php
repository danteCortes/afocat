<?php

namespace Afocat\Http\Controllers;

use Afocat\Accidente;
use Illuminate\Http\Request;
use DB;
use Validator;
use Carbon\Carbon;
use Afocat\Vehiculo;

class AccidenteController extends Controller
{
  public function index(){

    return view('accidente.inicio');
  }

  public function create(){
    return view('accidente.nuevo');
  }

  public function store(Request $request){

    Validator::make($request->all(), [
      'placa'         =>  'required',
      'zona'          =>  'required',
      'ocurrencia'    =>  'required|date_format:d/m/Y',
      'notificacion'  =>  'nullable|date_format:d/m/Y'
    ])->validate();

    if ($vehiculo = Vehiculo::find($request->placa)) {

      $accidente = new Accidente;
      $accidente->vehiculo_placa = $vehiculo->placa;
      $accidente->provincia = mb_strtoupper($request->provincia);
      $accidente->zona = mb_strtoupper($request->zona);
      $accidente->ocurrencia = $request->ocurrencia;
      if ($request->notificacion) {
        $accidente->notificacion = Carbon::createFromFormat('d/m/Y', $request->notificacion)->format('Y-m-d');
      }
      $accidente->save();
    }else{
      return redirect('accidente')->with('error', 'LA PLACA INGRESADA '.$request->placa.
        ' NO ESTA REGISTRADA EN EL SISTEMA, COMUNIQUESE CON EL ENCARGADO DE AFILIACIONES');
    }

    return redirect('accidentado/create')->with('correcto', 'AHORA PUEDE INGRESAR LOS DATOS DE LOS ACCIDENTADOS EN EL ACCIDENTE '.$accidente->id.
      '.');
  }

  public function show(Accidente $accidente){

    return view('accidente.mostrar')->with('accidente', $accidente);
  }

  public function edit(Accidente $accidente){
    return view('accidente.editar')->with('accidente', $accidente);
  }

  public function update(Request $request, Accidente $accidente){

    Validator::make($request->all(), [
      'placa'         =>  'required',
      'zona'          =>  'required',
      'ocurrencia'    =>  'required|date_format:d/m/Y',
      'notificacion'  =>  'nullable|date_format:d/m/Y'
    ])->validate();

    if ($vehiculo = Vehiculo::find($request->placa)) {

      $accidente->vehiculo_placa = $vehiculo->placa;
      $accidente->provincia = $request->provincia;
      $accidente->zona = $request->zona;
      $accidente->ocurrencia = $request->ocurrencia;
      if ($request->notificacion) {
        $accidente->notificacion = Carbon::createFromFormat('d/m/Y', $request->notificacion)->format('Y-m-d');
      }
      $accidente->save();
    }else{
      return redirect('accidente/'.$accidente->id.'/edit')->with('error', 'LA PLACA INGRESADA '.$request->placa.
        ' NO ESTA REGISTRADA EN EL SISTEMA, COMUNIQUESE CON EL ENCARGADO DE AFILIACIONES');
    }

    return redirect('accidente')->with('correcto', 'EL ACCIDENTE '.$accidente->id.
      ' FUE MODIFICADO CON EXITO.');
  }

  public function destroy(Accidente $accidente){
    $accidente->delete();
    return 1;
  }

  public function buscarAuto(Request $request){
    $vehiculo = \Afocat\Vehiculo::find($request['auto_placa']);
    if (isset($vehiculo)) {
      $cat = DB::table('afoc_afocats')->where('vehiculo_placa', '=', $vehiculo->placa)->orderBy('fin_contrato', 'desc')->get()->first();
      $cat = \Afocat\Afocat::find($cat->numero);

      $numero = $cat->numero;
      $duenio = $vehiculo->persona;
      if (isset($duenio)) {
        return ['vehiculo'=>$vehiculo->clase.' '.$vehiculo->marca.' '.$vehiculo->modelo.' '.$vehiculo->categoria,
          'duenio'=>$duenio->nombre.' '.$duenio->paterno.' '.$duenio->materno, 'vencimiento'=>$cat->fin_contrato, 'cat'=>$numero];
      } else {
        $duenio = $vehiculo->empresa;
        return ['vehiculo'=>$vehiculo->clase.' '.$vehiculo->marca.' '.$vehiculo->modelo.' '.$vehiculo->categoria,
          'duenio'=>$duenio->nombre, 'vencimiento'=>$cat->fin_contrato, 'cat'=>$numero];
      }
    } else {
      return ['vehiculo'=>'NO EXISTE ESTE VEHÍCULO, INGRÉSELO A LA BASE DE DATOS.',
        'duenio'=>'NO SE ENCONTRARON DATOS DEL DUEÑO', 'vencimiento'=>'NO SE ENCONTRARON DATOS DEL CAT',
        'cat'=>'NO SE ENCONTRARON DATOS DEL CAT'];
    }
  }

  public function listarAccidentes(Request $request){

    $line_quantity = intVal($request->current);
    $line_number = intVal($request->rowCount);
    $where = $request->searchPhrase;
    $sort = $request->sort;

    if (isset($sort['codigo'])) {
        $order_by = 'id';
        $order_name = $sort['codigo'];
    }
    if (isset($sort['vehiculo'])) {
        $order_by = 'vehiculo_placa';
        $order_name = $sort['vehiculo'];
    }
    if (isset($sort['ocurrencia'])) {
        $order_by = 'ocurrencia';
        $order_name = $sort['ocurrencia'];
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
    } else {

      if (empty($where)) {
        $accidentes = Accidente::offset($skip)
        ->limit($take)
        ->orderBy($order_by, $order_name)
        ->get();

      } else {
        $accidentes = Accidente::where('id', 'like', '%' . $where . '%')
          ->orWhere('vehiculo_placa', 'like', '%' . $where . '%')
          ->orWhere('ocurrencia', 'like', '%' . $where . '%')
          ->offset($skip)
          ->limit($take)
          ->orderBy($order_by, $order_name)
          ->get();
      }

      if (empty($where)) {

        $total = count(Accidente::all());
      } else {

        $total = Accidente::where('id', 'like', '%' . $where . '%')
          ->orWhere('vehiculo_placa', 'like', '%' . $where . '%')
          ->orWhere('ocurrencia', 'like', '%' . $where . '%')
          ->get();

        $total = count($total);
      }
    }

    foreach ($accidentes as $accidente):

      $data = array_merge(
        array
        (
          "codigo" => $accidente->id,
          "vehiculo" => $accidente->vehiculo_placa,
          "ocurrencia" => $accidente->ocurrencia
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


}
