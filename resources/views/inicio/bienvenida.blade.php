@extends('inicio.plantilla')

@section('titulo')
Inicio
@stop

@section('contenido')
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-lg-offset-2 col-md-offset-2">
    <div class="panel panel-default" style="background-color: rgb(255, 255, 128);">
      <div class="panel-body">
        <p class="text-justify">Bienvenido a su sistema de control interno de AFOCAT LEON DE HUÁNUCO, con este sistema interno podrá manejar con mayor facilidad las afiliaciones de sus clientes, tener estadisticas de siniestros, ayudarse en la contabilidad y economía de su empresa, etc.<p>
        <p>para comenzar a utilizar su nuevo sistema debe primero crear un usuario administrador para poder ingresar al sistema y pueda comenzar a trabajar. Entonces puede dar clic al boton siguiente para crear su usuario y contraseña.</p>
      </div>
      
    </div>
        <a class="btn btn-danger" href="<?=URL::to('usuario/create')?>" role="button">Siguiente</a>
  </div> 
</div>
@stop
