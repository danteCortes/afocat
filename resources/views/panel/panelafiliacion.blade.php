@extends('plantillanueva')

@section('titulo')
AFOCAT León de Huánuco
@stop

@section('estilos')
<style>
  .imagen {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    -webkit-transform: translate(-50%, -50%);


  }
</style>
@stop

@section('contenido')
<div class="row">
  <div class="imagen">
    <img src="{{ url('imagenes/fondo.png') }}" alt="" style="width: 150px; height: 150px;">
  </div>
</div>
@stop

@section('script')

@stop
