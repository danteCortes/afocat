<?php

namespace Afocat\Http\Controllers;

use Illuminate\Http\Request;
use Afocat\Usuario;
use Validator;
use Auth;

class UsuarioController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(){
    $usuarios = Usuario::where('id', '!=', Auth::user()->id)->get();
    return view('usuario.inicio')->with('usuarios', $usuarios);
  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuario = Usuario::all()->first();
        if (isset($usuario)) {
          return view('usuario.nuevo');
        }else{
          return view('inicio.usuario');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      Validator::make($request->all(), [
        'dni'=>'required|max:8|min:8',
        'nombre'=>'required',
        'paterno'=>'required',
        'materno'=>'required'
      ])->validate();

      $usuario = Usuario::where('area', '=', 0)->first();
      if (isset($usuario)) {
        $area = $request->area;
      }else{
        $area = 0;
      }

      $persona = \Afocat\Persona::find($request->dni);
      if (isset($persona)) {
        \Afocat\Usuario::create([
          'persona_dni' => $request['dni'],
          'password' => bcrypt($request['dni']),
          'area' => $area,
        ]);
      } else {
        \Afocat\Persona::create([
          'dni' => $request['dni'],
          'nombre' => mb_strtoupper($request['nombre']),
          'paterno' => mb_strtoupper($request['paterno']),
          'materno' => mb_strtoupper($request['materno']),
          'telefono' => mb_strtoupper($request['telefono']),
          'email' => $request['email']
        ]);

        \Afocat\Usuario::create([
          'persona_dni' => $request['dni'],
          'password' => bcrypt($request['dni']),
          'area' => $area,
        ]);
      }

      if (isset($usuario)) {
        return redirect('usuario');
      }else{
        return redirect('/');
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id){
    $usuario = Usuario::find($id);
    return view('usuario.editar')->with('usuario', $usuario);
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
      'dni'=>'required|max:8|min:8',
      'nombre'=>'required'
    ])->validate();

    $usuario = Usuario::find($id);
    $persona = \Afocat\Persona::find($usuario->persona_dni);
    $persona->dni = $request->dni;
    $persona->nombre = mb_strtoupper($request->nombre);
    $persona->telefono = $request->telefono;
    $persona->save();

    $usuario->persona_dni = $request->dni;
    $usuario->password = $request->dni;
    $usuario->area = $request->area;
    $usuario->save();

    $usuario = Usuario::where('area', '=', 0)->first();
    if (isset($usuario)) {
      return redirect('usuario');
    }else{
      return redirect('/');
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id){
    $usuario = Usuario::find($id);
    $usuario->delete();
    if ($id == Auth::user()->id) {
      return redirect('/')->with('advertencia', 'EL USUARIO '.$usuario->persona->nombre.' FUE ELIMINADO.');
    }

    return redirect('usuario')->with('advertencia', 'EL USUARIO '.$usuario->persona->nombre.' FUE ELIMINADO.');
  }
}
