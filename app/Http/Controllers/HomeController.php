<?php

namespace Afocat\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->area == 0) {
          return redirect('administrador');
        }elseif (Auth::user()->area == 1) {
          return redirect('afiliacion');
        }elseif (Auth::user()->area == 2) {
          return redirect('siniestros');
        }

    }
}
