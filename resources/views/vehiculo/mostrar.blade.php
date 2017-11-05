@extends('plantillanueva')

@section('titulo')
Vehículo | Mostrar
@stop

@section('estilos')
@stop

@section('contenido')
<div class="row">
  <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-sm-offset-2 col-md-offset-3 col-lg-offset-3">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">
          Datos del Vehículo
        </h3>
      </div>
      <table class="table table-bordered table-responsive">
        <tr>
          <th>PLACA</th>
          <td>{{$vehiculo->placa}}</td>
        </tr>
        <tr>
          <th>CLASE</th>
          <td>{{$vehiculo->clase}}</td>
        </tr>
        <tr>
          <th>MARCA</th>
          <td>{{$vehiculo->marca}}</td>
        </tr>
        <tr>
          <th>MODELO</th>
          <td>{{$vehiculo->modelo}}</td>
        </tr>
        <tr>
          <th>CATEGORIA</th>
          <td>{{$vehiculo->categoria}}</td>
        </tr>
        <tr>
          <th>ASIENTOS</th>
          <td>{{$vehiculo->asientos}}</td>
        </tr>
        <tr>
          <th>AÑO</th>
          <td>{{$vehiculo->anio}}</td>
        </tr>
        <tr>
          <th>USO DEL VEHÍCULO</th>
          <td>{{$vehiculo->uso}}</td>
        </tr>
        <tr>
          <th>NRO DEL MOTOR</th>
          <td>{{$vehiculo->motor}}</td>
        </tr>
      </table>
      <div class="panel-footer">
        <a href="<?=URL::to('vehiculo')?>" class="btn btn-primary">Atras</a>
      </div>
    </div>
  </div>
</div>
@stop

@section('scripts')
@stop
