<?php

namespace Afocat\Http\Controllers;

use Afocat\Usuario;

class InicioController extends Controller{

	public function usuario(){
		$usuario = Usuario::all()->first();
		if (isset($usuario)) {
			return redirect('/');
		}else{
			return view('inicio.usuario')->with('usuario', $usuario);
		}
	}

}
