<?php

namespace Afocat\Http\Controllers;

use Afocat\Gasto;
use Illuminate\Http\Request;

class GastoController extends Controller
{

  public function index(){
    $gastos = Gasto::all();
    return view('gasto.inicio')->with('gastos', $gastos);
  }

  public function create(){

  }

  public function store(Request $request){

    $gasto = new Gasto;
    $gasto->nombre = mb_strtoupper($request->nombre);
    $gasto->save();
    return redirect('gasto')->with('correcto', 'EL GASTO SE AGREGÓ EXITOSAMENTE');
  }

  public function show(Gasto $gasto){
      //
  }

  public function edit(Gasto $gasto)
  {
      //
  }

  public function update(Request $request, Gasto $gasto)
  {
      //
  }

  public function destroy(Gasto $gasto){
    $gasto->delete();
    return redirect('gasto')->with('advertencia', 'SE BORRÓ EL TIPO DE GASTO: '.$gasto->nombre.'.');
  }
}
