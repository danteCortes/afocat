<?php

namespace Afocat\Http\Controllers;

use Afocat\Anulado;
use Afocat\Afocat;
use Afocat\Duplicado;
use Illuminate\Http\Request;

class AnuladoController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(){
      //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    $anulados = Anulado::all();
    return view('anulado.nuevo', ['anulados'=>$anulados]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request){

    $this->borrarRegistros($request->numero);

    // Buscamos si existe un duplicado con ese numero
    if ($duplicado = Duplicado::find($request->numero)) {

      // Si existe el duplicado lo borramos.
      $duplicado->delete();
    }

    $anulado = new Anulado;
    $anulado->numero = $request->numero;
    $anulado->fecha = date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha)));
    $anulado->denuncia = $request->denuncia;
    $anulado->save();

    return redirect('anulado/create')->with('correcto', 'SE REGISTRÓ UN CAT ANULADO CON EXITO,
      SI EXISTIA UN CERTIFICADO VENDIDO O DUPLICADO CON ESTE NUMERO, YA FUE BORRADO DE LA BASE DE DATOS.');
  }

  private function borrarRegistros($numero){

    if($cat = Afocat::find($numero)){

      if ($duplicado = $cat->duplicado) {

        $this->borrarRegistros($duplicado->numero);
      }
      echo $cat->numero;
      // Si el CAT existe lo borramos
      $cat->delete();
    }
  }

    /**
     * Display the specified resource.
     *
     * @param  \Afocat\Anulado  $anulado
     * @return \Illuminate\Http\Response
     */
    public function show(Anulado $anulado)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Afocat\Anulado  $anulado
     * @return \Illuminate\Http\Response
     */
    public function edit(Anulado $anulado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Afocat\Anulado  $anulado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Anulado $anulado)
    {
        //
    }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \Afocat\Anulado  $anulado
   * @return \Illuminate\Http\Response
   */
  public function destroy(Anulado $anulado)
  {
    $anulado->delete();
    return redirect('anulado/create')->with('advertencia', 'EL CAT '.$anulado->numero.' FUE BORRADO DEL REGISTRO DE ANULADOS.');
  }
}
